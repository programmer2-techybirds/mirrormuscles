<?php
/*
Plugin Name: Tooltips
Plugin URI:  http://tomas.zhu.bz/wordpress-plugin-tooltips.html
Description: Wordpress Tooltips,You can add text,image,link,video,radio in tooltips, add tooltips in gallery. More amazing features? Do you want to customize a beautiful style for your tooltips? One Minute, Check <a href='https://tooltips.org/features-of-wordpress-tooltips-plugin/' target='_blank'> Features of WordPress Tooltips Pro</a>.
Version: 4.5.1
Author: Tomas Zhu | <a href='https://tooltips.org/category/blog/' target='_blank'>Docs</a> | <a href='https://tooltips.org/faq/' target='_blank'>FAQ</a> | <a href='https://tooltips.org/tooltips-support-ticket-controlpanel/' target='_blank'>Premium Support</a> 


Author URI: https://tooltips.org/category/wordpress-tooltips-demo/
Text Domain: wordpress-tooltips
License: GPLv3 or later
*/
/*  Copyright 2011 - 2017 Tomas Zhu
 This program comes with ABSOLUTELY NO WARRANTY;
 https://www.gnu.org/licenses/gpl-3.0.html
 https://www.gnu.org/licenses/quick-guide-gplv3.html
 */

//error_reporting(0); //version 3.6.1 follow users' request to remove this
if (!defined('ABSPATH'))
{
	exit;
}

add_action( 'wp_enqueue_scripts', 'tooltips_loader_scripts' );
function tooltips_loader_scripts()
{
	wp_register_style( 'qtip2css', plugin_dir_url( __FILE__ ).'js/qtip2/jquery.qtip.min.css');
	wp_enqueue_style( 'qtip2css' );
	
	wp_register_style( 'directorycss', plugin_dir_url( __FILE__ ).'js/jdirectory/directory.min.css');
	wp_enqueue_style( 'directorycss' );
	
	wp_register_script( 'qtip2js', plugin_dir_url( __FILE__ ).'js/qtip2/jquery.qtip.min.js', array('jquery'));
	wp_enqueue_script( 'qtip2js' );
	
	wp_register_script( 'directoryjs', plugin_dir_url( __FILE__ ).'js/jdirectory/jquery.directory.min.js', array('jquery'));
	wp_enqueue_script( 'directoryjs' );	
	

}

require_once("tooltipsfunctions.php");
function tooltipsHead()
{
	$m_pluginURL = get_option('siteurl').'/wp-content/plugins';
?>
 	<script type="text/javascript">	
	if(typeof jQuery=='undefined')
	{
		document.write('<'+'script src="<?php echo $m_pluginURL; ?>/<?php echo  '/wordpress-tooltips'; ?>/js/qtip/jquery.js" type="text/javascript"></'+'script>');
	}
	</script>
	<script type="text/javascript">

	function toolTips(whichID,theTipContent)
	{
			jQuery(whichID).qtip
			(
				{
					content:theTipContent,
   					style:
   					{
   						classes:' qtip-dark wordpress-tooltip-free qtip-rounded qtip-shadow'
    				},
    				position:
    				{
    					viewport: jQuery(window),
    					my: 'bottom center',
    					at: 'top center'
    				},
					show:'mouseover',
					hide: { fixed: true, delay: 200 }
				}
			)
	}
</script>
	
<?php
}

function tooltipsMenu()
{
	add_menu_page(__('Tooltips','wordpress-tooltips'), __('Tooltips','wordpress-tooltips'), 'manage_options', 'tooltipsfunctions.php','editTooltips');
	add_submenu_page('tooltipsfunctions.php',__('Edit Tooltips','wordpress-tooltips'), __('Edit Tooltips','wordpress-tooltips'),'manage_options', 'tooltipsfunctions.php','editTooltips');
}

add_action('admin_menu', 'tooltips_menu');

function tooltips_menu() {
	add_submenu_page('edit.php?post_type=tooltips',__('Global Settings','wordpress-tooltips'), __('Global Settings','wordpress-tooltips'),'manage_options', 'tooltipglobalsettings','tooltipGlobalSettings');
}

function showTooltips($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltips', $content);
	remove_filter('the_title', 'wptexturize');	  // version 3.5.1
	$content = apply_filters( 'filter_before_showtooltips',  $content);
	
	$curent_post = get_post($post);
	
	$curent_content = $curent_post->post_content;

	
	$m_result = tooltips_get_option('tooltipsarray');
	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			$tooltip_post_id = $m_single['post_id'];
			$get_post_meta_value_for_this_page = get_post_meta($tooltip_post_id, 'toolstipssynonyms', true);
			$get_post_meta_value_for_this_page = trim($get_post_meta_value_for_this_page);
			
			$tooltsip_synonyms = false;
			if (!(empty($get_post_meta_value_for_this_page)))
			{
				$tooltsip_synonyms = explode('|', $get_post_meta_value_for_this_page);
			}
				
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
					
			}
			else
			{
				$tooltsip_synonyms = array();
				$tooltsip_synonyms[] = $m_single['keyword'];
					
			}
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
				$tooltsip_synonyms[] = $m_single['keyword'];
				$tooltsip_synonyms = array_unique($tooltsip_synonyms);
			
				foreach ($tooltsip_synonyms as $tooltsip_synonyms_single)
				{
					$m_keyword = $tooltsip_synonyms_single;
						
						
					if (stripos($curent_content,$m_keyword) === false)
					{
							
					}
					else
					{
						$m_keyword_result .= '<script type="text/javascript">';
						$m_content = $m_single['content'];
						$m_content = str_ireplace('\\','',$m_content);
						$m_content = str_ireplace("'","\'",$m_content);
						$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
						if (!(empty($m_content)))
						{
							$m_keyword_result .= " toolTips('.classtoolTips$m_keyword_id','$m_content'); ";
						}
						$m_keyword_result .= '</script>';
					}
				}
			}
			
			$m_keyword_id++;
		}
	}
	
	$content = $content.$m_keyword_result;
	do_action('action_after_showtooltips', $content);
	$content = apply_filters( 'filter_after_showtooltips',  $content);
	add_filter('the_title', 'wptexturize'); // version 3.5.1
	return $content;
}

function showTooltipsInTag($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltipsintag', $content);
	$content = apply_filters( 'filter_before_showtooltipsintag',  $content);
	
	$curent_content = $content;

	
	$m_result = tooltips_get_option('tooltipsarray');
	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			if (stripos($curent_content,$m_single['keyword']) === false)
			{
				
			}
			else 
			{			
				$m_keyword_result .= '<script type="text/javascript">';
				$m_content = $m_single['content'];
				$m_content = str_ireplace('\\','',$m_content);
				$m_content = str_ireplace("'","\'",$m_content);
				$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
				if (!(empty($m_content)))
				{
					$m_keyword_result .= " toolTips('.classtoolTips$m_keyword_id','$m_content'); ";
				}
				$m_keyword_result .= '</script>';
			}
			$m_keyword_id++;
		}
	}
	$content = $content.$m_keyword_result;

	do_action('action_after_showtooltipsintag', $content);
	$content = apply_filters( 'filter_after_showtooltipsintag',  $content);

	return $content;
}


function tooltipsInContent($content)
{
	global $table_prefix,$wpdb,$post;
	
	do_action('action_before_tooltipsincontent', $content);
	$content = apply_filters( 'filter_before_tooltipsincontent',  $content);

	$disableInHomePage = get_option("disableInHomePage");
	
	if ($disableInHomePage == 'NO')
	{
		if (is_home())
		{
			return $content;
		}		
	}
	
	$showOnlyInSingleCategory = get_option("showOnlyInSingleCategory");
	
	if ($showOnlyInSingleCategory != 0)
	{
		
		$post_cats = wp_get_post_categories($post->ID);
		if (in_array($showOnlyInSingleCategory,$post_cats))
		{
			
		}
		else 
		{
			return $content;
		}
	}	
	
	$onlyFirstKeyword = get_option("onlyFirstKeyword");
	if 	($onlyFirstKeyword == false)
	{
		$onlyFirstKeyword = 'all';
	}

	$m_result = tooltips_get_option('tooltipsarray');
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
		
			$m_keyword = $m_single['keyword'];
			$m_content = $m_single['content'];

			$tooltip_post_id = $m_single['post_id'];

			$get_post_meta_value_for_this_page = get_post_meta($tooltip_post_id, 'toolstipssynonyms', true);
			$get_post_meta_value_for_this_page = trim($get_post_meta_value_for_this_page);
				
			$tooltsip_synonyms = false;
			if (!(empty($get_post_meta_value_for_this_page)))
			{
				$tooltsip_synonyms = explode('|', $get_post_meta_value_for_this_page);
			}
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
			
			}
			else
			{
				$tooltsip_synonyms = array();
				$tooltsip_synonyms[] = $m_keyword;
			
			}
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
				$tooltsip_synonyms[] = $m_keyword;
				$tooltsip_synonyms = array_unique($tooltsip_synonyms);
				
				foreach ($tooltsip_synonyms as $tooltsip_synonyms_single)
				{
					$m_keyword = $tooltsip_synonyms_single;
					$m_replace = "<span class='classtoolTips$m_keyword_id' style='border-bottom:2px dotted #888;'>$m_keyword</span>";
					
					if (stripos($content,$m_keyword) === false)
					{
					
					}
					else
					{
						if ($onlyFirstKeyword == 'all')
						{
							$content = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class='classtoolTips$m_keyword_id' style='border-bottom:2px dotted #888;'>"."\\2"."</span>"."\\3",$content);
						}
							
						if ($onlyFirstKeyword == 'first')
						{
							$content = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class='classtoolTips$m_keyword_id' style='border-bottom:2px dotted #888;'>"."\\2"."</span>"."\\3",$content,1);
						}
					}
				}
			}
							
			$m_keyword_id++;
		}
	}
	
	do_action('action_after_tooltipsincontent', $content);
	$content = apply_filters( 'filter_after_tooltipsincontent',  $content);
		
	return $content;
}

function nextgenTooltips()
{
?>
<script type="text/javascript">
	jQuery("img").load(function()
	{
		if ((jQuery(this).parent("a").attr('title') != '' )  && (jQuery(this).parent("a").attr('title') != undefined ))
		{
			toolTips(jQuery(this).parent("a"),jQuery(this).parent("a").attr('title'));
		}
		else
		{
			var tempAlt = jQuery(this).attr('alt');
			if (typeof(tempAlt) !== "undefined")
			{
				tempAlt = tempAlt.replace(' ', '');
				if (tempAlt == '')
				{
				
				}
				else
				{
					toolTips(jQuery(this),jQuery(this).attr('alt'));
				}
			}
		}
	}

	);
</script>
<?php
}

function tooltipsAdminHead()
{
?>	
<style type="text/css">
span.question, span.questionimage, span.questionexcerpt, span.questiontags, span.questionsinglecat {
  cursor: pointer;
  display: inline-block;
  line-height: 14px;
  width: 14px;
  height: 14px;
  border-radius: 7px;
  -webkit-border-radius:7px;
  -moz-border-radius:7px;
  background: #5893ae;
  color: #fff;
  text-align: center;
  position: relative;
  font-size: 10px;
  font-weight: bold;
}
span.question:hover { background-color: #21759b; }
span.questionimage:hover { background-color: #21759b; }
span.questiontags:hover { background-color: #21759b; }
span.questionsinglecat:hover { background-color: #21759b; }


div.tooltip {
  text-align: left;
  left: 25px;
  background: #21759b;
  color: #fff;
  position: absolute;
  z-index: 1000000;
  width: 400px;
  border-radius: 5px;
  -webkit-border-radius:5px;
  -moz-border-radius:5px;
top: -80px;
}

div.tooltip1 {
  text-align: left;
  left: 25px;
  background: #21759b;
  color: #fff;
  position: absolute;
  z-index: 1000000;
  width: 400px;
  border-radius: 5px;
  -webkit-border-radius:5px;
  -moz-border-radius:5px;
top: -50px;
}
div.tooltip3,div.tooltip24 {
  text-align: left;
  left: 25px;
  background: #21759b;
  color: #fff;
  position: absolute;
  z-index: 1000000;
  width: 400px;
  border-radius: 5px;
  -webkit-border-radius:5px;
  -moz-border-radius:5px;
top: -60px;
}
div.tooltip:before, .tooltip1:before, .tooltip3:before, .tooltip24:before {
  border-color: transparent #21759b transparent transparent;
  border-right: 6px solid #21759b;
  border-style: solid;
  border-width: 6px 6px 6px 0px;
  content: "";
  display: block;
  height: 0;
  width: 0;
  line-height: 0;
  position: absolute;
  top: 40%;
  left: -6px;
}
div.tooltip p, .tooltip1 p, .tooltip3 p, .tooltip24 p {
  margin: 10px;
 line-height:13px;
 font-size:11px;
 color:#eee; 
}
</style>										
<?php
}										
add_action('the_content','tooltipsInContent');
add_action('wp_head', 'tooltipsHead');
add_action('the_content','showTooltips');
add_action('admin_head', 'tooltipsAdminHead');

$enableTooltipsForExcerpt = get_option("enableTooltipsForExcerpt");
if ($enableTooltipsForExcerpt =='YES')
{
	add_action('the_excerpt','tooltipsInContent');
	add_action('the_excerpt','showTooltips');	
}

$enableTooltipsForTags = get_option("enableTooltipsForTags");
if ($enableTooltipsForTags =='YES')
{
	add_action('the_tags','tooltipsInContent');
	add_action('the_tags','showTooltipsInTag');
}

$enableTooltipsForImageCheck = get_option("enableTooltipsForImage");
if ($enableTooltipsForImageCheck == false)
{
	update_option("enableTooltipsForImage", "YES");
}
if ($enableTooltipsForImageCheck == 'YES')
{
	add_action('wp_footer','nextgenTooltips');
}


function add_tooltips_post_type() {
	$catlabels = array(
			'name'                          => 'Categories',
			'singular_name'                 => 'Tooltips Categories',
			'all_items'                     => 'All Tooltips',
			'parent_item'                   => 'Parent Tooltips',
			'edit_item'                     => 'Edit Tooltips',
			'update_item'                   => 'Update Tooltips',
			'add_new_item'                  => 'Add New Tooltips',
			'new_item_name'                 => 'New Tooltips',
	);
	
	$args = array(
			'label'                         => 'Categories',
			'labels'                        => $catlabels,
			'public'                        => true,
			'hierarchical'                  => true,
			'show_ui'                       => true,
			'show_in_nav_menus'             => true,
			'args'                          => array( 'orderby' => 'term_order' ),
			'rewrite'                       => array( 'slug' => 'tooltips_categories', 'with_front' => false ),
			'query_var'                     => true
	);
	
	register_taxonomy( 'tooltips_categories', 'tooltips', $args );
	
	
  $labels = array(
    'name' => __('Tooltips', 'wordpress-tooltips'),
    'singular_name' => __('Tooltip', 'wordpress-tooltips'),
    'add_new' => __('Add New', 'wordpress-tooltips'),
    'add_new_item' => __('Add New Tooltip', 'wordpress-tooltips'),
    'edit_item' => __('Edit Tooltip', 'wordpress-tooltips'),
    'new_item' => __('New Tooltip', 'wordpress-tooltips'),
    'all_items' => __('All Tooltips', 'wordpress-tooltips'),
    'view_item' => __('View Tooltip', 'wordpress-tooltips'),
    'search_items' => __('Search Tooltip', 'wordpress-tooltips'),
    'not_found' =>  __('No Tooltip found', 'wordpress-tooltips'),
    'not_found_in_trash' => __('No Tooltip found in Trash', 'wordpress-tooltips'), 
    'menu_name' => __('Tooltips', 'wordpress-tooltips')
  );
  
  $args = array(
    'labels' => $labels,
    'public' => false,
    'show_ui' => true, 
    'show_in_menu' => true, 
    '_builtin' =>  false,
    'query_var' => "tooltips",
    'rewrite' => false,
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor','author','custom-fields','thumbnail' )
  ); 
  register_post_type('tooltips', $args);
}
add_action( 'init', 'add_tooltips_post_type' );

function upgrade_check()
{
	$currentVersion = get_option('ztooltipversion');

	if (empty($currentVersion))
	{
		$m_result = get_option('tooltipsarray');
		if (!(empty($m_result)))
		{
			$m_keyword_id = 0;
			foreach ($m_result as $m_single)
			{
				$m_keyword = $m_single['keyword'];
				$m_content = $m_single['content'];				
				$my_post = array(
  				//'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
  				'post_title'    => $m_keyword,
  				'post_content'  => $m_content,
  				'post_status'   => 'publish',
  				'post_type'   => 'tooltips',
  				'post_author'   => 1,
				);
				wp_insert_post( $my_post );
			}
		}
	
	}
	update_option('ztooltipversion','4.5.1');
}
add_action( 'init', 'upgrade_check');

function tooltips_get_option($type)
{
	global $wpdb;
	$tooltipsarray = array();
	$m_single = array();
	if ($type == 'tooltipsarray')
	{
		$post_type = 'tooltips';
		$sql = $wpdb->prepare( "SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type=%s AND post_status='publish'",$post_type);
		$results = $wpdb->get_results( $sql );
	
		if ((!(empty($results))) && (is_array($results)) && (count($results) >0))
		{
			$m_single = array();
			foreach ($results as $single)
			{
				$m_single['keyword'] = $single->post_title;
				$m_single['content'] = $single->post_content;
				$m_single['post_id'] = $single->ID;
				$tooltipsarray[] = $m_single;
			}
		}
	}
	return $tooltipsarray;	
}

$enableTooltipsForImageCheck = get_option("enableTooltipsForImage");
if ($enableTooltipsForImageCheck == false)
{
	update_option("enableTooltipsForImage", "YES");
}

function showTooltipsInShorcode($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltips', $content);
	$content = apply_filters( 'filter_before_showtooltips',  $content);
	

	$curent_content = $content;

	
	$m_result = tooltips_get_option('tooltipsarray');
	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			
			if (stripos($curent_content,$m_single['keyword']) === false)
			{
				
			}
			else 
			{			
				$m_keyword_result .= '<script type="text/javascript">';
				$m_content = $m_single['content'];
				$m_content = str_ireplace('\\','',$m_content);
				$m_content = str_ireplace("'","\'",$m_content);
				$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
				if (!(empty($m_content)))
				{
					$m_keyword_result .= " toolTips('.classtoolTips$m_keyword_id','$m_content'); ";
				}
				$m_keyword_result .= '</script>';
			}
			$m_keyword_id++;
		}
	}
	$content = $content.$m_keyword_result;
	do_action('action_after_showtooltips', $content);
	$content = apply_filters( 'filter_after_showtooltips',  $content);
	return $content;
}

function tooltips_list_shortcode($atts)
{
	global $table_prefix,$wpdb,$post;


	$args = array( 'post_type' => 'tooltips', 'post_status' => 'publish' );
	$loop = new WP_Query( $args );
	$return_content = '';
	$return_content .= '<div class="tooltips_directory">';
	while ( $loop->have_posts() ) : $loop->the_post();
		$return_content .= '<div class="tooltips_list">'.get_the_title().'</div>';
	endwhile;
	$return_content = tooltipsInContent($return_content);
	$return_content = showTooltipsInShorcode($return_content);

	$return_content .= '</div>';
	
	return $return_content;
}

add_shortcode( 'tooltipslist', 'tooltips_list_shortcode' );

function tooltips_wiki_reference($atts, $keyword = null)
{
	extract(shortcode_atts( array(
	'content' => "Proper Shortcode Usage is: <div>[tooltips keyword='wordpress' content = 'hello, wp']</div>",
	), $atts ));

	$m_keyword_result = '';
	$keywordmd = md5(uniqid('',TRUE));
	$m_replace = "<span class='tooltipsall tooltip_post_id_custom_$keywordmd classtoolTipsCustomShortCode' style='border-bottom:2px dotted #888;'>".$keyword."</span>";
	$m_keyword_result .= $m_replace;

	$m_keyword_result .= '<script type="text/javascript">';
	$m_content = $content;

	$m_content = str_ireplace('\\','',$m_content);
	$m_content = str_ireplace("'","\'",$m_content);
	$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
	if (!(empty($m_content)))
	{
		$m_keyword_result .= " toolTips('.tooltip_post_id_custom_$keywordmd','$m_content'); ";
	}
	$m_keyword_result .= '</script>';

	return $m_keyword_result;
}

add_shortcode( 'tooltips_wiki_reference', 'tooltips_wiki_reference' );

add_shortcode( 'ttsref', 'tooltips_wiki_reference' );


function tomas_one_tooltip_shortcode( $atts, $content = null )
{
	extract(shortcode_atts( array(
	'keyword' => "Proper Shortcode Usage is: <div>[tooltips keyword='wordpress' content = 'hello, wp']</div>",
	'content' => "Proper Shortcode Usage is: <div>[tooltips keyword='wordpress' content = 'hello, wp']</div>",
	), $atts ));

	$m_keyword_result = '';
	$keywordmd = md5(uniqid('',TRUE));
	$m_replace = "<span class='tooltipsall tooltip_post_id_custom_$keywordmd classtoolTipsCustomShortCode' style='border-bottom:2px dotted #888;'>$keyword</span>";
	$m_keyword_result .= $m_replace;

	$m_keyword_result .= '<script type="text/javascript">';
	$m_content = $content;

	$m_content = str_ireplace('\\','',$m_content);
	$m_content = str_ireplace("'","\'",$m_content);
	$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
	if (!(empty($m_content)))
	{
		$m_keyword_result .= " toolTips('.tooltip_post_id_custom_$keywordmd','$m_content'); ";
	}
	$m_keyword_result .= '</script>';

	return $m_keyword_result;
}

add_shortcode('tooltips', 'tomas_one_tooltip_shortcode');


add_action('widgets_init', 'TooltipsWidgetInit');


/**** localization ****/
add_action('plugins_loaded','tooltips_load_textdomain');

function tooltips_load_textdomain()
{
	load_plugin_textdomain('wordpress-tooltips', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

function tooltips_plugin_action_links( $links, $file ) 
{
	if ( $file == plugin_basename( __FILE__ ))
	{
		
		 $settings_link = '<i><a href="https://tooltips.org/features-of-wordpress-tooltips-plugin/" target="_blank">'.esc_html__( 'DEMOs' , 'wordpress-tooltips').'</a></i>';
		 array_unshift($links, $settings_link);
		 		
		 $settings_link = '<a href="' . admin_url( 'edit.php?post_type=tooltips' ) . '">'.esc_html__( 'Settings' , 'wordpress-tooltips').'</a>';
		 array_unshift($links, $settings_link);
	}

	return $links;
}

add_filter( 'plugin_action_links', 'tooltips_plugin_action_links', 10, 2 );


function tooltips_pro_meta_box_control_meta_box()
{
	?>
<div class="inside" style=''>
<ul>
<li>
* Customization tooltips color, position, opacity, width, show method and more
</li>
<li>
* Tooltip in menu, posts title, tags...
</li>
<li>
* Display tooltips sitewide manually
<br /><i><font color="gray">Tooltips in WooCommerce Product, in Table Cell, on Button, in Pricing Table...</font></i> 
</li>										
<li>
* Add / Limit tooltips for any custom post types
</li>
<li>
* Hit stats of each Toolipts
</li>
<li>
* Disable tooltip in specified post
</li>										
<li>
* $9 only <a class="" target="_blank" href="https://tooltips.org/features-of-wordpress-tooltips-plugin/">More Featuers, Lifetime Upgrades, Unlimited Download, Ticket Support</a>
</li>
</ul>
</div>
<?php
}

function tooltips_pro_meta_box()
{
	if (isset($_GET['post_type']))
	{
		add_meta_box("tooltips_pro_meta_box", __( 'Features & Demos of Tooltips Pro', 'wordpress-tooltips' ), 'tooltips_pro_meta_box_control_meta_box', null, "side", "high", null);
	}
}

add_action( 'add_meta_boxes',  'tooltips_pro_meta_box' );


function content_tooltips_keyword_synonyms_control_meta_box()
{
	global $post;
	$current_page_id = get_the_ID();
	$get_post_meta_value_for_this_page = get_post_meta($current_page_id, 'toolstipssynonyms', true);
	global $wpdb;

	?>
	<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
	    <tbody>
	    <tr class="form-field">
	        <td>
	        	<p>Synonyms of the keyword</p>
				<input type="text" id="toolstipssynonyms" name="toolstipssynonyms" value="<?php echo $get_post_meta_value_for_this_page;  ?>">
				<p style="color:gray;font-size:12px;"><i>( separated by '|' )</i></p>
	        </td>
	    </tr>
	    </tbody>
	</table>
	<?php

}


function tooltips_keyword_synonyms_control_meta_box()
{
	global $post;

	if ($post->post_type == 'tooltips')
	{
		add_meta_box("tooltips_keyword_synonyms_control_meta_box_id", __( 'Synonyms of this tooltip', 'wordpress-tooltips' ), 'content_tooltips_keyword_synonyms_control_meta_box', null, "side", "high", null);
	}

}

function save_content_tooltips_keyword_synonyms_control_meta_box($post_id, $post, $update)
{
	global $post;

	$current_page_id = get_the_ID();

	$get_post_meta_value_for_this_page = get_post_meta($current_page_id, 'toolstipssynonyms', true);

	if(isset($_POST['toolstipssynonyms']) != "") {
		$meta_box_checkbox_value = $_POST['toolstipssynonyms'];
		update_post_meta( $current_page_id, 'toolstipssynonyms', $meta_box_checkbox_value );
	} else {
		update_post_meta( $current_page_id, 'toolstipssynonyms', '' );
	}
}

add_action( 'add_meta_boxes',  'tooltips_keyword_synonyms_control_meta_box' );
add_action( 'save_post', 'save_content_tooltips_keyword_synonyms_control_meta_box' , 10, 3);

function footernav()
{
?>
<script type="text/javascript">
jQuery('.tooltips_directory').directory();
</script>
<?php
}
add_action('wp_footer','footernav');
?>