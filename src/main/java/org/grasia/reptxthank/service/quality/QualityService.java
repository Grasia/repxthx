package org.grasia.reptxthank.service.quality;

import java.util.ArrayList;
import java.util.HashMap;

import org.grasia.reptxthank.model.Item;

public interface QualityService {

	public float qualityXThank(Item item);
	public HashMap<Long, Float> qualityXThank(ArrayList<Item> items);

}
