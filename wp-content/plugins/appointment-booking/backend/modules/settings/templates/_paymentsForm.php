<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post" action="<?php echo esc_url( add_query_arg( 'tab', 'payments' ) ) ?>">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="bookly_pmt_currency"><?php _e( 'Currency', 'bookly' ) ?></label>
                <select id="bookly_pmt_currency" class="form-control" name="bookly_pmt_currency">
                    <?php foreach ( \Bookly\Lib\Config::getCurrencyCodes() as $code ) : ?>
                        <option value="<?php echo $code ?>" <?php selected( get_option( 'bookly_pmt_currency' ), $code ) ?> ><?php echo $code ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="bookly_pmt_coupons"><?php _e( 'Coupons', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_coupons' ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_pay_locally"><?php _e( 'Service paid locally', 'bookly' ) ?></label>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_pay_locally', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( '1', __( 'Enabled', 'bookly' ) ) ) ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_2checkout">2Checkout</label>
            <img style="margin-left: 10px; float: right" src="<?php echo plugins_url( 'frontend/resources/images/2Checkout.png', \Bookly\Lib\Plugin::getMainFile() ) ?>" />
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_2checkout', array( 'f' => array( 'disabled', __( 'Disabled', 'bookly' ) ), 't' => array( 'standard_checkout', __( '2Checkout Standard Checkout', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-2checkout">
                <h4><?php _e( 'Instructions', 'bookly' ) ?></h4>
                <p>
                    <?php _e( 'In <b>Checkout Options</b> of your 2Checkout account do the following steps:', 'bookly' ) ?>
                </p>
                <ol>
                    <li><?php _e( 'In <b>Direct Return</b> select <b>Header Redirect (Your URL)</b>.', 'bookly' ) ?></li>
                    <li><?php _e( 'In <b>Approved URL</b> enter the URL of your booking page.', 'bookly' ) ?></li>
                </ol>
                <p>
                    <?php _e( 'Finally provide the necessary information in the form below.', 'bookly' ) ?>
                </p>
            </div>
            <div class="form-group ab-2checkout">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Account Number', 'bookly' ), 'bookly_pmt_2checkout_api_seller_id' ) ?>
            </div>
            <div class="form-group ab-2checkout">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Secret Word', 'bookly' ), 'bookly_pmt_2checkout_api_secret_word' ) ?>
            </div>
            <div class="form-group ab-2checkout">
                <label for="bookly_pmt_2checkout_sandbox"><?php _e( 'Sandbox Mode', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_2checkout_sandbox', array( array( 0, __( 'No', 'bookly' ) ), array( 1, __( 'Yes', 'bookly' ) ) ) ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_paypal">PayPal</label>
            <img style="margin-left: 10px; float: right" src="<?php echo plugins_url( 'frontend/resources/images/paypal.png', \Bookly\Lib\Plugin::getMainFile() ) ?>" />
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_paypal', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( 'ec', 'PayPal Express Checkout' ) ) ) ?>
            </div>
            <div class="form-group ab-paypal-ec">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Username', 'bookly' ), 'bookly_pmt_paypal_api_username' ) ?>
            </div>
            <div class="form-group ab-paypal-ec">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Password', 'bookly' ), 'bookly_pmt_paypal_api_password' ) ?>
            </div>
            <div class="form-group ab-paypal-ec">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Signature', 'bookly' ), 'bookly_pmt_paypal_api_signature' ) ?>
            </div>
            <div class="form-group ab-paypal-ec">
                <label for="bookly_pmt_paypal_sandbox"><?php _e( 'Sandbox Mode', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_paypal_sandbox', array( array( 1, __( 'Yes', 'bookly' ) ), array( 0, __( 'No', 'bookly' ) ) ) ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_authorizenet">Authorize.Net</label>
            <img style="margin-left: 10px; float: right" src="<?php echo plugins_url( 'frontend/resources/images/authorize_net.png', \Bookly\Lib\Plugin::getMainFile() ) ?>"/>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_authorizenet', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( 'aim', 'Authorize.Net AIM' ) ) ) ?>
            </div>
            <div class="form-group authorizenet">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Login ID', 'bookly' ), 'bookly_pmt_authorizenet_api_login_id' ) ?>
            </div>
            <div class="form-group authorizenet">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Transaction Key', 'bookly' ), 'bookly_pmt_authorizenet_transaction_key' ) ?>
            </div>
            <div class="form-group authorizenet">
                <label for="bookly_pmt_authorizenet_sandbox"><?php _e( 'Sandbox Mode', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_authorizenet_sandbox', array( array( 1, __( 'Yes', 'bookly' ) ), array( 0, __( 'No', 'bookly' ) ) ) ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_stripe">Stripe</label>
            <img class="pull-right" src="<?php echo plugins_url( 'frontend/resources/images/stripe.png', \Bookly\Lib\Plugin::getMainFile() ) ?>">
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_stripe', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( '1', __( 'Enabled', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-stripe">
                <h4><?php _e( 'Instructions', 'bookly' ) ?></h4>
                <p>
                    <?php _e( 'If <b>Publishable Key</b> is provided then Bookly will use <a href="https://stripe.com/docs/stripe.js" target="_blank">Stripe.js</a><br/>for collecting credit card details.', 'bookly' ) ?>
                </p>
            </div>
            <div class="form-group ab-stripe">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Secret Key', 'bookly' ), 'bookly_pmt_stripe_secret_key' ) ?>
            </div>
            <div class="form-group ab-stripe">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Publishable Key', 'bookly' ), 'bookly_pmt_stripe_publishable_key' ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_payu_latam">PayU Latam</label>
            <img class="pull-right" src="<?php echo plugins_url( 'frontend/resources/images/payu_latam.png', \Bookly\Lib\Plugin::getMainFile() ) ?>"/>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_payu_latam', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( '1', __( 'Enabled', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-payu_latam">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Key', 'bookly' ), 'bookly_pmt_payu_latam_api_key' ) ?>
            </div>
            <div class="form-group ab-payu_latam">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Account ID', 'bookly' ), 'bookly_pmt_payu_latam_api_account_id' ) ?>
            </div>
            <div class="form-group ab-payu_latam">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Merchant ID', 'bookly' ), 'bookly_pmt_payu_latam_api_merchant_id' ) ?>
            </div>
            <div class="form-group ab-payu_latam">
                <label for="bookly_pmt_payu_latam_sandbox"><?php _e( 'Sandbox Mode', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_payu_latam_sandbox', array( array( 0, __( 'No', 'bookly' ) ), array( 1, __( 'Yes', 'bookly' ) ) ) ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_payson">Payson</label>
            <img class="pull-right" src="<?php echo plugins_url( 'frontend/resources/images/payson.png', \Bookly\Lib\Plugin::getMainFile() ) ?>"/>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_payson', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( '1', __( 'Enabled', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-payson">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Agent ID', 'bookly' ), 'bookly_pmt_payson_api_agent_id' ) ?>
            </div>
            <div class="form-group ab-payson">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Key', 'bookly' ), 'bookly_pmt_payson_api_key' ) ?>
            </div>
            <div class="form-group ab-payson">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'Receiver Email (login)', 'bookly' ), 'bookly_pmt_payson_api_receiver_email' ) ?>
            </div>
            <div class="form-group ab-payson">
                <label for="bookly_pmt_payson_funding"><?php _e( 'Funding', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionFlags( 'bookly_pmt_payson_funding', array( array( 'CREDITCARD', __( 'Card', 'bookly' ) ), array( 'INVOICE', __( 'Invoice', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-payson">
                <label for="bookly_pmt_payson_fees_payer"><?php _e( 'Fees Payer', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_payson_fees_payer', array( array( 'PRIMARYRECEIVER', __( 'I am', 'bookly' ) ), array( 'SENDER', __( 'Client', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-payson">
                <label for="bookly_pmt_payson_sandbox"><?php _e( 'Sandbox Mode', 'bookly' ) ?></label>
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_payson_sandbox', array( array( 0, __( 'No', 'bookly' ) ), array( 1, __( 'Yes', 'bookly' ) ) ) ) ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="bookly_pmt_mollie">Mollie</label>
            <img class="pull-right" src="<?php echo plugins_url( 'frontend/resources/images/mollie.png', \Bookly\Lib\Plugin::getMainFile() ) ?>"/>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_pmt_mollie', array( array( 'disabled', __( 'Disabled', 'bookly' ) ), array( '1', __( 'Enabled', 'bookly' ) ) ) ) ?>
            </div>
            <div class="form-group ab-mollie">
                <?php \Bookly\Lib\Utils\Common::optionText( __( 'API Key', 'bookly' ), 'bookly_pmt_mollie_api_key' ) ?>
            </div>
        </div>
    </div>

    <?php do_action( 'bookly_render_payment_settings' ) ?>

    <div class="panel-footer">
        <?php \Bookly\Lib\Utils\Common::submitButton() ?>
        <?php \Bookly\Lib\Utils\Common::resetButton( 'ab-payments-reset' ) ?>
    </div>
</form>