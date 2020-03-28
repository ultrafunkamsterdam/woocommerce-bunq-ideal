<?php
/*
 * Plugin Name: Bunq iDeal Payment Gateway
 * Plugin URI: https://github.com/ultrafunkamsterdam
 * Description: Accept iDeal Payments on your Woocoommerce store
 * Author: Ultrafunkamsterdam
 * Author URI: https://github.com/ultrafunkamsterdam
 * Version: 1.0.0
 */
defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_payment_gateways', 'bunq_ideal_gateway_add' );
function bunq_ideal_gateway_add( $methods )
{
        $methods[ ] = 'WC_Gateway_Bunq_Ideal';
        return $methods;
}


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bunq_ideal_gateway_options_link' );
function bunq_ideal_gateway_options_link( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '">' . __( 'Settings', 'bunq-ideal-gateway' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}


add_action( 'plugins_loaded', 'bunq_ideal_gateway_init' );
function bunq_ideal_gateway_init()
{
        class WC_Gateway_Bunq_Ideal extends WC_Payment_Gateway
        {
                /**
                 * Class constructor
                 */
                public function __construct()
                {
                        $this->id   = 'bunq-ideal-gateway';
                        $this->icon = "data:image/png;base64,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";
                        $this->init_form_fields();
                        $this->init_settings();
                        $this->title       = $this->get_option( 'title' );
                        $this->description = $this->get_option( 'description' );
                        $this->bunqme_url  = $this->get_option( 'bunqme_url' );
                        $this->enabled     = $this->get_option( 'enabled' );
                        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
                                 &$this,
                                'process_admin_options' 
                        ) );
                        add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array(
                                 &$this,
                                'check_ipn' 
                        ) );
                        
                        if ( empty( $this->bunqme_url ) === true ) {
                                add_action( 'admin_notices', function()
                                {
?>
			    <div class="notice notice-error">
				<p><?php
                                        _e( 'your bunq.me username is not specified. The payment gateway will not work until this is fixed', 'bunq-ideal' );
				   ?>
			       </p>
			    </div>
			    <?php
                               }  ); //add_action
                        } // if 
                }
                /**
                 * 
                 */
                public function init_form_fields()
                {
                        $this->form_fields = array(
                                 'enabled' => array(
                                         'title' => 'Enable/Disable',
                                        'label' => 'Enable Bunq Ideal Gateway',
                                        'type' => 'checkbox',
                                        'description' => '',
                                        'default' => 'no' 
                                ),
                                'title' => array(
                                         'title' => 'Title',
                                        'type' => 'text',
                                        'description' => 'This controls the title which the user sees during checkout.',
                                        'default' => 'iDeal',
                                      
                                ),
                                'description' => array(
                                         'title' => 'Description',
                                        'type' => 'textarea',
                                        'description' => 'This controls the description which the user sees during checkout.',
                                        'default' => 'Pay safe and secure using iDeal' 
                                ),
                                'bunqme_url' => array(
                                         'title' => 'Bunq.me url',
                                        'type' => 'text',
                                        'description' => 'This should be your personal bunq.me link',
                                        'default' => 'https://bunq.me/....',
                                    
                                ) 
                        );
                }
                /**
                 * 
                 */
                public function payment_fields()
                {
                        if ( $this->description ) {
                                echo wpautop( wp_kses_post( $this->description ) );
                        }
                        $this->payment_scripts();
                }
                /*
                 * Custom CSS and JS to show bank selection box on checkout page
                 */
                public function payment_scripts()
                {
			?>
		    <script>
			function getIdealIssuers() {
			    jQuery.ajax({
				type: 'GET',
				url: 'https://api.bunq.me/v1/bunqme-merchant-directory-ideal',
				beforeSend: function(xhr) {
				    xhr.setRequestHeader("X-Bunq-Client-Request-Id", guid());
				    xhr.setRequestHeader("X-Bunq-Geolocation", "0 0 0 0 NL");	
				    xhr.setRequestHeader("X-Bunq-Language", "en_US");	
				    xhr.setRequestHeader("X-Bunq-Region", "en_US");	
				},
				success: function(issuerData) {
				    idealIssuers = issuerData['Response'][0]['IdealDirectory']['country'][0]['issuer'];
				    jQuery.each(idealIssuers, function(key, value) {   
					jQuery('#idealIssuer').append(jQuery("<option></option>")
					    .attr("value",value.bic)
					    .text(value.name)); 
				    });
	//                             $('#idealIssuer').show()
				}
			    });
			}

			function guid() {
			    function s4() {
				return Math.floor((1 + Math.random()) * 0x10000)
				    .toString(16)
				    .substring(1);
				}
			    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
	    		}
                </script>

                <div id="iDEAL" style="display:flex; width:100%; padding:1em; flex-direction:row; align-items:center">
                    <b>Bank: </b> 
                    <select name="idealIssuer" id="idealIssuer" style="padding:5px; font-weight:bold; color:rgba(255,255,255,.9);">
                        <option value="" disabled="" selected="">- - - - - - SELECT- - - - - -</option>
                    </select>
                    <br/>
                </div>
				<script>getIdealIssuers();</script>
    <?php
                }
                /*
                 * 
                 */
                public function validate_fields()
                {
                }
                /*
                 * We're processing the payments here
                 */
                public function process_payment( $order_id )
                {
                        if ( empty( $_POST[ 'idealIssuer' ] ) || "EUR" !== get_woocommerce_currency() ) {
                                return;
                        }
                        global $woocommerce;
                        $order      = wc_get_order( $order_id );
                        $amount     = number_format( $order->get_total(), 8, '.', '' );
                        $issuer     = sanitize_text_field( $_POST[ 'idealIssuer' ] );
                        $ideal_ref  = esc_html( get_bloginfo( 'name' ) ) . "-" . strval( $order_id );
                        $ideal_link = $this->generate_ideal_link( $amount, $ideal_ref, $issuer );
                        $order->update_status( 'on-hold', __( 'awaiting ideal payment completed', 'bunq-ideal-gateway' ) );
                        if ( !empty( $ideal_link ) ) {
                                return array(
                                         'result' => 'success',
                                        'redirect' => $ideal_link 
                                );
                        }
                        return array(
                                 'result' => 'fail',
                                'redirect' => '' 
                        );
                }
                /*
                 * 
                 */
                public function webhook()
                {
                }
                public function generate_ideal_link( $amount, $desc, $issuer )
                {
                        function httpPost( $url, $headers, $postData )
                        {
                                $ch = curl_init( $url );
                                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                                curl_setopt( $ch, CURLINFO_HEADER_OUT, true );
                                curl_setopt( $ch, CURLOPT_POST, true );
                                curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
                                // HTTP Headers for POST request 
                                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
                                $response = curl_exec( $ch );
                                $jsonData = json_decode( $response, true );
                                if ( empty( $response ) OR ( curl_getinfo( $ch, CURLINFO_HTTP_CODE == 500 ) ) ) {
                                        die( curl_error( $ch ) );
                                        curl_close( $ch );
                                        return false;
                                } else if ( isset( $jsonData[ 'Error' ] ) ) {
                                        var_dump( $jsonData );
                                        die( 'Error in response' );
                                        return false;
                                }
                                curl_close( $ch );
                                return $jsonData;
                        }
                        function httpGet( $url, $headers )
                        {
                                $ch = curl_init( $url );
                                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                                curl_setopt( $ch, CURLINFO_HEADER_OUT, true );
                                // HTTP Headers for GET request 
                                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
                                $response = curl_exec( $ch );
                                $jsonData = json_decode( $response, true );
                                if ( empty( $response ) OR ( curl_getinfo( $ch, CURLINFO_HTTP_CODE == 500 ) ) ) {
                                        die( curl_error( $ch ) );
                                        curl_close( $ch );
                                        return false;
                                } else if ( isset( $jsonData[ 'Error' ] ) ) {
                                        var_dump( $jsonData );
                                        die( 'Error in response' );
                                        return false;
                                }
                                curl_close( $ch );
                                return $jsonData;
                        }
                        function getBunqMeMerchantRequest( $url )
                        {
                                // HTTP Headers for GET request 
                                $headers = array(
                                         'Content-Type: application/json',
                                        'Content-Length: 0',
                                        'X-Bunq-Client-Request-Id: ' . uniqid(),
                                        'X-Bunq-Geolocation: 0 0 0 0 NL',
                                        'X-Bunq-Language: en_US',
                                        'X-Bunq-Region: en_US' 
                                );
                                $ch      = curl_init( $url );
                                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                                curl_setopt( $ch, CURLINFO_HEADER_OUT, true );
                                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
                                $response = curl_exec( $ch );
                                $jsonData = json_decode( $response, true );
                                if ( empty( $response ) OR ( curl_getinfo( $ch, CURLINFO_HTTP_CODE == 500 ) ) ) {
                                        die( curl_error( $ch ) );
                                        curl_close( $ch );
                                        return false;
                                } else if ( isset( $jsonData[ 'Error' ] ) ) {
                                        var_dump( $jsonData );
                                        die( 'Error in response' );
                                        return false;
                                }
                                curl_close( $ch );
                                return $jsonData;
                        }
                        function getBunqMeQrCode( $amount, $description )
                        {
                                // POST data
                                $postArray            = array(
                                         'amount' => array(
                                                 'currency' => 'EUR',
                                                'value' => $amount 
                                        ),
                                        'description' => $description 
                                );
                                $postData             = json_encode( $postArray );
                                // HTTP Headers for GET request 
                                $headers              = array(
                                         'Content-Type: application/json',
                                        'Content-Length: ' . strlen( $postData ),
                                        'X-Bunq-Client-Request-Id: ' . uniqid(),
                                        'X-Bunq-Geolocation: 0 0 0 0 NL',
                                        'X-Bunq-Language: en_US',
                                        'X-Bunq-Region: en_US' 
                                );
                                $requestUuidQrCode    = httpPost( 'https://api.bunq.me/v1/bunqme-fundraiser-profile/' . $GLOBALS[ 'bunqMeUuid' ] . '/qr-code-content', $headers, $postData );
                                $QrUuid               = $requestUuidQrCode[ 'Response' ][ 0 ][ 'Uuid' ][ 'uuid' ];
                                $getHeaders           = array(
                                         'Content-Type: application/json',
                                        'Content-Length: 0',
                                        'X-Bunq-Client-Request-Id: ' . uniqid(),
                                        'X-Bunq-Geolocation: 0 0 0 0 NL',
                                        'X-Bunq-Language: en_US',
                                        'X-Bunq-Region: en_US' 
                                );
                                $requestQrCodeContent = httpGet( 'https://api.bunq.me/v1/bunqme-fundraiser-profile/' . $GLOBALS[ 'bunqMeUuid' ] . '/qr-code-content/' . $QrUuid, $getHeaders );
                                $base64QrCode         = $requestQrCodeContent[ 'Response' ][ 0 ][ 'QrCodeImage' ][ 'base64' ];
                                $bunqToken            = $requestQrCodeContent[ 'Response' ][ 0 ][ 'QrCodeImage' ][ 'token' ];
                                $response[ 0 ]        = array(
                                         'base64QrCode' => $base64QrCode,
                                        'bunqToken' => $bunqToken 
                                );
                                return $response[ 0 ];
                        }
                        function generateIdealUrl( $bunqmeUrl, $amount, $description, $issuer )
                        {
                                $bunqMeRequestUuid = getBunqMe( $bunqmeUrl, $amount, $description, $issuer );
                                if ( $issuer == 'BUNQNL2A' ) {
                                        $bunqQrCode    = getBunqMeQrCode( $amount, $description );
                                        $response[ 0 ] = array(
                                                 'url' => '',
                                                'bunqQrCode' => $bunqQrCode[ 'base64QrCode' ],
                                                'bunqToken' => $bunqQrCode[ 'bunqToken' ] 
                                        );
                                } else {
                                        $reqCount = 0;
                                        do {
                                                $reqCount++;
                                                $merchantRequest = getBunqMeMerchantRequest( 'https://api.bunq.me/v1/bunqme-merchant-request/' . $bunqMeRequestUuid . '?_=' . uniqid() );
                                                $status          = $merchantRequest[ 'Response' ][ 0 ][ 'BunqMeMerchantRequest' ][ 'status' ];
                                        } while ( $status == 'PAYMENT_WAITING_FOR_CREATION' && $reqCount < 10 );
                                        $merchantRequest = getBunqMeMerchantRequest( 'https://api.bunq.me/v1/bunqme-merchant-request/' . $bunqMeRequestUuid . '?_=' . uniqid() );
                                        $issuerUrl       = $merchantRequest[ 'Response' ][ 0 ][ 'BunqMeMerchantRequest' ][ 'issuer_authentication_url' ];
                                        $response[ 0 ]   = array(
                                                 'url' => $issuerUrl,
                                                'bunqQrCode' => '',
                                                'bunqToken' => '' 
                                        );
                                }
                                return $response[ 0 ];
                        }
                        function getBunqMe( $bunqmeUrl, $amount, $description, $issuer )
                        {
                                // POST data
                                $postArray               = array(
                                         'pointer' => [
                                                 'type' => 'URL',
                                                'value' => $bunqmeUrl 
                                        ]
                                );
                                $postData                = json_encode( $postArray );
                                $headers                 = array(
                                         'Content-Type: application/json',
                                        'Content-Length: ' . strlen( $postData ),
                                        'X-Bunq-Client-Request-Id: ' . uniqid(),
                                        'X-Bunq-Geolocation: 0 0 0 0 NL',
                                        'X-Bunq-Language: en_US',
                                        'X-Bunq-Region: en_US' 
                                );
                                $jsonData                = httpPost( 'https://api.bunq.me/v1/bunqme-fundraiser-profile', $headers, $postData );
                                $bunqmeUuid              = $jsonData[ 'Response' ][ 0 ][ 'BunqMeFundraiserProfile' ][ 'uuid' ];
                                $GLOBALS[ 'bunqMeUuid' ] = $bunqmeUuid;
                                if ( $issuer == 'BUNQNL2A' ) {
                                        return true;
                                } else {
                                        return getRequestUuid( $bunqmeUuid, $amount, $description, $issuer );
                                }
                        }
                        function getRequestUuid( $bunqmeUuid, $amount, $description, $issuer )
                        {
                                // POST data
                                $postArray = array(
                                         'amount_requested' => [
                                                 'currency' => 'EUR',
                                                'value' => $amount 
                                        ],
                                        'issuer' => $issuer,
                                        'merchant_type' => 'IDEAL',
                                        'bunqme_type' => 'FUNDRAISER',
                                        'bunqme_uuid' => $bunqmeUuid,
                                        'description' => $description 
                                );
                                $postData  = json_encode( $postArray );
                                $headers   = array(
                                         'Content-Type: application/json',
                                        'Content-Length: ' . strlen( $postData ),
                                        'X-Bunq-Client-Request-Id: ' . uniqid(),
                                        'X-Bunq-Geolocation: 0 0 0 0 NL',
                                        'X-Bunq-Language: en_US',
                                        'X-Bunq-Region: en_US' 
                                );
                                $jsonData  = httpPost( 'https://api.bunq.me/v1/bunqme-merchant-request', $headers, $postData );
                                return $jsonData[ 'Response' ][ 0 ][ 'BunqMeMerchantRequest' ][ 'uuid' ];
                        }
                        $rv = generateIdealUrl( $this->bunqme_url, $amount, $desc, $issuer );
                        return $v[ 'url' ];
                }
        }
}




