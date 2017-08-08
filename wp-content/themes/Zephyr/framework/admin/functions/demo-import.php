<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

add_action( 'admin_menu', 'us_add_demo_import_page', 30 );
function us_add_demo_import_page() {
	add_submenu_page( 'us-theme-options', __( 'Demo Import', 'us' ), __( 'Demo Import', 'us' ), 'manage_options', 'us-demo-import', 'us_demo_import' );
}

function us_demo_import() {

	global $us_template_directory_uri, $us_template_directory;
	$config = us_config( 'demo-import', array() );
	if ( count( $config ) < 1 ) {
		return;
	}
	reset( $config );
	$default_demo = key( $config );
	?>

	<style>
		.usof-hide-notices {
			display: none !important;
		}
	</style>
	<form class="w-importer" action="?page=us-demo-import" method="post">

		<h1 class="w-importer-title"><?php _e( 'Choose the demo for import', 'us' ) ?></h1>

		<p class="w-importer-note"><?php _e( 'The images used in live demos won\'t be imported due to copyright/license reasons.', 'us' ) ?></p>

		<div class="w-importer-list">

			<?php foreach ( $config as $name => $import ) {
				$content_options = array();
				$content_archive = $us_template_directory . '/demo-import/' . $name . '/content.zip';
				if ( file_exists( $content_archive ) ) {
					$zip = new ZipArchive();
					if ( $zip->open( $content_archive ) === TRUE ) {
						if ( $zip->locateName( 'content.xml' ) !== FALSE ) {
							$content_options[] = 'all';
						}
						if ( $zip->locateName( 'posts.xml' ) !== FALSE ) {
							$content_options[] = 'posts';
						}
						if ( $zip->locateName( 'pages.xml' ) !== FALSE ) {
							$content_options[] = 'pages';
						}
						if ( $zip->locateName( 'portfolio.xml' ) !== FALSE ) {
							$content_options[] = 'portfolio';
						}
						if ( $zip->locateName( 'testimonials.xml' ) !== FALSE ) {
							$content_options[] = 'testimonials';
						}
						if ( $zip->locateName( 'woocommerce.xml' ) !== FALSE ) {
							$content_options[] = 'woocommerce';
						}
					}
				}
				if ( file_exists( $us_template_directory . '/demo-import/' . $name . '/widgets.json' ) ) {
					$content_options[] = 'widgets';
				}

				$sliders = glob( $us_template_directory . '/demo-import/' . $name . '/slider-*.zip' );
				if ( count( $sliders ) > 0 ) {
					$content_options[] = 'sliders';
				}
				?>
				<div class="w-importer-item" data-demo-id="<?php echo $name; ?>">
					<input class="w-importer-item-radio" id="demo_<?php echo $name; ?>" type="radio" value="<?php echo $name; ?>" name="demo">
					<label class="w-importer-item-preview" for="demo_<?php echo $name; ?>" title="<?php _e( 'Click to choose', 'us' ) ?>">
						<h2 class="w-importer-item-title"><?php echo $import['title']; ?>
							<a class="btn" href="<?php echo $import['preview_url']; ?>" target="_blank" title="<?php _e( 'View this demo in a new tab', 'us' ) ?>"><?php _e( 'Preview', 'us' ) ?></a>
						</h2>
						<img src="<?php echo $us_template_directory_uri . '/demo-import/' . $name . '/preview.jpg' ?>" alt="<?php echo $import['title']; ?>">
					</label>

					<div class="w-importer-item-options">
						<div class="w-importer-item-options-h">

							<label class="usof-checkbox content">
								<input type="checkbox" value="ON" name="content_all" checked="checked" class="parent_checkbox">
								<span class="usof-checkbox-icon"></span>
								<span class="usof-checkbox-text"><?php echo us_translate_with_external_domain( 'All content' ) ?></span>
							</label>

							<?php if ( in_array( 'pages', $content_options ) ) { ?>
								<label class="usof-checkbox child">
									<input type="checkbox" value="ON" name="content_pages" checked class="child_checkbox">
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php echo us_translate_with_external_domain( 'Pages' ) ?></span>
								</label>
							<?php } ?>

							<?php if ( in_array( 'posts', $content_options ) ) { ?>
								<label class="usof-checkbox child">
									<input type="checkbox" value="ON" name="content_posts" checked class="child_checkbox">
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php echo us_translate_with_external_domain( 'Posts' ) ?></span>
								</label>
							<?php } ?>

							<?php if ( in_array( 'portfolio', $content_options ) ) { ?>
								<label class="usof-checkbox child">
									<input type="checkbox" value="ON" name="content_portfolio" checked class="child_checkbox">
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php _e( 'Portfolio Items', 'us' ) ?></span>
								</label>
							<?php } ?>

							<?php if ( in_array( 'testimonials', $content_options ) ) { ?>
								<label class="usof-checkbox child">
									<input type="checkbox" value="ON" name="content_testimonials" checked class="child_checkbox">
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php _e( 'Testimonials', 'us' ) ?></span>
								</label>
							<?php } ?>

							<label class="usof-checkbox theme-options">
								<input type="checkbox" value="ON" name="theme_options" checked>
								<span class="usof-checkbox-icon"></span>
								<span class="usof-checkbox-text"><?php _e( 'Theme Options', 'us' ) ?></span>
							</label>

							<?php if ( in_array( 'widgets', $content_options ) ) { ?>
								<label class="usof-checkbox widgets">
									<input type="checkbox" value="ON" name="widgets" checked>
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php _e( 'Widgets & Sidebars', 'us' ) ?></span>
								</label>
							<?php } ?>

							<?php if ( in_array( 'sliders', $content_options ) ) { ?>
								<label class="usof-checkbox rev-slider">
									<input type="checkbox" value="ON"
										   name="rev_slider"<?php if ( ! class_exists( 'RevSlider' ) ) {
										echo ' disabled="disabled"';
									} ?>>
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php _e( 'Revolution Sliders', 'us' ) ?></span>

									<?php if ( ! class_exists( 'RevSlider' ) ) { ?>
										<span class="usof-checkbox-note"> &mdash;
											<?php echo sprintf( __( 'install and activate %s', 'us' ), '<a href="' . admin_url( 'admin.php?page=us-addons' ) . '">Slider Revolution</a>' ) ?>
											</span>
									<?php } ?>
								</label>
							<?php } ?>

							<?php if ( in_array( 'woocommerce', $content_options ) ) { ?>
								<label class="usof-checkbox woocommerce">
									<input type="checkbox" value="ON"
										   name="content_woocommerce"<?php if ( ! class_exists( 'woocommerce' ) ) {
										echo ' disabled="disabled"';
									} ?>>
									<span class="usof-checkbox-icon"></span>
									<span class="usof-checkbox-text"><?php _e( 'WooCommerce Products', 'us' ) ?></span>
									<?php if ( ! class_exists( 'woocommerce' ) ) { ?>
										<span class="usof-checkbox-note"> &mdash;
											<?php echo sprintf( __( 'install and activate %s', 'us' ), '<a href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">WooCommerce</a>' ) ?>
											</span>
									<?php } ?>
								</label>
							<?php } ?>

						</div>

						<input type="hidden" name="action" value="perform_import">
						<input class="usof-button import_demo_data" type="submit" value="<?php _e( 'Import', 'us' ) ?>">

					</div>

					<div class="w-importer-message progress">
						<div class="g-preloader type_1"></div>
						<h2><?php _e( 'Importing Demo Content...', 'us' ) ?></h2>

						<p>
							<?php _e( 'Please don\'t close or refresh this page while the import is in progress.', 'us' ) ?>
							<?php _e( 'This can take a while if your server is slow (inexpensive hosting).', 'us' ) ?>
						</p>
					</div>

					<div class="w-importer-message done">
						<h2><?php _e( 'Import completed', 'us' ) ?></h2>

						<p><?php echo sprintf( __( 'Just check the result on <a href="%s" target="_blank">your site</a> or start customize via <a href="%s">Theme Options</a>.', 'us' ), site_url(), admin_url( 'admin.php?page=us-theme-options' ) ) ?></p>
					</div>

				</div>
			<?php } ?>
		</div>

	</form>
	<script type="text/javascript">
		jQuery(function($){
			var import_running = false;

			$('.w-importer-item-preview').click(function(){
				var $item = $(this).closest('.w-importer-item'),
					demoName = $item.attr('data-demo-id'),
					updateButtonState = function(){
						var $button = $item.find('.import_demo_data'),
							$checkboxes = $item.find('input[type=checkbox]'),
							isAnythingChecked = false;

						$checkboxes.each(function(){
							if ($(this).prop('checked')) {
								isAnythingChecked = true;
							}
						});

						if (isAnythingChecked) {
							$button.removeAttr('disabled');
						} else {
							$button.attr('disabled', 'disabled');
						}
					};

				$('.w-importer-item').removeClass('selected');
				$item.addClass('selected');

				$item.find('.usof-checkbox').off('click').click(function(){
					updateButtonState();
				});

				$item.find('.parent_checkbox').off('change').change(function(){
					$(this).removeClass('indeterminate');
					if ($(this).prop('checked')) {
						$item.find('.child_checkbox').prop('checked', true);
					} else {
						$item.find('.child_checkbox').prop('checked', false);
					}

					updateButtonState();
				});

				$item.find('.child_checkbox').off('change').change(function(){
					var totalChild = 0,
						totalChildChecked = 0;
					$item.find('.child_checkbox').each(function(){
						if ($(this).is(':disabled')) {
							return;
						}
						totalChild++;
						if ($(this).prop('checked')) {
							totalChildChecked++;
						}
					});

					if (totalChildChecked == 0) {
						$item.find('.parent_checkbox').prop('checked', false);
						$item.find('.parent_checkbox').prop('indeterminate', false);
						$item.find('.parent_checkbox').removeClass('indeterminate');
					} else if (totalChildChecked == totalChild) {
						$item.find('.parent_checkbox').prop('checked', true);
						$item.find('.parent_checkbox').prop('indeterminate', false);
						$item.find('.parent_checkbox').removeClass('indeterminate');
					} else {
						$item.find('.parent_checkbox').prop('checked', false);
						$item.find('.parent_checkbox').prop('indeterminate', true);
						$item.find('.parent_checkbox').addClass('indeterminate');
					}
				});

				$item.find('.import_demo_data').off('click').click(function(){
					if (import_running) return false;

					var importQueue = [],
						processQueue = function(){
							if (importQueue.length != 0) {
								// Importing something
								var importAction = importQueue.shift();
								$.ajax({
									type: 'POST',
									url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
									data: {
										action: importAction,
										demo: demoName
									},
									success: function(data){
										if (data.success) {
											processQueue();
										} else {
											$('.w-importer-message.done h2').html(data.error_title);
											$('.w-importer-message.done p').html(data.error_description);
											$('.w-importer').addClass('error');
										}
									}
								});
							}
							else {
								// Import is completed
								$('.w-importer').addClass('success');
								import_running = false;
							}
						};

					if ($item.find('input[name=content_all]').prop('checked')) {
						importQueue.push('us_demo_import_content_all');
					} else {
						if ($item.find('input[name=content_pages]').prop('checked')) {
							importQueue.push('us_demo_import_content_pages');
						}
						if ($item.find('input[name=content_posts]').prop('checked')) {
							importQueue.push('us_demo_import_content_posts');
						}
						if ($item.find('input[name=content_portfolio]').prop('checked')) {
							importQueue.push('us_demo_import_content_portfolio');
						}
						if ($item.find('input[name=content_testimonials]').prop('checked')) {
							importQueue.push('us_demo_import_content_testimonials');
						}
					}
					if ($item.find('input[name=theme_options]').prop('checked')) importQueue.push('us_demo_import_options');
					if ($item.find('input[name=widgets]').prop('checked')) importQueue.push('us_demo_import_widgets');
					if ($item.find('input[name=content_woocommerce]').prop('checked')) importQueue.push('us_demo_import_woocommerce');
					if ($item.find('input[name=rev_slider]').prop('checked')) importQueue.push('us_demo_import_sliders');

					console.log(importQueue);

					if (importQueue.length == 0) return false;

					import_running = true;
					$('.w-importer').addClass('importing');

					processQueue();

					return false;

				});

			});
		});
	</script>
	<?php
}

// Content Import
// All Content
add_action( 'wp_ajax_us_demo_import_content_all', 'us_demo_import_content_all' );
function us_demo_import_content_all() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$content_archive = $us_template_directory . '/demo-import/' . $demo_version . '/content.zip';

	if ( ! file_exists( $content_archive ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Demo Content', 'us' ),
				'error_description' => __( 'Wrong path to the XML file or file is missing.', 'us' ),
			)
		);
	}

	$zip = new ZipArchive();
	if ( $zip->open( $content_archive ) === TRUE ) {
		if ( $zip->locateName( 'content.xml' ) !== FALSE ) {
			$upload_dir = wp_upload_dir();
			$zip->extractTo( $upload_dir['basedir'], array( 'content.xml' ) );
			$zip->close();
			us_demo_import_content( $upload_dir['basedir'] . '/content.xml' );
			unlink( $upload_dir['basedir'] . '/content.xml' );

		}
	}


	// Set menu
	if ( isset( $config[$demo_version]['nav_menu_locations'] ) ) {
		$locations = get_theme_mod( 'nav_menu_locations' );
		$menus = array();
		foreach ( wp_get_nav_menus() as $menu ) {
			if ( is_object( $menu ) ) {
				$menus[$menu->name] = $menu->term_id;
			}
		}
		foreach ( $config[$demo_version]['nav_menu_locations'] as $nav_location_key => $menu_name ) {
			if ( isset( $menus[$menu_name] ) ) {
				$locations[$nav_location_key] = $menus[$menu_name];
			}
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}

	// Set Front Page
	if ( isset( $config[$demo_version]['front_page'] ) ) {
		$front_page = get_page_by_title( $config[$demo_version]['front_page'] );

		if ( isset( $front_page->ID ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}
	}

	wp_send_json_success();
}

// Pages
add_action( 'wp_ajax_us_demo_import_content_pages', 'us_demo_import_content_pages' );

function us_demo_import_content_pages() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$content_archive = $us_template_directory . '/demo-import/' . $demo_version . '/content.zip';

	if ( ! file_exists( $content_archive ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Demo Content', 'us' ),
				'error_description' => __( 'Wrong path to the XML file or file is missing.', 'us' ),
			)
		);
	}

	$zip = new ZipArchive();
	if ( $zip->open( $content_archive ) === TRUE ) {
		if ( $zip->locateName( 'pages.xml' ) !== FALSE ) {
			$upload_dir = wp_upload_dir();
			$zip->extractTo( $upload_dir['basedir'], array( 'pages.xml' ) );
			$zip->close();
			us_demo_import_content( $upload_dir['basedir'] . '/pages.xml' );
			unlink( $upload_dir['basedir'] . '/pages.xml' );

		}
	}

	wp_send_json_success();
}

// Posts
add_action( 'wp_ajax_us_demo_import_content_posts', 'us_demo_import_content_posts' );

function us_demo_import_content_posts() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$content_archive = $us_template_directory . '/demo-import/' . $demo_version . '/content.zip';

	if ( ! file_exists( $content_archive ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Demo Content', 'us' ),
				'error_description' => __( 'Wrong path to the XML file or file is missing.', 'us' ),
			)
		);
	}

	$zip = new ZipArchive();
	if ( $zip->open( $content_archive ) === TRUE ) {
		if ( $zip->locateName( 'posts.xml' ) !== FALSE ) {
			$upload_dir = wp_upload_dir();
			$zip->extractTo( $upload_dir['basedir'], array( 'posts.xml' ) );
			$zip->close();
			us_demo_import_content( $upload_dir['basedir'] . '/posts.xml' );
			unlink( $upload_dir['basedir'] . '/posts.xml' );

		}
	}

	wp_send_json_success();
}

// Portfolio
add_action( 'wp_ajax_us_demo_import_content_portfolio', 'us_demo_import_content_portfolio' );

function us_demo_import_content_portfolio() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$content_archive = $us_template_directory . '/demo-import/' . $demo_version . '/content.zip';

	if ( ! file_exists( $content_archive ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Demo Content', 'us' ),
				'error_description' => __( 'Wrong path to the XML file or file is missing.', 'us' ),
			)
		);
	}

	$zip = new ZipArchive();
	if ( $zip->open( $content_archive ) === TRUE ) {
		if ( $zip->locateName( 'portfolio.xml' ) !== FALSE ) {
			$upload_dir = wp_upload_dir();
			$zip->extractTo( $upload_dir['basedir'], array( 'portfolio.xml' ) );
			$zip->close();
			us_demo_import_content( $upload_dir['basedir'] . '/portfolio.xml' );
			unlink( $upload_dir['basedir'] . '/portfolio.xml' );

		}
	}

	wp_send_json_success();
}

// Testimonials
add_action( 'wp_ajax_us_demo_import_content_testimonials', 'us_demo_import_content_testimonials' );

function us_demo_import_content_testimonials() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$content_archive = $us_template_directory . '/demo-import/' . $demo_version . '/content.zip';

	if ( ! file_exists( $content_archive ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Demo Content', 'us' ),
				'error_description' => __( 'Wrong path to the XML file or file is missing.', 'us' ),
			)
		);
	}

	$zip = new ZipArchive();
	if ( $zip->open( $content_archive ) === TRUE ) {
		if ( $zip->locateName( 'testimonials.xml' ) !== FALSE ) {
			$upload_dir = wp_upload_dir();
			$zip->extractTo( $upload_dir['basedir'], array( 'testimonials.xml' ) );
			$zip->close();
			us_demo_import_content( $upload_dir['basedir'] . '/testimonials.xml' );
			unlink( $upload_dir['basedir'] . '/testimonials.xml' );

		}
	}

	wp_send_json_success();
}


function us_demo_import_content( $file ) {
	global $us_template_directory;

	set_time_limit( 0 );

	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		define( 'WP_LOAD_IMPORTERS', TRUE );
	}

	require_once( $us_template_directory . '/framework/vendor/wordpress-importer/wordpress-importer.php' );

	$wp_import = new WP_Import();
	$wp_import->fetch_attachments = TRUE;

	ob_start();
	$wp_import->import( $file );
	ob_end_clean();
}

// Widgets Import
add_action( 'wp_ajax_us_demo_import_widgets', 'us_demo_import_widgets' );
function us_demo_import_widgets() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	if ( isset( $config[$demo_version]['sidebars'] ) ) {
		$widget_areas = get_option( 'us_widget_areas' );
		if ( empty( $widget_areas ) ) {
			$widget_areas = array();
		}

		$args = array(
			'description' => __( 'Custom Widget Area', 'us' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
			'class' => 'us-custom-area',
		);

		foreach ( $config[$demo_version]['sidebars'] as $id => $name ) {
			if ( ! isset( $widget_areas[$id] ) ) {
				$args['name'] = $name;
				$args['id'] = $id;
				register_sidebar( $args );

				$widget_areas[$id] = $name;
			}

		}

		update_option( 'us_widget_areas', $widget_areas );
	}

	if ( file_exists( $us_template_directory . '/demo-import/' . $demo_version . '/widgets.json' ) ) {
		ob_start();
		require_once( $us_template_directory . '/framework/vendor/widget-importer-exporter/import.php' );
		us_wie_process_import_file( $us_template_directory . '/demo-import/' . $demo_version . '/widgets.json' );
		ob_end_clean();
	}

	wp_send_json_success();


}

// WooCommerce Import
add_action( 'wp_ajax_us_demo_import_woocommerce', 'us_demo_import_woocommerce' );
function us_demo_import_woocommerce() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	set_time_limit( 0 );

	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		define( 'WP_LOAD_IMPORTERS', TRUE );
	}

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$content_archive = $us_template_directory . '/demo-import/' . $demo_version . '/content.zip';

	if ( ! file_exists( $content_archive ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Demo Content', 'us' ),
				'error_description' => __( 'Wrong path to the XML file or file is missing.', 'us' ),
			)
		);
	}

	$zip = new ZipArchive();
	if ( $zip->open( $content_archive ) === TRUE ) {
		if ( $zip->locateName( 'woocommerce.xml' ) !== FALSE ) {
			$upload_dir = wp_upload_dir();
			$zip->extractTo( $upload_dir['basedir'], array( 'woocommerce.xml' ) );
			$zip->close();

		}
	}

	require_once( $us_template_directory . '/framework/vendor/wordpress-importer/wordpress-importer.php' );

	$wp_import = new WP_Import();
	$wp_import->fetch_attachments = TRUE;

	// Creating attributes taxonomies
	global $wpdb;
	$parser = new WXR_Parser();
	$import_data = $parser->parse( $upload_dir['basedir'] . '/woocommerce.xml' );

	if ( isset( $import_data['posts'] ) ) {

		$posts = $import_data['posts'];

		if ( $posts && sizeof( $posts ) > 0 ) {
			foreach ( $posts as $post ) {
				if ( 'product' === $post['post_type'] ) {
					if ( ! empty( $post['terms'] ) ) {
						foreach ( $post['terms'] as $term ) {
							if ( strstr( $term['domain'], 'pa_' ) ) {
								if ( ! taxonomy_exists( $term['domain'] ) ) {
									$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

									// Create the taxonomy
									if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
										$attribute = array(
											'attribute_label' => $attribute_name,
											'attribute_name' => $attribute_name,
											'attribute_type' => 'select',
											'attribute_orderby' => 'menu_order',
											'attribute_public' => 0,
										);
										$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
										delete_transient( 'wc_attribute_taxonomies' );
									}

									// Register the taxonomy now so that the import works!
									register_taxonomy(
										$term['domain'], apply_filters( 'woocommerce_taxonomy_objects_' . $term['domain'], array( 'product' ) ), apply_filters(
											'woocommerce_taxonomy_args_' . $term['domain'], array(
												'hierarchical' => TRUE,
												'show_ui' => FALSE,
												'query_var' => TRUE,
												'rewrite' => FALSE,
											)
										)
									);
								}
							}
						}
					}
				}
			}
		}
	}

	ob_start();
	$wp_import->import( $upload_dir['basedir'] . '/woocommerce.xml' );
	ob_end_clean();

	// Set WooCommerce Pages
	$shop_page = get_page_by_title( 'Shop' );
	if ( isset( $shop_page->ID ) ) {
		update_option( 'woocommerce_shop_page_id', $shop_page->ID );
	}
	$cart_page = get_page_by_title( 'Cart' );
	if ( isset( $cart_page->ID ) ) {
		update_option( 'woocommerce_cart_page_id', $cart_page->ID );
	}
	$checkout_page = get_page_by_title( 'Checkout' );
	if ( isset( $checkout_page->ID ) ) {
		update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
	}
	$my_account_page = get_page_by_title( 'My Account' );
	if ( isset( $my_account_page->ID ) ) {
		update_option( 'woocommerce_myaccount_page_id', $my_account_page->ID );
	}

	unlink( $upload_dir['basedir'] . '/woocommerce.xml' );

	wp_send_json_success();
}

//Import Options
add_action( 'wp_ajax_us_demo_import_options', 'us_demo_import_options' );
function us_demo_import_options() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	if ( ! file_exists( $us_template_directory . '/demo-import/' . $demo_version . '/theme-options.json' ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Theme Options', 'us' ),
				'error_description' => __( 'Wrong path to the JSON file or file is missing.', 'us' ),
			)
		);
	}
	$updated_options = json_decode( file_get_contents( $us_template_directory . '/demo-import/' . $demo_version . '/theme-options.json' ), TRUE );

	if ( ! is_array( $updated_options ) ) {
		// Wrong file configuration
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Theme Options', 'us' ),
				'error_description' => __( 'Wrong file format of Theme Options data.', 'us' ),
			)
		);
	}

	usof_save_options( $updated_options );

	wp_send_json_success();
}

//Import Slider
add_action( 'wp_ajax_us_demo_import_sliders', 'us_demo_import_sliders' );
function us_demo_import_sliders() {
	global $us_template_directory;
	$config = us_config( 'demo-import', array() );

	//select which files to import
	$aviable_demos = array_keys( $config );
	$demo_version = $aviable_demos[0];
	if ( in_array( $_POST['demo'], $aviable_demos ) ) {
		$demo_version = $_POST['demo'];
	}

	$sliders = glob( $us_template_directory . '/demo-import/' . $demo_version . '/slider-*.zip' );

	if ( ! class_exists( 'RevSlider' ) OR ! ( count( $sliders ) > 0 ) ) {
		wp_send_json(
			array(
				'success' => FALSE,
				'error_title' => __( 'Failed to import Revolution Sliders', 'us' ),
				'error_description' => __( 'Wrong path to the ZIP file or file is missing.', 'us' ),
			)
		);
	}

	ob_start();
	if ( count( $sliders ) > 0 ) {
		foreach ( $sliders as $slider_filename ) {
			$_FILES["import_file"]["tmp_name"] = $slider_filename;
			$slider = new RevSlider();
			$response = $slider->importSliderFromPost();
			unset( $slider );
		}
	}

	ob_end_clean();

	wp_send_json_success();
}
