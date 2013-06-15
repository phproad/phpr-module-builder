jQuery(document).ready(function() {
	makeItemsSortable();
});

function updateItemList() {
	cancelPopup();

	$('#item_list').phpr().post('on_update_item_list', {
		update: '#item_list',
		loadIndicator: {
			show: true,
			element: '#item_list',
			hideOnSuccess: true,
			injectInElement: true,
			src: 'phproad/assets/images/form_load_30x30.gif'
		},
		afterUpdate: makeItemsSortable
	}).send();
}

function makeItemsSortable(session_key) {
	if (!$('#item_list').length)
		return;

	jQuery('#item_list').css('visibility', 'visible');
	var list = jQuery('#item_list').find('ol.nestedsortable');

	list.nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div',
		update: function(event, ui) {

			list.find('li').removeClass('even').filter(':odd').addClass('even');

			// Get the new sort order
			var sorted_item_ids = list.find('li').map(function() {
				return jQuery(this).attr('item_id').match(/(?:.+)[-=_](.+)/);
			}).get().join(',');

			$('#item_list').phpr().post('on_set_item_orders', {
				customIndicator: LightLoadingIndicator,
				data: {
					sort_order: sorted_item_ids,
					nesting_order: list.nestedSortable('serialize', {'attribute':'item_id'})
				}
			}).send();
		}
	});
}

function deleteMenuItem(item_id) {
	return $('#item_list').phpr().post('on_delete_item', {
		data: { item_id: item_id },
		confirm: 'Do you really want to delete this menu item? Any child items will be kept.',
		update: '#item_list',
		afterUpdate: makeItemsSortable,
		error: popupAjaxError,
		loadIndicator: {
			show: true,
			element: '#item_list',
			hideOnSuccess: true,
			injectInElement: true,
			src: 'phproad/assets/images/form_load_30x30.gif'
		}
	}).send();
}
