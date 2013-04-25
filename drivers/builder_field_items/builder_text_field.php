<?php

class Builder_Text_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Text Box',
			'description' => 'Single line text input'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('default_value', 'Default Value', 'left', db_varchar)->comment('Set a field value for when the form first loads.')->tab('Properties');
		$host->add_field('placeholder', 'Placeholder', 'right', db_varchar)->comment('Text to display inside the field when empty. ')->tab('Properties');
	}

	public function display_control()
	{
		$str = array();
		$str[] = '<div class="control-group">';
		$str[] = '<label class="control-label">';
		$str[] = $this->label;
		$str[] = '</label>';
		$str[] = '<div class="controls">';

		$str[] = '<input type="text" name="" value="'.$this->default_value.'" placeholder="'.$this->placeholder.'" />';

		$str[] = '</div>';

		// Comment
		if (strlen($this->comment))
			$str[] = '<span class="help-block">'.$this->comment.'</span>';

		$str[] = '</div>';

		return implode(PHP_EOL, $str);
	}

}