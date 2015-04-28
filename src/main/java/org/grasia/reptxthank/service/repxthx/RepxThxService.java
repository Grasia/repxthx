package org.grasia.reptxthank.service.repxthx;

import java.util.List;

import org.grasia.reptxthank.model.User;

public interface RepxThxService {
	
	List<User> getAllUsers();
	
	void launchThread();
}
