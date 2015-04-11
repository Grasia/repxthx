package org.grasia.reptxthank.service.quality.implementation;

import java.util.ArrayList;

import org.grasia.reptxthank.model.Item;
import org.grasia.reptxthank.model.Reputation;
import org.grasia.reptxthank.service.quality.QualityService;

public class QualityServiceImpl implements QualityService{
	
	private Reputation reputation;
	
	private double PR = 0.1;
	
	public QualityServiceImpl(Reputation reputation) {
		this.reputation = reputation;
	}
	
	@Override
	public float qualityXThank(Item item) {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public ArrayList<Item> qualityXThank(ArrayList<Item> items) {
		// TODO Auto-generated method stub
		return null;
	}

}
