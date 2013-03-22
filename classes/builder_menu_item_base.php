<?php

class Builder_Menu_Item_Base
{
	protected static $item_classes = array();

	public function get_info()
	{
		return array(
			'name'=>'Manual Link',
			'description'=>'Define a menu link manually'
		);
	}

	// Used to build the menu item form
	public function build_config_form($host)
	{
	}

	// Used to populate the menu item, eg. required fields URL and Label from a Blog Post
	public function populate_menu_item($host)
	{
	}

	public static function find_items()
	{
		if (!self::$item_classes)
		{            
			$modules = Core_Module_Manager::get_modules();
			foreach ($modules as $id=>$module)
			{
				$class_path = PATH_APP."/modules/".$id."/builder_types";

				if (!file_exists($class_path))
					continue;

				$iterator = new DirectoryIterator($class_path);

				foreach ($iterator as $file)
				{ 
					if (!$file->isDir() && preg_match('/^'.$id.'_[^\.]*\_menu_item.php$/i', $file->getFilename()))
						require_once($class_path.'/'.$file->getFilename());
				}
			}

			$classes = get_declared_classes();
			self::$item_classes = array();
			foreach ($classes as $class)
			{
				if (!preg_match('/_Menu_Item$/i', $class))
					continue;

				$reflection = new ReflectionClass($class); 
				if (!$reflection->isSubclassOf('Builder_Menu_Item_Base'))
					continue;

				$obj = new $class();
				self::$item_classes[] = $class;
			}
		}

		return self::$item_classes;
	}
}