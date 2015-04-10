package org.grasia.reptxthank.model;

import java.util.ArrayList;
import java.util.Date;

public class User {
	private long id;
	private String name;
	private long editCount;
	private Date registration;
	private ArrayList<Item> contributions;
	private ArrayList<Item> grateFulContributions;
	private float credit;
	private float reputation;
	
	public long getId() {
		return id;
	}
	public void setId(long id) {
		this.id = id;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public long getEditCount() {
		return editCount;
	}
	public void setEditCount(long editCount) {
		this.editCount = editCount;
	}
	public Date getRegistration() {
		return registration;
	}
	public void setRegistration(Date registration) {
		this.registration = registration;
	}
	public ArrayList<Item> getContributions() {
		return contributions;
	}
	public void setContributions(ArrayList<Item> contributions) {
		this.contributions = contributions;
	}
	public float getCredit() {
		return credit;
	}
	public void setCredit(float credit) {
		this.credit = credit;
	}
	public float getReputation() {
		return reputation;
	}
	public void setReputation(float reputation) {
		this.reputation = reputation;
	}
	public ArrayList<Item> getGrateFulContributions() {
		return grateFulContributions;
	}
	public void setGrateFulContributions(ArrayList<Item> grateFulContributions) {
		this.grateFulContributions = grateFulContributions;
	}
	
}
