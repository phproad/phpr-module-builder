<?php

class Builder_Forms extends Admin_Controller
{
	public $implement = 'Db_List_Behavior, Db_Form_Behavior';
	public $list_model_class = 'Builder_Form';
	public $list_record_url = null;
	public $list_record_onclick = null;

	public $form_preview_title = 'Form';
	public $form_create_title = 'New Form';
	public $form_edit_title = 'Edit Form';
	public $form_model_class = 'Builder_Form';
	public $form_not_found_message = 'Form not found';
	public $form_redirect = null;

	public $form_edit_save_flash = 'Form has been successfully saved';
	public $form_create_save_flash = 'Form has been successfully added';
	public $form_edit_delete_flash = 'Form has been successfully deleted';

	public $list_search_enabled = true;
	public $list_search_fields = array('@name');
	public $list_search_prompt = 'find forms by name, login or email';

	protected $required_permissions = array('builder:manage_forms');

	public $global_handlers = array();

	public $list_name = null;
	public $list_custom_prepare_func = null;
	public $list_custom_body_cells = null;
	public $list_custom_head_cells = null;
	public $list_custom_partial = null;

	public function __construct()
	{
		$this->app_menu = 'builder';
		$this->app_page = 'forms';
		$this->app_module_name = 'Builder';

		$this->list_record_url = url('builder/forms/edit');
		$this->form_redirect = url('builder/forms');
		$this->form_create_save_redirect = url('builder/forms/edit/%s');

		parent::__construct();
	}

	public function index()
	{
		$this->app_page_title = 'Forms';
	}

	public function get_field_types()
	{
		$field_list = Builder_Form_Manager::get_field_types();
		usort($field_list, array('Builder_Forms', 'field_type_compare'));
		return $field_list;
	}

	// Controller Events
	// 

	public function listwidget_before_form_popup_fields($model)
	{
		if ($model->is_new_record()) {
			$model->class_name = post('class_name');
			$model->form_fields_defined = false;
			$model->init_form_fields(null, true);
		}
	}

	public function listwidget_before_form_update_fields($model)
	{
		if ($model->is_new_record()) {
			$model->class_name = post('class_name');
			$model->form_fields_defined = false;
			$model->init_form_fields(null, true);
		}
	}

	public function form_after_create_save($page, $session_key)
	{
		if (post('create_close'))
			$this->form_create_save_redirect = url('builder/forms');
	}	

	// Internals
	// 

	public static function field_type_compare($a, $b)
	{
		return strcasecmp($a->get_name(), $b->get_name());
	}

}

