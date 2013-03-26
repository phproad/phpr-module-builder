<?php

class Builder_Radio_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Radio',
			'description' => 'Single choice radio buttons'
		);
	}

}