package org.grasia.reptxthank.model;

import java.util.ArrayList;
import java.util.HashMap;

public class Reputation {
	// key de cada hashMap son los id, de los usuarios y luego items
	// en ese orden.
	// en qu� momento se llenan estos datos?
	// private HashMap<Long, HashMap<Long, Long>> matrixE;
	private HashMap<Long, ArrayList<Item>> matrixE;
	// matriz de autores por item
	private HashMap<Long, ArrayList<User>> matrixP;
	// matriz de pesos dependiendo del tipo de interacci�n
	private HashMap<Long, HashMap<Long, Long>> matrixW;
	
	
	public HashMap<Long, ArrayList<Item>> getMatrixE() {
		return matrixE;
	}
	public void setMatrixE(HashMap<Long, ArrayList<Item>> matrixE) {
		this.matrixE = matrixE;
	}
	public HashMap<Long, ArrayList<User>> getMatrixP() {
		return matrixP;
	}
	public void setMatrixP(HashMap<Long, ArrayList<User>> matrixP) {
		this.matrixP = matrixP;
	}
	public HashMap<Long, HashMap<Long, Long>> getMatrixW() {
		return matrixW;
	}
	public void setMatrixW(HashMap<Long, HashMap<Long, Long>> matrixW) {
		this.matrixW = matrixW;
	}
	public ArrayList<Item> getItemsByUser(long userId){
		return this.matrixE.get(userId);
	}
	public long getFitnesValue(long userId, long itemId){
		return this.matrixW.get(userId).get(itemId);
	}
}
