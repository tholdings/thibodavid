<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post" action="<?php echo esc_url( add_query_arg( 'tab', 'customers' ) ) ?>" class="ab-settings-form">
    <div class="form-group">
        <label for="bookly_cst_create_account"><?php _e( 'Create WordPress user account for customers', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'If this setting is enabled then Bookly will be creating WordPress user accounts for all new customers. If the user is logged in then the new customer will be associated with the existing user account.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_cst_create_account' ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_cst_new_account_role"><?php _e( 'New user account role', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Select what role will be assigned to newly created WordPress user accounts for customers.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_cst_new_account_role', $roles ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_cst_phone_default_country"><?php _e( 'Phone field default country', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Select default country for the phone field in the \'Details\' step of booking. You can also let Bookly determine the country based on the IP address of the client.', 'bookly' ) ?></p>
        <select class="form-control" name="bookly_cst_phone_default_country" id="bookly_cst_phone_default_country" data-country="<?php echo get_option( 'bookly_cst_phone_default_country' ) ?>">
            <option value="disabled"><?php _e( 'Disabled', 'bookly' ) ?></option>
            <option value="auto"><?php _e( 'Guess country by user\'s IP address', 'bookly' ) ?></option>
            <option disabled><?php echo str_repeat( '&#9472;', 30 ) ?></option>
        </select>
    </div>
    <div class="form-group">
        <?php \Bookly\Lib\Utils\Common::optionText( __( 'Default country code', 'bookly' ), 'bookly_cst_default_country_code', __( 'Your clients must have their phone numbers in international format in order to receive text messages. However you can specify a default country code that will be used as a prefix for all phone numbers that do not start with "+" or "00". E.g. if you enter "1" as the default country code and a client enters their phone as "(600) 555-2222" the resulting phone number to send the SMS to will be "+1600555222".', 'bookly' ) ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_cst_cancel_action"><?php _e( 'Cancel appointment action', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'Select what happens when customer clicks cancel appointment link. With "Delete" the appointment will be deleted from the calendar. With "Cancel" only appointment status will be changed to "Cancelled".', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_cst_cancel_action',  array( array( 'delete', __( 'Delete', 'bookly' ) ), array( 'cancel', __( 'Cancel', 'bookly' ) ) ) ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_cst_combined_notifications"><?php _e( 'Combined notifications', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'If combined notifications are enabled then your clients will receive single notification for entire booking instead of separate notification per each booked appointment (e.g. when cart is enabled). You will need to edit corresponding templates in Email and SMS Notifications.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_cst_combined_notifications' ) ?>
    </div>
    <div class="panel-footer">
        <?php \Bookly\Lib\Utils\Common::submitButton() ?>
        <?php \Bookly\Lib\Utils\Common::resetButton( 'ab-customer-reset' ) ?>
    </div>
</form>