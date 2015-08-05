package org.grasia.reptxthank.dao.item;

import java.util.List;

import org.grasia.reptxthank.model.Item;

public interface ItemDao {
	
	public Item getItem(long id);
	public List<Item> getAllItems();
	public long addItem(Item item);
	public boolean updateItem(Item item);

}
