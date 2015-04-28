package org.grasia.reptxthank.utils;

import java.io.BufferedReader;
import java.io.InputStreamReader;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.HttpClientBuilder;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Value;


public class UpdaterThread extends Thread{

	@Value("${updater.thread.sleeptime}")
	private long sleeptime;
	
	private Logger LOGGER = LoggerFactory.getLogger(UpdaterThread.class);
	
	public void run(){
		LOGGER.info("Thread launched");
		// while(true)
	}
	
	// HTTP GET request
    private void sendGet() throws Exception {
 
        String url = "http://en.wikipedia.org/w/api.php?action=query&list=allusers&format=json&aulimit=100";
 
        HttpClient client = HttpClientBuilder.create().build();
        HttpGet request = new HttpGet(url);
        String aufrom;
        HttpResponse response;
 
        do {
            response = client.execute(request);
 
            BufferedReader rd = new BufferedReader(new InputStreamReader(
                    response.getEntity().getContent()));
 
            JsonParser jsonParser = new JsonParser();
            aufrom = jsonParser.parse(rd).getAsJsonObject()
                    .get("query-continue").getAsJsonObject().get("allusers")
                    .getAsJsonObject().get("aufrom").getAsString();
 
        } while (response.getStatusLine().getStatusCode() == 200
                && !aufrom.isEmpty());
 
    }
}
