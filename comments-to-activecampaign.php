<?php
/*
Plugin Name: Comments To ActiveCampaign
Plugin URI: http://www.haktansuren.com/comments-to-activecampaign
Description: The simplest way to collect leads from your comments to ActiveCampaign.
Author: Haktan Suren
Version: 1.0
Author URI: http://www.haktansuren.com/
*/

require_once plugin_dir_path(__FILE__)."admin-menu.php";
require_once plugin_dir_path(__FILE__)."enqueue.php";
require_once plugin_dir_path(__FILE__)."comments-process.php";
require_once plugin_dir_path(__FILE__)."activecampaign.php";
require_once plugin_dir_path(__FILE__)."metabox.php";

