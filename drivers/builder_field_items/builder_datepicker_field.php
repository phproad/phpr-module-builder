<?php

class Builder_Datepicker_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Date Picker',
			'code' => 'datepicker',
			'description' => 'Input for selecting a date'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('allow_past_dates', 'Allow past dates', 'right', db_bool)->comment('Allow dates in the past to be selected.');
	}	

	public function display_element()
	{
		$str = array();
		$str[] = '<div class="control-group">';
		$str[] = '<label class="control-label">';
		$str[] = $this->label;
		$str[] = '</label>';
		$str[] = '<div class="controls">';

		$str[] = form_widget($this->code, array(
			'class' => 'Db_DatePicker_Widget',
			'css_class' => $this->get_element_class(),
			'field_id' => $this->get_element_id(),
			'field_name' => $this->get_element_name(),
			'field_value' => $this->get_element_value(),
			'allow_past_dates' => $this->allow_past_dates
		));

		$str[] = '</div>';

		// Comment
		if (strlen($this->comment))
			$str[] = '<span class="help-block">'.$this->comment.'</span>';

		$str[] = '</div>';

		return implode(PHP_EOL, $str);
	}

}