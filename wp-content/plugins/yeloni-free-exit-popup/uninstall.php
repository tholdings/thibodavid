<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
//deleting the database variables
delete_option('yetience_yeloni_setup');
?>

