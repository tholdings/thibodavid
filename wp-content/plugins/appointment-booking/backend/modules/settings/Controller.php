<?php
namespace Bookly\Backend\Modules\Settings;

use Bookly\Lib;

/**
 * Class Controller
 * @package Bookly\Backend\Modules\Settings
 */
class Controller extends Lib\Base\Controller
{
    const page_slug = 'ab-settings';

    public function index()
    {
        /** @var \WP_Locale $wp_locale */
        global $wp_locale;

        wp_enqueue_media();
        $this->enqueueStyles( array(
            'frontend' => array( 'css/ladda.min.css' ),
            'backend'  => array( 'bootstrap/css/bootstrap-theme.min.css', )
        ) );

        $this->enqueueScripts( array(
            'backend'  => array(
                'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'js/jCal.js'  => array( 'jquery' ),
                'js/alert.js' => array( 'jquery' ),
            ),
            'module'   => array( 'js/settings.js' => array( 'jquery', 'bookly-intlTelInput.min.js', 'jquery-ui-sortable' ) ),
            'frontend' => array(
                'js/intlTelInput.min.js' => array( 'jquery' ),
                'js/spin.min.js'  => array( 'jquery' ),
                'js/ladda.min.js' => array( 'jquery' ),
            )
        ) );

        $current_tab = $this->hasParameter( 'tab' ) ? $this->getParameter( 'tab' ) : 'general';
        $alert = array( 'success' => array(), 'error' => array() );

        // Save the settings.
        if ( ! empty ( $_POST ) ) {
            switch ( $this->getParameter( 'tab' ) ) {
                case 'payments':  // Payments form.
                    $form = new Forms\Payments();
                    break;
                case 'business_hours':  // Business hours form.
                    $form = new Forms\BusinessHours();
                    break;
                case 'purchase_code':  // Purchase Code form.
                    $errors = apply_filters( 'bookly_save_purchase_codes', array(), $this->getParameter( 'purchase_code' ), null );
                    if ( empty ( $errors ) ) {
                        $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    } else {
                        $alert['error'] = array_merge( $alert['error'], $errors );
                    }
                    break;
                case 'general':  // General form.
                    $bookly_gen_time_slot_length = $this->getParameter( 'bookly_gen_time_slot_length' );
                    if ( in_array( $bookly_gen_time_slot_length, array( 5, 10, 12, 15, 20, 30, 45, 60, 90, 120, 180, 240, 360 ) ) ) {
                        update_option( 'bookly_gen_time_slot_length', $bookly_gen_time_slot_length );
                    }
                    update_option( 'bookly_gen_service_duration_as_slot_length', (int) $this->getParameter( 'bookly_gen_service_duration_as_slot_length' ) );
                    update_option( 'bookly_gen_allow_staff_edit_profile', (int) $this->getParameter( 'bookly_gen_allow_staff_edit_profile' ) );
                    update_option( 'bookly_gen_approve_page_url',       $this->getParameter( 'bookly_gen_approve_page_url' ) );
                    update_option( 'bookly_gen_cancel_denied_page_url', $this->getParameter( 'bookly_gen_cancel_denied_page_url' ) );
                    update_option( 'bookly_gen_cancel_page_url',        $this->getParameter( 'bookly_gen_cancel_page_url' ) );
                    update_option( 'bookly_gen_default_appointment_status', $this->getParameter( 'bookly_gen_default_appointment_status' ) );
                    update_option( 'bookly_gen_final_step_url',         $this->getParameter( 'bookly_gen_final_step_url' ) );
                    update_option( 'bookly_gen_link_assets_method',     $this->getParameter( 'bookly_gen_link_assets_method' ) );
                    update_option( 'bookly_gen_max_days_for_booking',   (int) $this->getParameter( 'bookly_gen_max_days_for_booking' ) );
                    update_option( 'bookly_gen_min_time_prior_booking', (int) $this->getParameter( 'bookly_gen_min_time_prior_booking' ) );
                    update_option( 'bookly_gen_min_time_prior_cancel',  $this->getParameter( 'bookly_gen_min_time_prior_cancel' ) );
                    update_option( 'bookly_gen_use_client_time_zone',   (int) $this->getParameter( 'bookly_gen_use_client_time_zone' ) );
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    break;
                case 'google_calendar':  // Google calendar form.
                    update_option( 'bookly_gc_client_id',      $this->getParameter( 'bookly_gc_client_id' ) );
                    update_option( 'bookly_gc_client_secret',  $this->getParameter( 'bookly_gc_client_secret' ) );
                    update_option( 'bookly_gc_event_title',    $this->getParameter( 'bookly_gc_event_title' ) );
                    update_option( 'bookly_gc_limit_events',   $this->getParameter( 'bookly_gc_limit_events' ) );
                    update_option( 'bookly_gc_two_way_sync',   $this->getParameter( 'bookly_gc_two_way_sync' ) );
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    break;
                case 'customers':  // Customers form.
                    update_option( 'bookly_cst_cancel_action',          $this->getParameter( 'bookly_cst_cancel_action' ) );
                    update_option( 'bookly_cst_create_account',         (int) $this->getParameter( 'bookly_cst_create_account' ) );
                    update_option( 'bookly_cst_default_country_code',   $this->getParameter( 'bookly_cst_default_country_code' ) );
                    update_option( 'bookly_cst_new_account_role',       $this->getParameter( 'bookly_cst_new_account_role' ) );
                    update_option( 'bookly_cst_combined_notifications', $this->getParameter( 'bookly_cst_combined_notifications' ) );
                    update_option( 'bookly_cst_phone_default_country',  $this->getParameter( 'bookly_cst_phone_default_country' ) );
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    break;
                case 'woocommerce':  // WooCommerce form.
                    foreach ( array( 'bookly_l10n_wc_cart_info_name', 'bookly_l10n_wc_cart_info_value' ) as $option_name ) {
                        update_option( $option_name, $this->getParameter( $option_name ) );
                        do_action( 'wpml_register_single_string', 'bookly', $option_name,  $this->getParameter( $option_name ) );
                    }
                    update_option( 'bookly_wc_enabled', $this->getParameter( 'bookly_wc_enabled' ) );
                    update_option( 'bookly_wc_product', $this->getParameter( 'bookly_wc_product' ) );
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    break;
                case 'cart':  // Cart form.
                    update_option( 'bookly_cart_show_columns', $this->getParameter( 'bookly_cart_show_columns', array() ) );
                    update_option( 'bookly_cart_enabled',      (int) $this->getParameter( 'bookly_cart_enabled' ) );
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    if ( get_option( 'bookly_wc_enabled' ) && $this->getParameter( 'bookly_cart_enabled' ) ) {
                        $alert['error'][] = sprintf( __( 'To use the cart, disable integration with WooCommerce <a href="%s">here</a>.', 'bookly' ), Lib\Utils\Common::escAdminUrl( self::page_slug, array( 'tab' => 'woocommerce' ) ) );
                    }
                    break;
                case 'company':  // Company form.
                    update_option( 'bookly_co_address', $this->getParameter( 'bookly_co_address' ) );
                    update_option( 'bookly_co_logo_attachment_id', $this->getParameter( 'bookly_co_logo_attachment_id' ) );
                    update_option( 'bookly_co_name',    $this->getParameter( 'bookly_co_name' ) );
                    update_option( 'bookly_co_phone',   $this->getParameter( 'bookly_co_phone' ) );
                    update_option( 'bookly_co_website', $this->getParameter( 'bookly_co_website' ) );
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    break;
                default:
                    // Let add-ons save their settings.
                    $alert = apply_filters( 'bookly_save_settings', $alert, $this->getParameter( 'tab' ), $this->getPostParameters() );
            }

            if ( in_array( $this->getParameter( 'tab' ), array ( 'payments', 'business_hours' ) ) ) {
                $form->bind( $this->getPostParameters(), $_FILES );
                $form->save();

                $alert['success'][] = __( 'Settings saved.', 'bookly' );
            }
        }

        $holidays   = $this->getHolidays();
        $candidates = $this->getCandidatesBooklyProduct();

        // Check if WooCommerce cart exists.
        if ( get_option( 'bookly_wc_enabled' ) && class_exists( 'WooCommerce', false ) ) {
            $post = get_post( wc_get_page_id( 'cart' ) );
            if ( $post === null || $post->post_status != 'publish' ) {
                $alert['error'][] = sprintf(
                    __( 'WooCommerce cart is not set up. Follow the <a href="%s">link</a> to correct this problem.', 'bookly' ),
                    Lib\Utils\Common::escAdminUrl( 'wc-status', array( 'tab' => 'tools' ) )
                );
            }
        }
        $cart_columns = array(
            'service'  => Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_service' ),
            'date'     => __( 'Date',  'bookly' ),
            'time'     => __( 'Time',  'bookly' ),
            'employee' => Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_employee' ),
            'price'    => __( 'Price', 'bookly' ),
        );

        $cart_columns = apply_filters( 'bookly_settings_cart_columns', $cart_columns );

        wp_localize_script( 'bookly-jCal.js', 'BooklyL10n',  array(
            'alert'       => $alert,
            'close'       => __( 'Close', 'bookly' ),
            'current_tab' => $current_tab,
            'days'        => array_values( $wp_locale->weekday_abbrev ),
            'months'      => array_values( $wp_locale->month ),
            'repeat'      => __( 'Repeat every year', 'bookly' ),
            'we_are_not_working' => __( 'We are not working on this day', 'bookly' ),
        ) );
        $roles = array();
        $wp_roles = new \WP_Roles();
        foreach ( $wp_roles->get_names() as $role => $name ) {
            $roles[] = array( $role, $name );
        }

        $this->render( 'index', compact( 'holidays', 'candidates', 'cart_columns', 'roles' ) );
    }

    /**
     * Ajax request for Holidays calendar
     */
    public function executeSettingsHoliday()
    {
        global $wpdb;

        $id      = $this->getParameter( 'id',  false );
        $day     = $this->getParameter( 'day', false );
        $holiday = $this->getParameter( 'holiday' ) == 'true';
        $repeat  = $this->getParameter( 'repeat' )  == 'true';

        // update or delete the event
        if ( $id ) {
            if ( $holiday ) {
                $wpdb->update( Lib\Entities\Holiday::getTableName(), array( 'repeat_event' => (int) $repeat ), array( 'id' => $id ), array( '%d' ) );
                $wpdb->update( Lib\Entities\Holiday::getTableName(), array( 'repeat_event' => (int) $repeat ), array( 'parent_id' => $id ), array( '%d' ) );
            } else {
                Lib\Entities\Holiday::query()->delete()->where( 'id', $id )->where( 'parent_id', $id, 'OR' )->execute();
            }
            // add the new event
        } elseif ( $holiday && $day ) {
            $holiday = new Lib\Entities\Holiday( array( 'date' => $day, 'repeat_event' => (int) $repeat ) );
            $holiday->save();
            foreach ( Lib\Entities\Staff::query()->fetchArray() as $employee ) {
                $staff_holiday = new Lib\Entities\Holiday( array( 'date' => $day, 'repeat_event' => (int) $repeat, 'staff_id'  => $employee['id'], 'parent_id' => $holiday->get( 'id' ) ) );
                $staff_holiday->save();
            }
        }

        // and return refreshed events
        echo $this->getHolidays();
        exit;
    }

    /**
     * @return mixed|string|void
     */
    protected function getHolidays()
    {
        $collection = Lib\Entities\Holiday::query()->where( 'staff_id', null )->fetchArray();
        $holidays = array();
        if ( count( $collection ) ) {
            foreach ( $collection as $holiday ) {
                $holidays[ $holiday['id'] ] = array(
                    'm' => (int) date( 'm', strtotime( $holiday['date'] ) ),
                    'd' => (int) date( 'd', strtotime( $holiday['date'] ) ),
                );
                // If not repeated holiday, add the year
                if ( ! $holiday['repeat_event'] ) {
                    $holidays[ $holiday['id'] ]['y'] = (int) date( 'Y', strtotime( $holiday['date'] ) );
                }
            }
        }

        return json_encode( $holidays );
    }

    protected function getCandidatesBooklyProduct()
    {
        $goods = array( array( 'id' => 0, 'name' => __( 'Select product', 'bookly' ) ) );
        $args  = array(
            'numberposts'      => -1,
            'post_type'        => 'product',
            'suppress_filters' => true
        );
        $collection = get_posts( $args );
        foreach ( $collection as $item ) {
            $goods[] = array( 'id' => $item->ID, 'name' => $item->post_title );
        }
        wp_reset_postdata();

        return $goods;
    }

    /**
     * Subscribe settings
     */
    public function executeSetSubscriptionOptions()
    {
        $email = $this->getParameter( 'email', false );
        if ( $email === false ) {
            update_option( 'bookly_gen_show_subscribe_notice', '0' );
            wp_send_json_success();
        } elseif ( is_email( $email ) == false ) {
            wp_send_json_error( array( 'message' => __( 'Please tell us your email', 'bookly' ) ) );
        } else {
            $url  = 'http://api.booking-wp-plugin.com/1.0/subscribers';
            $curl = new Lib\Curl\Curl();
            $curl->options['CURLOPT_HEADER']         = 0;
            $curl->options['CURLOPT_TIMEOUT']        = 25;
            $data = array( 'email' => $email, 'host' => parse_url( site_url(), PHP_URL_HOST ) );
            $response = json_decode( $curl->post( $url, $data ), true );
            if ( isset( $response['success'] ) && $response['success'] ) {
                update_option( 'bookly_gen_show_subscribe_notice', '0' );
            }
            wp_send_json_success();
        }
    }

    /**
     * Ajax request to dismiss admin notice for current user.
     */
    public function executeDismissAdminNotice()
    {
        update_user_meta( get_current_user_id(), $this->getParameter( 'prefix' ) . 'dismiss_admin_notice', 1 );
    }

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