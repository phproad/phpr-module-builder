<?php

class Builder_Menu_Base extends Phpr_Driver_Base
{
	public static $driver_folder = 'builder_menu_items';
	public static $driver_suffix = '_menu';    

	public function get_info()
	{
		return array(
			'name' => 'Unknown',
			'description' => 'Unknown menu item'
		);
	}

	// Used to build the menu item form
	public function build_config_ui($host)
	{
	}

	/**
	 * Initializes configuration data when the field is first created
	 * Use host object to access and set fields previously added with build_config_ui method.
	 * @param $host ActiveRecord object containing configuration fields values
	 */
	public function init_config_data($host) { }

	// Used to populate the menu item, eg. required fields URL and Label from a Blog Post
	public function validate_menu_item($host) { }

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
}