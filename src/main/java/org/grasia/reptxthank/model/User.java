package org.grasia.reptxthank.model;

import java.util.ArrayList;
import java.util.Date;
/*
 * 
 * CREATE TABLE IF NOT EXISTS `USER` (
  `pk_userId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `wiki_userId` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ending_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pk_userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
 */
public class User {
	private long id;
	private String name;
	private String wiki_userId;
	private String email;
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
	
	@Override
	public String toString(){
		return "UserId: "+id+" -Credit: "+credit+" -Reputation: "+reputation+"\n";
	}
	public String getWiki_userId() {
		return wiki_userId;
	}
	public void setWiki_userId(String wiki_userId) {
		this.wiki_userId = wiki_userId;
	}
	public String getEmail() {
		return email;
	}
	public void setEmail(String email) {
		this.email = email;
	}
	
}
