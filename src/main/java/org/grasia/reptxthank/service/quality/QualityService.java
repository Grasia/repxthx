package org.grasia.reptxthank.service.quality;

import java.util.ArrayList;

import org.grasia.reptxthank.model.Item;

public interface QualityService {

	public float qualityXThank(Item item);
	public ArrayList<Item> qualityXThank(ArrayList<Item> items);

}
