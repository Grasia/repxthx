package org.grasia.reptxthank.service.credit.implementation;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Set;

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
		HashMap<Long, ArrayList<Long>> matrixP = reputation.getMatrixP();
		ArrayList<Long> itemsLinkedToUser = reputation.getItemsByUser(user.getId());
		ArrayList<Long> itemsAuthByUser = matrixP.get(user.getId());
		HashMap<Long, User> usersMap = reputation.getUsersMap();
		HashMap<Long, Item> itemsMap = reputation.getItemsMap();
		
		numItemsByUser = itemsAuthByUser.size();
		float creditMedia = this.calcMediaCredit(usersMap);
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
	public ArrayList<User> creditXThank(ArrayList<User> users) {
		Iterator<User> it = users.iterator();
		ArrayList<User> reputatedUsers = new ArrayList<User>();
		while (it.hasNext()){
			User user = it.next();
			user.setCredit(this.creditXThank(user));
		}
		return reputatedUsers;
	}
	

	private float calcMediaCredit(HashMap<Long, User> mapUser){
		float media = 0;
		float totalUsers = 0;
		float totalCredit = 0;
		Set<Long> keys = mapUser.keySet();
		totalUsers = keys.size();
		Iterator<Long> it = keys.iterator();
		while(it.hasNext()){
			long key = it.next();
			totalCredit += mapUser.get(key).getCredit();
		}
		media = (totalCredit/totalUsers);
		return media;
	}


}
