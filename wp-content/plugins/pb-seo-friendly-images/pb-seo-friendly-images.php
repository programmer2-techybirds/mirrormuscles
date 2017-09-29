<?php
/*
Plugin Name: PB SEO Friendly Images
Plugin URI: https://wordpress.org/extend/plugins/pb-seo-friendly-images/
Description: This plugin is a full-featured solution for SEO friendly images. Optimize "alt" and "title" attributes for all images and post thumbnails. This plugin helps you to improve your traffic from search engines.
Version: 2.4.0
Author: Pascal Bajorat
Author URI: https://www.pascal-bajorat.com
Text Domain: pb-seo-friendly-images
Domain Path: /lang
License: GNU General Public License v.3

Copyright (c) 2017 by Pascal-Bajorat.com.
*/

/* Security-Check */
if ( !class_exists('WP') ) {
    die();
}

if( ! defined('pbsfi_file') ) {
    define('pbsfi_file', __FILE__);
}

if( ! defined('pbsfi_plugin_path') ) {
    define('pbsfi_plugin_path', plugin_dir_path(__FILE__));
}

if( ! defined('pbsfi_plugin_pro_path') ) {
    define('pbsfi_plugin_pro_path', pbsfi_plugin_path.'inc'.DIRECTORY_SEPARATOR.'pro.php');
}

require_once 'inc'.DIRECTORY_SEPARATOR.'pbSettingsFramework.php';

if( !class_exists('pbSEOFriendlyImages') ):

    class pbSEOFriendlyImages
    {
        public static $verMajor = '2.4';
        public static $verMinor = '0';

        public static $basename = false;
        public static $userSettings = array();
        public static $proVersion = false;

        public static $proURL = 'https://goo.gl/0SV2EU'; // fu bit.ly http://bit.ly/seo-friendly-images-pro
        public static $proURL2 = 'https://goo.gl/D5YWDj';

        /**
         * Init function
         */
        public static function init()
        {
            pbSEOFriendlyImages::$basename = plugin_basename(__FILE__);

            // Pro Version file check
            if( file_exists(pbsfi_plugin_pro_path) ) {
                pbSEOFriendlyImages::$proVersion = true;
            }

            /*
             * Language file
             */
            load_plugin_textdomain('pb-seo-friendly-images', false, dirname(pbSEOFriendlyImages::$basename).DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR);

            /*
             * Get settings and defaults
             */
            if( ! is_admin() ) {
                pbSEOFriendlyImages::$userSettings = array(
                    'optimize_img' => get_option('pbsfi_optimize_img', 'all'),
                    'sync_method' => get_option('pbsfi_sync_method', 'both'),
                    'override_alt' => get_option('pbsfi_override_alt', false),
                    'override_title' => get_option('pbsfi_override_title', false),
                    'alt_scheme' => get_option('pbsfi_alt_scheme', '%name - %title'),
                    'title_scheme' => get_option('pbsfi_title_scheme', '%title'),
                    'enable_lazyload' => get_option('pbsfi_enable_lazyload', true),
                    'enable_lazyload_acf' => get_option('pbsfi_enable_lazyload_acf', true),
                    'enable_lazyload_styles' => get_option('pbsfi_enable_lazyload_styles', false),
                    'lazyload_threshold' => get_option('pbsfi_lazyload_threshold', false),
                    'wc_title' => get_option('pbsfi_wc_title', false),
                    'disable_srcset' => get_option('pbsfi_disable_srcset', false),
                    'link_title' => get_option('pbsfi_link_title', false)
                );

                // process post thumbnails
                if( pbSEOFriendlyImages::$userSettings['optimize_img'] == 'all' || pbSEOFriendlyImages::$userSettings['optimize_img'] == 'thumbs' ) {
                    add_filter( 'wp_get_attachment_image_attributes', array(__CLASS__, 'addImgTitlePostThumbnail'), 10, 2 );
                }

                // process post images
                if( pbSEOFriendlyImages::$userSettings['optimize_img'] == 'all' || pbSEOFriendlyImages::$userSettings['optimize_img'] == 'post' ) {
                    add_filter( 'the_content', array(__CLASS__, 'prepareContentImages'), 999, 1 );

                    /*
                     * Support for AdvancedCustomFields
                     */
                    add_filter('acf/load_value/type=textarea', array(__CLASS__, 'prepareContentImages'), 20);
                    add_filter('acf/load_value/type=wysiwyg', array(__CLASS__, 'prepareContentImages'), 20);

                    //add_filter('acf_load_value-text', array(__CLASS__, 'encrypt_mails_in_content'), 20);
                    add_filter('acf_load_value-textarea', array(__CLASS__, 'prepareContentImages'), 20);
                    add_filter('acf_load_value-wysiwyg', array(__CLASS__, 'prepareContentImages'), 20);

                    // Woocommerce
                    if( pbSEOFriendlyImages::$userSettings['wc_title'] && pbSEOFriendlyImages::$proVersion ) {
                        add_filter('wp_get_attachment_image_attributes', array(__CLASS__, 'prepareContentImagesAttributes'), 20, 2);
                    }
                }
            } else {
                add_action( 'admin_enqueue_scripts', function(){
                    wp_register_style(
                        'pbsfi-admin-css',
                        plugins_url(dirname(pbSEOFriendlyImages::$basename)).'/css/admin.css',
                        false,
                        pbSEOFriendlyImages::$verMajor.'.'.pbSEOFriendlyImages::$verMinor
                    );
                    wp_enqueue_style( 'pbsfi-admin-css' );
                } );
            }
        }

        /**
         * get array key
         *
         * @param $key
         * @param $array
         * @return bool
         */
        public static function getArrayKey($key, $array)
        {
            if( array_key_exists($key, $array) ) {
                return $array[$key];
            } else {
                return false;
            }
        }

        /**
         * Scheme replacements / variables
         *
         * @param string $content scheme
         * @param bool|string $src image url
         * @param bool|int $imageID
         * @return string
         */
        public static function convertReplacements( $content, $src=false, $imageID=false )
        {
            //global $post;
            $post = get_post();

            $cats = '';
            if ( strrpos( $content, '%category' ) !== false ) {
                $categories = get_the_category();

                if ( $categories ) {
                    $i = 0;
                    foreach ( $categories as $cat ) {
                        if ( $i == 0 ) {
                            $cats = $cat->slug . $cats;
                        } else {
                            $cats = $cat->slug . ', ' . $cats;
                        }
                        ++$i;
                    }
                }
            }

            $tags = '';
            if ( strrpos( $content, '%tags' ) !== false ) {
                $posttags = get_the_tags();

                if ( $posttags ) {
                    $i = 0;
                    foreach ( $posttags as $tag ) {
                        if ( $i == 0 ) {
                            $tags = $tag->name . $tags;
                        } else {
                            $tags = $tag->name . ', ' . $tags;
                        }
                        ++$i;
                    }
                }
            }

            if( $src ) {
                $info = @pathinfo($src);
                $src = @basename($src,'.'.$info['extension']);

                $src = str_replace('-', ' ', $src);
                $src = str_replace('_', ' ', $src);
            } else {
                $src = '';
            }

            if( is_numeric($imageID) ) {
                $attachment = wp_prepare_attachment_for_js($imageID);

                if( is_array($attachment) ) {
                    $content = str_replace('%media_title', $attachment['title'], $content );
                    $content = str_replace('%media_alt', $attachment['alt'], $content );
                    $content = str_replace('%media_caption', $attachment['caption'], $content );
                    $content = str_replace('%media_description', $attachment['description'], $content );
                }
            }

            $content = str_replace('%media_title', $post->post_title, $content );
            $content = str_replace('%media_alt', $post->post_title, $content );
            $content = str_replace('%media_caption', $post->post_title, $content );
            $content = str_replace('%media_description', $post->post_title, $content );

            $content = str_replace('%name', $src, $content );
            $content = str_replace('%title', $post->post_title, $content );
            $content = str_replace('%category', $cats, $content );
            $content = str_replace('%tags', $tags, $content );
            $content = str_replace('%desc', $post->post_excerpt, $content);

            return $content;
        }

        /**
         * Process post images
         *
         * @param string $content
         * @return string
         */
        public static function prepareContentImages( $content )
        {
            if( empty($content) || !class_exists('DOMDocument') )
                return $content;

            if( get_post_type() == 'tribe_events' )
                return $content;

            $charset = DB_CHARSET || 'utf-8';
            $charset = apply_filters('pbsfi-charset', $charset);

            $document = new DOMDocument();
            if( function_exists('mb_convert_encoding') ) {
                $content = @mb_convert_encoding($content, 'HTML-ENTITIES', $charset);
            }
            @$document->loadHTML($content);

            if( !$document ) {
                return $content;
            }

            $imgTags = $document->getElementsByTagName('img');

            if( ! $imgTags->length )
                return $content;

            foreach ($imgTags as $tag) {
                $data_src = trim($tag->getAttribute('data-src'));
                $src = trim($tag->getAttribute('src'));

                if( !empty($data_src) ) {
                    $src = $data_src;
                }

                $imageID = pbSEOFriendlyImages::getImageID($src);

                /**
                 * Override Area
                 */
                if( pbSEOFriendlyImages::$userSettings['override_alt'] ) {
                    $alt = trim(pbSEOFriendlyImages::convertReplacements(
                        pbSEOFriendlyImages::$userSettings['alt_scheme'],
                        $src,
                        $imageID
                    ));

                    $alt = apply_filters('pbsfi-alt', $alt);

                    $tag->setAttribute('alt', $alt);
                } else {
                    $alt = trim($tag->getAttribute('alt'));
	                $alt = apply_filters('pbsfi-alt', $alt);
                }

                if( pbSEOFriendlyImages::$userSettings['override_title'] ) {

                    $title = trim(pbSEOFriendlyImages::convertReplacements(
                        pbSEOFriendlyImages::$userSettings['title_scheme'],
                        $src,
                        $imageID
                    ));

	                $title = apply_filters('pbsfi-title', $title);

                    $tag->setAttribute('title', $title);
                } else {
                    $title = trim($tag->getAttribute('title'));
	                $title = apply_filters('pbsfi-title', $title);
                }

                /**
                 * Check attributes
                 */
                if( !empty($alt) && empty($title) && (pbSEOFriendlyImages::$userSettings['sync_method'] == 'both' || pbSEOFriendlyImages::$userSettings['sync_method'] == 'alt' ) ) {

	                $alt = apply_filters('pbsfi-title', $alt);
                    $tag->setAttribute('title', $alt);
                    $title = $alt;

                } else if( empty($alt) && !empty($title)  && (pbSEOFriendlyImages::$userSettings['sync_method'] == 'both' || pbSEOFriendlyImages::$userSettings['sync_method'] == 'title' ) ) {

	                $title = apply_filters('pbsfi-alt', $title);
                    $tag->setAttribute('alt', $title);
                    $alt = $title;

                }

                /**
                 * set if empty after sync
                 */
                if( empty($alt) ) {
                    $alt = trim(pbSEOFriendlyImages::convertReplacements(
                        pbSEOFriendlyImages::$userSettings['alt_scheme'],
                        $src,
                        $imageID
                    ));

	                $alt = apply_filters('pbsfi-alt', $alt);
                    $tag->setAttribute('alt', $alt);
                }

                if( empty($title) ) {
                    $title = trim(pbSEOFriendlyImages::convertReplacements(
                        pbSEOFriendlyImages::$userSettings['title_scheme'],
                        $src,
                        $imageID
                    ));

	                $title = apply_filters('pbsfi-title', $title);
                    $tag->setAttribute('title', $title);
                }
            }

            return preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $document->saveHTML()));
        }

        /**
         * Prepare WooCommerce Products
         *
         * @param $attr
         * @param $attachment
         * @return mixed
         */
        public static function prepareContentImagesAttributes( $attr, $attachment )
        {
            // Get post parent
            $parent = get_post_field( 'post_parent', $attachment);

            // Get post type to check if it's product
            $type = get_post_field( 'post_type', $parent);
            if( $type != 'product' ){
                return $attr;
            }

            /// Get title
            $title = get_post_field( 'post_title', $parent);

            $attr['alt'] = apply_filters('pbsfi-wc-alt', $title);
            $attr['title'] = apply_filters('pbsfi-wc-title', $title);

            return $attr;
        }

        /**
         * Add image title and alt to post thumbnails
         *
         * @param $attr
         * @param null $attachment
         * @return mixed
         */
        public static function addImgTitlePostThumbnail( $attr, $attachment = null )
        {
            if( empty($attr['alt']) ) {

                $attr['title'] = trim(pbSEOFriendlyImages::convertReplacements(
                    pbSEOFriendlyImages::$userSettings['title_scheme'],
                    $attr['src']
                ));

                $attr['alt'] = trim(pbSEOFriendlyImages::convertReplacements(
                    pbSEOFriendlyImages::$userSettings['alt_scheme'],
                    $attr['src']
                ));

            } else {

                if( pbSEOFriendlyImages::$userSettings['sync_method'] == 'both' || pbSEOFriendlyImages::$userSettings['sync_method'] == 'alt' ) {
                    $attr['title'] = trim( strip_tags($attachment->post_title) );
                } else {
                    $attr['title'] = trim(pbSEOFriendlyImages::convertReplacements(
                        pbSEOFriendlyImages::$userSettings['title_scheme'],
                        $attr['src']
                    ));
                }

            }

	        $attr['alt'] = apply_filters('pbsfi-alt', $attr['alt']);
	        $attr['title'] = apply_filters('pbsfi-title', $attr['title']);

            return $attr;
        }

        /**
         * Get Image ID by URL
         *
         * @param string $url
         * @return int|bool
         */
        public static function getImageID( $url )
        {
            global $wpdb;

            $sql = $wpdb->prepare(
                'SELECT `ID` FROM `'.$wpdb->posts.'` WHERE `guid` = \'%s\';',
                esc_sql($url)
            );

            $attachment = $wpdb->get_col($sql);


            if( is_numeric( pbSEOFriendlyImages::getArrayKey(0, $attachment) ) ) {
                return (int) $attachment[0];
            }

            return false;
        }

        /**
         * Uninstall PB SEO Friendly Images
         */
        public static function uninstall()
        {
            /* Global */
            /** @var object $wpdb */
            global $wpdb;

            /* Remove settings */
            //delete_option();

            /* Clean DB */
            $wpdb->query("OPTIMIZE TABLE `" .$wpdb->options. "`");
        }
    }

endif; // class_exists

require_once 'inc'.DIRECTORY_SEPARATOR.'settings.php';
if( file_exists(pbsfi_plugin_pro_path) ) {
    require_once pbsfi_plugin_pro_path;
}

add_action(
    'plugins_loaded',
    array(
        'pbSEOFriendlyImages',
        'init'
    )
);

add_action(
    'plugins_loaded',
    array(
        'pbSEOFriendlyImagesSettings',
        'addSettings'
    )
);

if( class_exists('pbSEOFriendlyImagesPro') ) {
    add_action(
        'plugins_loaded',
        array(
            'pbSEOFriendlyImagesPro',
            'init'
        )
    );
}

register_uninstall_hook(
    __FILE__,
    array(
        'pbSEOFriendlyImages',
        'uninstall'
    )
);