package org.grasia.reptxthank.model;

import java.util.ArrayList;
import java.util.Date;

public class Item {
	private long id;
	private Date creationDate;
	private float quality;
	
	// Amount of people who thank for this item
	private long thankful;
	private ArrayList<User> contributors;
	private ArrayList<User> thankfulContributors;
	
	public long getId() {
		return id;
	}
	public void setId(long id) {
		this.id = id;
	}
	public Date getCreationDate() {
		return creationDate;
	}
	public void setCreationDate(Date creationDate) {
		this.creationDate = creationDate;
	}
	public ArrayList<User> getContributors() {
		return contributors;
	}
	public void setContributors(ArrayList<User> contributors) {
		this.contributors = contributors;
	}
	public float getQuality() {
		return quality;
	}
	public void setQuality(float quality) {
		this.quality = quality;
	}
	public long getThankful() {
		return thankful;
	}
	public void setThankful(long thankful) {
		this.thankful = thankful;
	}
	public ArrayList<User> getThankfulContributors() {
		return thankfulContributors;
	}
	public void setThankfulContributors(ArrayList<User> thankfulContributors) {
		this.thankfulContributors = thankfulContributors;
	}
}
