<?php
class AdditionBehavior extends ModelBehavior {
	public $_default = array('fields' => array('views'));
	public function setup(&$model, $settings = array()) {
		if (!isset($this->__settings[$model->alias])) {
			$this->__settings[$model->alias] = $this->_default;
		}
		$settings = is_array($settings) ? $settings : array($settings);
		$this->__settings[$model->alias] = array_merge($this->__settings[$model->alias], $settings);
	}
	public function sum(&$model, $id, $values = 1, $fields = array()) {
		if (empty($fields)) {
			$fields = $this->__settings[$model->alias]['fields'];
		}
		list($fields, $values) = $this->_beforeSum($fields, $values);
		$_set = $this->_build($fields, $values);
		$conditions = array($model->primaryKey => $id);
		$this->unbindAllModels($model, true);
		return $model->updateAll($_set, $conditions);
	}
	public function _beforeSum($fields, $values) {
		$values = is_array($values) ? $values : array($values);
		if (count($fields) > count($values)) {
			$_value = $values[count($values) - 1];
			$values = array_pad($values, count($fields), $_value);
		}
		return array($fields, $values);
	}
	public function _build($fields, $values) {
		$_set = array_combine($fields, $values);
		$results = array();
		foreach ($_set as $field => $value) {
			$_sign = $value < 0 ? "-" : "+";
			$_value = abs($value);
			$results[$field] = "{$field} {$_sign} {$_value}";
		}
		return $results;
	}
	// copy from SearchPlugin
	public function unbindAllModels(Model $model, $reset = false) {
		$assocs = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
		$unbind = array();
		foreach ($assocs as $assoc) {
			$unbind[$assoc] = array_keys($model->{$assoc});
		}
		$model->unbindModel($unbind, $reset);
	}
}

