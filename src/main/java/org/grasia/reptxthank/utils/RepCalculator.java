package org.grasia.reptxthank.utils;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Random;
import java.util.Set;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.credit.CreditService;
import org.grasia.reptxthank.service.credit.implementation.CreditServiceImpl;
import org.grasia.reptxthank.service.quality.QualityService;
import org.grasia.reptxthank.service.quality.implementation.QualityServiceImpl;
import org.grasia.reptxthank.service.reputation.ReputationService;
import org.grasia.reptxthank.service.reputation.implementation.ReputationServiceImpl;

public class RepCalculator {
	
	private int NUM_USERS = 9;
	private int NUM_ITEMS = 5;
	private static int ITERACTIONS = 100;
	
	public static void main(String[] args) {
		RepCalculator repCalculator = new RepCalculator();
		ArrayList<User> users = repCalculator.generateUsers();
		users = repCalculator.setInitRep(users);
		HashMap<Long, User> usersMap = repCalculator.genUsersMap(users);
		System.out.println(users.toString());
		System.out.println(usersMap.toString());
		ArrayList<Item> items = repCalculator.generateItems();
		HashMap<Long, Item> itemsMap = repCalculator.genItemsMap(items);
		items = repCalculator.setInitFitness(items);
		System.out.println(items.toString());
		System.out.println(itemsMap.toString());
		// Matriz sin peso de relacion user-item. 
		// Pueden estar tambien items agradecidos por user
		// ArrayList será de ids de Item
		HashMap<Long, ArrayList<Long>> matrixEUsers = repCalculator.buildMatrixEUsers(users, items);
		// HashMap itemId- List<userId>
		HashMap<Long, ArrayList<Long>> matrixEItems = repCalculator.buildMatrixEItems(matrixEUsers);
		
		System.out.println("matrixEUsers: ");
		System.out.println(matrixEUsers.toString());
		System.out.println("matrixEItems: ");
		System.out.println(matrixEItems.toString());
		// HashMap que refleja user-Lista de items hechos por dicho user
		HashMap<Long, ArrayList<Long>> matrixPUsers = repCalculator.buildMatrixPUsers(users, matrixEUsers);
		// HashMap que refleja item-lista de autores de dicho item
		HashMap<Long, ArrayList<Long>> matrixPItems = repCalculator.buildMatrixEItems(matrixPUsers);
		System.out.println("matrixPUsers: ");
		System.out.println(matrixPUsers.toString());
		System.out.println("matrixPItems: ");
		System.out.println(matrixPItems.toString()); 
		// HashMap que refleja el peso de la iteraccion que haya tenido un usuario con algún item
		HashMap<Long, HashMap<Long, Long>> matrixWUsers = repCalculator.buildMatrixWUsers(users, matrixEUsers, matrixPUsers);
		HashMap<Long, HashMap<Long, Long>> matrixWItems = repCalculator.buildMatrixWItems(items, matrixEItems, matrixPItems);
		System.out.println("matrixWUsers: ");
		System.out.println(matrixWUsers.toString());
		System.out.println("matrixWItems: ");
		System.out.println(matrixWItems.toString());
		
		
		Reputation rep = new Reputation();

		rep.setUsersMap(usersMap);
		rep.setItemsMap(itemsMap);
		rep.setMatrixEUsers(matrixEUsers);
		rep.setMatrixPUsers(matrixPUsers);
		rep.setMatrixWUsers(matrixWUsers);
		
		rep.setMatrixEItems(matrixEItems);
		rep.setMatrixPItems(matrixPItems);
		rep.setMatrixWItems(matrixWItems);
		
		int i = 0;
		ReputationService repService = new ReputationServiceImpl(rep);
		CreditService creditService = new CreditServiceImpl(rep);
		QualityService qualityService = new QualityServiceImpl(rep);
		
		System.out.println("################################################");
		
		while (i < ITERACTIONS){
			repService.reputXThank(users);
			creditService.creditXThank(users);
			qualityService.qualityXThank(items);
			System.out.println(users.toString());
			System.out.println(items.toString());
			System.out.println("################################################");
			i++;
		}
		
	}

	private HashMap<Long, HashMap<Long, Long>> buildMatrixWItems(
			ArrayList<Item> items, HashMap<Long, ArrayList<Long>> matrixE,
			HashMap<Long, ArrayList<Long>> matrixP) {
		HashMap<Long, HashMap<Long, Long>> matrixW = new HashMap<Long, HashMap<Long,Long>>();
		for(Item item : items){
			long itemId = item.getId();
			HashMap<Long, Long> weighted = new HashMap<Long, Long>();
			ArrayList<Long> linkedUsers = matrixE.get(itemId);
			ArrayList<Long> usersbyItem = matrixP.get(itemId);
			for(Long userId : linkedUsers){
				if(usersbyItem.indexOf(userId) > -1){
					weighted.put(userId, (long) 2);
				}
				else{
					weighted.put(userId, (long) 1);
				}
			}
			matrixW.put(itemId, weighted);
		}
		return matrixW;
	}

	private HashMap<Long, ArrayList<Long>> buildMatrixEItems(
			HashMap<Long, ArrayList<Long>> matrixEUsers) {
		HashMap<Long, ArrayList<Long>> matrixEItems = new HashMap<Long, ArrayList<Long>>();
		Set<Long> keys = matrixEUsers.keySet();
		for(Long userId : keys){
			ArrayList<Long> itemsByUser = matrixEUsers.get(userId);
			for(Long itemId : itemsByUser){
				Set<Long> itemsKeys = matrixEItems.keySet();
				if(itemsKeys.contains(itemId)){
					matrixEItems.get(itemId).add(userId);
				}
				else{
					ArrayList<Long> usersByItem = new ArrayList<Long>();
					usersByItem.add(userId);
					matrixEItems.put(itemId, usersByItem);
				}
			}
		}
		return matrixEItems;
	}

	private HashMap<Long, HashMap<Long, Long>> buildMatrixWUsers(
			ArrayList<User> users, HashMap<Long, ArrayList<Long>> matrixE,
			HashMap<Long, ArrayList<Long>> matrixP) {
		HashMap<Long, HashMap<Long, Long>> matrixW = new HashMap<Long, HashMap<Long,Long>>();
		for(User user : users){
			long userId = user.getId();
			HashMap<Long, Long> weighted = new HashMap<Long, Long>();
			ArrayList<Long> linkedItems = matrixE.get(userId);
			ArrayList<Long> itemsByUser = matrixP.get(userId);
			for(Long itemId : linkedItems){
				if(itemsByUser.indexOf(itemId) > -1){
					weighted.put(itemId, (long) 2);
				}
				else{
					weighted.put(itemId, (long) 1);
				}
			}
			matrixW.put(userId, weighted);
		}
		return matrixW;
	}

	private HashMap<Long, Item> genItemsMap(ArrayList<Item> items) {
		HashMap<Long, Item> itemsMap = new HashMap<Long, Item>();
		for(Item item : items){
			itemsMap.put(item.getId(), item);
		}
		return itemsMap;
	}

	private HashMap<Long, User> genUsersMap(ArrayList<User> users) {
		HashMap<Long, User> usersMap = new HashMap<Long, User>();
		for(User user : users){
			usersMap.put(user.getId(), user);
		}
		return usersMap;
	}

	private HashMap<Long, ArrayList<Long>> buildMatrixPUsers(ArrayList<User> users, 
			HashMap<Long, ArrayList<Long>> matrixE) {
		HashMap<Long, ArrayList<Long>> matrixP = new HashMap<Long, ArrayList<Long>>();
		for(User user : users){
			ArrayList<Long> itemsCreated = new ArrayList<Long>();
			if(user.getId() == 0 || user.getId() == 14){
				itemsCreated.addAll(matrixE.get(user.getId()));
			}
			else {
				if(matrixE.get(user.getId()).size() > 0){
					itemsCreated.add(matrixE.get(user.getId()).get(0));
				}
			}
			matrixP.put(user.getId(), itemsCreated);
		}
		return matrixP;
	}

	private HashMap<Long, ArrayList<Long>> buildMatrixEUsers(ArrayList<User> users, ArrayList<Item> items){
		HashMap<Long, ArrayList<Long>> matrixE = new HashMap<Long, ArrayList<Long>>();
		Random random = new Random();
		for(User user : users){
			ArrayList<Long> itemsLinked = new ArrayList<Long>();
			for(int i = 0; i < 4; i++){
				if((user.getId() != 2)
						&&(user.getId() != 6)
						&&(user.getId() != 13)){
					int index = random.nextInt(items.size());
					if (itemsLinked.indexOf(items.get(index).getId()) == -1){
						itemsLinked.add(items.get(index).getId());
					}
				}
			}
			matrixE.put(user.getId(), itemsLinked);
		}
		return matrixE;
	}
	
	private ArrayList<Item> setInitFitness(ArrayList<Item> items) {
		ArrayList<Item> auxItems = new ArrayList<Item>();
		float initFitness = (float) (1/Math.sqrt(items.size()));
		for(Item item : items){
			item.setQuality(initFitness);
			auxItems.add(item);
		}
		return auxItems;
	}

	private ArrayList<Item> generateItems() {
		ArrayList<Item> items = new ArrayList<Item>();
		for(int i = 0; i < NUM_ITEMS; i++){
			Item item = new Item();
			item.setId(i);
			item.setCreationDate(new Date());
			items.add(item);
		}
		return items;
	}

	private ArrayList<User> generateUsers(){
		ArrayList<User> users = new ArrayList<User>();
		for(int i = 0; i < NUM_USERS; i++){
			User user = new User();
			user.setId(i);
			user.setName("user-"+i);
			user.setRegistration(new Date());
			users.add(user);
		}
		return users;
	}
	
	private ArrayList<User> setInitRep(ArrayList<User> users){
		ArrayList<User> auxUsers = new ArrayList<User>();
		float initRep = (float) (1/Math.sqrt(users.size()));
		for(User user : users){
			user.setReputation(initRep);
			auxUsers.add(user);
		}
		return auxUsers;
	}

}
