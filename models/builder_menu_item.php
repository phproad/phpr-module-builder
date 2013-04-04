<?php

class Builder_Menu_Item extends Db_ActiveRecord
{
	public $table_name = 'builder_menu_items';

	public $implement = 'Db_Act_As_Tree, Db_Model_Dynamic, Db_Model_Sortable';

	public $act_as_tree_parent_key = 'parent_id';
	public $act_as_tree_sql_filter = null;
	public $act_as_tree_name_field = 'label';
	protected $categories_column;

	protected $added_fields = array();
	protected $menu_type_obj = null;
	public $class_name = null;

	protected static $item_list = null;

	public static function create($values = null)
	{
		return new self($values);
	}

	public function define_columns($context = null)
	{
		$this->define_column('label', 'Navigation Label')->validation()->required()->fn('trim');
		$this->define_column('title', 'Title Attribute');
		$this->define_column('url', 'URL')->invisible()->validation()->fn('trim');
		$this->define_column('url_suffix', 'URL Suffix')->invisible()->validation()->fn('trim');
		$this->define_column('element_id', 'ID');
		$this->define_column('element_class', 'Class');
	}

	public function define_form_fields($context = null)
	{
		$type_obj = $this->get_menu_type_object();
		
		if ($type_obj)
			$type_obj->build_config_form($this, $context);        
		
		$this->add_form_field('label', 'left')->tab('Properties');
		$this->add_form_field('title', 'right')->tab('Properties');
		$this->add_form_field('element_id', 'left')->tab('Properties')->comment('This ID will be bound to the list item (LI) tag', 'above');
		$this->add_form_field('element_class', 'right')->tab('Properties')->comment('This class will be added to the list item (LI) tag', 'above');
		$this->add_form_field('url_suffix', 'left')->tab('Properties')->comment('Example: #hash_anchor', 'above');
	}

	// Events
	//

	public function before_delete($id = null)
	{
		// Re-nest any children before deletion
		$children = $this->list_children('sort_order, label');
		if ($children->count)
		{
			$child_ids = array();
			foreach ($children as $child)
			{
				$child_ids[] = $child->id;
			}

			$bind = array(
				'parent_id' => ($this->parent_id ? $this->parent_id : NULL),
				'menu_id' => ($this->menu_id ? $this->menu_id : NULL),
				'child_ids' => array($child_ids)
			);

			Db_Helper::query('update builder_menu_items set parent_id=:parent_id where menu_id =:menu_id and id in (:child_ids)', $bind);
		}
	}

	public function before_validation($session_key=null)
	{
		$this->get_menu_type_object()->build_menu_item($this);
	}

	// Getters
	//

	public function get_menu_type_object()
	{
		if ($this->menu_type_obj !== null)
			return $this->menu_type_obj;

		if (!$this->class_name)
			$this->class_name = post('menu_item_class_name', 'Builder_Link_Menu_Item');

		if (!Phpr::$class_loader->load($this->class_name))
			throw new Phpr_ApplicationException("Class {$this->class_name} not found");

		$class_name = $this->class_name;

		return $this->menu_type_obj = new $class_name();
	}

	// Custom fields
	//

	public function add_field($code, $title, $side = 'full', $type = db_text, $tab = 'Event', $hidden = false)
	{
		$this->define_dynamic_column($code, $title, $type)->validation();
		if (!$hidden)
			$form_field = $this->add_dynamic_form_field($code, $side)->options_method('get_added_field_options')->option_state_method('get_added_field_option_state')->tab($tab);
		else
			$form_field = null;

		$this->added_fields[$code] = $form_field;

		return $form_field;
	}    

	public function get_added_field_options($db_name)
	{     
		$obj = $this->get_menu_type_object();
		$method_name = "get_{$db_name}_options";
		if (method_exists($obj, $method_name))
			return $obj->$method_name($this);

		throw new Phpr_SystemException("Method {$method_name} is not defined in {$this->class_name} class.");
	}
	
	public function get_added_field_option_state($db_name, $key_value)
	{       
		$obj = $this->get_menu_type_object();
		$method_name = "get_{$db_name}_option_state";
		if (method_exists($obj, $method_name))
			return $obj->$method_name($key_value);
			
		throw new Phpr_SystemException("Method {$method_name} is not defined in {$this->class_name} class.");
	}

	// Service methods
	//

	public function display_frontend($options = array(), &$str = null)
	{
		if (!$str)
			$str = "";

		$controller = Builder_Controller::get_instance();
		$page = ($controller) ? $controller->page : null;
		$children = $this->list_children('sort_order');

		$a_href = root_url($this->url);
		if ($this->url != '/' && $this->url_suffix) $a_href .= '/';
		if ($this->url_suffix) $a_href .= $this->url_suffix;

		$li_class = $this->element_class;
		
		$is_active = ($page && $page->url == $this->url);

		if ($children->count)
			$li_class .= " ".$options['class_dropdown_container'];

		if ($is_active)
			$li_class .= " active";

		$str .= '<li class="'.trim($li_class).'">'.PHP_EOL;
		$str .= '<a href="'.$a_href.'">';
		$str .= $this->label;
		$str .= '</a>'.PHP_EOL;

		if ($children->count)
		{
			$ul_class = $options['class_dropdown'];

			$str .= '<ul class="'.$ul_class.'">'.PHP_EOL;
			foreach ($children as $child)
			{
				$child->display_frontend($options, $str);
			}
			$str .= "</ul>".PHP_EOL;
		}

		$str .= "</li>".PHP_EOL;
		return $str;
	}

	public function get_url($recache=false)
	{
		if (!$recache && $this->url)
			return $this->url;

		return $this->url;
	}

	public function get_type_name()
	{
		$info = $this->get_menu_type_object()->get_info();
		return $info['name'];
	}

	// Relations
	//

	public function set_parent($parent_id)
	{
		Db_Helper::query('update builder_menu_items set parent_id=:parent_id where id=:id', array(
			'parent_id'=> intval($parent_id) ? intval($parent_id) : NULL,
			'id'=>$this->id
		));
	}

	public static function set_order_and_nesting($order_ids, $parent_ids)
	{
		if (is_string($order_ids))
			$order_ids = explode(',', $order_ids);
		if (is_string($parent_ids))
		{
			$parent_ids = explode(',', $parent_ids);
		}

		foreach ($order_ids as $index=>$id)
		{
			//For some reason 'NULL' doesn't work with arguments, so do it manually (with sanitation)
			$parent_id = isset($parent_ids[$id]) && intval($parent_ids[$id]) ? intval($parent_ids[$id]) : 'NULL';

			Db_Helper::query("update builder_menu_items set sort_order=:sort_order, parent_id=$parent_id where id=:id", array(
				'sort_order'=>$index+1,
				'id'=>$id
			));
		}
	}

}
