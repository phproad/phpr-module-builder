function menus_selected() {
	return jQuery('#listCms_Menus_index_list_body').find('tr td.list-checkbox input:checked');
}

function delete_selected() {
	if (!menus_selected()) {
		alert('Please select layouts to delete.');
		return false;
	}

	$('#listCms_Menus_index_list_body').phpr().post(
		'index_on_delete_selected',
		{
			confirm: 'Do you really want to delete selected menu(s)?',
			customIndicator: LightLoadingIndicator,
			afterUpdate: update_scrollable_toolbars,
			update: '#templates_page_content'
		}
	).send();
	return false;
}


jQuery(document).ready(function() {
	make_items_sortable();
});

function updateItemList() {
	cancelPopup();

	$('#item_list').phpr().post(
		'on_update_item_list',
		{
			update: '#item_list',
			loadIndicator: {
				show: true,
				element: '#item_list',
				hideOnSuccess: true,
				injectInElement: true,
				src: 'phproad/assets/images/form_load_30x30.gif'
			},
			success: make_items_sortable
		}
	).send();
}

function make_items_sortable(session_key) {
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

function delete_item(item_id) {
	return $('#item_list').phpr().post('on_delete_item', {
		confirm: 'Do you really want to delete this menu item? Any child items will be kept.',
		error: popupAjaxError,
		update: '#item_list',
		data: {
			item_id: item_id
		},
		loadIndicator: {
			show: true,
			element: '#item_list',
			hideOnSuccess: true,
			injectInElement: true,
			src: 'phproad/assets/images/form_load_30x30.gif'
		}
	}).send();
}

/*
 * jQuery UI Nested Sortable
 * v 1.3.4 / 28 apr 2011
 * http://mjsarfatti.com/sandbox/nestedSortable
 *
 * Depends:
 *	 jquery.ui.sortable.js 1.8+
 *
 * License CC BY-SA 3.0
 * Copyright 2010-2011, Manuele J Sarfatti
 */
(function(a){a.widget("ui.nestedSortable",a.extend({},a.ui.sortable.prototype,{options:{tabSize:20,disableNesting:"ui-nestedSortable-no-nesting",errorClass:"ui-nestedSortable-error",listType:"ol",maxLevels:0,revertOnError:1},_create:function(){this.element.data("sortable",this.element.data("nestedSortable"));return a.ui.sortable.prototype._create.apply(this,arguments)},destroy:function(){this.element.removeData("nestedSortable").unbind(".nestedSortable");return a.ui.sortable.prototype.destroy.apply(this,arguments)},_mouseDrag:function(d){this.position=this._generatePosition(d);this.positionAbs=this._convertPositionTo("absolute");if(!this.lastPositionAbs){this.lastPositionAbs=this.positionAbs}if(this.options.scroll){var f=this.options,e=false;if(this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"){if((this.overflowOffset.top+this.scrollParent[0].offsetHeight)-d.pageY<f.scrollSensitivity){this.scrollParent[0].scrollTop=e=this.scrollParent[0].scrollTop+f.scrollSpeed}else{if(d.pageY-this.overflowOffset.top<f.scrollSensitivity){this.scrollParent[0].scrollTop=e=this.scrollParent[0].scrollTop-f.scrollSpeed}}if((this.overflowOffset.left+this.scrollParent[0].offsetWidth)-d.pageX<f.scrollSensitivity){this.scrollParent[0].scrollLeft=e=this.scrollParent[0].scrollLeft+f.scrollSpeed}else{if(d.pageX-this.overflowOffset.left<f.scrollSensitivity){this.scrollParent[0].scrollLeft=e=this.scrollParent[0].scrollLeft-f.scrollSpeed}}}else{if(d.pageY-a(document).scrollTop()<f.scrollSensitivity){e=a(document).scrollTop(a(document).scrollTop()-f.scrollSpeed)}else{if(a(window).height()-(d.pageY-a(document).scrollTop())<f.scrollSensitivity){e=a(document).scrollTop(a(document).scrollTop()+f.scrollSpeed)}}if(d.pageX-a(document).scrollLeft()<f.scrollSensitivity){e=a(document).scrollLeft(a(document).scrollLeft()-f.scrollSpeed)}else{if(a(window).width()-(d.pageX-a(document).scrollLeft())<f.scrollSensitivity){e=a(document).scrollLeft(a(document).scrollLeft()+f.scrollSpeed)}}}if(e!==false&&a.ui.ddmanager&&!f.dropBehaviour){a.ui.ddmanager.prepareOffsets(this,d)}}this.positionAbs=this._convertPositionTo("absolute");if(!this.options.axis||this.options.axis!="y"){this.helper[0].style.left=this.position.left+"px"}if(!this.options.axis||this.options.axis!="x"){this.helper[0].style.top=this.position.top+"px"}for(var l=this.items.length-1;l>=0;l--){var m=this.items[l],g=m.item[0],c=this._intersectsWithPointer(m);if(!c){continue}if(g!=this.currentItem[0]&&this.placeholder[c==1?"next":"prev"]()[0]!=g&&!a.contains(this.placeholder[0],g)&&(this.options.type=="semi-dynamic"?!a.contains(this.element[0],g):true)){a(g).mouseenter();this.direction=c==1?"down":"up";if(this.options.tolerance=="pointer"||this._intersectsWithSides(m)){a(g).mouseleave();this._rearrange(d,m)}else{break}this._clearEmpty(g);this._trigger("change",d,this._uiHash());break}}var h=(this.placeholder[0].parentNode.parentNode&&a(this.placeholder[0].parentNode.parentNode).closest(".ui-sortable").length)?a(this.placeholder[0].parentNode.parentNode):null,b=this._getLevel(this.placeholder),j=this._getChildLevels(this.helper),k=this.placeholder[0].previousSibling?a(this.placeholder[0].previousSibling):null;if(k!=null){while(k[0].nodeName.toLowerCase()!="li"||k[0]==this.currentItem[0]){if(k[0].previousSibling){k=a(k[0].previousSibling)}else{k=null;break}}}newList=document.createElement(f.listType);this.beyondMaxLevels=0;if(h!=null&&this.positionAbs.left<h.offset().left){h.after(this.placeholder[0]);this._clearEmpty(h[0]);this._trigger("change",d,this._uiHash())}else{if(k!=null&&this.positionAbs.left>k.offset().left+f.tabSize){this._isAllowed(k,b+j+1);if(!k.children(f.listType).length){k[0].appendChild(newList)}k.children(f.listType)[0].appendChild(this.placeholder[0]);this._trigger("change",d,this._uiHash())}else{this._isAllowed(h,b+j)}}this._contactContainers(d);if(a.ui.ddmanager){a.ui.ddmanager.drag(this,d)}this._trigger("sort",d,this._uiHash());this.lastPositionAbs=this.positionAbs;return false},_mouseStop:function(e,f){if(this.beyondMaxLevels){this.placeholder.removeClass(this.options.errorClass);if(this.options.revertOnError){if(this.domPosition.prev){a(this.domPosition.prev).after(this.placeholder)}else{a(this.domPosition.parent).prepend(this.placeholder)}this._trigger("revert",e,this._uiHash())}else{var c=this.placeholder.parent().closest(this.options.items);for(var b=this.beyondMaxLevels-1;b>0;b--){c=c.parent().closest(this.options.items)}c.after(this.placeholder);this._trigger("change",e,this._uiHash())}}for(var b=this.items.length-1;b>=0;b--){var d=this.items[b].item[0];this._clearEmpty(d)}a.ui.sortable.prototype._mouseStop.apply(this,arguments)},serialize:function(d){var b=this._getItemsAsjQuery(d&&d.connected),c=[];d=d||{};a(b).each(function(){var f=(a(d.item||this).attr(d.attribute||"id")||"").match(d.expression||(/(.+)[-=_](.+)/)),e=(a(d.item||this).parent(d.listType).parent("li").attr(d.attribute||"id")||"").match(d.expression||(/(.+)[-=_](.+)/));if(f){c.push(((d.key||f[1])+"["+(d.key&&d.expression?f[1]:f[2])+"]")+"="+(e?(d.key&&d.expression?e[1]:e[2]):"root"))}});if(!c.length&&d.key){c.push(d.key+"=")}return c.join("&")},toHierarchy:function(e){e=e||{};var c=e.startDepthCount||0,d=[];a(this.element).children("li").each(function(){var f=b(a(this));d.push(f)});return d;function b(f){var h=(a(f).attr(e.attribute||"id")||"").match(e.expression||(/(.+)[-=_](.+)/));if(h){var g={id:h[2]};if(a(f).children(e.listType).children("li").length>0){g.children=[];a(f).children(e.listType).children("li").each(function(){var i=b(a(this));g.children.push(i)})}return g}}},toArray:function(f){f=f||{};var b=f.startDepthCount||0,c=[],d=2;c.push({item_id:"root",parent_id:"none",depth:b,left:"1",right:(a("li",this.element).length+1)*2});a(this.element).children("li").each(function(){d=e(this,b+1,d)});c=c.sort(function(h,g){return(h.left-g.left)});return c;function e(j,l,k){var i=k+1,m,h;if(a(j).children(f.listType).children("li").length>0){l++;a(j).children(f.listType).children("li").each(function(){i=e(a(this),l,i)});l--}m=(a(j).attr(f.attribute||"id")).match(f.expression||(/(.+)[-=_](.+)/));if(l===b+1){h="root"}else{var g=(a(j).parent(f.listType).parent("li").attr(f.attribute||"id")).match(f.expression||(/(.+)[-=_](.+)/));h=g[2]}if(m){c.push({item_id:m[2],parent_id:h,depth:l,left:k,right:i})}k=i+1;return k}},_clearEmpty:function(b){var c=a(b).children(this.options.listType);if(c.length&&!c.children().length){c.remove()}},_getLevel:function(b){var d=1;if(this.options.listType){var c=b.closest(this.options.listType);while(!c.is(".ui-sortable")){d++;c=c.parent().closest(this.options.listType)}}return d},_getChildLevels:function(d,f){var c=this,e=this.options,b=0;f=f||0;a(d).children(e.listType).children(e.items).each(function(g,h){b=Math.max(c._getChildLevels(h,f+1),b)});return f?b+1:b},_isAllowed:function(b,c){var d=this.options;if(b==null||!(b.hasClass(d.disableNesting))){if(d.maxLevels<c&&d.maxLevels!=0){this.placeholder.addClass(d.errorClass);this.beyondMaxLevels=c-d.maxLevels}else{this.placeholder.removeClass(d.errorClass);this.beyondMaxLevels=0}}else{this.placeholder.addClass(d.errorClass);if(d.maxLevels<c&&d.maxLevels!=0){this.beyondMaxLevels=c-d.maxLevels}else{this.beyondMaxLevels=1}}}}));a.ui.nestedSortable.prototype.options=a.extend({},a.ui.sortable.prototype.options,a.ui.nestedSortable.prototype.options)})(jQuery);

