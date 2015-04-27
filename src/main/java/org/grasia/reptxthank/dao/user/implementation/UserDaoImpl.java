package org.grasia.reptxthank.dao.user.implementation;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;

import org.grasia.reptxthank.dao.user.UserDao;
import org.grasia.reptxthank.model.User;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

@Transactional
@Repository("userDao")
public class UserDaoImpl implements UserDao {
	@Autowired
	private JdbcTemplate jdbcTemplate;
	
	private String qry0 = "SELECT pk_userId,"
			+ " user_name,"
			+ " credit,"
			+ " reputation"
			+ " FROM USER";
	
	@Override
	public User getUser(long id) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<User> getAllUsers() {
		List<User> users = jdbcTemplate.query(qry0, new UserMapper());
		return users;
	}

	@SuppressWarnings("deprecation")
	@Override
	public long addUser(User user) {
		String qry = "INSERT INTO USER(user_name, reputation, credit) values (? , ?, ?) RETURNING pk_userId";
		long id = jdbcTemplate.queryForLong(qry, 
				user.getName(),
				user.getReputation(),
				user.getCredit());
		return id;
	}

	@SuppressWarnings("deprecation")
	@Override
	public boolean updateUser(User user) {
		String qry = "UPDATE USER SET"
				+ " user_name = ?,"
				+ " reputation = ?,"
				+ " credit = ?"
				+ " WHERE pk_itemId = ?";
		int rowsModified = jdbcTemplate.queryForInt(qry, new Object[]{
				user.getName(), 
				user.getReputation(), 
				user.getCredit(),
				user.getId()});
		return rowsModified != 0 ? true : false;
	}

	private static final class UserMapper implements RowMapper<User> {
		
        public User mapRow(ResultSet rs, int rowNum) throws SQLException {
        	User user = new User();
        	user.setId(rs.getLong("pk_userId"));
        	user.setName(rs.getString("user_name"));
        	user.setReputation(rs.getFloat("reputation"));
            user.setCredit(rs.getFloat("credit"));
            return user;
        }
    }
	
}
