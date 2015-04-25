package org.grasia.reptxthank.service.quality.implementation;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.quality.QualityService;
import org.springframework.stereotype.Service;

@Service("quaService")
public class QualityServiceImpl implements QualityService{
	
	private Reputation reputation;
	
	private double PR = 0.1;
	private double LAMBDA = 0.5;
	
	public QualityServiceImpl(Reputation reputation) {
		this.reputation = reputation;
	}
	
	@Override
	public float qualityXThank(Item item) {
		float quality = 0;
		float itemEDegree = 0;
		float itemPDegree = 0;
		float averageRep = reputation.getAvgReputation();
		float sumRep = 0;
		float sumCredit = 0;
		
		
		HashMap<Long, User> usersMap = reputation.getUsersMap();
		HashMap<Long, ArrayList<Long>> matrixE = reputation.getMatrixEItems();
		HashMap<Long, ArrayList<Long>> matrixP = reputation.getMatrixPItems();
		HashMap<Long, HashMap<Long, Long>> matrixW = reputation.getMatrixWItems();
		
		itemEDegree = matrixE.get(item.getId()).size();
		itemPDegree = matrixP.get(item.getId()).size();
		
		sumRep = this.calcSumRep(usersMap, matrixE.get(item.getId()), matrixW.get(item.getId()), averageRep);
		sumCredit = this.calcSumCredit(usersMap, matrixP.get(item.getId()));
		
		// fitness
		quality = (float) ((((1-LAMBDA)/itemEDegree)*sumRep) + ((LAMBDA/itemPDegree)*sumCredit));
		
		return quality;
	}

	@Override
	public HashMap<Long, Float> qualityXThank(ArrayList<Item> items) {
		Iterator<Item> it = items.iterator();
//		ArrayList<Item> qualifiedItems = new ArrayList<Item>();
		HashMap<Long, Float> qualifiedItems = new HashMap<Long, Float>();
		
		float minFitness = Float.MAX_VALUE , maxFitness = 0;
		while (it.hasNext()){
			Item item = it.next();
			float fitness = this.qualityXThank(item);
			
			maxFitness = maxFitness < fitness ? fitness : maxFitness;
			minFitness = minFitness > fitness ? fitness : minFitness;
			
//			item.setQuality(this.qualityXThank(item));
			
			qualifiedItems.put(item.getId(), fitness);
		}
		
		normalize(qualifiedItems,minFitness,maxFitness);
		return qualifiedItems;
	}
	
	private void normalize(HashMap<Long, Float> qualifiedItems, float minValue, float maxValue){
		 Iterator<Entry<Long, Float>> it = qualifiedItems.entrySet().iterator();
		    while (it.hasNext()) {
		    	Entry<Long, Float> pair = it.next();
		        float value= pair.getValue(), normalizedFitness;

		        normalizedFitness = (value - minValue) / (maxValue - minValue);
		        
		        pair.setValue(normalizedFitness);
		       
		    }
		
	}

	private float calcSumRep(HashMap<Long, User> usersMap,
			ArrayList<Long> usersByItem, HashMap<Long, Long> weightsByItem, 
			float averageRep) {
		float sum = 0;
		for(long userId : usersByItem){
			sum += weightsByItem.get(userId)*(usersMap.get(userId).getReputation() - (PR*averageRep));
		}
		return sum;
	}


	private float calcSumCredit(HashMap<Long, User> usersMap,
			ArrayList<Long> authorsByItem) {
		float sum = 0;
		for(long userId : authorsByItem){
			sum += usersMap.get(userId).getCredit();
		}
		return sum;
	}
}
