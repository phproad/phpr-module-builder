<?php

class Builder_Module extends Core_Module_Base
{
	protected function set_module_info()
	{
		return new Core_Module_Detail(
			"Builder",
			"Builder Module",
			"PHPRoad",
			"http://phproad.com/"
		);
	}

	public function build_admin_menu($menu)
	{
		$top = $menu->add('builder', 'Builder', 'builder/menus')->icon('wrench')->permission(array('manage_menus', 'manage_forms'));
		$top->add_child('menus', 'Menus', 'builder/menus', 600)->permission('manage_menus');
		$top->add_child('forms', 'Forms', 'builder/forms', 600)->permission('manage_forms');
	}

	public function build_admin_permissions($host)
	{
		$host->add_permission_field($this, 'manage_menus', 'Manage Menus', 'right')->display_as(frm_checkbox)->comment('Modify and create site menus');
		$host->add_permission_field($this, 'manage_forms', 'Manage Forms', 'right')->display_as(frm_checkbox)->comment('Modify and create site form');
	}
}
