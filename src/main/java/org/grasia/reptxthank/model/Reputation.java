package org.grasia.reptxthank.model;

import java.util.ArrayList;
import java.util.HashMap;

public class Reputation {
	// key de cada hashMap son los id, de los usuarios y luego items
	// en ese orden.
	// en qu� momento se llenan estos datos?
	// private HashMap<Long, HashMap<Long, Long>> matrixE;
//	private HashMap<Long, ArrayList<Item>> matrixE;
	private HashMap<Long, ArrayList<Long>> matrixE;
	// matriz de autores por item
//	private HashMap<Long, ArrayList<User>> matrixP;
	private HashMap<Long, ArrayList<Long>> matrixP;
	// matriz de pesos dependiendo del tipo de interacci�n
	private HashMap<Long, HashMap<Long, Long>> matrixW;
	// Fitness corresponde a cada item. Se tiene en cuenta la totalidad de los items (TODOS)
//	private HashMap<Long, Long> fitness;
	private HashMap<Long, Item> itemsMap;
	private HashMap<Long, User> usersMap;
	
	public HashMap<Long, ArrayList<Long>> getMatrixE() {
		return matrixE;
	}
	public void setMatrixE(HashMap<Long, ArrayList<Long>> matrixE) {
		this.matrixE = matrixE;
	}
	public HashMap<Long, ArrayList<Long>> getMatrixP() {
		return matrixP;
	}
	public void setMatrixP(HashMap<Long, ArrayList<Long>> matrixP) {
		this.matrixP = matrixP;
	}
	public HashMap<Long, HashMap<Long, Long>> getMatrixW() {
		return matrixW;
	}
	public void setMatrixW(HashMap<Long, HashMap<Long, Long>> matrixW) {
		this.matrixW = matrixW;
	}
	public ArrayList<Long> getItemsByUser(long userId){
		return this.matrixE.get(userId);
	}
	public long getFitnesValue(long userId, long itemId){
		return this.matrixW.get(userId).get(itemId);
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
}
