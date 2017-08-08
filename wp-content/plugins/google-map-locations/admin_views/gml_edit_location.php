<?php 
/**
 * Section to display Custom Admin overview
 * @author Dotsquares
 */
?>
<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) 
	die('You are not allowed to call this page directly.'); 
	
	
$title = __('Google Map Location Edit');

global $wpdb;
if($_REQUEST['action']='edit' && $_REQUEST['locationId']!=''){
	$location_id = $_REQUEST['locationId'];
	
	$sql = "SELECT *FROM ".GML_TABLE_PREFIX."locations where id='$location_id'";
	$location_record = $wpdb->get_results($sql,'ARRAY_A');
	
}
?>
<div class="wrap" id="customers">
  <div class="icon32 icon32-posts-post" id="icon-edit"></div>
  <h2><?php echo esc_html( $title ); ?></h2>
  <div class="clear"></div>
  <?php if($_GET['trashed']==1){?>
  <div class="updated below-h2" id="message">
    <p> Record deleted successfully. </p>
  </div>
  <?php } if($_GET['message']=='added'){ ?>
  <div class="updated below-h2" id="message">
    <p> Record Added successfully. </p>
  </div>
  <?php } 
  if($_GET['message']=='updated'){ ?>
  <div class="updated below-h2" id="message">
    <p> Record Updated successfully. </p>
  </div>
  <?php } ?>
  <br />
 <form name="add_trainer" id="add_trainer" action="<?php echo admin_url('admin.php?page=add-location&action=edit&locationId=$location_id'); ?>" method="post" onsubmit="return checkForm_edit();" enctype="multipart/form-data"> 
  <table style="padding-left:5px;" class="create_trainer" cellspacing="5">
  	<tr>
		<td width="150">Title heading:</td>
		<td>
		<input class="my_input" type="hidden" name="location_id" id="location_id" value="<?php echo $location_record[0]['id']; ?>"/>
		<input class="my_input" type="hidden" name="category_id" id="category_id" value="<?php echo $location_record[0]['id']; ?>"/>
		<input class="my_input" type="text" name="name" id="name" placeholder="Full Name" value="<?php echo $location_record[0]['name']; ?>"/>
		
		</td>
	</tr>
	<tr>
		<td>Link of Page*: </td>
		


                 <td>
    
                 <select class="my_input" name="address_one" id="address_one">
                    

<?php


$args = array(
 'numberposts' => 100,
 'post_type'   => 'us_portfolio'
);
$lastposts = get_posts( $args );
//print_r($lastposts = get_posts( $args ));
if ( $lastposts ) {
   foreach ( $lastposts as $post ) :
       setup_postdata( $post ); 
  $pgid=  $post->guid;
  $loc_link =$location_record[0]['address_one'];
   

  
?>
  <option value="<?php echo $post->guid; ?>" <?php if ($loc_link == $pgid ){ echo 'selected' ; } ?> ><?php echo $post->post_name; ?></option>
 <?php

   endforeach; 
   wp_reset_postdata();
}

?>

  </select>
		
</td>

            


               
	</tr>	
	<tr>
		<td>Image Upload: </td>
		<td>

		<label for="upload_image">
    <input id="upload_image" type="text" name="address_two" value ="<?php echo $location_record[0]['address_two']; ?>" /> 
    <input id="upload_image_button" class="button" type="button" value="Upload Image" />
    <br />Enter a URL or upload an image
</label>

<?php
add_action('admin_enqueue_scripts', 'my_admin_scripts');

function my_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'my_plugin_page') {
        wp_enqueue_media();
        wp_register_script('my-admin-js', WP_PLUGIN_URL.'/my-plugin/my-admin.js', array('jquery'));
        wp_enqueue_script('my-admin-js');
    }
}

?>

<script>
    jQuery(document).ready(function($){


    var custom_uploader;


    $('#upload_image_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            console.log(custom_uploader.state().get('selection').toJSON());
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();

    });


});
    </script>
		
		
		<?php if($location_record[0]['address_two'] !='') {?>
		<img src="<?php echo $location_record[0]['address_two'];?>" width="50px" height="50px" />
		<?php } ?>
		
		</td>
	</tr>	
	<tr>
		<td>Shortfact->1 Data:</td>
		<td>
		<textarea  rows="4" cols="41" class=""  name="postcode" id="postcode"  placeholder="Shortfact->1 Data" /><?php echo $location_record[0]['postcode']; ?></textarea>
		</td>
	</tr>
	<tr>
		<td>Shortfact->2 Data:  </td>
		<td>
		<textarea  rows="4" cols="41" class="" name="phone" id="phone"  placeholder="Shortfact->2 Data:" /><?php echo $location_record[0]['phone'];?></textarea>
		</td>
	</tr>
	<tr>
		<td>Shortfact->3 Data: </td>
		<td>
		<textarea  rows="4" cols="41" class="" name="email" id="email" placeholder="Shortfact->3 Data:" /><?php echo $location_record[0]['email'];?></textarea>
		</td> 
	</tr>	
	<tr>
		<td>Latitude*: </td>
		<td>
		<input class="my_input" type="text" name="latitude" id="latitude"  placeholder="Latitude" value="<?php echo $location_record[0]['latitude']; ?>"/>
		</td>
	</tr>
	<tr>
		<td>Longitude*: </td>
		<td>
		<input class="my_input" type="text" name="longitude" id="longitude"  placeholder="Longitude" value="<?php echo $location_record[0]['longitude']; ?>"/>
		</td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td>
		<input type="submit" name="submit" id="submit" value="Edit Location" />
		</td>
	</tr>
  </table>
  </form>
</div>