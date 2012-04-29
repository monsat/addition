<?php
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
App::uses('AdditionBehavior', 'Addition.Model/Behavior');
class AdditionBehaviorMockModel extends CakeTestModel {
	public $useTable = "additions";
	public $hasOne = "User";
}
class AssocMockModel extends CakeTestModel {
	public $name = "User";
	public $useTable = "users";
}
class AdditionBehaviorTest extends CakeTestCase {
	public $fixtures = array("plugin.addition.addition", "plugin.addition.user");
	public $Model;
	public $Behavior;
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('AdditionBehaviorMockModel');
		$this->Model->User = ClassRegistry::init('AssocMockModel');
		$this->_reset();
	}

	public function _reset($settings = array()) {
		$this->Model->Behaviors->attach('Addition.Addition', $settings);
		$this->Behavior = $this->Model->Behaviors->Addition;
	}
	
	public function testSum(){
		$result = $this->Model->sum(1); 
		$this->assertSame($result, true);
		$result = $this->Model->sum(1,1); 
		$this->assertSame($result, true);
		$result = $this->Model->field("views", array('id'=>1));
		$this->assertSame($result, "3");
		// next
		$result = $this->Model->sum(2,2,array('views','points')); 
		$this->assertSame($result, true);
		$result = $this->Model->find("first", array('conditions'=>array('id'=>2)));
		$this->assertSame($result[$this->Model->alias]['views'], "4");
		$this->assertSame($result[$this->Model->alias]['points'], "4");
		// next
		$result = $this->Model->sum(3,array(1,-1),array('views','points')); 
		$this->assertSame($result, true);
		$result = $this->Model->find("first", array('conditions'=>array('id'=>3)));
		$this->assertSame($result[$this->Model->alias]['views'], "4");
		$this->assertSame($result[$this->Model->alias]['points'], "2");
	}
	
	public function testBuild(){
		$result = $this->Behavior->_build(array("field","next_field","third_field"), array(1,2,-1));
		$expected = array(
			'field' => "field + 1",
			'next_field' => "next_field + 2",
			'third_field' => "third_field - 1",
		);
		$this->assertSame($result, $expected);
		
	}
	public function testBeforeSum(){
		$fields = array("test","test_field","extra_field");
		$values = array(-1,1);
		$result = $this->Behavior->_beforeSum($fields, $values);
		$expected = array(
			array("test","test_field","extra_field"),
			array(-1,1,1),
		);
		$this->assertSame($result, $expected);
	}
}
