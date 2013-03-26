<?php

class Builder_Menu_Manager
{

	// Class/Object handling
	// 

	public static function get_menu_types()
	{
		return Phpr_Driver_Manager::get_drivers('Builder_Menu_Base');
	}

	public static function get_menu_type($code)
	{
		return Phpr_Driver_Manager::get_driver('Builder_Menu_Base', $code);
	}

}