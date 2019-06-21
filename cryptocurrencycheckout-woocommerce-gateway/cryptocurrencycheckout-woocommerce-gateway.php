<?php
/**
 * Plugin Name: CryptocurrencyCheckout WooCommerce Gateway
 * Plugin URI: https://cryptocurrencycheckout.com/
 * Description: Connects your WooCommerce Store Checkout to the CryptocurrencyCheckout Payment Gateway so you can start accepting Cryptocurrencies like Bitcoin, Ethereum, Dash, Litecoin and more for free. 
 * Author: CryptocurrencyCheckout
 * Author URI: https://cryptocurrencycheckout.com/
 * Version: 1.0.0
 * Text Domain: cryptocurrencycheckout-wc-gateway
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2018-2019 CryptocurrencyCheckout (support@cryptocurrencycheckout.com) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-CryptocurrencyCheckout-Gateway
 * @author    CryptocurrencyCheckout
 * @category  Admin
 * @copyright Copyright (c) 2018-2019 CryptocurrencyCheckout and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * This cryptocurrencycheckout gateway forks the WooCommerce core "Cheque" payment gateway to create another cryptocurrencycheckout payment method.
 */
 
defined( 'ABSPATH' ) or exit;


// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}


/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + cryptocurrencycheckout gateway
 */
function cryptocurrencycheckout_add_to_gateways( $gateways ) {
	$gateways[] = 'CryptocurrencyCheckout_WC_Gateway';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'cryptocurrencycheckout_add_to_gateways' );


/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function cryptocurrencycheckout_gateway_plugin_links( $links ) {

	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=cryptocurrencycheckout_gateway' ) . '">' . __( 'Configure', 'cryptocurrencycheckout-wc-gateway' ) . '</a>'
	);

	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cryptocurrencycheckout_gateway_plugin_links' );


/**
 * CryptocurrencyCheckout Payment Gateway
 *
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		CryptocurrencyCheckout_WC_Gateway
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		SkyVerge
 */
add_action( 'plugins_loaded', 'cryptocurrencycheckout_gateway_init', 11 );

function cryptocurrencycheckout_gateway_init() {

	class CryptocurrencyCheckout_WC_Gateway extends WC_Payment_Gateway {

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
	  
			$this->id                 = 'cryptocurrencycheckout_gateway';
			$this->icon               = apply_filters('woocommerce_cryptocurrencycheckout_icon', '');
			$this->has_fields         = false;
			$this->method_title       = __( 'CryptocurrencyCheckout', 'cryptocurrencycheckout-wc-gateway' );
			$this->method_description = __( 'Connects your WooCommerce Store Checkout to the CryptocurrencyCheckout Payment Gateway so you can start accepting Cryptocurrencies like Bitcoin, Ethereum, Dash, Litecoin and more for free.', 'cryptocurrencycheckout-wc-gateway' );
		  
			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();
		  
			// Define user set variables
			$this->title 			= $this->get_option( 'title' );
			$this->redirect 		= $this->get_option( 'redirect' );
			$this->payNow 			= $this->get_option( 'payNow' );
			$this->StoreName 		= $this->get_option( 'StoreName' );
			$this->StoreID 			= $this->get_option( 'StoreID' );
			$this->ConnectionID 	= $this->get_option( 'ConnectionID' );
			$this->APIToken 		= $this->get_option( 'APIToken' );

			$this->btcAddress 		= $this->get_option( 'btcAddress' );
			$this->ethAddress 		= $this->get_option( 'ethAddress' );
			$this->ltcAddress 		= $this->get_option( 'ltcAddress' );
			$this->dashAddress 		= $this->get_option( 'dashAddress' );
			$this->sendAddress 		= $this->get_option( 'sendAddress' );
			$this->cdzcAddress 		= $this->get_option( 'cdzcAddress' );

		  
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		  
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}
	
	
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
	  
			$this->form_fields = apply_filters( 'cryptocurrencycheckout_form_fields', array(
		  
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'cryptocurrencycheckout-wc-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable CryptocurrencyCheckout', 'cryptocurrencycheckout-wc-gateway' ),
					'default' => 'yes'
				),

				'redirect' => array(
					'title'   => __( 'Auto Redirect:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Automatically Redirects Customer to CryptocurrencyCheckout to pay after placing order.', 'cryptocurrencycheckout-wc-gateway' ),
					'default' => 'no'
				),

				'title' => array(
					'title'       => __( 'Title', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'cryptocurrencycheckout-wc-gateway' ),
					'default'     => __( 'CryptocurrencyCheckout', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),
				
				'payNow' => array(
					'title'       => __( 'Pay Now Button Text:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'This field allows you to edit the text displayed on your Pay Now Button. Examples: Pay Now, Pay with Bitcoin, Pay with Cryptocurrency.', 'cryptocurrencycheckout-wc-gateway' ),
					'default'     => __( 'Pay with CryptocurrencyCheckout', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				
				'StoreName' => array(
					'title'       => __( 'Unique Store Identifier:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your CryptocurrencyCheckout Unique Store Identifier (Automatically Generated in CryptocurrencyCheckout Dashboard, Installation Scripts, Woocommerce Section.)', 'cryptocurrencycheckout-wc-gateway' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),
				
				'StoreID' => array(
					'title'       => __( 'Store ID:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your CryptocurrencyCheckout Store ID (Automatically Generated in CryptocurrencyCheckout Dashboard, Installation Scripts, Woocommerce Section.)', 'cryptocurrencycheckout-wc-gateway' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ConnectionID' => array(
					'title'       => __( 'Connection ID:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your CryptocurrencyCheckout Connection ID (Automatically Generated in CryptocurrencyCheckout Dashboard, Installation Scripts, Woocommerce Section.)' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'btcAddress' => array(
					'title'       => __( 'BTC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Bitcoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ethAddress' => array(
					'title'       => __( 'ETH Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Ethereum Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ltcAddress' => array(
					'title'       => __( 'LTC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Litecoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'dashAddress' => array(
					'title'       => __( 'DASH Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Dash Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'sendAddress' => array(
					'title'       => __( 'SEND Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Social Send Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'cdzcAddress' => array(
					'title'       => __( 'CDZC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your CryptoDezireCash Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'APIToken' => array(
					'title'       => __( 'API Token Keys:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'textarea',
					'description' => __( 'Enter your CryptocurrencyCheckout API Token Keys (Generated in CryptocurrencyCheckout Dashboard, API Keys Section)' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				
			) );
		}
	
	
		/**
		 * Output for the order received page.
		 */

		public function thankyou_page( $order_id ) {

			$order = wc_get_order( $order_id );

			// POST Fields
			$url = 'https://cryptocurrencycheckout.com/validation';
			$postfields = array();
			$postfields['CC_STORE_NAME'] = $this->StoreName;
			$postfields['CC_STORE_ID'] = $this->StoreID;
			$postfields['CC_CONNECTION_ID'] = $this->ConnectionID;
			$postfields['CC_API_TOKEN'] = $this->APIToken;
			$postfields['CC_ORDER_ID'] = $order->id;
			$postfields['CC_GRANDTOTAL'] = $order->get_total();
			$postfields['CC_BTC_ADDRESS'] = $this->btcAddress;
			$postfields['CC_ETH_ADDRESS'] = $this->ethAddress;
			$postfields['CC_LTC_ADDRESS'] = $this->ltcAddress;
			$postfields['CC_DASH_ADDRESS'] = $this->dashAddress;
			$postfields['CC_SEND_ADDRESS'] = $this->sendAddress;
			$postfields['CC_CDZC_ADDRESS'] = $this->cdzcAddress;

			// perform a click action on the submit button of the form you are going to return

			if ( $this->redirect == 'yes' ) {
			wc_enqueue_js( 'jQuery( "#submit-form" ).click();' );
			}

			$htmlOutput = '<form method="POST" action="' . $url . '">';
			foreach ($postfields as $k => $v) {
				$htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
			}
			$htmlOutput .= '<input type="submit" id="submit-form" value="' . $this->payNow . '">';
			$htmlOutput .= '</form>';
			
			echo $htmlOutput;
			
		}
	
	
		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
	
			$order = wc_get_order( $order_id );
			
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', __( 'Awaiting cryptocurrencycheckout payment', 'cryptocurrencycheckout-wc-gateway' ) );
			
			// Reduce stock levels
			$order->reduce_order_stock();
			
			// Remove cart
			WC()->cart->empty_cart();
			
			// Return thankyou redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> $this->get_return_url( $order )
			);
		}
	
  } // end \CryptocurrencyCheckout_WC_Gateway class
}
