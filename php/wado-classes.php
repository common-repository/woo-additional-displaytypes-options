<?php

class WADO_Index_Letter {
	public $type;
	public $name;
	
	public function __construct($name) {
		$this->type = 'letter';
		$this->name = $name;
	}
}

class WADO_Index_Category {
	public $type;
	public $category;
	
	public function __construct($category) {
		$this->type = 'category';
		$this->category = $category;
	}
}

?>