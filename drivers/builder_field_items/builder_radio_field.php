<?php

class Builder_Radio_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Radio',
			'code' => 'radio',
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

	public function display_element()
	{
		$current_value = $this->get_element_value();

		$str = array();
		$str[] = '<div class="control-group">';
		$str[] = '<label class="control-label">';
		$str[] = $this->label;
		$str[] = '</label>';
		$str[] = '<div class="controls">';
		
		// Each option
		foreach ($this->get_radio_options() as $option_name) {
			$str[] = '<label class="radio '.(($this->display_inline) ? 'inline' : '').'">';
			$str[] = '<input type="radio" id="'.$this->get_element_id().'"';
			$str[] = '  class="'.$this->get_element_class().'"';
			$str[] = '  name="'.$this->get_element_name().'"';
			$str[] = '  value="'.$option_name.'"';
			$str[] = '  '.Phpr_Form::radio_state($option_name, $current_value).' />';
			$str[] = $option_name;
			$str[] = '</label>';
		}
		
		// Custom entry
		if ($this->allow_custom) {
			$str[] = '<span class="custom-radio '.(($this->display_inline) ? 'inline' : '').'">';
			
			$str[] = '<input type="radio" id="'.$this->get_element_id().'"';
			$str[] = '  class="'.$this->get_element_class().'"';
			$str[] = '  name="'.$this->get_element_name().'"';
			$str[] = '  value="custom"';
			$str[] = '  '.Phpr_Form::radio_state('custom', $current_value).' />';
			
			$str[] = '<input type="text" id="'.$this->get_element_id().'-custom"';
			$str[] = '  class="custom '.$this->get_element_class().'"';
			$str[] = '  name="'.$this->get_custom_element_name().'"';
			$str[] = '  value="" />';

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

	public function get_custom_element_name() 
	{
		return $this->code . '_custom';
	}
}