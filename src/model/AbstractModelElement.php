<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class AbstractModelElement {

	/**
	 * Convert an entity's property to array
	 * @return array
	 */
	abstract public function toDbArray();

}
