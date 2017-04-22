<?php
namespace Bookly\Lib;

/**
 * Class Plugin
 * @package Bookly\Lib
 */
abstract class Plugin extends Base\Plugin
{
    protected static $prefix = 'bookly_';
    protected static $title;
    protected static $version;
    protected static $slug;
    protected static $directory;
    protected static $main_file;
    protected static $basename;
    protected static $text_domain;
    protected static $root_namespace;

    public static function registerHooks()
    {
        parent::registerHooks();
        if ( get_option( 'bookly_sms_notify_weekly_summary' ) && get_option( 'bookly_sms_token' ) ) {
            NotificationSender::registerSmsSummaryReport();
        }
    }

}