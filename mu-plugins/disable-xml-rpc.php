<?php
/**
 * Plugin Name: Disable XML-RPC
 * Description: Disables XML-RPC functionality for security
 * Version: 1.0
 */

add_filter('xmlrpc_enabled', '__return_false'); 
