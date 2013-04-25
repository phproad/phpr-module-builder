<?php

class Builder_Field_Base extends Phpr_Driver_Base
{
	public static $driver_folder = 'builder_field_items';
	public static $driver_suffix = '_field';

	public function get_info()
	{
		return array(
			'name' => 'Unknown',
			'code' => 'unknown',
			'description' => 'Unknown form field'
		);
	}

	// Used to build the field item field
	public function build_config_ui($host)
	{
	}

	public function get_name() 
	{
		$info = $this->get_info();
		return (isset($info['name'])) ? $info['name'] : false;
	}

	public function get_code() 
	{
		$info = $this->get_info();
		return (isset($info['code'])) ? $info['code'] : false;
	}

	public function get_description() 
	{
		$info = $this->get_info();
		return (isset($info['description'])) ? $info['description'] : false;
	}

	/**
	 * Initializes configuration data when the field is first created
	 * Use host object to access and set fields previously added with build_config_ui method.
	 * @param $host ActiveRecord object containing configuration fields values
	 */
	public function init_config_data($host) { }

	// Returns a string rendering the form field
	public function display_element() {}

	// Getters
	// 

	// Returns a summary of the field's options or settings.
	public function get_summary() {}

	// HTML element value
	public function get_element_value() 
	{
		$options = $this->get_form_options();
		$array_name = $options->field_array_name;
		$code = $this->code;
		
		if ($array_name && isset($options->data[$array_name][$code]))
			$value = $options->data[$array_name][$code];
		else if (isset($options->data[$code]))
			$value = $options->data[$code];
		else 
			$value = $this->default_value;

		return $value;
	}

	// HTML element name
	public function get_element_name() 
	{
		$options = $this->get_form_options();
		if ($options->field_array_name)
			return $options->field_array_name.'['.$this->code.']';
		else
			return $this->code;
	}
	
	// HTML element CSS class
	public function get_element_class() 
	{
		$class = array();
		$type_code = $this->get_code();
		$options = $this->get_form_options();

		// CSS for all fields
		if ($options->field_class)
			$class[] = $options->field_class;

		// CSS for just this field type
		if (isset($options->field_classes[$type_code]))
			$class[] = $options->field_classes[$type_code];

		// CSS defined by UI
		if (strlen($this->element_class))
			$class[] = $this->element_class;
		else
			$class[] = "builder-".$this->code;

		// CSS for field type
		$class[] = "builder-" . Phpr_Inflector::slugify($type_code);

		return implode(' ', $class);
	}

	// HTML element identifier
	public function get_element_id() 
	{
		if (strlen($this->element_id))
			return $this->element_id;

		return "id-builder-".$this->code;
	}

}