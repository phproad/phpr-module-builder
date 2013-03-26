<?php

class Builder_Forms extends Admin_Controller
{
	public $implement = 'Db_ListBehavior, Db_FormBehavior';
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
	public $list_search_fields = array('@business_name');
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

		parent::__construct();
	}

	public function index()
	{
		$this->app_page_title = 'Forms';
	}

	public function get_item_types()
	{
		$type_list = Builder_Menu_Manager::get_menu_types();
		usort($type_list, array('Builder_Menus', 'item_type_compare'));
		return $type_list;
	}
}

