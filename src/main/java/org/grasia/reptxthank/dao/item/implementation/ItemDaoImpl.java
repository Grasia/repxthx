package org.grasia.reptxthank.dao.item.implementation;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;

import org.grasia.reptxthank.dao.item.ItemDao;
import org.grasia.reptxthank.model.Item;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.springframework.stereotype.Repository;

@Repository("itemDao")
public class ItemDaoImpl implements ItemDao {
	
	@Autowired
	private JdbcTemplate jdbcTemplate;
	
	private String qry0 = "SELECT pk_itemId,"
			+ " item_type"
			+ " FROM USER";
	
	@Override
	public Item getItem(long id) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<Item> getAllItems() {
		List<Item> items = jdbcTemplate.query(qry0, new ItemMapper());
		return items;
	}

	@Override
	public void addItem(Item item) {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void updateItem(Item item) {
		// TODO Auto-generated method stub
		
	}

	private static final class ItemMapper implements RowMapper<Item> {
		
        public Item mapRow(ResultSet rs, int rowNum) throws SQLException {
        	Item item = new Item();
        	item.setId(rs.getLong("pk_userId"));
        	item.setType(rs.getString("item_type"));
            return item;
        }
    }
	
}
