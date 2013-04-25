<?php

class Builder_Checkbox_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Check Box',
			'description' => 'List of check boxes'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('options', 'Checkbox options', 'left', db_text)->comment('Use one line per option');
		$host->add_field('allow_custom', 'Allow custom input', 'right', db_bool)->comment('Allow the user to type in their own option.');
		$host->add_field('display_inline', 'Display inline', 'right', db_bool)->comment('Place a tick here if you want the checkboxes to be side by side, by default they are stacked.');
	}

	public function get_summary()
	{
		$host = $this->get_host_object();
		return str_replace(array("\r", "\n"), ', ', $host->options);
	}

	public function display_control()
	{
		$str = array();
		$str[] = '<div class="control-group">';
		$str[] = '<label class="control-label">';
		$str[] = $this->label;
		$str[] = '</label>';
		$str[] = '<div class="controls">';
		
		// Each option
		foreach ($this->get_checkbox_options() as $option) {
			$str[] = '<label class="checkbox '.(($this->display_inline) ? 'inline' : '').'">';
			$str[] = '<input type="checkbox" name="" value="" class="checkbox" />';
			$str[] = $option;
			$str[] = '</label>';
		}
		
		// Custom entry
		if ($this->allow_custom) {
			$str[] = '<span class="custom-checkbox '.(($this->display_inline) ? 'inline' : '').'">';
			$str[] = '<input type="checkbox" name="" value="" class="checkbox" />';
			$str[] = '<input type="text" name="" value="" />';
			$str[] = '</span>';
		}

		$str[] = '</div>';

		// Comment
		if (strlen($this->comment))
			$str[] = '<span class="help-block">'.$this->comment.'</span>';

		$str[] = '</div>';

		return implode(PHP_EOL, $str);
	}

	public function get_checkbox_options() 
	{
		$options = explode("\n", $this->options);
		return $options;
	}
}