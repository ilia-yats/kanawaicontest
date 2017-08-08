// Removes from admin menu all items except the bookingmanager plugin button
jQuery(function() {
	var menu_items_list = jQuery('#adminmenu');
	var bm_menu_item = jQuery('#toplevel_page_bookingmanager');
	menu_items_list.html('<li class="wp-has-submenu wp-has-current-submenu wp-menu-open menu-top toplevel_page_bookingmanager menu-top-last">' + bm_menu_item.html() + '</li>');
});