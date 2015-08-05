package org.grasia.reptxthank.utils;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.HttpClientBuilder;
import org.grasia.reptxthank.dao.user.UserDao;
import org.grasia.reptxthank.model.User;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;

import com.google.gson.Gson;

@Component("updaterThread")
public class UpdaterThread extends Thread{

	@Value("${updater.thread.sleeptime}")
	private long SLEEPTIME;
	
	@Value("${update.url.base}")
	private String URL_BASE;
	
	@Value("${update.url.query.users.prefix}")
	private String QUERY_PREFIX;
	
	@Value("${update.url.query.users.param}")
	private String QUERY_PARAM;

	@Autowired
	private UserDao userDao;
	
	private Logger LOGGER = LoggerFactory.getLogger(UpdaterThread.class);
	
	public void run(){
		LOGGER.info("Thread launched");
		try {
			sendGet();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	private List<User> getUsersList(JSONArray users) {
		LOGGER.debug("Building a list of users");
		ArrayList<User> userList = new ArrayList<User>();
        Gson gson = new Gson();
		for(Object user : users){
			userList.add(gson.fromJson(((JSONObject)user).toJSONString(), User.class));
		}
		return userList;
	}

	private void saveAllUsers(List<User> users){
		for(User user : users){
			userDao.addUser(user);
		}
	}
	
    private void sendGet() throws Exception {
 
    	String url = this.URL_BASE + this.QUERY_PREFIX;
    	LOGGER.debug("URL TO REQUEST: " + url);
        JSONParser parser = new JSONParser();
        HttpResponse response = null;
        String aufrom = "";
        do {
        	// String restUrl = URLEncoder.encode("You url parameter value", "UTF-8");
    		HttpClient client = HttpClientBuilder.create().build();
        	HttpGet request = new HttpGet(URLEncoder.encode(url, "UTF-8"));
            response = client.execute(request);
            BufferedReader rd = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
            JSONObject objResponse = (JSONObject) parser.parse(rd);
            aufrom = (String) ((JSONObject)((JSONObject)objResponse.get("query-continue")).get("allusers")).get("aufrom");
            JSONArray users = (JSONArray) ((JSONObject)objResponse.get("query")).get("allusers");
            List<User> usersList = getUsersList(users);
            saveAllUsers(usersList);
            url = this.URL_BASE + this.QUERY_PREFIX + "&" +this.QUERY_PARAM + "=" + aufrom;
        } while (response.getStatusLine().getStatusCode() == 200 && !aufrom.isEmpty());
    }
}
