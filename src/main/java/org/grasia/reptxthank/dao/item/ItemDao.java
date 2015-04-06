package org.grasia.reptxthank.dao.item;

import java.util.List;

import org.grasia.reptxthank.model.Item;

public interface ItemDao {
	
	public Item getItem(long id);
	public List<Item> getAllItems();
	public void addItem(Item item);
	public void updateItem(Item item);

}
