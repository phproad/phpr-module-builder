<?php

$table = Db_Structure::table('builder_menus');
    $table->primary_key('id');
    $table->column('name', db_varchar, 100);
    $table->column('code', db_varchar, 100);
    $table->column('short_description', db_varchar);
    $table->footprints();
    $table->save();

$table = Db_Structure::table('builder_menu_items');
    $table->primary_key('id');
    $table->column('menu_id', db_number)->index();
    $table->column('parent_id', db_number)->index();
    $table->column('label', db_varchar, 100);
    $table->column('title', db_varchar, 100);
    $table->column('url', db_varchar, 100);
    $table->column('url_suffix', db_varchar, 100);
    $table->column('element_id', db_varchar, 100);
    $table->column('element_class', db_varchar, 100);
    $table->column('config_data', db_text);
    $table->column('class_name', db_varchar, 100);
    $table->column('sort_order', db_number);
    $table->save();
