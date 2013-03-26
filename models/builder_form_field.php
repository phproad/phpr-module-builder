<?php

class Builder_Form_Field extends Db_ActiveRecord
{
	public $table_name = 'builder_form_fields';
	public $implement = 'Db_Model_Dynamic';

	public $is_enabled = true;

	protected $form_fields_defined = false;
	protected static $cache = array();

	public $belongs_to = array(
	);

	public static function create()
	{
		return new self();
	}

	public function define_columns($context = null)
	{
		$this->define_column('provider_name', 'Provider');
		$this->define_column('is_enabled', 'Enabled')->order('desc');
		$this->define_column('code', 'API Code')->default_invisible();
	}

	public function define_form_fields($context = null)
	{
		$this->class_name = 'Builder_Text_Field';
		
		// Prevent duplication
		if ($this->form_fields_defined) return false; 
		$this->form_fields_defined = true;

		// Mixin provider class
		if ($this->class_name && !$this->is_extended_with($this->class_name))
			$this->extend_with($this->class_name);

		$this->form_context = $context;

		// Build form
		$this->add_form_field('is_enabled');
		$this->build_config_ui($this, $context);
		$this->add_form_field('code', 'full')->disabled()
			->comment('A unique code used to reference this provider by other modules.');

		// Load provider's default data
		if ($this->is_new_record())
			$this->init_config_data($this);
	}

	// Events
	// 

	public function after_fetch()
	{
		// Mixin provider class
		if ($this->class_name && !$this->is_extended_with($this->class_name))
			$this->extend_with($this->class_name);
	}

}
