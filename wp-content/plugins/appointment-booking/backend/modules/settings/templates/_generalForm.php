<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post" action="<?php echo esc_url( add_query_arg( 'tab', 'general' ) ) ?>" class="ab-settings-form">
    <div class="form-group">
        <label for="bookly_gen_time_slot_length"><?php _e( 'Time slot length', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Select a time interval which will be used as a step when building all time slots in the system.', 'bookly' ) ?></p>
        <select class="form-control" name="bookly_gen_time_slot_length" id="bookly_gen_time_slot_length">
            <?php foreach ( array( 5, 10, 12, 15, 20, 30, 45, 60, 90, 120, 180, 240, 360 ) as $duration ) :
                $duration_output = \Bookly\Lib\Utils\DateTime::secondsToInterval( $duration * 60 ); ?>
                <option value="<?php echo $duration ?>" <?php selected( get_option( 'bookly_gen_time_slot_length' ), $duration ) ?>>
                    <?php echo $duration_output ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="form-group">
        <label for="bookly_gen_service_duration_as_slot_length"><?php _e( 'Service duration as slot length', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Enable this option to make slot length equal to service duration at the Time step of booking form.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_gen_service_duration_as_slot_length' ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_gen_default_appointment_status"><?php _e( 'Default appointment status', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Select status for newly booked appointments.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_gen_default_appointment_status', array( array( \Bookly\Lib\Entities\CustomerAppointment::STATUS_PENDING, __( 'Pending', 'bookly' ) ), array( \Bookly\Lib\Entities\CustomerAppointment::STATUS_APPROVED, __( 'Approved', 'bookly' ) ) ) ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_gen_min_time_prior_booking"><?php _e( 'Minimum time requirement prior to booking', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set how late appointments can be booked (for example, require customers to book at least 1 hour before the appointment time).', 'bookly' ) ?></p>
        <select class="form-control" name="bookly_gen_min_time_prior_booking"
                id="bookly_gen_min_time_prior_booking">
            <option value="0"><?php _e( 'Disabled', 'bookly' ) ?></option>
            <?php foreach ( array_merge( range( 1, 12 ), range( 24, 144, 24 ), range( 168, 672, 168 ) ) as $hour ) : ?>
                <option value="<?php echo $hour ?>" <?php selected( get_option( 'bookly_gen_min_time_prior_booking' ), $hour ) ?>><?php echo \Bookly\Lib\Utils\DateTime::secondsToInterval( $hour * HOUR_IN_SECONDS ) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="form-group">
        <label for="bookly_gen_min_time_prior_cancel"><?php _e( 'Minimum time requirement prior to canceling', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set how late appointments can be cancelled (for example, require customers to cancel at least 1 hour before the appointment time).', 'bookly' ) ?></p>
        <select class="form-control" name="bookly_gen_min_time_prior_cancel"
                id="bookly_gen_min_time_prior_cancel">
            <option value="0"><?php _e( 'Disabled', 'bookly' ) ?></option>
            <?php foreach ( array_merge( array( 1 ), range( 2, 12, 2 ), range( 24, 168, 24 ) ) as $hour ) : ?>
                <option value="<?php echo $hour ?>" <?php selected( get_option( 'bookly_gen_min_time_prior_cancel' ), $hour ) ?>><?php echo \Bookly\Lib\Utils\DateTime::secondsToInterval( $hour * HOUR_IN_SECONDS ) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="form-group">
        <label for="bookly_gen_approve_page_url"><?php _e( 'Approve appointment URL', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set the URL of a page that is shown to staff after they approve their appointment.', 'bookly' ) ?></p>
        <input class="form-control" type="text" name="bookly_gen_approve_page_url" id="bookly_gen_approve_page_url"
               value="<?php form_option( 'bookly_gen_approve_page_url' ) ?>"
               placeholder="<?php esc_attr_e( 'Enter a URL', 'bookly' ) ?>"/>
    </div>
    <div class="form-group">
        <label for="bookly_gen_cancel_page_url"><?php _e( 'Cancel appointment URL (success)', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set the URL of a page that is shown to clients after they successfully cancelled their appointment.', 'bookly' ) ?></p>
        <input class="form-control" type="text" name="bookly_gen_cancel_page_url" id="bookly_gen_cancel_page_url"
               value="<?php form_option( 'bookly_gen_cancel_page_url' ) ?>"
               placeholder="<?php esc_attr_e( 'Enter a URL', 'bookly' ) ?>"/>
    </div>
    <div class="form-group">
        <label for="bookly_gen_cancel_denied_page_url"><?php _e( 'Cancel appointment URL (denied)', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set the URL of a page that is shown to clients when the cancellation of appointment is not available anymore.', 'bookly' ) ?></p>
        <input class="form-control" type="text" id="bookly_gen_cancel_denied_page_url"
               name="bookly_gen_cancel_denied_page_url"
               value="<?php form_option( 'bookly_gen_cancel_denied_page_url' ) ?>"
               placeholder="<?php esc_attr_e( 'Enter a URL', 'bookly' ) ?>"/>
    </div>
    <div class="form-group">
        <label for="bookly_gen_max_days_for_booking"><?php _e( 'Number of days available for booking', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set how far in the future the clients can book appointments.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionNumeric( 'bookly_gen_max_days_for_booking', 1, 1 ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_gen_use_client_time_zone"><?php _e( 'Display available time slots in client\'s time zone', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'The value is taken from client’s browser.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_gen_use_client_time_zone' ) ?>
    </div>
    <div class="form-group">
        <label for="ab_settings_final_step_url_mode"><?php _e( 'Final step URL', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Set the URL of a page that the user will be forwarded to after successful booking. If disabled then the default Done step is displayed.', 'bookly' ) ?></p>
        <select class="form-control" id="ab_settings_final_step_url_mode">
            <?php foreach ( array( __( 'Disabled', 'bookly' ) => 0, __( 'Enabled', 'bookly' ) => 1 ) as $text => $mode ) : ?>
                <option value="<?php echo esc_attr( $mode ) ?>" <?php selected( get_option( 'bookly_gen_final_step_url' ), $mode ) ?> ><?php echo $text ?></option>
            <?php endforeach ?>
        </select>
        <input class="form-control"
               style="margin-top: 5px; <?php echo get_option( 'bookly_gen_final_step_url' ) == '' ? 'display: none' : '' ?>"
               type="text" name="bookly_gen_final_step_url"
               value="<?php form_option( 'bookly_gen_final_step_url' ) ?>"
               placeholder="<?php esc_attr_e( 'Enter a URL', 'bookly' ) ?>"/>
    </div>
    <div class="form-group">
        <label for="bookly_gen_allow_staff_edit_profile"><?php _e( 'Allow staff members to edit their profiles', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'If this option is enabled then all staff members who are associated with WordPress users will be able to edit their own profiles, services, schedule and days off.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_gen_allow_staff_edit_profile' ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_gen_link_assets_method"><?php _e( 'Method to include Bookly JavaScript and CSS files on the page', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'With "Enqueue" method the JavaScript and CSS files of Bookly will be included on all pages of your website. This method should work with all themes. With "Print" method the files will be included only on the pages which contain Bookly booking form. This method may not work with all themes.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_gen_link_assets_method', array( array( 'enqueue', 'Enqueue' ), array( 'print', 'Print' ) ) ) ?>
    </div>

    <div class="panel-footer">
        <?php \Bookly\Lib\Utils\Common::submitButton() ?>
        <?php \Bookly\Lib\Utils\Common::resetButton() ?>
    </div>
</form>