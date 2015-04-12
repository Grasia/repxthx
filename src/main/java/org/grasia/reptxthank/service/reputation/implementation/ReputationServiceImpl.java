package org.grasia.reptxthank.service.reputation.implementation;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.reputation.ReputationService;

public class ReputationServiceImpl implements ReputationService {

	private Reputation reputation;
	
	private double PF = 0.1;
	
	public ReputationServiceImpl(Reputation reputation) {
		this.reputation = reputation;
	}

	@Override
	public float reputXThank(User user) {
		float repxuser = 0;
		float numItemsByUser = 0;
		float sum = 0;
		ArrayList<Long> itemsByUser = reputation.getItemsByUser(user.getId());
		HashMap<Long, HashMap<Long, Long>> matrixW = reputation.getMatrixWUsers();
		HashMap<Long, Item> itemsMap = reputation.getItemsMap(); // hashmap de items key=itemId
		numItemsByUser = itemsByUser.size();
		float fitnessMedia = reputation.getAvgFitness();
		Iterator<Long> it = itemsByUser.iterator();
		while(it.hasNext()){
			long item = it.next();
			float itemFitness = itemsMap.get(item).getQuality();
			long wia = matrixW.get(user.getId()).get(item);
			sum += wia*(itemFitness-(PF)*fitnessMedia);
		}
		// repxuser = (1/(numItemsByUser^(or)))*sum;
		repxuser = numItemsByUser == 0 ? user.getReputation() : (1/(numItemsByUser))*sum;
		return repxuser;
	}

	@Override
	public ArrayList<User> reputXThank(ArrayList<User> users) {
		Iterator<User> it = users.iterator();
		ArrayList<User> reputatedUsers = new ArrayList<User>();
		while (it.hasNext()){
			User user = it.next();
			user.setReputation(this.reputXThank(user));
		}
		return reputatedUsers;
	}
	
}
