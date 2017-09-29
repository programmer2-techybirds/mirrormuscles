<?php

/*

AppBuddy: 
	Most of this deactivate restration code comes from the BP Disable Activation Reloaded plugin and
	requires the following copyright to use. Thanks Damian Logghe, et al.

**********
* License
****************************************************************************
*	Copyright (C) 2011-2013 Damian Logghe and contributors
*
*	Permission is hereby granted, free of charge, to any person obtaining
*	a copy of this software and associated documentation files (the
*	"Software"), to deal in the Software without restriction, including
*	without limitation the rights to use, copy, modify, merge, publish,
*	distribute, sublicense, and/or sell copies of the Software, and to
*	permit persons to whom the Software is furnished to do so, subject to
*	the following conditions:
*
*	The above copyright notice and this permission notice shall be
*	included in all copies or substantial portions of the Software.
*
*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
*	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
*	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
*	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
*	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
*	OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
*	WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
****************************************************************************/
/***
    Copyright (C) 2009 John Lynn(crashutah.com)

    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or  any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses>.

    */
	
/***
    Credit goes to AndyPeatling for most of the initial code
    */

include $this->plugin['dir'] . 'inc/vendor/bp-disable-activation-reloaded/bp-disable-activation-loader.php';

/**
 * AppBuddy can use all the code from BP_Disable_Activation_Reloaded,
 * but we want to use AppPresser settings, so we'll just extend it and
 * use our own constructor so we can do just that.
 * 
 * @since 3.0.0
 */

class AppBuddy_Deactivate_Activation extends BP_Disable_Activation_Reloaded {

	protected $_options;
	
	private static $instance = null;
 
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
 
    /**
     * Creates or returns an instance of this class.
     *
     * @return  Foo A single instance of this class.
     */
    public static function get_instance() {
 
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
 
    } // end get_instance;

    /**
     * We use all the code from BP_Disable_Activation_Reloaded
     * except for the settings so we'll use our own constructor
     * so we can skip all of that.
     */
	function __construct() {
		
		$this->loadOptions();
		
		if( $this->_options['enable_login'] == 'true' ) {
			add_action( 'bp_init', array($this,'my_plugin_init' ));
		}

	}

	/**
	 * We need to use our own settings here
	 */
	public function loadOptions() {

		$this->_options = array();

		$this->_options['enable_login'] = ( appp_get_setting( 'appbuddy_disable_activation', 'false') == 'on' ) ? 'true' : 'false';
		$this->_options['redirection'] = apply_filters( 'appbuddy_registration_redirect', home_url() );
	}
}