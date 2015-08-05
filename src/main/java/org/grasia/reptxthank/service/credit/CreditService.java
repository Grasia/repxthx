package org.grasia.reptxthank.service.credit;

import java.util.ArrayList;
import java.util.HashMap;

import org.grasia.reptxthank.model.User;

public interface CreditService {
	
	public float creditXThank(User user);
//	public ArrayList<User> creditXThank(ArrayList<User> users);
	public HashMap<Long, Float> creditXThank(ArrayList<User> users);

}
