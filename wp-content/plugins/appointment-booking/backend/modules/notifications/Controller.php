<?php
namespace Bookly\Backend\Modules\Notifications;

use Bookly\Lib;

/**
 * Class Controller
 * @package Bookly\Backend\Modules\Notifications
 */
class Controller extends Lib\Base\Controller
{
    public function index()
    {
        $this->enqueueStyles( array(
            'frontend' => array( 'css/ladda.min.css' ),
            'backend'  => array( 'bootstrap/css/bootstrap-theme.min.css', ),
        ) );

        $this->enqueueScripts( array(
            'backend'  => array(
                'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'js/angular.min.js',
                'js/help.js'  => array( 'jquery' ),
                'js/alert.js' => array( 'jquery' ),
            ),
            'module'   => array(
                'js/notification.js' => array( 'jquery' ),
                'js/ng-app.js' => array( 'jquery', 'bookly-angular.min.js' ),
            ),
            'frontend' => array(
                'js/spin.min.js'  => array( 'jquery' ),
                'js/ladda.min.js' => array( 'jquery' ),
            )
        ) );
        $cron_reminder = (array) get_option( 'bookly_cron_reminder_times' );
        $form  = new Forms\Notifications( 'email' );
        $alert = array( 'success' => array() );
        // Save action.
        if ( ! empty ( $_POST ) ) {
            $form->bind( $this->getPostParameters() );
            $form->save();
            $alert['success'][] = __( 'Settings saved.', 'bookly' );
            update_option( 'bookly_email_content_type',       $this->getParameter( 'bookly_email_content_type' ) );
            update_option( 'bookly_email_reply_to_customers', $this->getParameter( 'bookly_email_reply_to_customers' ) );
            update_option( 'bookly_email_sender',             $this->getParameter( 'bookly_email_sender' ) );
            update_option( 'bookly_email_sender_name',        $this->getParameter( 'bookly_email_sender_name' ) );
            foreach ( array( 'staff_agenda', 'client_follow_up', 'client_reminder' ) as $type ) {
                $cron_reminder[ $type ] = $this->getParameter( $type . '_cron_hour' );
            }
            update_option( 'bookly_cron_reminder_times', $cron_reminder );
        }
        $cron_path = realpath( Lib\Plugin::getDirectory() . '/lib/utils/send_notifications_cron.php' );
        wp_localize_script( 'bookly-alert.js', 'BooklyL10n',  array(
            'alert' => $alert,
            'sent_successfully' => __( 'Sent successfully', 'bookly' )
        ) );
        $this->render( 'index', compact( 'form', 'cron_path', 'cron_reminder' ) );
    }

    public function executeGetEmailNotificationsData()
    {
        $form = new Forms\Notifications( 'email' );

        $bookly_email_sender_name  = get_option( 'bookly_email_sender_name' ) == '' ?
            get_option( 'blogname' )    : get_option( 'bookly_email_sender_name' );

        $bookly_email_sender = get_option( 'bookly_email_sender' ) == '' ?
            get_option( 'admin_email' ) : get_option( 'bookly_email_sender' );

        $result = array(
            'ab_notifications' => $form->getData(),
            'bookly_email_sender' => $bookly_email_sender,
            'bookly_email_sender_name'  => $bookly_email_sender_name,
            'ab_types' => $form->types
        );

        wp_send_json_success( $result );
    }

    public function executeTestEmailNotifications()
    {
        $to_email      = $this->getParameter( 'to_email' );
        $content_type  = $this->getParameter( 'content_type' );
        $notifications = array();
        foreach ( $this->getParameter( 'notifications' ) as $notification ) {
            if ( $notification['active'] == '1' ) {
                $notifications[] = $notification['type'];
            }
        }
        // Change Content-Type: for test email notification.
        add_filter( 'bookly_email_headers', function ( $headers ) use ( $content_type ) {
            foreach ( $headers as &$header ) {
                if ( strpos( $header, 'Content-Type:' ) !== false ) {
                    $header = $content_type == 'plain' ? 'Content-Type: text/plain; charset=utf-8' : 'Content-Type: text/html; charset=utf-8';
                }
            }

            return $headers;
        }, 10, 1 );

        $extra = $this->getParameter( 'reply_to_customers' )
            ? array(
                'reply-to' => array(
                    'email' => $this->getParameter( 'bookly_email_sender' ),
                    'name'  => $this->getParameter( 'bookly_email_sender_name' ),
                ),
            )
            : array();

        Lib\NotificationSender::sendTestEmailNotifications( $to_email, $notifications, $extra, $content_type );

        wp_send_json_success( $_POST );
    }

    // Protected methods.

    /**
     * Override parent method to add 'wp_ajax_bookly_' prefix
     * so current 'execute*' methods look nicer.
     *
     * @param string $prefix
     */
    protected function registerWpActions( $prefix = '' )
    {
        parent::registerWpActions( 'wp_ajax_bookly_' );
    }

}