<?php

class Builder_Link_Menu extends Builder_Menu_Base
{
	public function get_info()
	{
		return array(
			'name'=>'Manual Link',
			'description'=>'Define a menu link manually'
		);
	}

	public function build_config_ui($host)
	{
		$host->add_field('manual_label', 'Link Label', 'full', db_varchar, 'Link')
			->comment('Please specify a label for this link', 'above')
			->validation()->required('Please a label for this link');

		$host->add_field('manual_url', 'Link URL', 'full', db_varchar, 'Link')
			->comment('Please specify a URL for this link', 'above')
			->validation()->required('Please a URL for this link');            
	}

	public function validate_menu_item($host)
	{
		$host->label = $host->manual_label;
		$host->url = $host->manual_url;
	}

}