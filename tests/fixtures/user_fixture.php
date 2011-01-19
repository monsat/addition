<?php
class UserFixture extends CakeTestFixture {
	var $name = "User";
	var $fields = array(
		'id' => array('type' => "integer", 'key' => "primary"),
		'addition_id' => array('type' => "integer", 'default'=>0),
	);
	var $records = array(
		array('id' => 1, 'addition_id' => 1),
		array('id' => 2, 'addition_id' => 2),
		array('id' => 3, 'addition_id' => 3),
	);
}
