package org.grasia.reptxthank.service.reputation.implementation;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;

import org.grasia.reptxthank.dao.user.UserDao;
import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.reputation.ReputationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service("repService")
public class ReputationServiceImpl implements ReputationService {

	@Autowired
	private UserDao userDao;
	
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
		HashMap<Long, HashMap<Long, Long>> matrixW = reputation
				.getMatrixWUsers();
		HashMap<Long, Item> itemsMap = reputation.getItemsMap(); // hashmap de
																	// items
																	// key=itemId
		numItemsByUser = itemsByUser.size();
		float fitnessMedia = reputation.getAvgFitness();
		Iterator<Long> it = itemsByUser.iterator();

		while (it.hasNext()) {
			long item = it.next();
			float itemFitness = itemsMap.get(item).getQuality();
			long wia = matrixW.get(user.getId()).get(item);
			sum += wia * (itemFitness - (PF) * fitnessMedia);
		}
		// repxuser = (1/(numItemsByUser^(or)))*sum;
		repxuser = numItemsByUser == 0 ? user.getReputation()
				: (1 / (numItemsByUser)) * sum;
		return repxuser;
	}

	private void normalize(HashMap<Long, Float> reputatedUSers, float minValue,
			float maxValue) {
		Iterator<Entry<Long, Float>> it = reputatedUSers.entrySet().iterator();
		while (it.hasNext()) {
			Entry<Long, Float> pair = it.next();
			float value = pair.getValue(), normalizedFitness;

			normalizedFitness = (value - minValue)/ (maxValue - minValue);

			pair.setValue(normalizedFitness);

		}

	}

	@Override
	public HashMap<Long, Float> reputXThank(ArrayList<User> users) {
		Iterator<User> it = users.iterator();
		// ArrayList<User> reputatedUsers = new ArrayList<User>();
		HashMap<Long, Float> reputatedUsers = new HashMap<Long, Float>();
		float minReputation =Float.MAX_VALUE , maxReputation = 0;
		while (it.hasNext()) {
			User user = it.next();
			float reputation = this.reputXThank(user);

			maxReputation = maxReputation < reputation ? reputation : maxReputation;
			minReputation = minReputation > reputation ? reputation: minReputation;
			// user.setReputation(this.reputXThank(user));
			reputatedUsers.put(user.getId(), this.reputXThank(user));
		}
		normalize(reputatedUsers, minReputation, maxReputation);
		return reputatedUsers;
	}

}
