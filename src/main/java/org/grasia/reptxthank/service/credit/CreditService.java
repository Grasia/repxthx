package org.grasia.reptxthank.service.credit;

import java.util.ArrayList;

import org.grasia.reptxthank.model.User;

public interface CreditService {
	
	public float creditXThank(User user);
	public ArrayList<User> creditXThank(ArrayList<User> users);

}
