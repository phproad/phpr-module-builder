<?php

class Builder_Menus extends Admin_Controller
{
	public $implement = 'Db_List_Behavior, Db_Form_Behavior';
	public $list_model_class = 'Builder_Menu';
	public $list_record_url = null;

	public $form_preview_title = 'Menu';
	public $form_create_title = 'New Menu';
	public $form_edit_title = 'Edit Menu';
	public $form_model_class = 'Builder_Menu';
	public $form_not_found_message = 'Menu not found';
	public $form_redirect = null;
	public $form_edit_save_auto_timestamp = true;
	public $form_create_save_redirect = null;
	public $form_flash_id = 'form-flash';

	public $form_edit_save_flash = 'The menu has been successfully saved';
	public $form_create_save_flash = 'The menu has been successfully added';
	public $form_edit_delete_flash = 'The menu has been successfully deleted';

	public $list_search_enabled = true;
	public $list_columns = array();
	public $list_search_fields = array('name', 'description');
	public $list_search_prompt = 'find menus by name or description';
	public $list_custom_partial = '';
	public $list_custom_prepare_func = 'list_custom_prepare';
	public $list_custom_body_cells = '';
	public $list_custom_head_cells = '';
	public $list_items_per_page = 20;
	public $list_name = 'Builder_Menus_index_list';

	protected $required_permissions = array('builder:manage_menus');
	public $enable_concurrency_locking = true;

	protected $global_handlers = array(
		'on_load_item_form',
		'on_update_item_list',
		'on_manage_item',
		'on_delete_item',
		'on_set_item_orders',
		'on_save',
	);

	public function __construct()
	{
		Phpr::$events->fire_event('builder:on_configure_menus_page', $this);

		parent::__construct();
		$this->app_menu = 'builder';
		$this->app_module_name = 'Menus';

		$this->list_record_url = url('builder/menus/edit');
		$this->form_redirect = url('builder/menus');
		$this->form_create_save_redirect = url('builder/menus/edit/%s/'.uniqid());
		$this->app_page = 'menus';
	}

	public function list_custom_prepare($model, $options)
	{
		$updated_data = Phpr::$events->fire_event('builder:on_prepare_list_custom_data', $model, $options);
		foreach ($updated_data as $updated)
		{
			if ($updated)
				return $updated;
		}

		return $model;
	}

	public function index()
	{
		$this->app_page_title = 'Menus';
	}

	public function get_item_types()
	{
		$type_list = Builder_Menu_Manager::get_menu_types();
		usort($type_list, array('Builder_Menus', 'item_type_compare'));
		return $type_list;
	}

	protected function index_on_delete_selected()
	{
		$items_processed = 0;
		$items_deleted = 0;

		$item_ids = post('list_ids', array());
		$this->view_data['list_checked_records'] = $item_ids;

		foreach ($item_ids as $item_id)
		{
			$item = null;
			try
			{
				$item = Builder_Menu::create()->find($item_id);
				if (!$item)
					throw new Phpr_ApplicationException('Menu with identifier '.$item_id.' not found');

				$item->delete();
				$items_deleted++;
				$items_processed++;
			}
			catch (Exception $ex)
			{
				if (!$item)
					Phpr::$session->flash['error'] = $ex->getMessage();
				else
					Phpr::$session->flash['error'] = 'Error deleting menu "'.$item->name.'": '.$ex->getMessage();

				break;
			}
		}

		if ($items_processed)
		{
			$message = null;

			if ($items_deleted)
				$message = 'Menus deleted: '.$items_deleted;

			Phpr::$session->flash['success'] = $message;
		}

		$this->display_partial('templates_page_content');
	}

	protected function index_on_refresh()
	{
		$this->display_partial('templates_page_content');
	}

	protected function on_save($id)
	{
		Phpr::$router->action == 'create' ? $this->create_on_save() : $this->edit_on_save($id);
	}

	public function form_after_create_save($page, $session_key)
	{
		if (post('create_close'))
			$this->form_create_save_redirect = url('builder/menus').'?'.uniqid();
	}

	public function list_get_row_class($model)
	{
		if (!($model instanceof Builder_Menu))
			return null;
	}

	/*
	 * Menu Items
	 */

	protected function on_load_item_form()
	{
		try
		{
			$id = post('item_id');
			$item = $id ? Builder_Menu_Item::create()->find($id) : Builder_Menu_Item::create();
			
			if (!$item)
				throw new Phpr_ApplicationException('Menu item not found');

			if ($item->is_new_record())
			{
				$item->init_columns();
				$item->class_name = post('menu_item_class_name', 'Builder_Link_Menu_Item');
				$item->init_form_fields('create');
			}
			else
			{
				$item->init_columns();
				$item->init_form_fields();
			}

			$this->view_data['item'] = $item;
			$this->view_data['session_key'] = post('edit_session_key');
			$this->view_data['item_id'] = post('item_id');
			$this->view_data['track_tab'] = false;
		}
		catch (Exception $ex)
		{
			throw new Phpr_ApplicationException($ex->getMessage());
			$this->handle_page_error($ex);
		}

		$this->display_partial('item_form');
	}

	protected function on_manage_item($parent_id = null)
	{
		try
		{			
			$menu = $this->get_model_obj($parent_id);

			$is_new_record = post('new_object_flag', false);

			$model = Builder_Menu_Item::create();

			if (!$is_new_record)
				$model = $model->find(post('item_id'));

			$model->class_name = post('menu_item_class_name');
			$model->menu_id = $parent_id;
			$model->init_columns();
			$model->init_form_fields();
			$model->save(post('Builder_Menu_Item'), post('edit_session_key'));
			
			$menu->items->add($model, post('edit_session_key'));

			Phpr::$session->flash['success'] = "Menu item added successfully.";

			$this->display_partial('item_list', array(
				'session_key'=>$this->form_get_edit_session_key(),
				'menu' => $menu,
			));
		}
		catch (Exception $ex)
		{
			Phpr::$response->ajax_report_exception($ex, true, true);
		}
	}

	protected function on_update_item_list($parent_id = null)
	{
		try
		{
			$this->display_partial('item_list', array(
				'session_key'=>$this->form_get_edit_session_key(),
				'menu' => $this->get_model_obj($parent_id),
			));
		}
		catch (Exception $ex)
		{
			Phpr::$response->ajax_report_exception($ex, true, true);
		}
	}

	protected function on_delete_item($parent_id = null)
	{
		try
		{
			$menu = $this->get_model_obj($parent_id);

			$id = post('item_id');
			$item = $id ? Builder_Menu_Item::create()->find($id) : Builder_Menu_Item::create();
			if ($item)
			{
				$menu->items->delete($item, $this->form_get_edit_session_key());
				$item->init_columns();
				$item->init_form_fields();
				$item->delete();
			}

			$this->display_partial('item_list', array(
				'session_key'=>$this->form_get_edit_session_key(),
				'menu' => $menu,
			));
		}
		catch (Exception $ex)
		{
			Phpr::$response->ajax_report_exception($ex, true, true);
		}
	}

	/*
	 * Set nesting and orders
	 */
	protected function on_set_item_orders($parent_id = null)
	{
		parse_str(post('nesting_order'), $parent_ids);
		$parent_ids = isset($parent_ids['item']) ? $parent_ids['item'] : array();

		// Nesting & Sorting
		Builder_Menu_Item::set_order_and_nesting(post('sort_order'), $parent_ids);
	}

	private function get_model_obj($id)
	{
		return strlen($id) ? $this->form_find_model_object($id) : (new $this->form_model_class);
	}

	// Internals
	// 

	public static function item_type_compare($a, $b)
	{
		return strcasecmp($a->get_name(), $b->get_name());
	}
}
