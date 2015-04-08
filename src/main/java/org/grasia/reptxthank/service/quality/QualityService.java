package org.grasia.reptxthank.service.quality;

import java.util.ArrayList;

import org.grasia.reptxthank.model.Item;

public interface QualityService {

	public long qualityXThank(long itemId);
	public ArrayList<Item> qualityXThank(ArrayList<Item> items);

}
