package org.grasia.reptxthank.service.repxthx.implementation;

import java.util.List;

import org.grasia.reptxthank.dao.user.UserDao;
import org.grasia.reptxthank.model.User;
import org.grasia.reptxthank.service.repxthx.RepxThxService;
import org.grasia.reptxthank.utils.UpdaterThread;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Service;

@Component
@Service("repxthxService")
public class RepxThxServiceImpl implements RepxThxService {
	
	@Autowired
	private UserDao userDao;
	
	@Autowired
	private UpdaterThread updaterThread;

	private boolean isFirstTime = true;
	
	@Override
	public List<User> getAllUsers() {
		List<User> users = userDao.getAllUsers();
		return users;
	}

	@Override
	public void launchThread() {
		// TODO Auto-generated method stub
		updaterThread.start();
	}

	@Override
	public void addUsersList(List<User> users) {
		for(User user : users){
			userDao.addUser(user);
		}
	}

	public boolean isFirstTime() {
		return isFirstTime;
	}

	public void setFirstTime(boolean isFirstTime) {
		this.isFirstTime = isFirstTime;
	}	
}
