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
			+ " FROM ITEM";
	
	@Override
	public Item getItem(long id) {
		List<Item> items = null;
		String qryAux = qry0 + " WHERE pk_itemId = ?";
		items = jdbcTemplate.query(qryAux, new Object[]{id}, new ItemMapper());
		return items.size() > 0 ? items.get(0) : null;
	}

	@Override
	public List<Item> getAllItems() {
		List<Item> items = jdbcTemplate.query(qry0, new ItemMapper());
		return items;
	}

	@SuppressWarnings("deprecation")
	@Override
	public long addItem(Item item) {
		String qry = "INSERT INTO ITEM(item_type, quality) values (? , ?) RETURNING pk_itemId";
		long id = jdbcTemplate.queryForLong(qry, 
				item.getType(),
				item.getQuality());
		return id;
	}

	@SuppressWarnings("deprecation")
	@Override
	public boolean updateItem(Item item) {
		String qry = "UPDATE ITEM SET quality = ? WHERE pk_itemId = ?";
		int rowsModified = jdbcTemplate.queryForInt(qry, new Object[]{item.getQuality(), item.getId()});
		return rowsModified != 0 ? true : false;
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
