<?php

class Builder_Form_Field extends Db_ActiveRecord
{
	public $table_name = 'builder_form_fields';
	public $implement = 'Db_Model_Dynamic, Db_Model_Sortable';

	public $is_enabled = true;

	public $custom_columns = array(
		'field_type_name' => db_varchar,
		'field_type_code' => db_varchar,
		'field_summary' => db_varchar
	);

	protected $added_fields = array();
	protected $form_fields_defined = false;
	protected static $cache = array();

	protected $form_options = array();

	public $belongs_to = array(
		'form' => array('class_name'=>'Builder_Form', 'foreign_key'=>'form_id')
	);

	public function define_columns($context = null)
	{
		$this->define_column('is_enabled', 'Enabled')->order('desc');
		$this->define_column('label', 'Field Label')->validation()->required();
		$this->define_column('code', 'Field Code')->validation()->required();
		$this->define_column('comment', 'Field Comment');
		$this->define_column('sort_order', 'Order');
		$this->define_column('element_id', 'ID');
		$this->define_column('element_class', 'Class');

		$this->define_column('field_type_name', 'Field Type');
		$this->define_column('field_summary', 'Summary');
	}

	public function define_form_fields($context = null)
	{
		// Prevent duplication
		if ($this->form_fields_defined) return false; 
		$this->form_fields_defined = true;

		$this->form_context = $context;

		// Build form
		$this->add_form_field('label', 'left')->comment('Label displayed on the front end.')->tab('Field');
		$this->add_form_field('code', 'right')->comment('A unique code used to identify the field.')->tab('Field');
		$this->add_form_field('comment', 'full')->comment('Comment to display next to this field. Eg: Please provide your full name.')->tab('Field');

		$this->add_form_field('is_enabled')->tab('Properties');
		$this->add_form_field('element_id', 'left')->comment('For design purposes, you can enter a unique identifier for this field.')->tab('Properties');
		$this->add_form_field('element_class', 'right')->comment('For design purposes, you can give this field a CSS class.')->tab('Properties');

		// Field driver extension
		if ($this->init_field_extension()) {
			$this->build_config_ui($this, $context);
			
			// Load field's default data
			if ($this->is_new_record())
				$this->init_config_data($this);
		}
	}

	//
	// Events
	// 

	public function after_fetch()
	{
		$this->init_field_extension();
	}

	//
	// Service methods
	// 

	public function init_field_extension()
	{
		if (!strlen($this->class_name))
			return false;

		// Mixin class
		if ($this->class_name && !$this->is_extended_with($this->class_name))
			$this->extend_with($this->class_name);

		return true;
	}

	public function set_form_options($options = array()) 
	{
		$this->form_options = $options;
	}

	public function get_form_options()
	{
		return (object)$this->form_options;
	}

	// Custom columns
	// 

	public function eval_field_type_name()
	{
		return $this->get_name();
	}

	public function eval_field_type_code()
	{
		return $this->get_code();
	}

	public function eval_field_summary()
	{
		return $this->get_summary($this);
	}

	// Dynamic model
	// 

	public function add_field($code, $title, $side = 'full', $type = db_text)
	{
		$form_column = $this->define_dynamic_column($code, $title, $type);
		$form_field = $this->add_dynamic_form_field($code, $side)->tab('Field');
		$this->added_fields[$code] = $form_field;
		return $form_field;
	}	

}
