package org.grasia.reptxthank.service.repxthx.implementation;

import java.util.List;

import org.grasia.reptxthank.dao.user.UserDao;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.repxthx.RepxThxService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Service;

@Component
@Service("repxthxService")
public class RepxThxServiceImpl implements RepxThxService {
	
	@Autowired
	private UserDao userDao;

	@Override
	public List<User> getAllUsers() {
		List<User> users = userDao.getAllUsers();
		return users;
	}

	@Override
	public void launchThread() {
		// TODO Auto-generated method stub
		
	}
	
	
	
}
