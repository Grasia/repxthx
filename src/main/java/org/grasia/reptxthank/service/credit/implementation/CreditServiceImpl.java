package org.grasia.reptxthank.service.credit.implementation;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.credit.CreditService;

public class CreditServiceImpl implements CreditService{
	
	private Reputation reputation;
	
	private double PA = 0.1;
	
	public CreditServiceImpl(Reputation reputation) {
		this.reputation = reputation;
	}

	@Override
	public float creditXThank(User user) {
		float creditXUser = 0;
		float numItemsByUser = 0;
		float sum = 0;
		HashMap<Long, ArrayList<Long>> matrixP = reputation.getMatrixPUsers();
		ArrayList<Long> itemsLinkedToUser = reputation.getItemsByUser(user.getId());
		ArrayList<Long> itemsAuthByUser = matrixP.get(user.getId());
		HashMap<Long, Item> itemsMap = reputation.getItemsMap();
		
		numItemsByUser = itemsAuthByUser.size();
		float creditMedia = reputation.getAvgCredit();
		Iterator<Long> it = itemsLinkedToUser.iterator();
		while(it.hasNext()){
			long itemId = it.next();
			if(itemsAuthByUser.indexOf(itemId) != -1){
				float itemFitness = itemsMap.get(itemId).getQuality();
				sum += (itemFitness-(PA)*creditMedia);
			}
		}
		creditXUser = numItemsByUser == 0 ? user.getCredit() : (1/(numItemsByUser))*sum;
		return creditXUser;
	}

	
	@Override
	public HashMap<Long, Float> creditXThank(ArrayList<User> users) {
		Iterator<User> it = users.iterator();
//		ArrayList<User> reputatedUsers = new ArrayList<User>();
		HashMap<Long, Float> reputatedUsers = new HashMap<Long, Float>();
		float minCredit = Float.MAX_VALUE , maxCredit = 0;
		while (it.hasNext()){
			User user = it.next();
			float credit = this.creditXThank(user);
			
			maxCredit = maxCredit < credit ? credit : maxCredit;
			minCredit = minCredit > credit ? credit : minCredit;
//			user.setCredit(this.creditXThank(user));
			reputatedUsers.put(user.getId(), credit);
		}
		normalize(reputatedUsers,minCredit,maxCredit);
		return reputatedUsers;
	}
	
	private void normalize(HashMap<Long, Float> reputatedUsers, float minValue, float maxValue){
		 Iterator<Entry<Long, Float>> it = reputatedUsers.entrySet().iterator();
		    while (it.hasNext()) {
		    	HashMap.Entry<Long, Float> pair = it.next();
		        float value= pair.getValue(), normalizedFitness;
		       
		        normalizedFitness = (value - minValue) / (maxValue - minValue);
		        
		        pair.setValue(normalizedFitness);

		    }
		
	}

}
