package org.grasia.reptxthank.controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class ReptXThankController {
	
	@RequestMapping("/welcome")
	public String sayHello(ModelMap model){
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