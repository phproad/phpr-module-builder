<?php

class Builder_Form_Field extends Db_ActiveRecord
{
	public $table_name = 'builder_form_fields';
	public $implement = 'Db_Model_Dynamic';

	public $is_enabled = true;

	protected $added_fields = array();

	protected $form_fields_defined = false;
	protected static $cache = array();

	public $belongs_to = array(
	);

	public function define_columns($context = null)
	{
		$this->define_column('is_enabled', 'Enabled')->order('desc');
		$this->define_column('label', 'Field Label')->validation()->required();
		$this->define_column('code', 'Field Code')->validation()->required();
		$this->define_column('comment', 'Field Comment');
	}

	public function define_form_fields($context = null)
	{
		// Prevent duplication
		if ($this->form_fields_defined) return false; 
		$this->form_fields_defined = true;

		$this->form_context = $context;

		// Build form
		$this->add_form_field('is_enabled');
		$this->add_form_field('label', 'left') ->comment('Label displayed on the front end.');
		$this->add_form_field('code', 'right') ->comment('A unique code used to identify the field.');
		$this->add_form_field('comment', 'full') ->comment('Comment to display next to this field. Eg: Please provide your full name.');

		// Field driver extension
		if ($this->init_field_extension()) {
			$this->build_config_ui($this, $context);
			
			// Load field's default data
			if ($this->is_new_record())
				$this->init_config_data($this);
		}
	}

	// Events
	// 

	public function after_fetch()
	{
		$this->init_field_extension();
	}

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

	// Dynamic model
	// 

	public function add_field($code, $title, $side = 'full', $type = db_text)
	{
		$form_column = $this->define_dynamic_column($code, $title, $type);
		$form_field = $this->add_dynamic_form_field($code, $side);
		$this->added_fields[$code] = $form_field;
		return $form_field;
	}	

}
