<?php

class Builder_Form_Manager
{

	// Class/Object handling
	// 

	public static function get_field_types()
	{
		return Phpr_Driver_Manager::get_drivers('Builder_Field_Base');
	}

	public static function get_field_type($code)
	{
		return Phpr_Driver_Manager::get_driver('Builder_Field_Base', $code);
	}

}