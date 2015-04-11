package org.grasia.reptxthank.service.reputation;

import java.util.ArrayList;

import org.grasia.reptxthank.model.User;

public interface ReputationService {

	public float reputXThank(User user);
	public ArrayList<User> reputXThank(ArrayList<User> users);

}
