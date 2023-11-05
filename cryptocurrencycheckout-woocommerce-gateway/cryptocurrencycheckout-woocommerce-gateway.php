<?php
/**
 * Plugin Name: CryptocurrencyCheckout WooCommerce Gateway
 * Plugin URI: https://cryptocurrencycheckout.com/
 * Description: Connects your WooCommerce Store Checkout to the CryptocurrencyCheckout Payment Gateway so you can start accepting Cryptocurrencies like Bitcoin, Ethereum, Dash, Litecoin and more for free. 
 * Version: 2.0.19
 * Author: cryptocurrencycheckout
 * Text Domain: cryptocurrencycheckout-wc-gateway
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2018-2023 CryptocurrencyCheckout (support@cryptocurrencycheckout.com) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-CryptocurrencyCheckout-Gateway
 * @author    CryptocurrencyCheckout
 * @category  Admin
 * @copyright Copyright (c) 2018-2020 CryptocurrencyCheckout and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
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
 * Provides a Payment Gateway Connection to CryptocurrencyCheckout.com; where buyers can make payment in multiple Cryptocurrencies. 
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		CryptocurrencyCheckout_WC_Gateway
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @package		WooCommerce/Classes/Payment
 * @author 		CryptocurrencyCheckout
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
			$this->emailButton		= $this->get_option( 'emailButton' );
			$this->Instructions 	= $this->get_option( 'Instructions' );
			$this->APIToken 		= $this->get_option( 'APIToken' );

			$this->btcAddress 		= $this->get_option( 'btcAddress' );
			$this->ethAddress 		= $this->get_option( 'ethAddress' );
			$this->ltcAddress 		= $this->get_option( 'ltcAddress' );
			$this->dashAddress 		= $this->get_option( 'dashAddress' );
			$this->sendAddress 		= $this->get_option( 'sendAddress' );
			$this->cdzcAddress 		= $this->get_option( 'cdzcAddress' );
			$this->arrrAddress 		= $this->get_option( 'arrrAddress' );
			$this->colxAddress 		= $this->get_option( 'colxAddress' );
			$this->znzAddress 		= $this->get_option( 'znzAddress' );
			$this->thcAddress 		= $this->get_option( 'thcAddress' );
			$this->ecaAddress 		= $this->get_option( 'ecaAddress' );
			$this->pivxAddress 		= $this->get_option( 'pivxAddress' );
			$this->nbrAddress 		= $this->get_option( 'nbrAddress' );
			$this->galiAddress 		= $this->get_option( 'galiAddress' );
			$this->bitcAddress 		= $this->get_option( 'bitcAddress' );
			$this->okAddress 		= $this->get_option( 'okAddress' );
			$this->arkAddress 		= $this->get_option( 'arkAddress' );
			$this->veilAddress 		= $this->get_option( 'veilAddress' );
			$this->dogeAddress 		= $this->get_option( 'dogeAddress' );
			$this->nbxAddress 		= $this->get_option( 'nbxAddress' );
			$this->xnvAddress 		= $this->get_option( 'xnvAddress' );
			$this->sumoAddress 		= $this->get_option( 'sumoAddress' );
			$this->rpdAddress 		= $this->get_option( 'rpdAddress' );
			$this->telosAddress 	= $this->get_option( 'telosAddress' );
			$this->kmdAddress 		= $this->get_option( 'kmdAddress' );
			$this->vrscAddress 		= $this->get_option( 'vrscAddress' );
			$this->banAddress 		= $this->get_option( 'banAddress' );
			$this->xbtxAddress 		= $this->get_option( 'xbtxAddress' );
			$this->sinAddress 		= $this->get_option( 'sinAddress' );
			$this->xrpAddress 		= $this->get_option( 'xrpAddress' );
			$this->upxAddress 		= $this->get_option( 'upxAddress' );
			$this->adcAddress 		= $this->get_option( 'adcAddress' );
			$this->ritoAddress 		= $this->get_option( 'ritoAddress' );
			$this->birAddress 		= $this->get_option( 'birAddress' );
			$this->axeAddress 		= $this->get_option( 'axeAddress' );
			$this->hushAddress 		= $this->get_option( 'hushAddress' );
			$this->ccyAddress 		= $this->get_option( 'ccyAddress' );
			$this->motaAddress 		= $this->get_option( 'motaAddress' );
			$this->pgoAddress 		= $this->get_option( 'pgoAddress' );
			$this->bitgAddress 		= $this->get_option( 'bitgAddress' );
			$this->grtAddress 		= $this->get_option( 'grtAddress' );
			$this->nulsAddress 		= $this->get_option( 'nulsAddress' );
			$this->audaxAddress 	= $this->get_option( 'audaxAddress' );
			$this->dmsAddress 		= $this->get_option( 'dmsAddress' );
			$this->dapsAddress 		= $this->get_option( 'dapsAddress' );
			$this->idxAddress 		= $this->get_option( 'idxAddress' );
			$this->burqAddress 		= $this->get_option( 'burqAddress' );
			$this->htmlAddress 		= $this->get_option( 'htmlAddress' );
			$this->ghostAddress 	= $this->get_option( 'ghostAddress' );
			$this->fdrAddress 		= $this->get_option( 'fdrAddress' );
			$this->zerAddress 		= $this->get_option( 'zerAddress' );
			$this->btczAddress 		= $this->get_option( 'btczAddress' );
			$this->aliasAddress 	= $this->get_option( 'aliasAddress' );
			$this->xdnAddress 		= $this->get_option( 'xdnAddress' );
			$this->ethoAddress 		= $this->get_option( 'ethoAddress' );
			$this->drgnAddress 		= $this->get_option( 'drgnAddress' );
			$this->signaAddress 	= $this->get_option( 'signaAddress' );
			$this->dvpnAddress 		= $this->get_option( 'dvpnAddress' );
			$this->tubeAddress 		= $this->get_option( 'tubeAddress' );
			$this->tnAddress 		= $this->get_option( 'tnAddress' );
			$this->ergAddress 		= $this->get_option( 'ergAddress' );
			$this->sigusdAddress 	= $this->get_option( 'sigusdAddress' );
			$this->bnjAddress 		= $this->get_option( 'bnjAddress' );
			$this->usdtAddress 		= $this->get_option( 'usdtAddress' );
			$this->eggAddress 		= $this->get_option( 'eggAddress' );
			$this->nlifeAddress 	= $this->get_option( 'nlifeAddress' );
			$this->xhvAddress 		= $this->get_option( 'xhvAddress' );
			$this->xusdAddress 		= $this->get_option( 'xusdAddress' );
			$this->moonshotAddress 	= $this->get_option( 'moonshotAddress' );
			$this->gthAddress 		= $this->get_option( 'gthAddress' );
			$this->hnsAddress 		= $this->get_option( 'hnsAddress' );
			$this->rtmAddress 		= $this->get_option( 'rtmAddress' );
			$this->cdsAddress 		= $this->get_option( 'cdsAddress' );
			$this->xmrAddress 		= $this->get_option( 'xmrAddress' );
			$this->prcyAddress 		= $this->get_option( 'prcyAddress' );
			$this->cashAddress 		= $this->get_option( 'cashAddress' );
			$this->ktsAddress 		= $this->get_option( 'ktsAddress' );
			$this->grlcAddress 		= $this->get_option( 'grlcAddress' );
			$this->primoAddress 	= $this->get_option( 'primoAddress' );
			$this->ccxAddress 		= $this->get_option( 'ccxAddress' );
			$this->bdxAddress 		= $this->get_option( 'bdxAddress' );
			$this->kasAddress 		= $this->get_option( 'kasAddress' );
			$this->mariaAddress 	= $this->get_option( 'mariaAddress' );
			$this->fluxAddress 		= $this->get_option( 'fluxAddress' );
			$this->papryAddress 	= $this->get_option( 'papryAddress' );
			$this->nexaAddress 		= $this->get_option( 'nexaAddress' );
			$this->bchAddress 		= $this->get_option( 'bchAddress' );
			$this->kiiroAddress 	= $this->get_option( 'kiiroAddress' );
			$this->blocxAddress 	= $this->get_option( 'blocxAddress' );

		  
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		  
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}

		
	
		/**
		 * Initialize Gateway Settings Form Fields
		 * This is where the Store will enter all of their CryptocurrencyCheckout Settings.
		 */
		public function init_form_fields() {
	  
			$this->form_fields = apply_filters( 'cryptocurrencycheckout_form_fields', array(
		  
				'enabled' => array(
					'title'   => __( 'Enable/Disable:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable CryptocurrencyCheckout', 'cryptocurrencycheckout-wc-gateway' ),
					'default' => 'yes'
				),

				'redirect' => array(
					'title'   => __( 'Auto Redirect:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Automatically Redirect Customer to CryptocurrencyCheckout to pay after placing an order. (Disable for testing/troubleshooting)', 'cryptocurrencycheckout-wc-gateway' ),
					'default' => 'no'
				),

				'emailButton' => array(
					'title'   => __( 'Email Payment Button:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'This option adds a fallback Pay Now button to the order email, in case the customer needs to attempt to make payment a 2nd time.', 'cryptocurrencycheckout-wc-gateway' ),
					'default' => 'yes'
				),

				'Instructions' => array(
					'title'       => __( 'Email Payment Instructions:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'textarea',
					'description' => __( 'If the above Email Payment Button is enabled these instructions will display in the Order Email, instructing the customer they can attempt to pay again if they failed to pay during checkout.', 'cryptocurrencycheckout-wc-gateway' ),
					'default'     => __( 'If you have not made payment yet, please click the button below to make your crypto payment now.', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'title' => array(
					'title'       => __( 'Title:', 'cryptocurrencycheckout-wc-gateway' ),
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

				'arrrAddress' => array(
					'title'       => __( 'ARRR Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your PirateChain Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'colxAddress' => array(
					'title'       => __( 'COLX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your ColossusXT Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'znzAddress' => array(
					'title'       => __( 'ZNZ Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Zenzo Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'thcAddress' => array(
					'title'       => __( 'THC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your HempCoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ecaAddress' => array(
					'title'       => __( 'ECA Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Electra Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'pivxAddress' => array(
					'title'       => __( 'PIVX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Pivx Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'nbrAddress' => array(
					'title'       => __( 'NBR Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Niobio Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'galiAddress' => array(
					'title'       => __( 'GALI Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Galilel Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'bitcAddress' => array(
					'title'       => __( 'BITC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Bitcash Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'okAddress' => array(
					'title'       => __( 'OK Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your OKcash Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'arkAddress' => array(
					'title'       => __( 'ARK Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Ark Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'veilAddress' => array(
					'title'       => __( 'VEIL Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Veil Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'dogeAddress' => array(
					'title'       => __( 'DOGE Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Dogecoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'nbxAddress' => array(
					'title'       => __( 'NBX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Netbox Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xnvAddress' => array(
					'title'       => __( 'XNV Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Nerva Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'sumoAddress' => array(
					'title'       => __( 'SUMO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Sumokoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'rpdAddress' => array(
					'title'       => __( 'RPD Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Rapids Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'telosAddress' => array(
					'title'       => __( 'TELOS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Transcendence Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'kmdAddress' => array(
					'title'       => __( 'KMD Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Komodo Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'vrscAddress' => array(
					'title'       => __( 'VRSC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Verus Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'banAddress' => array(
					'title'       => __( 'BAN Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Banano Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xbtxAddress' => array(
					'title'       => __( 'XBTX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Bitcoin-Subsidium Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'sinAddress' => array(
					'title'       => __( 'SIN Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Sinovate Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xrpAddress' => array(
					'title'       => __( 'XRP Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Ripple Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'upxAddress' => array(
					'title'       => __( 'UPX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Uplexa Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'adcAddress' => array(
					'title'       => __( 'ADC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Audiocoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ritoAddress' => array(
					'title'       => __( 'RITO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your RitoCoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'birAddress' => array(
					'title'       => __( 'BIR Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Birake Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'axeAddress' => array(
					'title'       => __( 'AXE Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Axe Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'hushAddress' => array(
					'title'       => __( 'HUSH Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Hush Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ccyAddress' => array(
					'title'       => __( 'CCY Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your CCY Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'motaAddress' => array(
					'title'       => __( 'MOTA Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your MotaCoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'pgoAddress' => array(
					'title'       => __( 'PGO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Pengolin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'bitgAddress' => array(
					'title'       => __( 'BITG Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your BitGreen Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'grtAddress' => array(
					'title'       => __( 'GRT Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your GoldenRatioToken Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'nulsAddress' => array(
					'title'       => __( 'NULS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Nuls Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'audaxAddress' => array(
					'title'       => __( 'AUDAX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Audax Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'dmsAddress' => array(
					'title'       => __( 'DMS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Documentchain Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'dapsAddress' => array(
					'title'       => __( 'DAPS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your DapsCoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'idxAddress' => array(
					'title'       => __( 'IDX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your IndexChain Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'burqAddress' => array(
					'title'       => __( 'BURQ Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Al-Buraq Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'htmlAddress' => array(
					'title'       => __( 'HTML Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your HTML Coin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ghostAddress' => array(
					'title'       => __( 'GHOST Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Ghost Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'fdrAddress' => array(
					'title'       => __( 'FDR Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your French Digital Reserve Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'zerAddress' => array(
					'title'       => __( 'ZERO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Zero Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'btczAddress' => array(
					'title'       => __( 'BTCZ Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your BitcoinZ Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'aliasAddress' => array(
					'title'       => __( 'ALIAS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Alias Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xdnAddress' => array(
					'title'       => __( 'XDN Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your DigitalNote Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ethoAddress' => array(
					'title'       => __( 'ETHO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Etho Protocol Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'drgnAddress' => array(
					'title'       => __( 'DRGN Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Dragonchain Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'signaAddress' => array(
					'title'       => __( 'SIGNA Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Signum Network Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'dvpnAddress' => array(
					'title'       => __( 'DVPN Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Sentinel Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'tubeAddress' => array(
					'title'       => __( 'TUBE Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your BitTube Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'tnAddress' => array(
					'title'       => __( 'TN Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Turtle Network Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ergAddress' => array(
					'title'       => __( 'ERG Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Ergo Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'sigusdAddress' => array(
					'title'       => __( 'SIGUSD Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your SigmaUSD Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'bnjAddress' => array(
					'title'       => __( 'BNJ Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Binjit Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'usdtAddress' => array(
					'title'       => __( 'USDT Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Tether Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'eggAddress' => array(
					'title'       => __( 'EGG Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your NestEGG Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'nlifeAddress' => array(
					'title'       => __( 'NLIFE Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Night Life Crypto Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xhvAddress' => array(
					'title'       => __( 'XHV Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Haven Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xusdAddress' => array(
					'title'       => __( 'XUSD Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Haven xUSD Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'moonshotAddress' => array(
					'title'       => __( 'MOONSHOT Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Moonshot Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'gthAddress' => array(
					'title'       => __( 'GTH Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Gather Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'hnsAddress' => array(
					'title'       => __( 'HNS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Handshake Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'rtmAddress' => array(
					'title'       => __( 'RTM Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Raptoreum Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'cdsAddress' => array(
					'title'       => __( 'CDS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Crypto Development Services Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'xmrAddress' => array(
					'title'       => __( 'XMR Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Monero Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'prcyAddress' => array(
					'title'       => __( 'PRCY Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your PRivaCY Coin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'cashAddress' => array(
					'title'       => __( 'CASH Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Litecash Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ktsAddress' => array(
					'title'       => __( 'KTS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Klimatas Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'grlcAddress' => array(
					'title'       => __( 'GRLC Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Garlicoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'primoAddress' => array(
					'title'       => __( 'PRIMO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Primo Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'ccxAddress' => array(
					'title'       => __( 'CCX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Conceal Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'bdxAddress' => array(
					'title'       => __( 'BDX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Beldex Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'kasAddress' => array(
					'title'       => __( 'KAS Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Kaspa Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'mariaAddress' => array(
					'title'       => __( 'MARIA Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Maria Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'fluxAddress' => array(
					'title'       => __( 'FLUX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Flux Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'papryAddress' => array(
					'title'       => __( 'PAPRY Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Paprikacoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'nexaAddress' => array(
					'title'       => __( 'NEXA Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your Nexa Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'bchAddress' => array(
					'title'       => __( 'BCH Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your BitcoinCash Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'kiiroAddress' => array(
					'title'       => __( 'KIIRO Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your KiiroCoin Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
					'default'     => __( '', 'cryptocurrencycheckout-wc-gateway' ),
					'desc_tip'    => true,
				),

				'blocxAddress' => array(
					'title'       => __( 'BLOCX Address:', 'cryptocurrencycheckout-wc-gateway' ),
					'type'        => 'text',
					'description' => __( 'Enter your BLOCX Address, must match the address input in CryptocurrencyCheckout Dashboard Connection.' ),
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
			$postfields['CC_ORDER_ID'] = $order->get_id();
			$postfields['CC_GRANDTOTAL'] = $order->get_total();

			$postfields['CC_BTC_ADDRESS'] = $this->btcAddress;
			$postfields['CC_ETH_ADDRESS'] = $this->ethAddress;
			$postfields['CC_LTC_ADDRESS'] = $this->ltcAddress;
			$postfields['CC_DASH_ADDRESS'] = $this->dashAddress;
			$postfields['CC_SEND_ADDRESS'] = $this->sendAddress;
			$postfields['CC_CDZC_ADDRESS'] = $this->cdzcAddress;
			$postfields['CC_ARRR_ADDRESS'] = $this->arrrAddress;
			$postfields['CC_COLX_ADDRESS'] = $this->colxAddress;
			$postfields['CC_ZNZ_ADDRESS'] = $this->znzAddress;
			$postfields['CC_THC_ADDRESS'] = $this->thcAddress;
			$postfields['CC_ECA_ADDRESS'] = $this->ecaAddress;
			$postfields['CC_PIVX_ADDRESS'] = $this->pivxAddress;
			$postfields['CC_NBR_ADDRESS'] = $this->nbrAddress;
			$postfields['CC_GALI_ADDRESS'] = $this->galiAddress;
			$postfields['CC_BITC_ADDRESS'] = $this->bitcAddress;
			$postfields['CC_OK_ADDRESS'] = $this->okAddress;
			$postfields['CC_ARK_ADDRESS'] = $this->arkAddress;
			$postfields['CC_VEIL_ADDRESS'] = $this->veilAddress;
			$postfields['CC_DOGE_ADDRESS'] = $this->dogeAddress;
			$postfields['CC_NBX_ADDRESS'] = $this->nbxAddress;
			$postfields['CC_XNV_ADDRESS'] = $this->xnvAddress;
			$postfields['CC_SUMO_ADDRESS'] = $this->sumoAddress;
			$postfields['CC_RPD_ADDRESS'] = $this->rpdAddress;
			$postfields['CC_TELOS_ADDRESS'] = $this->telosAddress;
			$postfields['CC_KMD_ADDRESS'] = $this->kmdAddress;
			$postfields['CC_VRSC_ADDRESS'] = $this->vrscAddress;
			$postfields['CC_BAN_ADDRESS'] = $this->banAddress;
			$postfields['CC_XBTX_ADDRESS'] = $this->xbtxAddress;
			$postfields['CC_SIN_ADDRESS'] = $this->sinAddress;
			$postfields['CC_XRP_ADDRESS'] = $this->xrpAddress;
			$postfields['CC_UPX_ADDRESS'] = $this->upxAddress;
			$postfields['CC_ADC_ADDRESS'] = $this->adcAddress;
			$postfields['CC_RITO_ADDRESS'] = $this->ritoAddress;
			$postfields['CC_BIR_ADDRESS'] = $this->birAddress;
			$postfields['CC_AXE_ADDRESS'] = $this->axeAddress;
			$postfields['CC_HUSH_ADDRESS'] = $this->hushAddress;
			$postfields['CC_CCY_ADDRESS'] = $this->ccyAddress;
			$postfields['CC_MOTA_ADDRESS'] = $this->motaAddress;
			$postfields['CC_PGO_ADDRESS'] = $this->pgoAddress;
			$postfields['CC_BITG_ADDRESS'] = $this->bitgAddress;
			$postfields['CC_GRT_ADDRESS'] = $this->grtAddress;
			$postfields['CC_NULS_ADDRESS'] = $this->nulsAddress;
			$postfields['CC_AUDAX_ADDRESS'] = $this->audaxAddress;
			$postfields['CC_DMS_ADDRESS'] = $this->dmsAddress;
			$postfields['CC_DAPS_ADDRESS'] = $this->dapsAddress;
			$postfields['CC_IDX_ADDRESS'] = $this->idxAddress;
			$postfields['CC_BURQ_ADDRESS'] = $this->burqAddress;
			$postfields['CC_HTML_ADDRESS'] = $this->htmlAddress;
			$postfields['CC_GHOST_ADDRESS'] = $this->ghostAddress;
			$postfields['CC_FDR_ADDRESS'] = $this->fdrAddress;
			$postfields['CC_ZER_ADDRESS'] = $this->zerAddress;
			$postfields['CC_BTCZ_ADDRESS'] = $this->btczAddress;
			$postfields['CC_ALIAS_ADDRESS'] = $this->aliasAddress;
			$postfields['CC_XDN_ADDRESS'] = $this->xdnAddress;
			$postfields['CC_ETHO_ADDRESS'] = $this->ethoAddress;
			$postfields['CC_DRGN_ADDRESS'] = $this->drgnAddress;
			$postfields['CC_SIGNA_ADDRESS'] = $this->signaAddress;
			$postfields['CC_DVPN_ADDRESS'] = $this->dvpnAddress;
			$postfields['CC_TUBE_ADDRESS'] = $this->tubeAddress;
			$postfields['CC_TN_ADDRESS'] = $this->tnAddress;
			$postfields['CC_ERG_ADDRESS'] = $this->ergAddress;
			$postfields['CC_SIGUSD_ADDRESS'] = $this->sigusdAddress;
			$postfields['CC_BNJ_ADDRESS'] = $this->bnjAddress;
			$postfields['CC_USDT_ADDRESS'] = $this->usdtAddress;
			$postfields['CC_EGG_ADDRESS'] = $this->eggAddress;
			$postfields['CC_NLIFE_ADDRESS'] = $this->nlifeAddress;
			$postfields['CC_XHV_ADDRESS'] = $this->xhvAddress;
			$postfields['CC_XUSD_ADDRESS'] = $this->xusdAddress;
			$postfields['CC_MOONSHOT_ADDRESS'] = $this->moonshotAddress;
			$postfields['CC_GTH_ADDRESS'] = $this->gthAddress;
			$postfields['CC_HNS_ADDRESS'] = $this->hnsAddress;
			$postfields['CC_RTM_ADDRESS'] = $this->rtmAddress;
			$postfields['CC_CDS_ADDRESS'] = $this->cdsAddress;
			$postfields['CC_XMR_ADDRESS'] = $this->xmrAddress;
			$postfields['CC_PRCY_ADDRESS'] = $this->prcyAddress;
			$postfields['CC_CASH_ADDRESS'] = $this->cashAddress;
			$postfields['CC_KTS_ADDRESS'] = $this->ktsAddress;
			$postfields['CC_GRLC_ADDRESS'] = $this->grlcAddress;
			$postfields['CC_PRIMO_ADDRESS'] = $this->primoAddress;
			$postfields['CC_CCX_ADDRESS'] = $this->ccxAddress;
			$postfields['CC_BDX_ADDRESS'] = $this->bdxAddress;
			$postfields['CC_KAS_ADDRESS'] = $this->kasAddress;
			$postfields['CC_MARIA_ADDRESS'] = $this->mariaAddress;
			$postfields['CC_FLUX_ADDRESS'] = $this->fluxAddress;
			$postfields['CC_PAPRY_ADDRESS'] = $this->papryAddress;
			$postfields['CC_NEXA_ADDRESS'] = $this->nexaAddress;
			$postfields['CC_BCH_ADDRESS'] = $this->bchAddress;
			$postfields['CC_KIIRO_ADDRESS'] = $this->kiiroAddress;
			$postfields['CC_BLOCX_ADDRESS'] = $this->blocxAddress;

			// This is an auto redirect option for thank you page, if enabled in Wordpress/WooCommerce Dashboard, will automatically click the payNow button, redirecting customers to CryptocurrencyCheckout
			if ( $this->redirect == 'yes' ) {
			wc_enqueue_js( 'jQuery( "#submit-form" ).click();' );
			}
			
			// Display Payment Button to Customer, clicking this button will HTTP POST and pass the customer to the CryptocurrencyCheckout Payment Gateway.
			$htmlOutput = '<form method="POST" action="' . $url . '">';
			foreach ($postfields as $k => $v) {
				if (empty($v)){
					//if empty, skip.
				} else {
					$htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
				}
			}
			$htmlOutput .= '<input type="submit" id="submit-form" value="' . $this->payNow . '">';
			$htmlOutput .= '</form>';
			
			echo $htmlOutput;
			
		}



		/**
		 * Add content to the WC emails.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */

		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

			
			if ($this->emailButton === 'yes' && ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status('on-hold')) {

				// POST Fields
				$url = 'https://cryptocurrencycheckout.com/email/validation';
				$api = $this->APIToken;

				$postfields = array();
				$postfields['SN'] = $this->StoreName;
				$postfields['SI'] = $this->StoreID;
				$postfields['CI'] = $this->ConnectionID;
				$postfields['OI'] = $order->get_id();
				$postfields['OT'] = $order->get_total();
				
				$postfields['BTC'] = $this->btcAddress;
				$postfields['ETH'] = $this->ethAddress;
				$postfields['LTC'] = $this->ltcAddress;
				$postfields['DASH'] = $this->dashAddress;
				$postfields['SEND'] = $this->sendAddress;
				$postfields['CDZC'] = $this->cdzcAddress;
				$postfields['ARRR'] = $this->arrrAddress;
				$postfields['COLX'] = $this->colxAddress;
				$postfields['ZNZ'] = $this->znzAddress;
				$postfields['THC'] = $this->thcAddress;
				$postfields['ECA'] = $this->ecaAddress;
				$postfields['PIVX'] = $this->pivxAddress;
				$postfields['NBR'] = $this->nbrAddress;
				$postfields['GALI'] = $this->galiAddress;
				$postfields['BITC'] = $this->bitcAddress;
				$postfields['OK'] = $this->okAddress;
				$postfields['ARK'] = $this->arkAddress;
				$postfields['VEIL'] = $this->veilAddress;
				$postfields['DOGE'] = $this->dogeAddress;
				$postfields['NBX'] = $this->nbxAddress;
				$postfields['XNV'] = $this->xnvAddress;
				$postfields['SUMO'] = $this->sumoAddress;
				$postfields['RPD'] = $this->rpdAddress;
				$postfields['TELOS'] = $this->telosAddress;
				$postfields['KMD'] = $this->kmdAddress;
				$postfields['VRSC'] = $this->vrscAddress;
				$postfields['BAN'] = $this->banAddress;
				$postfields['XBTX'] = $this->xbtxAddress;
				$postfields['SIN'] = $this->sinAddress;
				$postfields['XRP'] = $this->xrpAddress;
				$postfields['UPX'] = $this->upxAddress;
				$postfields['ADC'] = $this->adcAddress;
				$postfields['RITO'] = $this->ritoAddress;
				$postfields['BIR'] = $this->birAddress;
				$postfields['AXE'] = $this->axeAddress;
				$postfields['HUSH'] = $this->hushAddress;
				$postfields['CCY'] = $this->ccyAddress;
				$postfields['MOTA'] = $this->motaAddress;
				$postfields['PGO'] = $this->pgoAddress;
				$postfields['BITG'] = $this->bitgAddress;
				$postfields['GRT'] = $this->grtAddress;
				$postfields['NULS'] = $this->nulsAddress;
				$postfields['AUDAX'] = $this->audaxAddress;
				$postfields['DMS'] = $this->dmsAddress;
				$postfields['DAPS'] = $this->dapsAddress;
				$postfields['IDX'] = $this->idxAddress;
				$postfields['BURQ'] = $this->burqAddress;
				$postfields['HTML'] = $this->htmlAddress;
				$postfields['GHOST'] = $this->ghostAddress;
				$postfields['FDR'] = $this->fdrAddress;
				$postfields['ZER'] = $this->zerAddress;
				$postfields['BTCZ'] = $this->btczAddress;
				$postfields['ALIAS'] = $this->aliasAddress;
				$postfields['XDN'] = $this->xdnAddress;
				$postfields['ETHO'] = $this->ethoAddress;
				$postfields['DRGN'] = $this->drgnAddress;
				$postfields['SIGNA'] = $this->signaAddress;
				$postfields['DVPN'] = $this->dvpnAddress;
				$postfields['TUBE'] = $this->tubeAddress;
				$postfields['TN'] = $this->tnAddress;
				$postfields['ERG'] = $this->ergAddress;
				$postfields['SIGUSD'] = $this->sigusdAddress;
				$postfields['BNJ'] = $this->bnjAddress;
				$postfields['USDT'] = $this->usdtAddress;
				$postfields['EGG'] = $this->eggAddress;
				$postfields['NLIFE'] = $this->nlifeAddress;
				$postfields['XHV'] = $this->xhvAddress;
				$postfields['XUSD'] = $this->xusdAddress;
				$postfields['MOONSHOT'] = $this->moonshotAddress;
				$postfields['GTH'] = $this->gthAddress;
				$postfields['HNS'] = $this->hnsAddress;
				$postfields['RTM'] = $this->rtmAddress;
				$postfields['CDS'] = $this->cdsAddress;
				$postfields['XMR'] = $this->xmrAddress;
				$postfields['PRCY'] = $this->prcyAddress;
				$postfields['CASH'] = $this->cashAddress;
				$postfields['KTS'] = $this->ktsAddress;
				$postfields['GRLC'] = $this->grlcAddress;
				$postfields['PRIMO'] = $this->primoAddress;
				$postfields['CCX'] = $this->ccxAddress;
				$postfields['BDX'] = $this->bdxAddress;
				$postfields['KAS'] = $this->kasAddress;
				$postfields['MARIA'] = $this->mariaAddress;
				$postfields['FLUX'] = $this->fluxAddress;
				$postfields['PAPRY'] = $this->papryAddress;
				$postfields['NEXA'] = $this->nexaAddress;
				$postfields['BCH'] = $this->bchAddress;
				$postfields['KIIRO'] = $this->kiiroAddress;
				$postfields['BLOCX'] = $this->blocxAddress;
	
				$htmlOutput ='<div style="padding-top: 20px; padding-bottom: 20px;">';
				$htmlOutput .= '' . $this->Instructions . '<br><br>';
				$htmlOutput .= '<a href="' . $url . '?AI=' . $api . '';

				foreach ($postfields as $k => $v) {
					if (empty($v)){
						//if empty, skip.
					} else {
						$htmlOutput .= '&' . $k . '=' . $v;
					}
				}

				$htmlOutput .= '" >';
				$htmlOutput .= '<button style="background-color: #007bff; color: white; font-size: 16px; padding: 8px 20px; border-radius: 6px;">'. $this->payNow .'</button>';
				$htmlOutput .= '</a></div>';
	
				echo $htmlOutput;

			}
			
		}
	
	
		/**
		 * Process the payment and return the result
		 * This will put the order into on-hold status, reduce inventory levels, and empty customer shopping cart.
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
	
			$order = wc_get_order( $order_id );
			
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', __( 'Awaiting cryptocurrencycheckout payment', 'cryptocurrencycheckout-wc-gateway' ) );
			
			// Reduce stock levels
			wc_reduce_stock_levels($order_id);
			
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
