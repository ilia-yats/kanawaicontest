<?php 
/**
 * Section to display Custom Admin overview
 * @author Dotsquares
 */
?>
<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) 
	die('You are not allowed to call this page directly.'); 
	
	
$title = __('Google Map Default Settings');

global $wpdb;
if($_REQUEST['page']='gml-settings'){
	$settings_id = 1;
	
	$sql = "SELECT *FROM ".GML_TABLE_PREFIX."settings where id='$settings_id'";
	$setting_record = $wpdb->get_results($sql,'ARRAY_A');
	
}
?>
<div class="wrap" id="customers">
  <div class="icon32 icon32-posts-post" id="icon-edit"></div>
  <h2><?php echo esc_html( $title ); ?></h2>
  <div class="clear"></div>
  <?php if(isset($_SESSION['edited'])){ ?>
  <div class="updated below-h2" id="message">
    <p> <?php
	 echo $_SESSION['edited'];
	 unset($_SESSION['edited']);
	 ?>. </p>
  </div>
  <?php } ?>
 
  
 <form name="add_trainer" id="add_trainer" action="<?php echo admin_url('admin.php?page=gml-settings&action=edit'); ?>" method="post" onsubmit="return settings_edit();"> 
  <div>
 	<div style="float:left;">
		<p style="padding-left:200px;"><strong>Default Settings </strong></p>
  		<table style="padding-left:5px; float:left;" class="create_trainer" cellspacing="5">
  	
	<tr>
		<td>Default Latitude: </td>
		<td>
		<input class="my_input" type="text" name="latitude" id="latitude"  placeholder="Latitude" value="<?php echo $setting_record[0]['latitude']; ?>"/>
		</td>
	</tr>
	<tr>
		<td>Default Longitude: </td>
		<td>
		<input class="my_input" type="text" name="longitude" id="longitude"  placeholder="Longitude" value="<?php echo $setting_record[0]['longitude']; ?>"/>
		</td>
	</tr>
	
	<tr>
		<td>Map Height</td>
		<td>
		<input class="my_input" type="text" name="map_height" id="map_height" value="<?php echo $setting_record[0]['map_height'];?>" />
		</td>
		
	</tr>
	<tr>
		<td>Map Width</td>
		<td>
		<input class="my_input" type="text" name="map_width" id="map_width" value="<?php echo $setting_record[0]['map_width'];?>" />
		</td>
		
	</tr>
	<tr>
		<td colspan="2"><hr /></td>
		
	</tr>
	<tr>
		<td colspan="2" style="padding:20px 10px 10px 1px; font-weight:bold;">Map Location Information Settings</td>
		
		
	</tr>
	<tr>
		<td>Show Phone</td>
		<td>
		<?php $show_phone =  $setting_record[0]['show_phone'];?>
		<input type="radio" <?php if($show_phone==1){echo 'checked="checked"';}?> name="show_phone" id="yes" value="1"/>Yes
		<input type="radio" <?php if($show_phone==0){echo 'checked="checked"';}?> name="show_phone" id="no" value="0" />No
		</td>
		
	</tr>
	<tr>
		<td>Show Email</td>
		<td>
		<?php $show_email =  $setting_record[0]['show_email'];?>
		<input type="radio" <?php if($show_email==1){echo 'checked="checked"';}?> name="show_email" id="yes" value="1"/>Yes
		<input type="radio" <?php if($show_email==0){echo 'checked="checked"';}?> name="show_email" id="no" value="0" />No
		</td>
		
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
		<input class="my_input" type="hidden" name="settings_id" id="settings_id" value="<?php echo $setting_record[0]['id']; ?>"/>
		<input type="submit" name="submit" id="submit" value="Edit Settings" />
		</td>
	</tr>
  </table>
  	</div>
  	
  </div>
  </form>
</div>
<div class="clear"></div>
<hr />
<div>
<ul>
	<li>Use short code<strong> "[gml-google-show-map]"</strong></li>
</ul>
</div>