<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    echo $progress_tracker;
?>
<?php if ( get_option( 'bookly_pmt_coupons' ) ) : ?>
    <div class="ab-row ab-info-text-coupon"><?php echo $info_text_coupon ?></div>
    <div class="ab-row ab-list">
        <?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_coupon' ) ?>
        <?php if ( $coupon_code ) : ?>
            <?php echo esc_attr( $coupon_code ) . ' ✓' ?>
        <?php else : ?>
            <input class="ab-user-coupon" name="ab_coupon" type="text" value="<?php echo esc_attr( $coupon_code ) ?>" />
            <button class="ab-btn ladda-button btn-apply-coupon" data-style="zoom-in" data-spinner-size="40">
                <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_apply' ) ?></span><span class="spinner"></span>
            </button>
        <?php endif ?>
        <div class="ab-label-error ab-coupon-error"></div>
    </div>
<?php endif ?>

<div class="ab-payment-nav">
    <div class="ab-row"><?php echo $info_text ?></div>
    <?php if ( $pay_local ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="local"/>
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_locally' ) ?></span>
            </label>
        </div>
    <?php endif ?>

    <?php if ( $pay_paypal ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="paypal"/>
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_paypal' ) ?></span>
                <img src="<?php echo plugins_url( 'frontend/resources/images/paypal.png', \Bookly\Lib\Plugin::getMainFile() ) ?>" alt="PayPal" />
            </label>
            <?php if ( $payment['gateway'] == Bookly\Lib\Entities\Payment::TYPE_PAYPAL && $payment['status'] == 'error' ) : ?>
                <div class="ab-label-error"><?php echo $payment['data'] ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ( $pay_authorizenet ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="card" data-form="authorizenet" />
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_ccard' ) ?></span>
                <img src="<?php echo $url_cards_image ?>" alt="cards" />
            </label>
            <form class="bookly-authorizenet" style="display: none; margin-top: 15px;">
                <?php include '_card_payment.php' ?>
            </form>
        </div>
    <?php endif ?>

    <?php if ( $pay_stripe ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="card" data-form="stripe" />
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_ccard' ) ?></span>
                <img src="<?php echo $url_cards_image ?>" alt="cards" />
            </label>
            <?php if ( get_option( 'bookly_pmt_stripe_publishable_key' ) != '' ) : ?>
                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
            <?php endif ?>
            <form class="bookly-stripe" style="display: none; margin-top: 15px;">
                <input type="hidden" id="publishable_key" value="<?php echo get_option( 'bookly_pmt_stripe_publishable_key' ) ?>">
                <?php include '_card_payment.php' ?>
            </form>
        </div>
    <?php endif ?>

    <?php if ( $pay_2checkout ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="2checkout"/>
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_ccard' ) ?></span>
                <img src="<?php echo $url_cards_image ?>" alt="cards" />
            </label>
        </div>
    <?php endif ?>

    <?php if ( $pay_payu_latam ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="payu_latam"/>
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_ccard' ) ?></span>
                <img src="<?php echo $url_cards_image ?>" alt="cards" />
            </label>
            <?php if ( $payment['gateway'] == Bookly\Lib\Entities\Payment::TYPE_PAYULATAM && $payment['status'] == 'error' ) : ?>
                <div class="ab-label-error" style="padding-top: 5px;">* <?php echo $payment['data'] ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>
    <div class="ab-row ab-list" style="display: none">
        <input type="radio" class="ab-coupon-free" name="payment-method-<?php echo $form_id ?>" value="coupon" />
    </div>

    <?php if ( $pay_payson ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="payson"/>
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_ccard' ) ?></span>
                <img src="<?php echo $url_cards_image ?>" alt="cards" />
            </label>
            <?php if ( $payment['gateway'] == Bookly\Lib\Entities\Payment::TYPE_PAYSON && $payment['status'] == 'error' ) : ?>
                <div class="ab-label-error" style="padding-top: 5px;">* <?php echo $payment['data'] ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ( $pay_mollie ) : ?>
        <div class="ab-row ab-list">
            <label>
                <input type="radio" class="ab-payment" name="payment-method-<?php echo $form_id ?>" value="mollie"/>
                <span><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_mollie' ) ?></span>
                <img src="<?php echo plugins_url( 'frontend/resources/images/mollie.png', \Bookly\Lib\Plugin::getMainFile() ) ?>" alt="Mollie" />
            </label>
            <?php if ( $payment['gateway'] == Bookly\Lib\Entities\Payment::TYPE_MOLLIE && $payment['status'] == 'error' ) : ?>
                <div class="ab-label-error" style="padding-top: 5px;">* <?php echo $payment['data'] ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>
    <?php do_action( 'bookly_render_payment_gateway_selector', $form_id, $payment ) ?>
</div>

<?php if ( $pay_local ) : ?>
    <div class="bookly-gateway-buttons pay-local ab-row ab-nav-steps">
        <button class="ab-left ab-back-step ab-btn ladda-button" data-style="zoom-in"  data-spinner-size="40">
            <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ) ?></span>
        </button>
        <button class="ab-right ab-next-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
            <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_next' ) ?></span>
        </button>
    </div>
<?php endif ?>

<?php if ( $pay_paypal ) : ?>
    <div class="bookly-gateway-buttons pay-paypal ab-row ab-nav-steps" style="display:none">
        <?php Bookly\Lib\Payment\PayPal::renderForm( $form_id, $response_url ) ?>
    </div>
<?php endif ?>

<?php if ( $pay_2checkout ) : ?>
    <div class="bookly-gateway-buttons pay-2checkout ab-row ab-nav-steps" style="display:none">
        <?php Bookly\Lib\Payment\TwoCheckout::renderForm( $form_id, $response_url ) ?>
    </div>
<?php endif ?>

<?php if ( $pay_payu_latam ) : ?>
    <div class="bookly-gateway-buttons pay-payu_latam ab-row ab-nav-steps" style="display:none">
        <?php Bookly\Lib\Payment\PayuLatam::renderForm( $form_id, $response_url ) ?>
    </div>
<?php endif ?>

<?php if ( $pay_authorizenet || $pay_stripe ) : ?>
    <div class="bookly-gateway-buttons pay-card ab-row ab-nav-steps" style="display:none">
        <button class="ab-left ab-back-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
            <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ) ?></span>
        </button>
        <button class="ab-right ab-next-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
            <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_next' ) ?></span>
        </button>
    </div>
<?php endif ?>

<?php if ( $pay_payson ) : ?>
    <div class="bookly-gateway-buttons pay-payson ab-row ab-nav-steps" style="display:none">
        <?php Bookly\Lib\Payment\Payson::renderForm( $form_id, $response_url ) ?>
    </div>
<?php endif ?>

<?php if ( $pay_mollie ) : ?>
    <div class="bookly-gateway-buttons pay-mollie ab-row ab-nav-steps" style="display:none">
        <?php Bookly\Lib\Payment\Mollie::renderForm( $form_id, $response_url ) ?>
    </div>
<?php endif ?>

<?php do_action( 'bookly_render_payment_gateway', $form_id, $response_url ) ?>

<div class="bookly-gateway-buttons pay-coupon ab-row ab-nav-steps" style="display: none">
    <button class="ab-left ab-back-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
        <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ) ?></span>
    </button>
    <button class="ab-right ab-next-step ab-coupon-payment ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
        <span class="ladda-label"><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_next' ) ?></span>
    </button>
</div>
