package org.grasia.reptxthank.model;

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
	@Override
	public String toString(){
		// return "UserId: "+id+" -Credit: "+credit+" -Reputation: "+reputation+"\n";
		return "UserId: "+id+"; Name: "+name;
	}
}
