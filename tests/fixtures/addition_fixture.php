<?php
class AdditionFixture extends CakeTestFixture {
	var $name = "Addition";
	var $fields = array(
		'id' => array('type'=>"integer", 'key'=>"primary"),
		'views' => array('type'=>"integer", 'default'=>0),
		'points' => array('type'=>"integer", 'default'=>0),
	);
	var $records = array(
		array('id'=>1,'views'=>1,'points'=>1),
		array('id'=>2,'views'=>2,'points'=>2),
		array('id'=>3,'views'=>3,'points'=>3),
	);
}
