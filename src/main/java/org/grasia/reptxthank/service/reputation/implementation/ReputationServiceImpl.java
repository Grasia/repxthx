package org.grasia.reptxthank.service.reputation.implementation;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Set;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.reputation.ReputationService;

public class ReputationServiceImpl implements ReputationService {

	private Reputation reputation;

	@Override
	public long reputXThank(long userId) {
		long repxuser = 0;
		long numItemsByUser = 0;
		long sum = 0;
		ArrayList<Item> itemsByUser = reputation.getItemsByUser(userId);
		HashMap<Long, HashMap<Long, Long>> matrixW = reputation.getMatrixW();
		HashMap<Long, Long> fitnessMatrix = reputation.getFitness();
		numItemsByUser = itemsByUser.size();
		long fitnessMedia = this.calcMediaFitness(fitnessMatrix);
		Iterator<Item> it = itemsByUser.iterator();
		while(it.hasNext()){
			Item item = it.next();
			long itemFitness = fitnessMatrix.get(item.getId());
			long wia = matrixW.get(userId).get(item.getId());
			// sum += wia*(itemFitness-(pf)*fitnessMedia);
			sum += wia*(itemFitness-fitnessMedia);
		}
		// repxuser = (1/(numItemsByUser^(or)))*sum;
		repxuser = (1/(numItemsByUser))*sum;
		return repxuser;
	}

	@Override
	public ArrayList<User> reputXThank(ArrayList<User> users) {
		Iterator<User> it = users.iterator();
		ArrayList<User> reputatedUsers = new ArrayList<User>();
		while (it.hasNext()){
			User user = it.next();
			user.setReputation(this.reputXThank(user.getId()));
		}
		return reputatedUsers;
	}

	private long calcMediaFitness(HashMap<Long, Long> matrixF){
		long media = 0;
		long totalItems = 0;
		long totalFitness = 0;
		Set<Long> keys = matrixF.keySet();
		totalItems = keys.size();
		Iterator<Long> it = keys.iterator();
		while(it.hasNext()){
			long key = it.next();
			totalFitness += matrixF.get(key);
		}
		media = (totalFitness/totalItems);
		return media;
	}

}
