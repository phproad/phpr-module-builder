<?php

class Builder_Dropdown_Field extends Builder_Field_Base
{

	public function get_info()
	{
		return array(
			'name' => 'Drop Down',
			'code' => 'dropdown',
			'description' => 'Drop down list of choices'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('options', 'Dropdown Options', 'left', db_text)->comment('Use one line per option');
		$host->add_field('empty_option', 'Empty Option', 'right', db_varchar)->comment('Label to use when no dropdown option is selected.');
	}

	public function init_config_data($host) 
	{ 
		$host->empty_option = '- select -';
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
		$str[] = '<select id="'.$this->get_element_id().'" class="'.$this->get_element_class().'" name="'.$this->get_element_name().'">';
		
		if ($this->empty_option !== false)
			$str[] .= '<option value="">'.h($this->empty_option).'</option>'.PHP_EOL;

		// Each option
		foreach ($this->get_dropdown_options() as $option_name) {
			$str[] = '<option value="'.$option_name.'" '.Phpr_Form::option_state($current_value, $option_name).'>';
			$str[] = $option_name;
			$str[] = '</option>';
		}
		
		$str[] = '</select>';
		$str[] = '</div>';

		// Comment
		if (strlen($this->comment))
			$str[] = '<span class="help-block">'.$this->comment.'</span>';

		$str[] = '</div>';

		return implode(PHP_EOL, $str);
	}

	public function get_dropdown_options() 
	{
		$options = explode("\n", $this->options);
		return $options;
	}
}