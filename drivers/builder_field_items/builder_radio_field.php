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

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('options', 'Radio options', 'left', db_text)->comment('Use one line per option');
		$host->add_field('allow_custom', 'Allow custom input', 'right', db_bool)->comment('Allow the user to type in their own option.');
		$host->add_field('display_inline', 'Display inline', 'right', db_bool)->comment('Place a tick here if you want the radio to be side by side, by default they are stacked.');

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
		foreach ($this->get_radio_options() as $option) {
			$str[] = '<label class="radio '.(($this->display_inline) ? 'inline' : '').'">';
			$str[] = '<input type="radio" name="XXXXX" value="" class="radio" />';
			$str[] = $option;
			$str[] = '</label>';
		}
		
		// Custom entry
		if ($this->allow_custom) {
			$str[] = '<span class="custom-radio '.(($this->display_inline) ? 'inline' : '').'">';
			$str[] = '<input type="radio" name="XXXXX" value="" class="radio" />';
			$str[] = '<input type="text" name="XXXXXCUSTOM" value="" />';
			$str[] = '</span>';
		}

		$str[] = '</div>';

		// Comment
		if (strlen($this->comment))
			$str[] = '<span class="help-block">'.$this->comment.'</span>';

		$str[] = '</div>';

		return implode(PHP_EOL, $str);
	}

	public function get_radio_options() 
	{
		$options = explode("\n", $this->options);
		return $options;
	}
}