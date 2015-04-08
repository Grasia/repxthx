package org.grasia.reptxthank.utils;
 
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.HttpClientBuilder;
import org.apache.http.util.EntityUtils;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
 
public class HttpClientExample {
	
    public static void main(String[] args) throws Exception {
 
        HttpClientExample http = new HttpClientExample();
 
        System.out.println("Testing 1 - Send Http GET request");
        http.sendGet();
 
    }

    private void sendGet() throws Exception {
 
        String url = "http://en.wikipedia.org/w/api.php?action=query&list=allusers&format=json&aulimit=100";
 
        HttpClient client = HttpClientBuilder.create().build();
        HttpGet request = new HttpGet(url);
        HttpResponse response;
        
        
        response = client.execute(request);
        HttpEntity entity = response.getEntity();
		String content = EntityUtils.toString(entity);
		System.out.println("Response--> "+ content);
        JSONParser parser = new JSONParser();
        JSONObject json = (JSONObject) parser.parse(content);
        System.out.println("JSONObject Response --> "+ json.toString());
    }
}