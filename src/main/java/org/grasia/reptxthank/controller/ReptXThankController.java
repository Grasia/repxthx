package org.grasia.reptxthank.controller;

import org.grasia.reptxthank.service.repxthx.RepxThxService;
import org.json.simple.JSONArray;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class ReptXThankController {
	
	@Autowired
	private RepxThxService repxthxService;
	
	@RequestMapping("/welcome")
	public String sayHello(ModelMap model){
		JSONArray users = (JSONArray) repxthxService.getAllUsers();
		model.addAttribute("message", "ReptXThank users");
		return "welcome";
	}
	
	@RequestMapping("/")
	public String fisrtContact(ModelMap model){
		model.addAttribute("message", "World!! Please, try to go to ReptXThank/welcome");
		return "welcome";
	}
	
	@RequestMapping("/item_quality/{id_item}")
	public String getItemQuality(ModelMap model,
			@PathVariable String id_item){
		model.addAttribute("message", "id_param received: "+id_item);
		return "welcome";
	}
	
}