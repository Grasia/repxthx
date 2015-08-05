package org.grasia.reptxthank.dao.user;

import java.util.List;

import org.grasia.reptxthank.model.User;

public interface UserDao {
	public User getUser(long id);
	public List<User> getAllUsers();
	public long addUser(User user);
	public boolean updateUser(User user);

}
