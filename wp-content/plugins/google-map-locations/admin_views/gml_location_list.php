<?php 
/**
 * Section to display Custom Admin overview
 * @author Dotsquares
 */
?>
<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) 
	die('You are not allowed to call this page directly.'); 
	
$title = __('Google Map Locations Listing');

global $wpdb;

$max = 10;
/*Get the current page eg index.php?pg=4*/

if(isset($_GET['pg'])){
    $p =$_GET['pg'];
}else{
    $p = 1;
}

$limit = ($p - 1) * $max;
$prev = $p - 1;
$next = $p + 1;
$limits = (int)($p - 1) * $max;

$sql = "SELECT *FROM ".GML_TABLE_PREFIX."locations order by created_date desc limit $limits,$max";
$trainerList = $wpdb->get_results($sql);


//Get total records from db
$totalres = "SELECT COUNT(id) AS totalCount FROM ".GML_TABLE_PREFIX."locations";
$totalTrainers = $wpdb->get_results($totalres,'ARRAY_A');
//devide it with the max value & round it up
$total_record = $totalTrainers[0]['totalCount'];
$totalposts = ceil($total_record / $max);
$lpm1 = $totalposts - 1;


?>
<div class="wrap" id="customers">
  <div class="icon32 icon32-posts-post" id="icon-edit"></div>
  <h2><?php echo esc_html( $title ); ?>
    <?php /*?><a class="add-new-h2" href="<?php echo admin_url('admin.php?page=add-boxes');?>"><?php _e('New Boxes') ?></a><?php */?>
  </h2>
  <div class="clear"></div>
  <?php if($_GET['trashed']==1){?>
  <div class="updated below-h2" id="message">
    <p> Record deleted successfully. </p>
  </div>
  <?php } if(isset($_SESSION['edited'])){ ?>
  <div class="updated below-h2" id="message">
    <p> <?php
	 echo $_SESSION['edited'];
	 unset($_SESSION['edited']);
	 ?>. </p>
  </div>
  <?php } if(isset($_SESSION['deleted'])){ ?>
  <div class="updated below-h2" id="message">
    <p> <?php
	 echo $_SESSION['deleted'];
	 unset($_SESSION['deleted']);
	 ?>. </p>
  </div>
  <?php } 
   if(isset($_SESSION['added'])){ ?>
  <div class="updated below-h2" id="message">
    <p> <?php
	 echo $_SESSION['added'];
	 unset($_SESSION['added']);
	 ?>. </p>
  </div>
  <?php } ?>
  
    <p>
	<a title="Add New" href="<?php echo admin_url( 'admin.php?page=add-location')?>">Add New</a>
	 </p>
  
  <table width="70%" cellspacing="0" class="widefat">
    <thead>
      <tr>
        <th scope="col" ><?php _e('ID'); ?></th>
        <th scope="col" ><?php _e('Title Heading'); ?></th>
        <th scope="col" ><?php _e('Shortfact->1 '); ?></th>
		<th scope="col" ><?php _e('Latitude '); ?></th>
		<th scope="col" ><?php _e('Longitude '); ?></th>
		<th scope="col" ><?php _e('Date '); ?></th>
        <?php /*?><th scope="col" ><?php _e('Action'); ?></th><?php */?>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th scope="col" ><?php _e('ID'); ?></th>
        <th scope="col" ><?php _e('Trainer Name'); ?></th>
        <th scope="col" ><?php _e('Postcode '); ?></th>
		<th scope="col" ><?php _e('Latitude '); ?></th>
		<th scope="col" ><?php _e('Longitude '); ?></th>		
		<th scope="col" ><?php _e('Date '); ?></th>
        <?php /*?><th scope="col" ><?php _e('Action'); ?></th><?php */?>
      </tr>
    </tfoot>
    <tbody>
      <?php
				if($trainerList) {
					global $wpdb;
					foreach($trainerList as $trainers) {
					
						$class = ( !isset($class) || $class == 'class="alternate"' ) ? '' : 'class="alternate"';
						
						?>
        <tr id="customer-<?php echo $cid ?>" <?php echo $class; ?>  >
        <td scope="row"><?php echo $trainers->id; ?></td>
        <td><?php echo $trainers->name; ?>		
		<div class="row-actions">
			<span class="edit">
			<a title="Edit this item" href="<?php echo admin_url( 'admin.php?page=add-location&amp;action=edit&amp;locationId=' . $trainers->id)?>">Edit</a>
			</span> |
			<span class="edit">
			<a title="Edit this item" href="<?php echo admin_url( 'admin.php?page=add-location&amp;action=delete&amp;locationId=' . $trainers->id)?>">Delete</a>
			</span>
		</div>
          <div class="row-actions"><span class="edit"><br />
            </span></div></td>
			<td scope="row"><?php echo $trainers->postcode; ?></td>
			<td scope="row"><?php echo $trainers->latitude; ?></td>
			<td scope="row"><?php echo $trainers->longitude; ?></td>			
        <td><?php echo date("d M Y", strtotime($trainers->created_date)); ?></td>   
        <?php /*?><td><a href="<?php echo admin_url('admin.php?page=order-boxes&action=delete&deleteid='.$cid );?>">Delete</a></td><?php */?>
      </tr>      
      <?php
					}
				} else {
					echo '<tr><td colspan="7" align="center"><strong>' . __('No entries found') . '</strong></td></tr>';
				}
				?>
    </tbody>
  </table>
  <div class="tablenav">    
    <?php if ( $total_record > $max ) { ?>
    <div class="tablenav-pages">
		<?php
		  echo pagination($total_record, $totalposts,$p,$lpm1,$prev,$next);
		?>
    </div>
    <?php }?>
  </div>
</div>