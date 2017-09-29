<?php
/**
 * All the callback request will be handled here
 */
include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'cometchat_init.php');
require_once( "Social/Auth.php" );
require_once( "Social/Client.php" );

Social_Client::handle();
