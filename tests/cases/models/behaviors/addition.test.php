<?php
App::import('Behavior', 'Addition.Addition');
class AdditionBehaviorMockModel extends CakeTestModel {
	var $useTable = "additions";
	var $actsAs = array('Addition.Addition');
}
class AdditionBehaviorTest extends CakeTestCase {
	var $fixtures = array("plugin.addition.addition");
	var $Model;
	var $Behavior;
	function startCase() {
		$this->Model =& ClassRegistry::init('AdditionBehaviorMockModel');
		$this->_reset();
	}

	function _reset($settings = array()) {
		$this->Model->Behaviors->attach('Addition.Addition', $settings);
		$this->Behavior = $this->Model->Behaviors->Addition;
	}
	
	function testSum(){
		$result = $this->Model->sum(1); 
		$this->assertIdentical($result, true);
		$result = $this->Model->sum(1,1); 
		$this->assertIdentical($result, true);
		$result = $this->Model->field("views", array('id'=>1));
		$this->assertIdentical($result, "3");
		// next
		$result = $this->Model->sum(2,2,array('views','points')); 
		$this->assertIdentical($result, true);
		$result = $this->Model->find("first", array('conditions'=>array('id'=>2)));
		$this->assertIdentical($result[$this->Model->alias]['views'], "4");
		$this->assertIdentical($result[$this->Model->alias]['points'], "4");
		// next
		$result = $this->Model->sum(3,array(1,-1),array('views','points')); 
		$this->assertIdentical($result, true);
		$result = $this->Model->find("first", array('conditions'=>array('id'=>3)));
		$this->assertIdentical($result[$this->Model->alias]['views'], "4");
		$this->assertIdentical($result[$this->Model->alias]['points'], "2");
	}
	
	function testBuild(){
		$result = $this->Behavior->_build(array("field","next_field","third_field"), array(1,2,-1));
		$expected = array(
			'field' => "field + 1",
			'next_field' => "next_field + 2",
			'third_field' => "third_field - 1",
		);
		$this->assertIdentical($result, $expected);
		
	}
	function testBeforeSum(){
		$fields = array("test","test_field","extra_field");
		$values = array(-1,1);
		$result = $this->Behavior->_beforeSum($fields, $values);
		$expected = array(
			array("test","test_field","extra_field"),
			array(-1,1,1),
		);
		$this->assertIdentical($result, $expected);
	}
}
