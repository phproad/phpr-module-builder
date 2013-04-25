<?php

class Builder_Datepicker_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Date Picker',
			'description' => 'Input for selecting a date'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('allow_past_dates', 'Allow past dates', 'right', db_bool)->comment('Allow dates in the past to be selected.');
	}	

	public function display_control()
	{
		$str = array();
		$str[] = '<div class="control-group">';
		$str[] = '<label class="control-label">';
		$str[] = $this->label;
		$str[] = '</label>';
		$str[] = '<div class="controls">';

		$str[] = form_widget('xxx', array(
			'class' => 'Db_DatePicker_Widget',
			'css_class' => 'XXXXX',
			'field_id' => 'XXXXXX',
			'field_name' => 'XXXXXX',
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