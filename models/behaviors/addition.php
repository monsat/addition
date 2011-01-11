<?php
class AdditionBehavior extends ModelBehavior {
	var $_default = array('fields' => array('views'));
	function setup(&$model, $settings = array()) {
		if (!isset($this->__settings[$model->alias])) {
			$this->__settings[$model->alias] = $this->_default;
		}
		$settings = is_array($settings) ? $settings : array($settings);
		$this->__settings[$model->alias] = array_merge($this->__settings[$model->alias], $settings);
	}
	function sum(&$model, $id, $values = 1, $fields = array()) {
		list($fields, $values) = $this->_beforeSum($fields, $values);
		$_set = $this->_build($fields, $values);
		$_query = $this->_buildQuery($id, $_set, $model->useTable);
		return $this->_execute($_query, $model);
	}
	function _beforeSum($fields, $values) {
		if (empty($fields)) {
			$fields = $this->__settings[$model->alias]['fields'];
		}
		$values = is_array($values) ? $values : array($values);
		if (count($fields) > count($values)) {
			$_value = $values[count($values) - 1];
			$_values = array_fill(count($values), count($fields) - count($values), $_value);
			$values = array_merge($values, $_values);
		}
		return array($fields, $values);
	}
	function _build($fields, $values) {
		$_set = array_combine($fields, $values);
		$_results = array();
		foreach ($_set as $field => $value) {
			$_results[] = "{$field} = {$field} + {$value}";
		}
		return join(" , ", $_results);
	}
	function _buildQuery($id, $set, $modelname) {
		return "UPDATE {$modelname} SET {$set} WHERE id = {$id}";
	}
	function _execute($query, &$model) {
		return !!$model->execute( $query );
	}
}

