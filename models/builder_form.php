<?php

class Builder_Form extends Db_ActiveRecord
{
	public $table_name = 'builder_forms';

	public $has_many = array(
		'fields' => array('class_name'=>'Builder_Form_Field', 'foreign_key'=>'form_id', 'order'=>'sort_order, id', 'delete'=>true)
	);

	public function define_columns($context = null)
	{
		$this->define_column('name', 'Name')->order('asc')->validation()->fn('trim')->required('Please specify the menu name.');
		$this->define_column('code', 'Code')->validation()->fn('trim')->required('Please specify a unique code')->unique('Code must be unique');
		$this->define_multi_relation_column('fields', 'fields', 'Fields', '@code')->invisible();
	}

	public function define_form_fields($context = null)
	{
		$this->add_form_field('name', 'left')->tab('Form')->validation()->required();
		$this->add_form_field('code', 'right')->tab('Form')->validation()->required();

		// Fields
		$this->add_form_field('fields')->display_as(frm_widget, array(
			'class'=>'Db_List_Widget', 
			'columns' => array('label', 'field_type_name', 'field_summary'),
			'search_enabled' => false,
			'no_data_message' => 'This form has no fields yet',
			'control_panel' => 'fields_control_panel',
			'is_editable' => true,
			'form_title' => 'Field',
			'sorting_column' => 'sort_order',
			'show_reorder' => true,
			'show_checkboxes' => true,
			'show_delete_icon' => true,
		))->tab('Form');  		
	}

	public function display_form($options = array())
	{
		$options = array_merge(array(
				'container_class' => 'form',
				'field_array_name' => null,
				'field_class' => null, 
				'field_classes' => array(),
				'data' => array(),
			), $options);

		$str = '';
		$str .= '<div class="'.$options['container_class'].'">';

		foreach ($this->fields as $field) {
			$field->set_form_options($options);
			$str .= $field->display_element();
		}

		$str .= '</div>';
		return $str;
	}	
}
