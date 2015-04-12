package org.grasia.reptxthank.model;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Set;

public class Reputation {
	// key de cada hashMap son los id, de los usuarios y luego items
	// en ese orden.
	// en qu� momento se llenan estos datos?
	// private HashMap<Long, HashMap<Long, Long>> matrixE;
//	private HashMap<Long, ArrayList<Item>> matrixE;
	private HashMap<Long, ArrayList<Long>> matrixEUsers;
	private HashMap<Long, ArrayList<Long>> matrixEItems;
	// matriz de autores por item
//	private HashMap<Long, ArrayList<User>> matrixP;
	private HashMap<Long, ArrayList<Long>> matrixPUsers;
	private HashMap<Long, ArrayList<Long>> matrixPItems;
	// matriz de pesos dependiendo del tipo de interacci�n
	private HashMap<Long, HashMap<Long, Long>> matrixWUsers;
	private HashMap<Long, HashMap<Long, Long>> matrixWItems;
	// Fitness corresponde a cada item. Se tiene en cuenta la totalidad de los items (TODOS)
//	private HashMap<Long, Long> fitness;
	private HashMap<Long, Item> itemsMap;
	private HashMap<Long, User> usersMap;

	public ArrayList<Long> getItemsByUser(long userId){
		return this.matrixEUsers.get(userId);
	}
	public ArrayList<Long> getUsersByItem(long itemId){
		return this.matrixEItems.get(itemId);
	}
	public HashMap<Long, Item> getItemsMap() {
		return itemsMap;
	}
	public void setItemsMap(HashMap<Long, Item> itemsMap) {
		this.itemsMap = itemsMap;
	}
	public HashMap<Long, User> getUsersMap() {
		return usersMap;
	}
	public void setUsersMap(HashMap<Long, User> usersMap) {
		this.usersMap = usersMap;
	}
	public HashMap<Long, ArrayList<Long>> getMatrixEUsers() {
		return matrixEUsers;
	}
	public void setMatrixEUsers(HashMap<Long, ArrayList<Long>> matrixEUsers) {
		this.matrixEUsers = matrixEUsers;
	}
	public HashMap<Long, ArrayList<Long>> getMatrixEItems() {
		return matrixEItems;
	}
	public void setMatrixEItems(HashMap<Long, ArrayList<Long>> matrixEItems) {
		this.matrixEItems = matrixEItems;
	}
	public HashMap<Long, ArrayList<Long>> getMatrixPUsers() {
		return matrixPUsers;
	}
	public void setMatrixPUsers(HashMap<Long, ArrayList<Long>> matrixPUsers) {
		this.matrixPUsers = matrixPUsers;
	}
	public HashMap<Long, ArrayList<Long>> getMatrixPItems() {
		return matrixPItems;
	}
	public void setMatrixPItems(HashMap<Long, ArrayList<Long>> matrixPItems) {
		this.matrixPItems = matrixPItems;
	}
	public HashMap<Long, HashMap<Long, Long>> getMatrixWUsers() {
		return matrixWUsers;
	}
	public void setMatrixWUsers(HashMap<Long, HashMap<Long, Long>> matrixWUsers) {
		this.matrixWUsers = matrixWUsers;
	}
	public HashMap<Long, HashMap<Long, Long>> getMatrixWItems() {
		return matrixWItems;
	}
	public void setMatrixWItems(HashMap<Long, HashMap<Long, Long>> matrixWItems) {
		this.matrixWItems = matrixWItems;
	}
	
	public float getAvgFitness(){
		float media = 0;
		float totalItems = 0;
		float totalFitness = 0;
		Set<Long> keys = this.itemsMap.keySet();
		totalItems = keys.size();
		Iterator<Long> it = keys.iterator();
		while(it.hasNext()){
			long key = it.next();
			totalFitness += this.itemsMap.get(key).getQuality();
		}
		media = (totalFitness/totalItems);
		return media;
	}
	
	public float getAvgCredit(){
		float media = 0;
		float totalUsers = 0;
		float totalCredit = 0;
		Set<Long> keys = this.usersMap.keySet();
		totalUsers = keys.size();
		Iterator<Long> it = keys.iterator();
		while(it.hasNext()){
			long key = it.next();
			totalCredit += this.usersMap.get(key).getCredit();
		}
		media = (totalCredit/totalUsers);
		return media;
	}
	
	public float getAvgReputation(){
		float media = 0;
		float totalUsers = 0;
		float totalRep = 0;
		Set<Long> keys = this.usersMap.keySet();
		totalUsers = keys.size();
		Iterator<Long> it = keys.iterator();
		while(it.hasNext()){
			long key = it.next();
			totalRep += this.usersMap.get(key).getReputation();
		}
		media = (totalRep/totalUsers);
		return media;
	}
}
