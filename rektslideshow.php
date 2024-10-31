<?php
/*
Plugin Name: Rekt Slideshow
Plugin URI: http://www.rektproductions.com/rektslideshow
Description: A photo slideshow plugin with options. Set dimensions, fade timing, number of photos and upload photos using Wordpress media uploader, all from the options panel. Insert a clean, customizable slideshow anywhere on your site with a simple shortcode.
Version: 1.0.5
Author: Paul Cushing
Author URI: http://www.rektproductions.com
License: GPL2
*/

/*  Copyright 2011  Paul Cushing  (email : pcushing@rektproductions.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'Rektslide_menu');
add_action('admin_init', 'rektslide_admin_init');
add_action('wp_head', 'rektslideshow_putheader');
add_action('wp_footer', 'rektslideshow_putfooter');

// Setup Defaults ---------

$plugin_dir = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

$options = get_option('rektslideshow_options');

if (!isset($options['numphotos'])) {
	$options['numphotos'] = 4;
}
$num_rekt_photos = $options['numphotos'];

// Build options page ------

function Rektslide_menu() {
	add_options_page('Rekt Slideshow Options', 'Rekt Slideshow', 'manage_options', 'rektslide', 'rektslide_optionspage');
}

function rektslide_optionspage() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} ?>
	<div class="wrap">
	<h2>Rekt Slideshow Settings</h2>
	<form action="options.php" method="post">
	<?php settings_fields('rektslideshow_options'); ?>
	<?php do_settings_sections('rektslide'); ?>
	<br /><br />
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	<br /><br />
	<h3>Instructions</h3><hr>
	<p>This plugin inserts this slideshow when you add the shortcode [rektslideshow] anywhere you want it to appear. For more info or to ask questions, check out <a href="http://www.rektproductions.com/rektslideshow">Rekt Slideshow</a>.</p>	
	</form></div>
<?php }

function rektslide_admin_init(){
	register_setting( 'rektslideshow_options', 'rektslideshow_options', 'rektslideshow_options_validate' );
	
	// Sections
	add_settings_section('rektslideshow_general', 'General Settings', 'rektslideshow_general_text', 'rektslide');
	add_settings_section('rektslideshow_photos', 'Slideshow Photos', 'rektslideshow_photos_text', 'rektslide');	

	// Get number of photos to put into slideshow
	add_settings_field('rektslideshow_num_pictures', 'Number Of Photos', 'rektslideshow_set_numphotos', 'rektslide', 'rektslideshow_general');
	
	// Dimensions
	add_settings_field('rektslideshow_width', 'Width (px)', 'rektslideshow_setting_width', 'rektslide', 'rektslideshow_general');
	add_settings_field('rektslideshow_height', 'Height (px)', 'rektslideshow_setting_height', 'rektslide', 'rektslideshow_general');

	// Sleep and Fade
	add_settings_field('rektslideshow_sleep', 'Sleep Time (seconds)', 'rektslideshow_setting_sleep', 'rektslide', 'rektslideshow_general');
	add_settings_field('rektslideshow_fade', 'Crossfade Time (seconds)', 'rektslideshow_setting_fade', 'rektslide', 'rektslideshow_general');

	// Set add_settings_fields for each photo
	global $num_rekt_photos; 
	for ($i = 1; $i <= $num_rekt_photos; $i++) {
		add_settings_field('rektslideshow_photo'.$i, 'Photo '.$i, 'rektslideshow_set_photo', 'rektslide', 'rektslideshow_photos', $i);
	}
	
}

function rekt_admin_scripts() {
	global $plugin_dir;
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('rekt-upload', $plugin_dir."rektslide.js", array('jquery','media-upload','thickbox'));
	wp_enqueue_script('rekt-upload');
}

function rekt_admin_styles() {
wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'rektslide') {
add_action('admin_print_scripts', 'rekt_admin_scripts');
add_action('admin_print_styles', 'rekt_admin_styles');
}

function rektslideshow_general_text() {
	echo '<p>Basic slideshow settings.</p>';
}

function rektslideshow_set_numphotos() {
	$options = get_option('rektslideshow_options');
	global $num_rekt_photos;
	if (isset($options['numphotos'])) {
		$num_rekt_photos = $options['numphotos'];
	}
	echo "<input id='rektslideshow_num_pictures' name='rektslideshow_options[numphotos]' size='4' type='text' value='{$num_rekt_photos}' />&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"Submit\" type=\"submit\" value=\"Update\" /><br />";
}

function rektslideshow_photos_text() {
	echo '<p>Slideshow photos. (Paste a link to an image or Upload one from your computer.)</p>'; 
}

function rektslideshow_setting_width() {
	$options = get_option('rektslideshow_options');
	echo "<input id='rektslideshow_width' name='rektslideshow_options[width]' size='4' type='text' value='{$options['width']}' />";
}

function rektslideshow_setting_height() {
	$options = get_option('rektslideshow_options');
	echo "<input id='rektslideshow_height' name='rektslideshow_options[height]' size='4' type='text' value='{$options['height']}' />";
}

function rektslideshow_setting_sleep() {
	$options = get_option('rektslideshow_options');
	echo "<input id='rektslideshow_sleep' name='rektslideshow_options[sleep]' size='4' type='text' value='{$options['sleep']}' />";
}

function rektslideshow_setting_fade() {
	$options = get_option('rektslideshow_options');
	echo "<input id='rektslideshow_fade' name='rektslideshow_options[fade]' size='4' type='text' value='{$options['fade']}' />";
}

function rektslideshow_set_photo($photonumber) {
	$options = get_option('rektslideshow_options');
	$photonum = "photo".$photonumber;
	$photoback = $options['photo'.$photonumber];
	echo "<input id='textbox_{$photonum}' name='rektslideshow_options[{$photonum}]' size='80' type='text' value='{$photoback}' />&nbsp;&nbsp;&nbsp;&nbsp;<input id=\"{$photonum}\" class=\"photo_upload_button\" type=\"button\" value=\"Choose Image\" /><br />";
	if(isset($options['photo'.$photonumber]) && $options['photo'.$photonumber] != "") {
		echo "<img id=\"image_{$photonum}\"src=\"{$photoback}\" style=\"width: 100px;\"><br />";
	}
}

// Validate and trim options input
function rektslideshow_options_validate($input) {
	$options = get_option('rektslideshow_options');
	$options['width'] = trim($input['width']);
	$options['height'] = trim($input['height']);
	$options['numphotos'] = trim($input['numphotos']);
	$options['sleep'] = trim($input['sleep']);
	$options['fade'] = trim($input['fade']);
	global $num_rekt_photos;
	for ($i = 1; $i <= $num_rekt_photos; $i++) {
		$options['photo'.$i] = trim($input['photo'.$i]);
	}
	return $options;
}


// --- Header and Footer ---

if( !is_admin()){
   wp_deregister_script('jquery'); 
   wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"), false, '1.4.4'); 
   wp_enqueue_script('jquery');
   wp_deregister_script('rektslide'); 
   wp_register_script('rektslide', ($plugin_dir."jquery.cross-slide.min.js"), false, '0.6.2'); 
   wp_enqueue_script('rektslide');
}

function rektslideshow_putheader() {    
 global $options;
 echo "<style type=\"text/css\">
   #rektslideshow {
	width: {$options['width']}px;
	height: {$options['height']}px;
   } 
 </style>";
}

function rektslideshow_putfooter() {
 global $options;
 global $plugin_dir;
 global $num_rekt_photos;

 echo "<script type=\"text/javascript\">
  $(function() {
   $('#rektslideshow').crossSlide({
   sleep: {$options['sleep']},
   fade: {$options['fade']}
  }, [";
 for ($i = 1; $i <= $num_rekt_photos; $i++) {
	if (isset($options['photo'.$i]) && $options['photo'.$i] != "") {
		$enc_picfile = base64_encode($options['photo'.$i]);
		$encurl_picfile = urlencode($enc_picfile);
		echo "{ src: '{$plugin_dir}picsize.php?src={$encurl_picfile}&h={$options['height']}&w={$options['width']}&zc=1' }";
		if ($i < $num_rekt_photos) {
			echo ",";
		}
	}
 }
  echo "])
  });
 </script>";
}    



// --- Create Shortcode ---

function rektslideshow_output() {
	return "<div id=\"rektslideshow\"></div>"; 
}
add_shortcode( 'rektslideshow', 'rektslideshow_output' );
 
?>
