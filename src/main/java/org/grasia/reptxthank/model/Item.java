package org.grasia.reptxthank.model;


public class Item {
	private long id;
	private String type;
	private String title;
	private float quality;
	
	public long getId() {
		return id;
	}
	public void setId(long id) {
		this.id = id;
	}
	public float getQuality() {
		return quality;
	}
	public void setQuality(float quality) {
		this.quality = quality;
	}
	public String getType() {
		return type;
	}
	public void setType(String type) {
		this.type = type;
	}
	
	public String getTitle() {
		return title;
	}
	public void setTitle(String title) {
		this.title = title;
	}
	@Override
	public String toString(){
		return "ItemId: "+id+" -Fitness: "+quality+"\n";
		
	}

}
