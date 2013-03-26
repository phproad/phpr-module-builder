<?php

class Builder_Textarea_Field extends Builder_Field_Base
{

	public function get_info()
	{
		return array(
			'name' => 'Text Area',
			'description' => 'Multiple line text input'
		);
	}

}