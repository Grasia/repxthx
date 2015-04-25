package org.grasia.reptxthank.dao.user;

import java.util.List;

import org.grasia.reptxthank.model.User;

public interface UserDao {
	public User getUser(long id);
	public List<User> getAllUsers();
	public void addUser(User user);
	public void updateUser(User user);

}
