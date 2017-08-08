<?php
/* 
Plugin Name: Google Map Locations 
Plugin URI: http://wordpress.org/plugins/google-map-locations/
Description: This plugin lets you add a google map with locations with bubble.
Version: 1.0 
Author: Dotsquares 
Author URI: http://wordpressplugins.projectstatus.in
License: GPL2
*/ 

$siteurl = get_option('siteurl');
define('GML_FOLDER', dirname(plugin_basename(__FILE__)));
define('GML_URL', $siteurl.'/wp-content/plugins/' . GML_FOLDER);
define('GML_FILE_PATH', dirname(__FILE__));
define('GML_DIR_NAME', basename(GML_FILE_PATH));

global $wpdb;

include('includes/gml_functions.php');

// this is the table prefix
$gml_table_prefix=$wpdb->prefix.'gml_';

define('GML_TABLE_PREFIX', $gml_table_prefix);


register_activation_hook(__FILE__,'gml_install');
register_deactivation_hook(__FILE__ , 'gml_uninstall' );

add_action('admin_menu','gml_admin_menu');
add_action( 'admin_init', 'my_plugin_admin_css' );
add_shortcode( 'gml-google-show-map', 'gml_show_map' );


function gml_install()
{
    global $wpdb;
		
	$table_locations = GML_TABLE_PREFIX."locations";
    $structure = "CREATE TABLE $table_locations (
        id INT(11) NOT NULL AUTO_INCREMENT,
		category_id INT(11) NOT NULL,
        name VARCHAR(255) NOT NULL,
		address_one VARCHAR(255) NOT NULL,
		address_two VARCHAR(255) NOT NULL,
		postcode VARCHAR(255) NOT NULL,
		phone VARCHAR(255) NULL,
		email VARCHAR(255) NULL,
		latitude VARCHAR(130) NOT NULL,	
        longitude VARCHAR(130) NOT NULL,
		location_image VARCHAR(130) NULL,
		location_view_url VARCHAR(130) NULL,	 	 
        description text,		
		created_date datetime NOT NULL,
		modified_date datetime NOT NULL,
		status TINYINT(2) NOT NULL,
	UNIQUE KEY id (id)
    );";	
    $wpdb->query($structure);
	 
    $wpdb->query(
	"INSERT INTO $table_locations(
		category_id,
		name,
		address_one,
		address_two,
		postcode,
		phone,
		email,
		latitude,
		longitude,
		location_image,
		location_view_url,
		description,
		created_date,
		modified_date,
		status
	) VALUES(
		'1',
		'Test Location name',
		'b-360',
		'Prince Road',
		'S36 1FH',
		'+00 00 0000 0000',
		'test@email.com',
		'53.4865667',
		'-1.6158775',
		'test.jpg',
		'http//:www.testcreatelocation.com',
		'test description',
		'2013-08-06 10:20:30',
		'2013-08-06 10:20:30',
		1
	)");
	
	/*
	*
	* Create new table for settings
	*
	*/	
	$table_settings = GML_TABLE_PREFIX."settings";
    $structure = "CREATE TABLE $table_settings (
        id INT(11) NOT NULL AUTO_INCREMENT,
        latitude VARCHAR(130) NOT NULL,	
        longitude VARCHAR(130) NOT NULL,		 
        map_height VARCHAR(20) NULL,
		map_width VARCHAR(20) NULL,
		show_phone TINYINT(2) NOT NULL,
		show_email TINYINT(2) NOT NULL,
		show_image TINYINT(2) NOT NULL,
		show_vd_link TINYINT(2) NOT NULL,
		created_date datetime NOT NULL,	
		status TINYINT(2) NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
	 
    $wpdb->query(
	"INSERT INTO $table_settings(
		latitude,
		longitude,
		map_height,
		map_width,
		show_phone,
		show_email,
		show_image,
		show_vd_link,
		created_date,
		status
	) VALUES(
		'53.5500',
		'2.4333',
		'600',
		'600',
		'1',
		'1',
		'1',
		'1',
		'".date('Y-m-d')."',
		1
	)");
			
		
}
/*
*
* Plugin uninstall process
*
*/	
function gml_uninstall()
{
    global $wpdb;
  	
	$table = GML_TABLE_PREFIX."locations";
 	$structure = "drop table if exists $table";
    //$wpdb->query($structure);
	
	$table = GML_TABLE_PREFIX."settings";
 	$structure = "drop table if exists $table";
    //$wpdb->query($structure); 
} 
/*
*
* Add custom css and javascript files
*
*/	
function my_plugin_admin_css(){
	
	/* Register our stylesheet. */
	wp_register_style('gml-styles',  GML_URL . '/css/gml-styles.css', '');
	wp_enqueue_style( 'gml-styles');
    
	 wp_register_script( 'gml-custom',  GML_URL . '/js/gml-custom.js', '');
	 wp_enqueue_script( 'gml-custom');	   
	
} 
/*
*
* Create admin menu for wp admin
*
*/	
function gml_admin_menu() {

$parent_slug = 'google-map-location'; 
add_menu_page("All Locations", "Google Map Locations", 'manage_options', $parent_slug, "gml_location_list", GML_URL."/images/menu.gif");
add_submenu_page($parent_slug, 'Add New Location','Add New Location','manage_options','add-location','gml_add_location');
add_submenu_page($parent_slug, 'Settings','Settings','manage_options','gml-settings','gml_settings');

}
 
function gml_show_map(){
	gml_show_map_with_location();
}
function gml_location_list(){
	
	include('admin_views/gml_location_list.php');	

}

function gml_add_location(){
	global $wpdb;
	
	if(!isset($_REQUEST['action'])){
	
		include('admin_views/gml_add_location.php');
	
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='add'){
		
		gml_add_new_location();
	}
	
	if($_REQUEST['action']=='edit' && isset($_REQUEST['location_id'])){
	
		gml_edit_location();
	
	}	
	if($_REQUEST['action']=='edit' && !isset($_REQUEST['location_id'])){
	
	
		include('admin_views/gml_edit_location.php');
	
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete' && $_REQUEST['action']!='edit'){
	
		gml_delete_location();
	
	}

}

function gml_settings(){

	if($_REQUEST['action']=='edit'){
	
		gml_edit_settings();
	
	}else{
		include('admin_views/gml_edit_settings.php');	
	}

 }
 
?>