<?php
namespace Bookly\Lib\Payment;

use Bookly\Lib;

/**
 * Class PayPal
 * @package Bookly\Lib\Payment
 */
class PayPal
{
    // Array for cleaning PayPal request
    static public $remove_parameters = array( 'action', 'ab_fid', 'error_msg', 'token', 'PayerID',  'type' );

    /**
     * The array of products for checkout
     *
     * @var array
     */
    protected $products = array();

    /**
     * Send the Express Checkout NVP request
     *
     * @param $form_id
     * @throws \Exception
     */
    public function send_EC_Request( $form_id )
    {
        if ( !session_id() ) {
            @session_start();
        }

        // create the data to send on PayPal
        $data = array(
            'SOLUTIONTYPE' => 'Sole',
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE'  => get_option( 'bookly_pmt_currency' ),
            'NOSHIPPING' => 1,
            'RETURNURL'  => add_query_arg( array( 'action' => 'ab-paypal-return', 'ab_fid' => $form_id ), Lib\Utils\Common::getCurrentPageURL() ),
            'CANCELURL'  => add_query_arg( array( 'action' => 'ab-paypal-cancel', 'ab_fid' => $form_id ), Lib\Utils\Common::getCurrentPageURL() )
        );
        $total = 0;
        foreach ( $this->products as $index => $product ) {
            $data[ 'L_PAYMENTREQUEST_0_NAME' . $index ] = $product->name;
            $data[ 'L_PAYMENTREQUEST_0_AMT' . $index ]  = $product->price;
            $data[ 'L_PAYMENTREQUEST_0_QTY' . $index ]  = $product->qty;

            $total += ( $product->qty * $product->price );
        }
        $data['PAYMENTREQUEST_0_AMT']     = $total;
        $data['PAYMENTREQUEST_0_ITEMAMT'] = $total;

        // send the request to PayPal
        $response = $this->sendNvpRequest( 'SetExpressCheckout', $data );

        // Respond according to message we receive from PayPal
        if ( 'SUCCESS' == strtoupper( $response['ACK'] ) || 'SUCCESSWITHWARNING' == strtoupper( $response['ACK'] ) ) {
            $paypalurl = 'https://www' . ( get_option( 'bookly_pmt_paypal_sandbox' ) ? '.sandbox' : '' ) . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=' . urldecode( $response['TOKEN'] );
            header( 'Location: ' . $paypalurl );
            exit;
        } else {
            header( 'Location: ' . wp_sanitize_redirect( add_query_arg( array( 'action' => 'ab-paypal-error', 'ab_fid' => $form_id, 'error_msg' => urlencode( $response['L_LONGMESSAGE0'] ) ), Lib\Utils\Common::getCurrentPageURL() ) ) );
            exit;
        }
    }

    /**
     * Send the NVP Request to the PayPal
     *
     * @param       $method
     * @param array $data
     * @return array
     */
    public function sendNvpRequest( $method, array $data )
    {
        $url  = 'https://api-3t' . ( get_option( 'bookly_pmt_paypal_sandbox' ) ? '.sandbox' : '' ) . '.paypal.com/nvp';

        $curl = new Lib\Curl\Curl();
        $curl->options['CURLOPT_SSL_VERIFYPEER'] = false;
        $curl->options['CURLOPT_SSL_VERIFYHOST'] = false;

        $data['METHOD']    = $method;
        $data['VERSION']   = '76.0';
        $data['USER']      = get_option( 'bookly_pmt_paypal_api_username' );
        $data['PWD']       = get_option( 'bookly_pmt_paypal_api_password' );
        $data['SIGNATURE'] = get_option( 'bookly_pmt_paypal_api_signature' );

        $httpResponse = $curl->post( $url, $data );
        if ( ! $httpResponse ) {
            exit( $curl->error() );
        }

        // Extract the response details.
        parse_str( $httpResponse, $PayPalResponse );

        if ( ! array_key_exists( 'ACK', $PayPalResponse ) ) {
            exit( 'Invalid HTTP Response for POST request to ' . $url );
        }

        return $PayPalResponse;
    }

    public static function renderForm( $form_id, $response_url )
    {
        $replacement = array(
            '%form_id%' => $form_id,
            '%gateway%' => Lib\Entities\Payment::TYPE_PAYPAL,
            '%response_url%' => $response_url,
            '%back%'    => Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ),
            '%next%'    => Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_next' ),
        );
        $form = '<form method="post" class="ab-%gateway%-form">
                <input type="hidden" name="ab_fid" value="%form_id%"/>
                <input type="hidden" name="response_url" value="%response_url%"/>
                <input type="hidden" name="action" value="ab-paypal-express-checkout"/>
                <button class="ab-left ab-back-step ab-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40"><span class="ladda-label">%back%</span></button>
                <button class="ab-right ab-next-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40"><span class="ladda-label">%next%</span></button>
             </form>';
        echo strtr( $form, $replacement );
    }

    /**
     * Add the Product for payment
     *
     * @param \stdClass $product
     */
    public function addProduct( \stdClass $product )
    {
        $this->products[] = $product;
    }

}