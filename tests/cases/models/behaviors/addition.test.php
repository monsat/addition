<?php
App::import('Behavior', 'Addition.Addition');
class AdditionBehaviorMockModel extends CakeTestModel {
	var $useTable = false;
	var $actsAs = array('Addition.Addition');
}

class AdditionBehaviorTest extends CakeTestCase {
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
	function testBuild(){
		$result = $this->Behavior->_build(array("field","next_field","third_field"), array(1,2,-1));
		$expected = "field = field + 1 , next_field = next_field + 2 , third_field = third_field + -1";
		$this->assertIdentical($result, $expected);
	}
	function testBuildQuery(){
		$result = $this->Behavior->_buildQuery("","","ModelName");
		$expected = "UPDATE ModelName SET  WHERE id = ";
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
