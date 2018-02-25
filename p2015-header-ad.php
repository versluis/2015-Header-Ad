<?php
/**
 * Plugin Name: 2015 Header Ad
 * Plugin URI: http://wpguru.co.uk
 * Description: inserts a block of ad code into the TwentyFifteen Theme's Header and after Posts
 * Version: 0.1 Beta
 * Author: Jay Versluis
 * Author URI: http://wpguru.co.uk
 * License: GPL2
 * Text Domain: p2015-header-ad
 * Domain Path: /languages
 */
 
/*  Copyright 2016  Jay Versluis (email support@wpguru.co.uk)
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
// Add a new submenu under DASHBOARD
function p2015HeaderAd_menu() {
	
	// using a wrapper function (easy, but not good for adding JS later - hence not used)
	add_theme_page('2015 Header Ad', '2015 Header Ad', 'administrator', 'p2015-header-ad', 'p2015_header_ad_main');
}
add_action('admin_menu', 'p2015HeaderAd_menu');
// add a text domain - http://codex.wordpress.org/I18n_for_WordPress_Developers#I18n_for_theme_and_plugin_developers
function p2015HeaderAd_textdomain()
{
	load_plugin_textdomain('p2015-header-ad', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	// load_plugin_textdomain('domain', false, dirname(plugin_basename(__FILE__)));
}
add_action('plugins_loaded', 'p2015HeaderAd_textdomain');
////////////////////////////////////////////
// here's the code for the actual admin page
function p2015_header_ad_main  () {
	// link some styles to the admin page
	// $p2015headeradstyles = plugins_url ('p2015-header-ad-styles.css', __FILE__);
	// wp_enqueue_style ('p2015headeradstyles', $p2015headeradstyles );
	
	// check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient privileges to access this page. Sorry!') );
    }	
	
	// check if we're actually using TwentyThirteen
	if (!function_exists('twentythirteen_setup')) {
		// p2015_header_ad_warning();
	}
	
	// if we've not used this before, populate the database
	if (get_option ('p2015HeaderCode') == '') {
		p2015_header_ad_sample_data ();
	}
	if (get_option ('p2015HeaderAdDisplayOption') == '') {
	   p2015_header_ad_display_option ();
	   }
	
	/////////////////////////////////////////////////////////////////////////////////////
	// SAVING CHANGES
	/////////////////////////////////////////////////////////////////////////////////////
	
	if (isset($_POST['SaveChanges'])) {
		// save content of text box
		update_option ('p2015HeaderCode', stripslashes ($_POST['p2015HeaderCode']));
		
		// save option to display ad for logged in users
		if (isset($_POST['p2015HeaderAdDisplayOption'])) {
			update_option ('p2015HeaderAdDisplayOption', 'yes');
		} else {
			update_option ('p2015HeaderAdDisplayOption', 'no');
		}
		
		// save option for ad after content
		if (isset($_POST['p2015HeaderShowAfterContent'])) {
			update_option ('p2015HeaderShowAfterContent', 'yes');
		} else {
			update_option ('p2015HeaderShowAfterContent', 'no');
		}
		
		// save option for ad on front page
		if (isset($_POST['p2015HeaderShowOnFrontPage'])) {
			update_option ('p2015HeaderShowOnFrontPage', 'yes');
		} else {
			update_option ('p2015HeaderShowOnFrontPage', 'no');
		}
		
		// save option for ad on home page
		if (isset($_POST['p2015HeaderShowOnHomePage'])) {
			update_option ('p2015HeaderShowOnHomePage', 'yes');
		} else {
			update_option ('p2015HeaderShowOnHomePage', 'no');
		}
		
		// save priority for ad after content
		if (isset($_POST['p2015HeaderPriority'])) {
			update_option ('p2015HeaderPriority', 'yes');
		} else {
			update_option ('p2015HeaderPriority', '10');
		}
		
		// display settings saved message
		p2015_header_ad_settings_saved();
	}
	
	if (isset ($_POST['SampleData'])) {
		// populate with sample data
		p2015_header_ad_sample_data ();
		
		// display settings saved message
		p2015_header_ad_settings_saved();
	}
	
	
	//////////////////////////////////
	// READ IN DATABASE OPTION
	//////////////////////////////////
	
	$p2015HeaderCode = get_option ('p2015HeaderCode');
	$p2015HeaderAdDisplayOption = get_option ('p2015HeaderAdDisplayOption');
	$p2015HeaderShowAfterContent = get_option('p2015HeaderShowAfterContent');
	$p2015HeaderShowOnFrontPage = get_option('p2015HeaderShowOnFrontPage');
	$p2015HeaderPriority = get_option('p2015HeaderPriority');
	$p2015HeaderShowOnHomePage = get_option ('p2015HeaderShowOnHomePage');
	
	///////////////////////////////////////
	// MAIN AMDIN CONTENT SECTION
	///////////////////////////////////////
	
	
	// display heading with icon WP style
	?>
    <form name="p2015HeaderAdForm" method="post" action="">
    <div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2><?php _e('2015 Header Advertising', 'p2015-header-ad'); ?></h2>
    
    <p><strong><?php _e('Enter some HTML in the box, and it will be displayed above the TwentyThirteen header.', 'p2015-header-ad'); ?> </strong></p>
    <p><em><?php _e('Works best when used with Google Adsense Responsive Ads.', 'p2015-header-ad'); ?></em></p>
    
    <pre>
    <textarea name="p2015HeaderCode" cols="80" rows="10" class="p2015CodeBox"><?php echo trim($p2015HeaderCode); ?></textarea></pre>
    
    <?php ////////////////////////////// ?>
	<?php ////////////////////////////// ?>

    <h2>General Options</h2>
    
    <?php
    // option to display ad for logged in users
    // @since 1.0
    ?>
    <p><strong><?php _e('Display ads for users who are logged in?', 'p2015-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2015HeaderAdDisplayOption; ?>" name="p2015HeaderAdDisplayOption" <?php if ($p2015HeaderAdDisplayOption == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Untick the box to show ads only to visitors.', 'p2015-header-ad'); ?></em></p>

     <?php 
    // option to display ads after content
    // @since 1.0
    ?>
    <p><strong><?php _e('Display ads after the post content?', 'p2015-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2015HeaderShowAfterContent; ?>" name="p2015HeaderShowAfterContent" <?php if ($p2015HeaderShowAfterContent == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Untick the box to suppress ads at the end of a post.', 'p2015-header-ad'); ?></em></p>
    <br>
    
    <?php ////////////////////////////// ?>
	<?php ////////////////////////////// ?>
    
    <h2>Front Page Options</h2>
    
    <?php 
	// option to suppress ads on the front page
	// @since 1.1
	?>
	<p><strong><?php _e('Disable header ad on the front page?', 'p2015-header-ad'); ?></strong>&nbsp;
    <input type="checkbox" value="<?php $p2015HeaderShowOnHomePage; ?>" name="p2015HeaderShowOnHomePage" <?php if ($p2015HeaderShowOnHomePage == 'yes') echo 'checked'; ?>/>
    </p>
    
    <p><em><?php _e('When ticked, the header ad will be shown on single posts only.', 'p2015-header-ad'); ?></em></p>
	
    
    <?php 
    // display ads after content on front page
    // @since 1.0
    ?>
    <p><strong><?php _e('Show after-content-ads on the front page?', 'p2015-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2015HeaderShowOnFrontPage; ?>" name="p2015HeaderShowOnFrontPage" <?php if ($p2015HeaderShowOnFrontPage == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Works best with longer posts, but looks cluttered with short posts and status updates.', 'p2015-header-ad'); ?></em></p>
    
    <br>
    <p class="save-button-wrap">
    <input type="submit" name="SaveChanges" class="button-primary" value="<?php _e('Save Changes', 'p2015-header-ad'); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="SampleData" class="button-secondary" value="<?php _e('Use Sample Data', 'p2015-header-ad'); ?>" />
    
    </form>
    <p>&nbsp;</p>
<h2><?php _e('Check it out', 'p2015-header-ad'); ?></h2>
<p><?php _e('This is what your advert will look like:', 'p2015-header-ad'); ?></p>
    <p>
  <?php	
	
	///////////////////
	// DISPLAY PREVIEW
	//////////////////
	
	echo get_option ('p2015HeaderCode');
	////////////////////////////////////////////////////////
	// ADMIN FOOTER CONTENT
	////////////////////////////////////////////////////////
?>
    <br><br>
    <hr width="90%">
    <br>    
    <p><em><?php _e('This plugin was brought to you by', 'p2015-header-ad'); ?></em><br />
    <a href="http://wpguru.co.uk" target="_blank"><img src="
    <?php 
    echo plugins_url('images/guru-header-2015.png', __FILE__);
    ?>" width="300"></a>
    </p5
    ><p><a href="http://wpguru.co.uk/" target="_blank">Plugin by Jay Versluis</a> | <a href="https://github.com/versluis/2015-Header-Ad" target="_blank">Fork me on GitHub</a> | <a href="http://wphosting.tv" target="_blank">WP Hosting</a></p>
	<?php
} // end of main function
// populate database with sample code
function p2015_header_ad_sample_data () {
	update_option ('p2015HeaderCode', '<a href="http://wordpress.org" target="_blank"><img style="border:0px" src="' . plugins_url('images/Header-Advert.png', __FILE__) . '" width="468" height="60" alt=""></a>');
}
// populate database with default value for 'display to logged in users'
function p2015_header_ad_display_option () {
    update_option ('p2015HeaderAdDisplayOption', 'yes');
}
// Put a "settings updated" message on the screen 
function p2015_header_ad_settings_saved () {
	?>
    <div class="updated">
    <p><strong><?php _e('Your settings have been saved.', 'p2015-header-ad'); ?></strong></p>
    </div>
	<?php
} // end of settings saved
// Put a warning message on the screen 
function p2015_header_ad_warning () {
	?>
    <div class="error">
    <p><strong><?php _e('You are not using the TwentyThirteen Theme.', 'p2015-header-ad'); ?><br>
    <?php _e('Please activate it first, otherwise results are unpredictable!', 'p2015-header-ad'); ?><br><br>
    
	<?php _e ('You can <a href="https://wordpress.org/themes/twentythirteen/" target="_blank">download TwentyThirteen here</a>. Or if you have already installed it,', 'p2015-header-ad'); ?> <a href="<?php echo admin_url( 'themes.php'); ?>"><?php _e('activate it here', 'p2015-header-ad'); ?></a>.</strong></p>
    </div>
	<?php
} // end of settings saved
// display the advert
function p2015DisplayAdvert () {
	
	// get our scripts ready
	wp_enqueue_script ('jquery');
	$p2015HeaderScript = plugins_url ('p2015-header-ad-script.js', __FILE__);
	wp_enqueue_script ('p2015-header-ad-script', $p2015HeaderScript, '', '', true);
	
	$p2015HeaderCode = get_option ('p2015HeaderCode');
	$p2015HeaderLoggedIn = get_option ('p2015HeaderAdDisplayOption');
	
	// use different top style depending on custom header
	if (get_header_image() == '') {
		// if no header image is present
		// $p2015HeaderCode = '<div id="p2015HeaderAd" style="top: 45px">' . $p2015HeaderCode . '</div>';
	} else {
		// if we have a header image
		// $p2015HeaderCode = '<div id="p2015HeaderAd" style="top: 30px">' . $p2015HeaderCode . '</div>';
	}
	
	// don't display if we're in the admin interface
	// since @1.0
	if (is_admin()) {
		$p2015HeaderCode = '';
	}
	
	// show ads to logged in users?
	// since @1.0
	if (is_user_logged_in () && $p2015HeaderLoggedIn == 'no') {
		$p2015HeaderCode = '';
	}
	
	// don't display code for logged in eMember users
	// since @1.0
	if (function_exists('wp_emember_is_member_logged_in')) {
		if (wp_emember_is_member_logged_in() && $p2015HeaderLoggedIn == 'no') {
			$p2015HeaderCode = '';
		}
	}
	
	// do we want the ad on the front page?
	// since @1.1
	if (is_front_page() && get_option('p2015HeaderShowOnHomePage') != 'no') {
		$p2015HeaderCode = '';
	}
	
	// check if we're actually using TwentyThirteen, then display the code
	/*
	if (function_exists('twentythirteen_setup')) {
		echo $p2015HeaderCode;
	}
	*/
}
add_action ('get_header', 'p2015DisplayAdvert');
// adds the same advert underneath a single post
// @since 1.0
function p2015Header_ads_after_posts($content) {
	
	// we can either return $content (no advert) or $ad_content (with advert)
	$ad_content = $content . '<br><br>' . get_option('p2015HeaderCode') . '<br><br>';
	
	// do we want this option?
	if (!get_option('p2015HeaderShowAfterContent') || get_option('p2015HeaderShowAfterContent') == 'no') {
		return $content;
	}
	
	// when user is logged in, do not display the ad
	if (is_user_logged_in () && get_option('p2015HeaderAdDisplayOption') == 'no') {
		return $content;
	}
	
	// the same goes for eMeber users
	if (function_exists('wp_emember_is_member_logged_in')) {
		if (wp_emember_is_member_logged_in() && get_option('p2015HeaderAdDisplayOption') == 'no') {
			return $content;
		} 
	}
	
	// do we want ads on the front page?
	if (is_home() && get_option('p2015HeaderShowOnFrontPage') == 'yes') {
		return $ad_content;
	} 
	
	// show ad after content?
	if (get_option('p2015HeaderShowAfterContent') == 'yes' && !is_home() && !is_page()) {
		return $ad_content;
	}
	
	// DEFAULT:
	// none of the above were true - just return the content
	return $content;
}
// add filter to the_content
add_filter ('the_content', 'p2015Header_ads_after_posts', 10);
// link some styles to the admin page
// added hook since @1.6
function p2015HeaderEnqueueStyles () {
	$p2015headeradstyles = plugins_url ('p2015-header-ad-styles.css', __FILE__);
	wp_enqueue_style ('p2015headeradstyles', $p2015headeradstyles );
}
add_action('wp_enqueue_scripts', 'p2015HeaderEnqueueStyles');
?>