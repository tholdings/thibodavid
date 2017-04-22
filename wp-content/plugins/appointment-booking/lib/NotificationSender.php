<?php
namespace Bookly\Lib;

/**
 * Class NotificationSender
 * @package Bookly\Lib
 */
abstract class NotificationSender
{
    /**
     * @var SMS
     */
    private static $sms = null;

    /**
     * Send instant notifications.
     *
     * @param Entities\CustomerAppointment $ca
     */
    public static function send( Entities\CustomerAppointment $ca )
    {
        list ( $codes, $appointment, $customer, $staff ) = self::_prepareData( $ca );

        $status = $ca->get( 'status' );
        $extra  = get_option( 'bookly_email_reply_to_customers' )
            ? array( 'reply-to' => array( 'email' => $customer->get( 'email' ), 'name' => $customer->get( 'name' ) ) )
            : array();

        foreach ( array( 'email', 'sms' ) as $gateway ) {
            $to_staff = new Entities\Notification();
            $to_staff->loadBy( array( 'type' => "staff_{$status}_appointment", 'gateway' => $gateway ) );
            if ( $to_staff->get( 'active' ) ) {
                // Send notification to staff member (and admins if necessary).
                self::_send( $to_staff, $codes, $staff->get( 'email' ), $staff->get( 'phone' ), $extra );
            }

            $to_client = new Entities\Notification();
            $to_client->loadBy( array( 'type' => "client_{$status}_appointment", 'gateway' => $gateway ) );
            if ( $to_client->get( 'active' ) ) {
                // Client time zone offset.
                if ( $ca->get( 'time_zone_offset' ) !== null ) {
                    $codes->set( 'appointment_start', Utils\DateTime::applyTimeZoneOffset( $appointment->get( 'start_date' ), $ca->get( 'time_zone_offset' ) ) );
                }
                // Send notification to client.
                self::_send( $to_client, $codes, $customer->get( 'email' ), $customer->get( 'phone' ) );
            }
        }
    }

    /**
     * Send notification from cart.
     *
     * @param Entities\CustomerAppointment[] $ca_list
     */
    public static function sendFromCart( array $ca_list )
    {
        if ( Config::areCombinedNotificationsEnabled() && ! empty( $ca_list ) ) {
            $status    = get_option( 'bookly_gen_default_appointment_status' );
            $cart_info = array();
            $payments  = array();
            $customer  = null;
            $codes     = null;
            $total     = 0.0;
            $compound_tokens = array();
            $email_to_staff  = new Entities\Notification();
            $sms_to_staff    = new Entities\Notification();

            $email_to_staff->loadBy( array( 'type' => "staff_{$status}_appointment", 'gateway' => 'email' ) );
            $sms_to_staff->loadBy( array( 'type' => "staff_{$status}_appointment", 'gateway' => 'sms' ) );

            foreach ( $ca_list as $ca ) {
                if ( ! isset( $compound_tokens[ $ca->get( 'compound_token' ) ] ) ) {
                    if ( $ca->get( 'compound_token' ) ) {
                        $compound_tokens[ $ca->get( 'compound_token' ) ] = true;
                    }
                    list ( $codes, $appointment, $customer, $staff ) = self::_prepareData( $ca );
                    $extra = get_option( 'bookly_email_reply_to_customers' )
                        ? array( 'reply-to' => array( 'email' => $customer->get( 'email' ), 'name' => $customer->get( 'name' ) ) )
                        : array();

                    if ( $email_to_staff->get( 'active' ) ) {
                        // Send email to staff member (and admins if necessary).
                        self::_send( $email_to_staff, $codes, $staff->get( 'email' ), $staff->get( 'phone' ), $extra );
                    }
                    if ( $sms_to_staff->get( 'active' ) ) {
                        // Send SMS to staff member (and admins if necessary).
                        self::_send( $sms_to_staff, $codes, $staff->get( 'email' ), $staff->get( 'phone' ), $extra );
                    }

                    // Prepare data for {cart_info} || {cart_info_c}.
                    $cart_info[] = array(
                        'appointment_price' => ( $codes->get( 'service_price' ) + $codes->get( 'extras_total_price', 0 ) )  * $codes->get( 'number_of_persons' ),
                        'appointment_start' => $ca->get( 'time_zone_offset' ) !== null
                            ? Utils\DateTime::applyTimeZoneOffset( $appointment->get( 'start_date' ), $ca->get( 'time_zone_offset' ) )
                            : $codes->get( 'appointment_start' ),
                        'cancel_url'   => admin_url( 'admin-ajax.php?action=bookly_cancel_appointment&token=' . $codes->get( 'appointment_token' ) ),
                        'service_name' => $codes->get( 'service_name' ),
                        'staff_name'   => $codes->get( 'staff_name' ),
                        'extras'       => apply_filters( 'bookly_service_extras_get_data_for_appointment', array(), $ca->get( 'extras' ), true ),
                    );
                    if ( ! isset( $payments[ $ca->get( 'payment_id' ) ] ) ) {
                        if ( $ca->get( 'payment_id' ) ) {
                            $payments[ $ca->get( 'payment_id' ) ] = true;
                        }
                        $total += $codes->get( 'total_price' );
                    }
                }
            }
            $codes->set( 'total_price', $total );
            $codes->set( 'cart_info',   $cart_info );
            // Send notification to client.
            foreach ( array( 'email', 'sms' ) as $gateway ) {
                $to_client = new Entities\Notification();
                $to_client->loadBy( array( 'type' => "client_{$status}_appointment_cart", 'gateway' => $gateway ) );
                if ( $to_client->get( 'active' ) ) {
                    self::_send( $to_client, $codes, $customer->get( 'email' ), $customer->get( 'phone' ) );
                }
            }
        } else { // Combined notifications disabled.
            foreach ( $ca_list as $ca ) {
                self::send( $ca );
            }
        }
    }

    /**
     * Send scheduled notification.
     *
     * @param Entities\Notification $notification
     * @param Entities\CustomerAppointment $ca
     * @return bool
     */
    public static function sendFromCron( Entities\Notification $notification, Entities\CustomerAppointment $ca )
    {
        list ( $codes, $appointment, $customer ) = self::_prepareData( $ca );

        // Client time zone offset.
        if ( $ca->get( 'time_zone_offset' ) !== null ) {
            $codes->set( 'appointment_start', Utils\DateTime::applyTimeZoneOffset( $appointment->get( 'start_date' ), $ca->get( 'time_zone_offset' ) ) );
        }
        // Send notification to client.
        $result = self::_send( $notification, $codes, $customer->get( 'email' ), $customer->get( 'phone' ), array(), $ca->get( 'locale' ) );

        return $result;
    }

    /**
     * Send email with username and password for newly created WP user.
     *
     * @param Entities\Customer $customer
     * @param $username
     * @param $password
     */
    public static function sendEmailForNewUser( Entities\Customer $customer, $username, $password )
    {
        foreach ( array( 'email', 'sms' ) as $gateway ) {
            $to_client = new Entities\Notification();
            $to_client->loadBy( array( 'type' => 'client_new_wp_user', 'gateway' => $gateway ) );

            if ( $to_client->get( 'active' ) ) {
                $codes = new NotificationCodes();
                $codes->set( 'client_email', $customer->get( 'email' ) );
                $codes->set( 'client_name',  $customer->get( 'name' ) );
                $codes->set( 'client_phone', $customer->get( 'phone' ) );
                $codes->set( 'new_password', $password );
                $codes->set( 'new_username', $username );
                $codes->set( 'site_address', site_url() );

                self::_send( $to_client, $codes, $customer->get( 'email' ), $customer->get( 'phone' ) );
            }
        }
    }

    /**
     * Prepare data for email.
     *
     * @param Entities\CustomerAppointment $ca
     * @return array [ NotificationCodes, Entities\Appointment, Entities\Customer, Entities\Staff ]
     */
    private static function _prepareData( Entities\CustomerAppointment $ca )
    {
        global $sitepress;
        if ( $sitepress instanceof \SitePress ) {
            $sitepress->switch_lang( $ca->get( 'locale' ), true );
        }
        $appointment = new Entities\Appointment();
        $appointment->load( $ca->get( 'appointment_id' ) );

        $customer = new Entities\Customer();
        $customer->load( $ca->get( 'customer_id' ) );

        $staff = new Entities\Staff();
        $staff->load( $appointment->get( 'staff_id' ) );

        $service = new Entities\Service();
        $staff_service = new Entities\StaffService();
        if ( $ca->get( 'compound_service_id' ) ) {
            $service->load( $ca->get( 'compound_service_id' ) );
            $staff_service->loadBy( array( 'staff_id' => $staff->get( 'id' ), 'service_id' => $service->get( 'id' ) ) );
            $price = $service->get( 'price' );
            // The appointment ends when the last service ends in the compound service.
            $bounding = Entities\Appointment::query( 'a' )
                ->select( 'MIN(a.start_date) AS start, MAX(DATE_ADD(a.end_date, INTERVAL a.extras_duration SECOND)) AS end' )
                ->leftJoin( 'CustomerAppointment', 'ca', 'ca.appointment_id = a.id' )
                ->where( 'ca.compound_token', $ca->get( 'compound_token' ) )
                ->groupBy( 'ca.compound_token' )
                ->fetchRow();
            $appointment_start = $bounding['start'];
            $appointment_end   = $bounding['end'];
        } else {
            $service->load( $appointment->get( 'service_id' ) );
            $staff_service->loadBy( array( 'staff_id' => $staff->get( 'id' ), 'service_id' => $service->get( 'id' ) ) );
            $price = $staff_service->get( 'price' );
            $appointment_end   = date_create( $appointment->get( 'end_date' ) )->modify( '+' . $appointment->get( 'extras_duration' ) . ' sec' )->format( 'Y-m-d H:i:s' );
            $appointment_start = $appointment->get( 'start_date' );
        }

        $staff_photo = wp_get_attachment_image_src( $staff->get( 'attachment_id' ), 'full' );
        $codes = new NotificationCodes();
        $codes->set( 'appointment_end',   $appointment_end );
        $codes->set( 'appointment_start', $appointment_start );
        $codes->set( 'appointment_token', $ca->get( 'token' ) );
        $codes->set( 'booking_number' ,   $appointment->get( 'id' ) );
        $codes->set( 'category_name',     $service->getCategoryName() );
        $codes->set( 'client_email',      $customer->get( 'email' ) );
        $codes->set( 'client_name',       $customer->get( 'name' ) );
        $codes->set( 'client_phone',      $customer->get( 'phone' ) );
        $codes->set( 'custom_fields',     $ca->getFormattedCustomFields( 'text' ) );
        $codes->set( 'custom_fields_2c',  $ca->getFormattedCustomFields( 'html' ) );
        $codes->set( 'number_of_persons', $ca->get( 'number_of_persons' ) );
        if ( $ca->get( 'payment_id' ) ) {
            $codes->set( 'payment_type',  Entities\Payment::typeToString( Entities\Payment::find( $ca->get( 'payment_id' ) )->get( 'type' ) ) );
        }
        $codes->set( 'service_info',      $service->getInfo() );
        $codes->set( 'service_name',      $service->getTitle() );
        $codes->set( 'service_price',     $price );
        $codes->set( 'staff_email',       $staff->get( 'email' ) );
        $codes->set( 'staff_info',        $staff->getInfo() );
        $codes->set( 'staff_name',        $staff->getName() );
        $codes->set( 'staff_phone',       $staff->get( 'phone' ) );
        $codes->set( 'staff_photo',       $staff_photo ? $staff_photo[0] : '' );

        $codes->setStaffService( $staff_service );

        $codes = apply_filters( 'bookly_prepare_notification_codes', $codes, $ca );

        if ( $ca->get( 'payment_id' ) ) {
            $payment = Entities\Payment::query()->select( 'total, paid' )->where( 'id', $ca->get( 'payment_id' ) )->fetchRow();
            $codes->set( 'total_price', $payment['total'] );
            $codes->set( 'amount_paid', $payment['paid'] );
            $codes->set( 'amount_due',  $payment['total'] - $payment['paid'] );
        } else {
            $codes->set( 'total_price', ( $codes->get( 'service_price' ) + $codes->get( 'extras_total_price', 0 ) ) * $codes->get( 'number_of_persons' ) );
            $codes->set( 'amount_paid', '' );
            $codes->set( 'amount_due',  '' );
        }

        return array( $codes, $appointment, $customer, $staff );
    }

    /**
     * Send test notification emails.
     *
     * @param string $to_email
     * @param array  $notification_types
     * @param array  $extra
     * @param        $content_type
     */
    public static function sendTestEmailNotifications( $to_email, array $notification_types, array $extra = array(), $content_type )
    {
        foreach ( $notification_types as $type ) {
            $notification = new Entities\Notification();
            $notification->loadBy( array( 'type' => $type, 'gateway' => 'email' ) );

            $yesterday_12 = date_create( 'yesterday 12 hours' )->format( 'Y-m-d H:i:s' );
            $yesterday_13 = date_create( 'yesterday 13 hours' )->format( 'Y-m-d H:i:s' );
            $cart_info = array( array(
                'service_name'      => 'Service Name',
                'appointment_start' => $yesterday_12,
                'staff_name'        => 'Staff Name',
                'appointment_price' => 24,
                'cancel_url'        => '#',
            ) );

            $codes = new NotificationCodes();
            $codes->set( 'amount_due',         '' );
            $codes->set( 'amount_paid',        '' );
            $codes->set( 'appointment_end',    $yesterday_13 );
            $codes->set( 'appointment_start',  $yesterday_12 );
            $codes->set( 'cart_info',          $cart_info );
            $codes->set( 'category_name',      'Category Name' );
            $codes->set( 'client_email',       'client@example.com' );
            $codes->set( 'client_name',        'Client Name' );
            $codes->set( 'client_phone',       '12345678' );
            $codes->set( 'extras',             'Extras 1, Extras 2' );
            $codes->set( 'extras_total_price', '4' );
            $codes->set( 'new_password',       'New Password' );
            $codes->set( 'new_username',       'New User' );
            $codes->set( 'next_day_agenda',    '' );
            $codes->set( 'number_of_persons',  '1' );
            $codes->set( 'payment_type',       Entities\Payment::typeToString( Entities\Payment::TYPE_LOCAL ) );
            $codes->set( 'service_info',       'Service info text' );
            $codes->set( 'service_name',       'Service Name' );
            $codes->set( 'service_price',      '10' );
            $codes->set( 'staff_email',        'staff@example.com' );
            $codes->set( 'staff_info',         'Staff info text' );
            $codes->set( 'staff_name',         'Staff Name' );
            $codes->set( 'staff_phone',        '23456789' );
            $codes->set( 'staff_photo',        'https://dummyimage.com/100/dddddd/000000' );
            $codes->set( 'total_price',        '24' );

            self::_send( $notification, $codes, $to_email, '', $extra, null, $content_type );
        }
    }

    /**
     * Send email to $mail_to.
     *
     * @param Entities\Notification $notification
     * @param NotificationCodes     $codes
     * @param                       $mail_to
     * @param string                $phone
     * @param array                 $extra
     * @param null|string           $language_code
     * @param null|string           $content_type
     * @return bool
     */
    private static function _send( Entities\Notification $notification, NotificationCodes $codes, $mail_to, $phone = '', $extra = array(), $language_code = null, $content_type = null )
    {
        $result  = false;
        $message_template = Utils\Common::getTranslatedString( $notification->get( 'gateway' ) . '_' . $notification->get( 'type' ), $notification->get( 'message' ), $language_code );
        $content_type = $content_type ?: get_option( 'bookly_email_content_type' );
        $message = $codes->replace( $message_template, $notification->get( 'gateway' ), $content_type );
        if ( $notification->get( 'gateway' ) == 'email' ) {
            // Send email to recipient.
            $subject = $codes->replace( Utils\Common::getTranslatedString( $notification->get( 'gateway' ) . '_' . $notification->get( 'type' ) . '_subject', $notification->get( 'subject' ), $language_code ) );
            $headers = Utils\Common::getEmailHeaders( $extra );
            $message = $content_type == 'plain' ? $message : wpautop( $message );
            $result  = wp_mail( $mail_to, $subject, $message, $headers );
            // Send copy to administrators.
            if ( $notification->get( 'copy' ) ) {
                $admin_emails = Utils\Common::getAdminEmails();
                if ( ! empty ( $admin_emails ) ) {
                    wp_mail( $admin_emails, $subject, $message, $headers );
                }
            }
        } elseif ( $notification->get( 'gateway' ) == 'sms' ) {
            // Send sms.
            if ( self::$sms === null ) {
                self::$sms = new SMS();
            }
            if ( $phone != '' ) {
                $result = self::$sms->sendSms( $phone, $message, $notification->getTypeId() );
            }
            if ( $notification->get( 'copy' ) ) {
                if ( ( $administrator_phone = get_option( 'bookly_sms_administrator_phone', '' ) != '' ) ) {
                    self::$sms->sendSms( $administrator_phone, $message, $notification->getTypeId() );
                }
            }
        }

        return $result;
    }

    public static function registerSmsSummaryReport()
    {
        add_action( 'bookly_daily_routine', function () {
            if ( get_option( 'bookly_sms_notify_weekly_summary_sent' ) != date( 'W' ) ) {
                $admin_emails = Utils\Common::getAdminEmails();
                if ( ! empty ( $admin_emails ) ) {
                    $sms     = new SMS();
                    $start   = date_create( 'last week' )->format( 'Y-m-d 00:00:00' );
                    $end     = date_create( 'this week' )->format( 'Y-m-d 00:00:00' );
                    $summary = $sms->getSummary( Utils\DateTime::applyTimeZoneOffset( $start, 0 ), Utils\DateTime::applyTimeZoneOffset( $end, 0 ) );
                    if ( $summary !== false ) {
                        $notification_list = '';
                        foreach ( $summary->notifications as $type_id => $count ) {
                            $notification_list .= PHP_EOL . Entities\Notification::getName( Entities\Notification::getTypeString( $type_id ) ) . ': ' . $count->delivered;
                            if ( $count->delivered < $count->sent ) {
                                $notification_list .= ' (' . $count->sent . ' ' . __( 'sent to our system', 'bookly' ) . ')';
                            }
                        }
                        // For balance.
                        $sms->loadProfile();
                        $message =
__( 'Hope you had a good weekend! Here\'s a summary of messages we\'ve delivered last week:
{notification_list}

Your system sent a total of {total} messages last week (that\'s {delta} {sign} than the week before).
Cost of sending {total} messages was {amount}. You current Bookly SMS balance is {balance}.

Thank you for using Bookly SMS. We wish you a lucky week!
Bookly SMS Team.', 'bookly' );
                        $message = strtr( $message,
                            array(
                                '{notification_list}' => $notification_list,
                                '{total}'             => $summary->total,
                                '{delta}'             => abs( $summary->delta ),
                                '{sign}'              => $summary->delta >= 0 ? __( 'more', 'bookly' ) : __( 'less', 'bookly' ),
                                '{amount}'            => '$' . $summary->amount,
                                '{balance}'           => '$' . $sms->getBalance(),
                            )
                        );
                        wp_mail( $admin_emails, __( 'Bookly SMS weekly summary', 'bookly' ), $message );
                        update_option( 'bookly_sms_notify_weekly_summary_sent', date( 'W' ) );
                    }
                }
            }
        }, 10, 0 );
    }

}