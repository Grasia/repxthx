package org.grasia.reptxthank.service.reputation;

import java.util.ArrayList;
import java.util.HashMap;

import org.grasia.reptxthank.model.User;

public interface ReputationService {

	public float reputXThank(User user);
	// public ArrayList<User> reputXThank(ArrayList<User> users);
	public HashMap<Long, Float> reputXThank(ArrayList<User> users);

}
