<?php

class Builder_Field_Base extends Phpr_Driver_Base
{
	public static $driver_folder = 'builder_field_items';
	public static $driver_suffix = '_field';

	public function get_info()
	{
		return array(
			'name' => 'Unknown',
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

}