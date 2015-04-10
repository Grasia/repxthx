package org.grasia.reptxthank.utils;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Random;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.User;

public class RepCalculator {
	
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
		HashMap<Long, ArrayList<Long>> matrixE = repCalculator.buildMatrixE(users, items);
		System.out.println(matrixE.toString());
		// HashMap que refleja user-Lista de items hechos por dicho user
		HashMap<Long, ArrayList<Long>> matrixP = repCalculator.buildMatrixP(users, matrixE);
		System.out.println(matrixP.toString());
		HashMap<Long, HashMap<Long, Long>> matrixW = repCalculator.buildMatrixW(users, matrixE, matrixP);
		System.out.println(matrixW.toString());
		
	}

	private HashMap<Long, HashMap<Long, Long>> buildMatrixW(
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

	private HashMap<Long, ArrayList<Long>> buildMatrixP(ArrayList<User> users, 
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

	private HashMap<Long, ArrayList<Long>> buildMatrixE(ArrayList<User> users, ArrayList<Item> items){
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
		for(int i = 0; i < 5; i++){
			Item item = new Item();
			item.setId(i);
			item.setCreationDate(new Date());
			items.add(item);
		}
		return items;
	}

	private ArrayList<User> generateUsers(){
		ArrayList<User> users = new ArrayList<User>();
		for(int i = 0; i < 16; i++){
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
