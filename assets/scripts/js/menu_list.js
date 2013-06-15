function menus_selected() {
	return jQuery('#listBuilder_Menus_index_list_body').find('tr td.list-checkbox input:checked').length;
}

function delete_selected() {
	if (!menus_selected()) {
		alert('Please select the menus you want to delete.');
		return false;
	}

	$('#listBuilder_Menus_index_list_body').phpr().post('index_on_delete_selected', {
		confirm: 'Do you really want to delete selected menu(s)?',
		customIndicator: LightLoadingIndicator,
		afterUpdate: update_scrollable_toolbars,
		update: '#templates_page_content'
	}).send();
	
	return false;
}

