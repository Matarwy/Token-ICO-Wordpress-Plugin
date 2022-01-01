<?php

/*
 Plugin Name: Token ICO
 Description: Sell your ERC20 ICO tokens from your WordPress site.
 Version: 1.0.0
 Author: Alhassan Matarwy
 Text Domain: token-ico
 Domain Path: /languages
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
//use GuzzleHttp\Client;
//use GuzzleHttp\HandlerStack;
//use GuzzleHttp\Middleware;
//use GuzzleHttp\Psr7;
//use GuzzleHttp\Psr7\Request;
//use Psr\Http\Message\RequestInterface;
// Explicitly globalize to support bootstrapped WordPress
global 
    $Token_ICO_plugin_basename,
    $Token_ICO_options,
    $Token_ICO_plugin_dir,
    $Token_ICO_plugin_url_path,
    $Token_ICO_services,
    $Token_ICO_amp_icons_css
;
  
// ... Your plugin's main file logic ...
$Token_ICO_plugin_basename = plugin_basename( dirname( __FILE__ ) );
$Token_ICO_plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
$Token_ICO_plugin_url_path = untrailingslashit( plugin_dir_url( __FILE__ ) );
// HTTPS?
$Token_ICO_plugin_url_path = ( is_ssl() ? str_replace( 'http:', 'https:', $Token_ICO_plugin_url_path ) : $Token_ICO_plugin_url_path );
// Set plugin options
$Token_ICO_options = stripslashes_deep( get_option( 'token-ico_options', array() ) );
require $Token_ICO_plugin_dir . '/vendor/autoload.php';
function Token_ICO_init()
{
    global  $Token_ICO_plugin_dir, $Token_ICO_plugin_basename, $Token_ICO_options ;
    // Load the textdomain for translations
    load_plugin_textdomain( 'token-ico', false, $Token_ICO_plugin_basename . '/languages/' );
}

add_filter( 'init', 'Token_ICO_init' );
function Token_ICO_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_plugin_dir ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'buybuttontext' => '',
        'minimum'       => '',
        'maximum'       => '',
        'step'          => '',
        'placeholder'   => '',
        'gaslimit'      => '',
        'tokenname'     => '',
        'description'   => '',
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    $gaslimit = ( !empty($attributes['gaslimit']) ? $attributes['gaslimit'] : (( !empty($options['gaslimit']) ? esc_attr( $options['gaslimit'] ) : "200000" )) );
    $tokenName = ( !empty($attributes['tokenname']) ? $attributes['tokenname'] : (( !empty($options['tokenname']) ? esc_attr( $options['tokenname'] ) : "TESTCOIN" )) );
    $placeholder = ( !empty($attributes['placeholder']) ? $attributes['placeholder'] : (( !empty($options['placeholder']) ? esc_attr( $options['placeholder'] ) : __( "Input ETH amount", 'token-ico' ) )) );
    $step = ( !empty($attributes['step']) ? $attributes['step'] : (( !empty($options['step']) ? esc_attr( $options['step'] ) : "0.1" )) );
    $minimum = ( !empty($attributes['minimum']) ? $attributes['minimum'] : (( !empty($options['min']) ? esc_attr( $options['min'] ) : "0" )) );
    $maximum = "1000000000";
    $maximum = ( !empty($attributes['maximum']) ? $attributes['maximum'] : (( !empty($options['max']) ? esc_attr( $options['max'] ) : "1000000000" )) );

    $buyButtonText = ( !empty($attributes['buybuttontext']) ? $attributes['buybuttontext'] : (( !empty($options['buyButtonText']) ? esc_attr( $options['buyButtonText'] ) : sprintf( __( "Buy %s with<br>Metamask", 'token-ico' ), $tokenName ) )) );
    $description = ( !empty($attributes['description']) ? $attributes['description'] : (( !empty($options['description']) ? esc_attr( $options['description'] ) : sprintf(
        __( 'Make sure that you send %3$s from a wallet that supports %2$s tokens or from an address for which you control the private key. Otherwise you will not be able to interact with the %1$s tokens received. Do not send %3$s directly from an exchange to the ICO address.', 'token-ico' ),
        $tokenName,
        Token_ICO_getTokenStandardName(),
        Token_ICO_getBlockchainCurrencyTickerName()
    ) )) );
    $coinList = ( !empty($options['coinList']) ? esc_attr( $options['coinList'] ) : '' );
    // remove all whitespaces
    $coinList = preg_replace( '/\\s+/', '', $coinList );
    $showIcons = ( !empty($options['showIcons']) ? esc_attr( $options['showIcons'] ) : '' );
    $base_currency = ( !empty($options['base_currency']) ? esc_attr( $options['base_currency'] ) : Token_ICO_getBlockchainCurrencyTicker() );
    $base_currency_lower = strtolower( $base_currency );
    $base_currency_img = '';
    if ( '' != $showIcons && '0' != $showIcons && 'off' != strtolower( $showIcons ) && 'false' != strtolower( $showIcons ) ) {
        
        if ( file_exists( $Token_ICO_plugin_dir . '/icons/' . $base_currency . '.png' ) ) {
            $base_currency_img = '<img class="token-ico-rate-img token-ico-rate-img-' . $base_currency_lower . '" src="' . $Token_ICO_plugin_url_path . '/icons/' . $base_currency . '.png"></img>';
        } else {
            $base_currency_img = '<img class="token-ico-rate-img token-ico-rate-img-' . $base_currency_lower . ' token-ico-rate-img-noimage" src="' . $Token_ICO_plugin_url_path . '/icons/ETH.png"></img>';
        }
    
    }
    $ratesHtml = '';
    $coins = array();
    
    if ( $coinList ) {
        $coins = explode( ',', str_replace( " ", "", $coinList ) );
        $htmlArray = array();
        foreach ( $coins as $coin ) {
            $token = strtolower( $coin );
            $tokenImg = '';
            if ( '' != $showIcons && '0' != $showIcons && 'off' != strtolower( $showIcons ) && 'false' != strtolower( $showIcons ) ) {
                
                if ( file_exists( $Token_ICO_plugin_dir . '/icons/' . $coin . '.png' ) ) {
                    $tokenImg = '<img class="token-ico-rate-img token-ico-rate-img-' . $token . '-ord float-left" src="' . $Token_ICO_plugin_url_path . '/icons/' . $coin . '.png"></img>';
                } else {
                    $tokenImg = '<img class="token-ico-rate-img token-ico-rate-img-' . $token . '-ord token-ico-rate-img-noimage float-left" src="' . $Token_ICO_plugin_url_path . '/icons/ETH.png"></img>';
                }
            
            }
            $html = '<div class="row token-ico-rate-token-container">' . '<div class="token-ico-rate-token-value col-md-5 col-6">' . '<span id="rate' . $token . '" class="token-ico-rate token-ico-coin-rate-' . $token . '">0</span>' . '</div>' . '<div class="col-md-7 col-6">' . $tokenImg . '<span class="token-ico-rate-coin float-left">' . $coin . '</span>' . '</div>' . '</div>';
            $htmlArray[] = $html;
        }
        $ratesHtml = '<h4 class="token-ico-rate-token-container-wrapper">' . implode( "", $htmlArray ) . '</h4>';
    }
    
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-shortcode">
<div class="row">
    <div class="col">
        <h2 class="token-ico-gaslimit">' . sprintf( __( 'Gas Limit: %s', 'token-ico' ), $gaslimit ) . '</h2>
    </div>
</div>
<div class="row">
    <div class="col-md-5 col-12">
        <div class="token-ico-quantity">
            <input type="number" name="etherInput" id="etherInput" placeholder="' . $placeholder . '" step="' . $step . '" min="' . $minimum . '" max="' . $maximum . '" class="token-ico-bottom-input-one">
            <div class="quantity-nav">
                <div class="quantity-button quantity-up">
                    <i class="fa fa-chevron-up" aria-hidden="true"></i>
                </div>
                <div class="quantity-button quantity-down">
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="token-ico-rate-token-value col-6 hidden-lg d-lg-none hidden-md d-md-none">
        <span id="rate' . $base_currency_lower . '" class="token-ico-rate token-ico-rate-eth token-ico-coin-rate-' . $base_currency_lower . '">0</span>
    </div>
    <div class="token-ico-rate-eth col-6 hidden-lg d-lg-none hidden-md d-md-none">' . $base_currency_img . '<span class="token-ico-rate-coin">' . $base_currency . '</span>
    </div>
    <div class="token-ico-rate-eth col-md-2 col-md-offset-0 col-6 col-offset-6 visible-lg visible-md d-none d-md-block">' . $base_currency_img . '<span class="token-ico-rate-coin">' . $base_currency . '</span>
    </div>
    <div class="token-ico-bottom-button col-md-5 col-12">
        <button class="button token-ico-bottom-button-two" id="buyTokensButton">' . $buyButtonText . '</button>
    </div>
</div>' . $ratesHtml . '<div class="row">
    <div class="col">
        <p class="token-ico-description">' . $description . '</p>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $ret ) ) );
}

add_shortcode( 'token-ico', 'Token_ICO_shortcode' );
function Token_ICO_input_currency_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_plugin_dir ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'showicons'    => '',
        'basecurrency' => '',
    ), $attributes, 'token-ico-input-currency' );
    $options = $Token_ICO_options;
    $showIcons = ( !empty($attributes['showicons']) ? $attributes['showicons'] : (( !empty($options['showIcons']) ? esc_attr( $options['showIcons'] ) : "" )) );
    $base_currency = ( !empty($attributes['basecurrency']) ? $attributes['basecurrency'] : (( !empty($options['base_currency']) ? esc_attr( $options['base_currency'] ) : Token_ICO_getBlockchainCurrencyTicker() )) );
    $base_currency_lower = strtolower( $base_currency );
    $base_currency_img = '';
    if ( '' != $showIcons && '0' != $showIcons && 'off' != strtolower( $showIcons ) && 'false' != strtolower( $showIcons ) ) {
        
        if ( file_exists( $Token_ICO_plugin_dir . '/icons/' . $base_currency . '.png' ) ) {
            $base_currency_img = '<img class="token-ico-rate-img token-ico-rate-img-' . $base_currency_lower . '" src="' . $Token_ICO_plugin_url_path . '/icons/' . $base_currency . '.png"></img>';
        } else {
            $base_currency_img = '<img class="token-ico-rate-img token-ico-rate-img-' . $base_currency_lower . ' token-ico-rate-img-noimage" src="' . $Token_ICO_plugin_url_path . '/icons/ETH.png"></img>';
        }
    
    }
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-shortcode token-ico-input-currency-shortcode">
<div class="row">
    <div class="token-ico-rate-token-value col-6 hidden-lg d-lg-none hidden-md d-md-none">
        <span id="rate' . $base_currency_lower . '" class="token-ico-rate token-ico-rate-eth token-ico-coin-rate-' . $base_currency_lower . '">0</span>
    </div>
    <div class="token-ico-rate-eth col-6 hidden-lg d-lg-none hidden-md d-md-none">' . $base_currency_img . '<span class="token-ico-rate-coin">' . $base_currency . '</span>
    </div>
    <div class="token-ico-rate-eth col-md-2 col-md-offset-0 col-6 col-offset-6 visible-lg visible-md d-none d-md-block">' . $base_currency_img . '<span class="token-ico-rate-coin">' . $base_currency . '</span>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $ret ) ) );
}

add_shortcode( 'token-ico-input-currency', 'Token_ICO_input_currency_shortcode' );
function Token_ICO_limit_shortcode( $attributes )
{
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'label'    => '',
        'gaslimit' => '',
    ), $attributes, 'token-ico-limit' );
    $options = $Token_ICO_options;
    $gaslimit = ( !empty($attributes['gaslimit']) ? $attributes['gaslimit'] : (( !empty($options['gaslimit']) ? esc_attr( $options['gaslimit'] ) : "200000" )) );
    $label = ( !empty($attributes['label']) ? $attributes['label'] : __( 'Gas Limit: %s', 'token-ico' ) );
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-shortcode token-ico-limit-shortcode">
<div class="row">
    <div class="col">
        <h2 class="token-ico-gaslimit">' . sprintf( $label, $gaslimit ) . '</h2>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $ret ) ) );
}

add_shortcode( 'token-ico-limit', 'Token_ICO_limit_shortcode' );
function Token_ICO_buy_button_shortcode( $attributes )
{
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'buybuttontext' => '',
        'tokenname'     => '',
    ), $attributes, 'token-ico-buy-button' );
    $options = $Token_ICO_options;
    $tokenName = ( !empty($attributes['tokenname']) ? $attributes['tokenname'] : (( !empty($options['tokenname']) ? esc_attr( $options['tokenname'] ) : "TESTCOIN" )) );
    $buyButtonText = ( !empty($attributes['buybuttontext']) ? $attributes['buybuttontext'] : (( !empty($options['buyButtonText']) ? esc_attr( $options['buyButtonText'] ) : sprintf( __( "Buy %s with<br>Metamask", 'token-ico' ), $tokenName ) )) );
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-shortcode token-ico-buy-button-shortcode">
<div class="row">
    <div class="token-ico-bottom-button col-12">
        <button class="button token-ico-bottom-button-two" id="buyTokensButton">' . $buyButtonText . '</button>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $ret ) ) );
}

add_shortcode( 'token-ico-buy-button', 'Token_ICO_buy_button_shortcode' );
function Token_ICO_currency_list_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_plugin_dir ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'showicons' => 'true',
        'coinlist'  => '',
    ), $attributes, 'token-ico-currency-list' );
    $options = $Token_ICO_options;
    $coinList = ( !empty($attributes['coinlist']) ? $attributes['coinlist'] : (( !empty($options['coinList']) ? esc_attr( $options['coinList'] ) : '' )) );
    // remove all whitespaces
    $coinList = preg_replace( '/\\s+/', '', $coinList );
    $showIcons = ( !empty($attributes['showicons']) ? $attributes['showicons'] : (( !empty($options['showIcons']) ? esc_attr( $options['showIcons'] ) : "" )) );
    $base_currency = ( !empty($options['base_currency']) ? esc_attr( $options['base_currency'] ) : Token_ICO_getBlockchainCurrencyTicker() );
    $base_currency_lower = strtolower( $base_currency );
    $base_currency_img = '';
    if ( '' != $showIcons && '0' != $showIcons && 'off' != strtolower( $showIcons ) && 'false' != strtolower( $showIcons ) ) {
        
        if ( file_exists( $Token_ICO_plugin_dir . '/icons/' . $base_currency . '.png' ) ) {
            $base_currency_img = '<img class="token-ico-rate-img token-ico-rate-img-' . $base_currency_lower . '" src="' . $Token_ICO_plugin_url_path . '/icons/' . $base_currency . '.png"></img>';
        } else {
            $base_currency_img = '<img class="token-ico-rate-img token-ico-rate-img-' . $base_currency_lower . ' token-ico-rate-img-noimage" src="' . $Token_ICO_plugin_url_path . '/icons/ETH.png"></img>';
        }
    
    }
    $ratesHtml = '';
    $coins = array();
    
    if ( $coinList ) {
        $coins = explode( ',', str_replace( " ", "", $coinList ) );
        $htmlArray = array();
        foreach ( $coins as $coin ) {
            $token = strtolower( $coin );
            $tokenImg = '';
            if ( '' != $showIcons && '0' != $showIcons && 'off' != strtolower( $showIcons ) && 'false' != strtolower( $showIcons ) ) {
                
                if ( file_exists( $Token_ICO_plugin_dir . '/icons/' . $coin . '.png' ) ) {
                    $tokenImg = '<img class="token-ico-rate-img token-ico-rate-img-' . $token . '-ord float-left" src="' . $Token_ICO_plugin_url_path . '/icons/' . $coin . '.png"></img>';
                } else {
                    $tokenImg = '<img class="token-ico-rate-img token-ico-rate-img-' . $token . '-ord token-ico-rate-img-noimage float-left" src="' . $Token_ICO_plugin_url_path . '/icons/ETH.png"></img>';
                }
            
            }
            $html = '<div class="row token-ico-rate-token-container">' . '<div class="token-ico-rate-token-value col-md-5 col-6">' . '<span id="rate' . $token . '" class="token-ico-rate token-ico-coin-rate-' . $token . '">0</span>' . '</div>' . '<div class="col-md-7 col-6">' . $tokenImg . '<span class="token-ico-rate-coin float-left">' . $coin . '</span>' . '</div>' . '</div>';
            $htmlArray[] = $html;
        }
        $ratesHtml = '<h4 class="token-ico-currency-list-rate-token-container-wrapper">' . implode( "", $htmlArray ) . '</h4>';
    }
    
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-shortcode token-ico-currency-list-shortcode">' . $ratesHtml . '</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $ret ) ) );
}

add_shortcode( 'token-ico-currency-list', 'Token_ICO_currency_list_shortcode' );
function Token_ICO_input_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_plugin_dir ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'minimum'     => '',
        'maximum'     => '',
        'step'        => '',
        'placeholder' => '',
    ), $attributes, 'token-ico-input' );
    $options = $Token_ICO_options;
    $placeholder = ( !empty($attributes['placeholder']) ? $attributes['placeholder'] : (( !empty($options['placeholder']) ? esc_attr( $options['placeholder'] ) : __( "Input ETH amount", 'token-ico' ) )) );
    $step = ( !empty($attributes['step']) ? $attributes['step'] : (( !empty($options['step']) ? esc_attr( $options['step'] ) : "0.1" )) );
    $minimum = ( !empty($attributes['minimum']) ? $attributes['minimum'] : (( !empty($options['min']) ? esc_attr( $options['min'] ) : "0" )) );
    $maximum = "1000000000";
    $maximum = ( !empty($attributes['maximum']) ? $attributes['maximum'] : (( !empty($options['max']) ? esc_attr( $options['max'] ) : "1000000000" )) );
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-shortcode token-ico-input-shortcode">
<div class="row">
    <div class="col-12">
        <div class="token-ico-quantity">
            <input type="number" name="etherInput" id="etherInput" placeholder="' . $placeholder . '" step="' . $step . '" min="' . $minimum . '" max="' . $maximum . '" class="token-ico-bottom-input-one">
            <div class="quantity-nav">
                <div class="quantity-button quantity-up">
                    <i class="fa fa-chevron-up" aria-hidden="true"></i>
                </div>
                <div class="quantity-button quantity-down">
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $ret ) ) );
}

add_shortcode( 'token-ico-input', 'Token_ICO_input_shortcode' );
function Token_ICO_calc_display_value( $value )
{
    if ( $value < 1 ) {
        return array( 0.01 * round( 100 * $value ), '' );
    }
    if ( $value < 1000 ) {
        return array( 0.1 * round( 10 * $value ), '' );
    }
    if ( $value < 1000000 ) {
        return array( 0.1 * round( 10 * 0.001 * $value ), __( 'K', 'token-ico' ) );
    }
    return array( 0.1 * round( 10 * 1.0E-6 * $value ), __( 'M', 'token-ico' ) );
}

class Token_ICO_Logger
{
    /**
     * Add a log entry.
     *
     * This is not the preferred method for adding log messages. Please use log() or any one of
     * the level methods (debug(), info(), etc.). This method may be deprecated in the future.
     *
     * @param string $handle
     * @param string $message
     * @param string $level
     *
     * @see https://docs.woocommerce.com/wc-apidocs/source-class-WC_Logger.html#105
     *
     * @return bool
     */
    public function add( $handle, $message, $level = 'unused' )
    {
        error_log( $handle . ': ' . $message );
        return true;
    }

}
function Token_ICO_log( $error )
{
    static  $logger = false ;
    // Create a logger instance if we don't already have one.
    if ( false === $logger ) {
        /**
         * Check if WooCommerce is active
         * https://wordpress.stackexchange.com/a/193908/137915
         **/
        
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && class_exists( "WC_Logger", false ) ) {
            $logger = new WC_Logger();
        } else {
            $logger = new Token_ICO_Logger();
        }
    
    }
    $logger->add( 'token-ico', $error );
}

function Token_ICO_get_total_received_blockcypher( $crowdsaleAddress, $cacheKey )
{
    $url = "https://api.blockcypher.com/v1/eth/main/addrs/{$crowdsaleAddress}/balance";
    $response = wp_remote_get( $url, array(
        'sslverify' => false,
    ) );
    ?>
    <!-- LOG: blockcypher.com API is called -->
    <?php 
    
    if ( is_wp_error( $response ) ) {
        $error_string = $response->get_error_message();
        Token_ICO_log( "Token_ICO_get_total_received_blockcypher({$crowdsaleAddress}):" . $error_string );
        return array( $error_string, null );
    }
    
    $http_code = wp_remote_retrieve_response_code( $response );
    
    if ( 200 != $http_code ) {
        Token_ICO_log( "Token_ICO_get_total_received_blockcypher({$crowdsaleAddress}): Result code is not 200" . $http_code );
        return array( __( "Result code is not 200", 'token-ico' ), null );
    }
    
    $body = wp_remote_retrieve_body( $response );
    
    if ( !$body ) {
        Token_ICO_log( "Token_ICO_get_total_received_blockcypher({$crowdsaleAddress}): Empty body" );
        return array( __( "Empty body", 'token-ico' ), null );
    }
    
    $j = json_decode( $body, true );
    
    if ( !isset( $j["total_received"] ) ) {
        Token_ICO_log( "Token_ICO_get_total_received_blockcypher({$crowdsaleAddress}): No total_received field" . $body );
        return array( __( "No total_received field", 'token-ico' ), null );
    }
    
    $total_received = $j["total_received"];
    set_transient( $cacheKey . "-token-ico", $total_received, 5 * 60 );
    ?>
    <!-- LOG: blockcypher.com API call result is saved -->
    <?php 
    return array( null, $total_received );
}

function Token_ICO_get_total_received_etherscan( $crowdsaleAddress, $cacheKey )
{
    $url = sprintf( Token_ICO_get_tx_list_api_url_template(), $crowdsaleAddress );
    //        Token_ICO_log("Token_ICO_get_total_received_etherscan($crowdsaleAddress):" . $url);
    $response = wp_remote_get( $url, array(
        'sslverify' => false,
    ) );
    ?>
    <!-- LOG: etherscan.io total_received API is called -->
    <?php 
    
    if ( is_wp_error( $response ) ) {
        $error_string = $response->get_error_message();
        Token_ICO_log( "Token_ICO_get_total_received_etherscan({$crowdsaleAddress}):" . $error_string );
        return array( $error_string, null );
    }
    
    $http_code = wp_remote_retrieve_response_code( $response );
    
    if ( 200 != $http_code ) {
        Token_ICO_log( "Token_ICO_get_total_received_etherscan({$crowdsaleAddress}): Result code is not 200" . $http_code );
        return array( __( "Result code is not 200", 'token-ico' ), null );
    }
    
    $body = wp_remote_retrieve_body( $response );
    //        Token_ICO_log("Token_ICO_get_total_received_etherscan($crowdsaleAddress): body=" . $body);
    
    if ( !$body ) {
        Token_ICO_log( "Token_ICO_get_total_received_etherscan({$crowdsaleAddress}): Empty body" );
        return array( __( "Empty body", 'token-ico' ), null );
    }
    
    $j = json_decode( $body, true );
    
    if ( !isset( $j["status"] ) ) {
        Token_ICO_log( "Token_ICO_get_total_received_etherscan({$crowdsaleAddress}): No status field" . $body );
        return array( __( "No status field", 'token-ico' ), null );
    }
    
    
    if ( "1" !== $j["status"] ) {
        Token_ICO_log( "Token_ICO_get_total_received_etherscan({$crowdsaleAddress}): Bad status field" . $body );
        return array( __( "Bad status field", 'token-ico' ), null );
    }
    
    
    if ( !isset( $j["result"] ) ) {
        Token_ICO_log( "Token_ICO_get_total_received_etherscan({$crowdsaleAddress}): No result field" . $body );
        return array( __( "No result field", 'token-ico' ), null );
    }
    
    $trxns = $j["result"];
    $total_received = new phpseclib3\Math\BigInteger( 0 );
    foreach ( $trxns as $tx ) {
        $toAddress = $tx['to'];
        if ( strtolower( $toAddress ) != strtolower( $crowdsaleAddress ) ) {
            continue;
        }
        $value = $tx['value'];
        $total_received = $total_received->add( new phpseclib3\Math\BigInteger( $value ) );
    }
    set_transient( $cacheKey . "-token-ico", $total_received->toString(), 5 * 60 );
    ?>
    <!-- LOG: etherscan.io total_received API call result is stored -->
    <?php 
    return array( null, $total_received->toString() );
}

function _Token_ICO_wei_to_ether( $balance )
{
    $powDecimals = new phpseclib3\Math\BigInteger( pow( 10, 18 ) );
    list( $q, $r ) = $balance->divide( $powDecimals );
    $sR = $r->toString();
    $tokenDecimalChar = '.';
    $tokenDecimals = 18;
    $strBalanceDecimals = sprintf( '%018s', $sR );
    $strBalanceDecimals2 = substr( $strBalanceDecimals, 0, $tokenDecimals );
    
    if ( str_pad( "", $tokenDecimals, "0" ) == $strBalanceDecimals2 ) {
        $strBalance = rtrim( $q->toString() . $tokenDecimalChar . $strBalanceDecimals, '0' );
    } else {
        $strBalance = rtrim( $q->toString() . $tokenDecimalChar . $strBalanceDecimals2, '0' );
    }
    
    $strBalance = rtrim( $strBalance, $tokenDecimalChar );
    return $strBalance;
}

function Token_ICO_progress_shortcode( $attributes )
{
    //        global $Token_ICO_plugin_url_path;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'crowdsale' => '',
        'softcap'   => '',
        'hardcap'   => '',
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    //        $blockchain_network = Token_ICO_getBlockchainNetwork();
    $crowdsaleAddress = ( !empty($attributes['crowdsale']) ? esc_attr( $attributes['crowdsale'] ) : (( !empty($options['crowdsaleAddress']) ? esc_attr( $options['crowdsaleAddress'] ) : "" )) );
    //        $icostart = !empty($attributes['icostart']) ? esc_attr($attributes['icostart']) :
    //                (!empty($options['icostart']) ? esc_attr($options['icostart']) : "");
    //
    //        $icoperiod = !empty($attributes['icoperiod']) ? esc_attr($attributes['icoperiod']) :
    //                (!empty($options['icoperiod']) ? esc_attr($options['icoperiod']) : "");
    $softcap = ( !empty($attributes['softcap']) ? esc_attr( $attributes['softcap'] ) : (( !empty($options['softcap']) ? esc_attr( $options['softcap'] ) : "" )) );
    $hardcap = ( !empty($attributes['hardcap']) ? esc_attr( $attributes['hardcap'] ) : (( !empty($options['hardcap']) ? esc_attr( $options['hardcap'] ) : "10000" )) );
    //
    ////	$base_currency = ! empty( $options['base_currency'] ) ? esc_attr( $options['base_currency'] ) : Token_ICO_getBlockchainCurrencyTicker();
    //        $base_symbol = !empty($options['base_symbol']) ? esc_attr($options['base_symbol']) : '';
    //
    //        if ('' !== $softcap && doubleval($hardcap) <= doubleval($softcap)) {
    //            return '<div class="alert alert-warning"><strong>' . __('Warning!', 'token-ico') . '</strong> ' . __('TokenICO Progress is not configured properly. Check that softcap is less than hardcap.', 'token-ico') . '</div>';
    //        }
    //
    //        $cacheKey = "$blockchain_network:$crowdsaleAddress:balance";
    //        $total_received = get_transient($cacheKey . "-token-ico");
    //        if (false === $total_received) {
    //            $total_received = "0";
    //            switch ($blockchain_network) {
    //                case "mainnet":
    //                    list($error, $total_received) = Token_ICO_get_total_received_blockcypher($crowdsaleAddress, $cacheKey);
    //                    if ($error) {
    //                        return '<div class="alert alert-danger"><strong>' . __('Error!', 'token-ico') . '</strong> ' . $error . '</div>';
    //                    }
    //                    break;
    //
    //                default:
    //                    list($error, $total_received) = Token_ICO_get_total_received_etherscan($crowdsaleAddress, $cacheKey);
    //                    if ($error) {
    //                        return '<div class="alert alert-danger"><strong>' . __('Error!', 'token-ico') . '</strong> ' . $error . '</div>';
    //                    }
    //                    break;
    //            }
    //        }
    //
    //        $total_received_eth = _Token_ICO_wei_to_ether(new phpseclib3\Math\BigInteger($total_received));
    //
    ?>
    <!-- total_received: <?php 
    // echo $total_received
    ?> -->
    <!-- total_received_eth: <?php 
    // echo $total_received_eth
    ?> -->
    <?php 
    //        // show only two digits after decimal point
    //        $total_received_eth_display = Token_ICO_calc_display_value($total_received_eth);
    //        $total_received_eth_display = sprintf(__('%1$s%2$s%3$s', 'token-ico'), $base_symbol, $total_received_eth_display[0], $total_received_eth_display[1]);
    //
    ?>
    <!-- total_received_eth_display: <?php 
    // echo $total_received_eth_display
    ?> -->
    <?php 
    //        $total_received_percent = 100 * $total_received_eth / doubleval($hardcap);
    //
    ?>
    <!-- total_received_percent: <?php 
    // echo $total_received_percent
    ?> -->
    <?php 
    //        $hardcap_display = Token_ICO_calc_display_value(doubleval($hardcap));
    //        $hardcap_display = sprintf(__('%1$s%2$s%3$s', 'token-ico'), $base_symbol, $hardcap_display[0], $hardcap_display[1]);
    //
    //        if ('' !== $softcap) {
    //            $soft_hard_percent = 100 * doubleval($softcap) / doubleval($hardcap);
    //            $soft_hard_remain_percent = (100 - $soft_hard_percent) / 2;
    //
    //            $softcap_display = Token_ICO_calc_display_value(doubleval($softcap));
    //            $softcap_display = sprintf(__('%1$s%2$s%3$s', 'token-ico'), $base_symbol, $softcap_display[0], $softcap_display[1]);
    //
    //            $js = '';
    //            // https://bootsnipp.com/snippets/featured/progress-bar-meter
    //            $ret =
    //'<div class="twbs"><div class="container-fluid token-ico-progress-shortcode">
    //    <div class="row token-ico-progress-content">
    //        <div class="col-12">
    //            <div class="progress">
    //                <div style="display:none" class="token-ico-progress-total-received-eth">' . $total_received_eth . '</div>
    //                <div class="progress-bar token-ico-progress-progress-bar" role="progressbar" aria-valuenow="' . $total_received_eth . '" aria-valuemin="0" aria-valuemax="' . $hardcap . '" style="width: ' . $total_received_percent . '%;" data-toggle="tooltip" data-placement="top" title="' . sprintf(__('%1$s%2$s', 'token-ico'), $total_received_eth, Token_ICO_getBlockchainCurrencyTicker()) . '">
    //                    <span class="sr-only token-ico-progress-percent-complete">' . sprintf(__('%s%% Complete', 'token-ico'), $total_received_percent) . '</span>
    //                </div>
    //            </div>
    //            <div class="progress-meter">
    //                <div class="meter meter-left" style="width: ' . $soft_hard_percent . '%;"><span class="meter-text token-ico-progress-total-display">' . $total_received_eth_display . '</span></div>
    //                <div class="meter meter-left" style="width: ' . $soft_hard_remain_percent . '%;"><span class="meter-text">' . sprintf(__('Soft: %s', 'token-ico'), '<span token-ico-progress-softcap-display>' . $softcap_display . '</span>') . '</span></div>
    //                <div class="meter meter-right" style="width: ' . $soft_hard_remain_percent . '%;"><span class="meter-text">' . sprintf(__('Hard: %s', 'token-ico'), '<span token-ico-progress-hardcap-display>' . $hardcap_display . '</span>') . '</span></div>
    //            </div>
    //        </div>
    //    </div>
    //</div></div>';
    //        } else {
    //            $js = '';
    //            // https://bootsnipp.com/snippets/featured/progress-bar-meter
    //            $ret =
    //'<div class="twbs"><div class="container-fluid token-ico-progress-shortcode">
    //    <div class="row token-ico-progress-content">
    //        <div class="col-12">
    //            <div class="progress">
    //                <div style="display:none" class="token-ico-progress-total-received-eth">' . $total_received_eth . '</div>
    //                <div class="progress-bar token-ico-progress-progress-bar" role="progressbar" aria-valuenow="' . $total_received_eth . '" aria-valuemin="0" aria-valuemax="' . $hardcap . '" style="width: ' . $total_received_percent . '%;" data-toggle="tooltip" data-placement="top" title="' . sprintf(__('%1$s%2$s', 'token-ico'), $total_received_eth, Token_ICO_getBlockchainCurrencyTicker()) . '">
    //                    <span class="sr-only token-ico-progress-percent-complete">' . sprintf(__('%s%% Complete', 'token-ico'), $total_received_percent) . '</span>
    //                </div>
    //            </div>
    //            <div class="progress-meter">
    //                <div class="meter meter-left" style="width: 50%;"><span class="meter-text token-ico-progress-total-display">' . $total_received_eth_display . '</span></div>
    //                <div class="meter meter-right" style="width: 50%;"><span class="meter-text">' . sprintf(__('Hard: %s', 'token-ico'), '<span token-ico-progress-hardcap-display>' . $hardcap_display . '</span>') . '</span></div>
    //            </div>
    //        </div>
    //    </div>
    //</div></div>';
    //        }
    Token_ICO_enqueue_scripts_();
    $js = '';
    // https://bootsnipp.com/snippets/featured/progress-bar-meter
    $ret = '<div class="token-ico-progress-shortcode-wrapper"' . ' data-crowdsale="' . $crowdsaleAddress . '" ' . ' data-softcap="' . $softcap . '" ' . ' data-hardcap="' . $hardcap . '" ' . '></div>';
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $js . $ret ) ) );
}

add_shortcode( 'token-ico-progress', 'Token_ICO_progress_shortcode' );
function Token_ICO_progress_value_shortcode( $attributes )
{
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'crowdsale' => '',
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    $blockchain_network = Token_ICO_getBlockchainNetwork();
    $crowdsaleAddress = ( !empty($attributes['crowdsale']) ? esc_attr( $attributes['crowdsale'] ) : (( !empty($options['crowdsaleAddress']) ? esc_attr( $options['crowdsaleAddress'] ) : "" )) );
    $cacheKey = "{$blockchain_network}:{$crowdsaleAddress}:balance";
    $total_received = get_transient( $cacheKey . "-token-ico" );
    if ( false === $total_received ) {
        switch ( $blockchain_network ) {
            case "mainnet":
                list( $error, $total_received ) = Token_ICO_get_total_received_blockcypher( $crowdsaleAddress, $cacheKey );
                if ( $error ) {
                    return '<div class="alert alert-danger"><strong>' . __( 'Error!', 'token-ico' ) . '</strong> ' . $error . '</div>';
                }
                break;
            default:
                list( $error, $total_received ) = Token_ICO_get_total_received_etherscan( $crowdsaleAddress, $cacheKey );
                if ( $error ) {
                    return '<div class="alert alert-danger"><strong>' . __( 'Error!', 'token-ico' ) . '</strong> ' . $error . '</div>';
                }
                break;
        }
    }
    $total_received_eth = _Token_ICO_wei_to_ether( new phpseclib3\Math\BigInteger( $total_received ) );
    $js = '';
    // https://bootsnipp.com/snippets/featured/progress-bar-meter
    $ret = '<div class="twbs"><div class="container-fluid token-ico-progress-shortcode token-ico-progress-value-shortcode">
<div class="row token-ico-progress-content token-ico-progress-value-content">
    <div class="col-12">
        <div style="display:none" class="token-ico-progress-value-total-received-eth">' . $total_received_eth . '</div>
        <div class="token-ico-progress-value-total-display">' . $total_received_eth . ' ETH</div>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $js . $ret ) ) );
}

add_shortcode( 'token-ico-progress-value', 'Token_ICO_progress_value_shortcode' );
function Token_ICO_progress_percent_shortcode( $attributes )
{
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'crowdsale' => '',
        'hardcap'   => '',
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    $blockchain_network = Token_ICO_getBlockchainNetwork();
    $crowdsaleAddress = ( !empty($attributes['crowdsale']) ? esc_attr( $attributes['crowdsale'] ) : (( !empty($options['crowdsaleAddress']) ? esc_attr( $options['crowdsaleAddress'] ) : "" )) );
    $hardcap = ( !empty($attributes['hardcap']) ? esc_attr( $attributes['hardcap'] ) : (( !empty($options['hardcap']) ? esc_attr( $options['hardcap'] ) : "10000" )) );
    $cacheKey = "{$blockchain_network}:{$crowdsaleAddress}:balance";
    $total_received = get_transient( $cacheKey . "-token-ico" );
    if ( false === $total_received ) {
        switch ( $blockchain_network ) {
            case "mainnet":
                list( $error, $total_received ) = Token_ICO_get_total_received_blockcypher( $crowdsaleAddress, $cacheKey );
                if ( $error ) {
                    return '<div class="alert alert-danger"><strong>' . __( 'Error!', 'token-ico' ) . '</strong> ' . $error . '</div>';
                }
                break;
            default:
                list( $error, $total_received ) = Token_ICO_get_total_received_etherscan( $crowdsaleAddress, $cacheKey );
                if ( $error ) {
                    return '<div class="alert alert-danger"><strong>' . __( 'Error!', 'token-ico' ) . '</strong> ' . $error . '</div>';
                }
                break;
        }
    }
    $total_received_eth = _Token_ICO_wei_to_ether( new phpseclib3\Math\BigInteger( $total_received ) );
    $total_received_percent = 100 * $total_received_eth / doubleval( $hardcap );
    $js = '';
    // https://bootsnipp.com/snippets/featured/progress-bar-meter
    $ret = '<div class="twbs"><div class="container-fluid token-ico-progress-shortcode token-ico-progress-percent-shortcode">
<div class="row token-ico-progress-content token-ico-progress-percent-content">
    <div class="col-12">
        <div style="display:none" class="token-ico-progress-percent-total-received-eth">' . $total_received_eth . '</div>
        <div><span class="token-ico-progress-percent-display">' . $total_received_percent . '</span><span class="token-ico-progress-percent-display-percent">%</span></div>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $js . $ret ) ) );
}

add_shortcode( 'token-ico-progress-percent', 'Token_ICO_progress_percent_shortcode' );
function Token_ICO_balance_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'tokenname'   => '',
        'label'       => __( 'Account', 'token-ico' ),
        'placeholder' => __( 'Input your Network account address', 'token-ico' ),
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    $tokenName = ( !empty($attributes['tokenname']) ? $attributes['tokenname'] : (( !empty($options['tokenname']) ? esc_attr( $options['tokenname'] ) : "TESTCOIN" )) );
    $label = ( !empty($attributes['label']) ? $attributes['label'] : __( 'Account', 'token-ico' ) );
    $placeholder = ( !empty($attributes['placeholder']) ? $attributes['placeholder'] : __( 'Input your Network account address', 'token-ico' ) );
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-balance-shortcode">
<div class="row token-ico-balance-account-wrapper hidden" hidden>
    <div class="col-12">
        <div class="form-group">
            <label class="control-label" for="token-ico-balance-account">' . $label . '</label>
            <div class="input-group" style="margin-top: 8px">
                <input type="text"
                        value=""
                        placeholder="' . $placeholder . '"
                        id="token-ico-balance-account"
                        class="form-control">
            </div>
        </div>
    </div>
</div>
<div class="row token-ico-balance-content">
    <div class="col-md-6 col-6 token-ico-balance-value-wrapper">
        <div class="token-ico-balance-value">0</div>
    </div>
    <div class="col-md-6 col-6 token-ico-balance-token-name-wrapper">
        <div class="token-ico-balance-token-name">' . $tokenName . '</div>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $js . $ret ) ) );
}

add_shortcode( 'token-ico-balance', 'Token_ICO_balance_shortcode' );
function Token_ICO_referral_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'url'         => '',
        'label'       => __( 'Account', 'token-ico' ),
        'placeholder' => __( 'Input your Network account address', 'token-ico' ),
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    $url = ( !empty($attributes['url']) ? $attributes['url'] : '' );
    $label = ( !empty($attributes['label']) ? $attributes['label'] : __( 'Account', 'token-ico' ) );
    $placeholder = ( !empty($attributes['placeholder']) ? $attributes['placeholder'] : __( 'Input your Network account address', 'token-ico' ) );
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-referral-shortcode">
<div class="row token-ico-referral-account-wrapper">
    <div class="col-12">
        <div class="form-group">
            <label class="control-label" for="token-ico-referral-account">' . $label . '</label>
            <div class="input-group" style="margin-top: 8px">
                <input type="text"
                        value=""
                        placeholder="' . $placeholder . '"
                        id="token-ico-referral-account"
                        class="form-control">
            </div>
        </div>
    </div>
</div>
<div class="row token-ico-referral-content">
    <div class="col-12">
        <div class="form-group">
            <label class="control-label" for="token-ico-referral-link">' . __( 'Referral link', 'token-ico' ) . '</label>
            <div class="input-group" style="margin-top: 8px">
                <input type="hidden"
                        value="' . $url . '"
                        id="token-ico-referral-url">
                <input style="cursor: text;" type="text" disabled="disabled"
                        value=""
                        data-clipboard-action="copy"
                        id="token-ico-referral-link"
                        class="form-control">
                <span class="input-group-btn">
                    <button class="button btn btn-default ico-copy-button" type="button"
                            data-input-id="token-ico-referral-link"
                            title="' . __( 'Copy', 'token-ico' ) . '">
                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $js . $ret ) ) );
}

add_shortcode( 'token-ico-referral', 'Token_ICO_referral_shortcode' );
function Token_ICO_purchases_shortcode( $attributes )
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_options ;
    $attributes = shortcode_atts( array(
        'tokenname'   => '',
        'label'       => __( 'Account', 'token-ico' ),
        'placeholder' => __( 'Input your Network account address', 'token-ico' ),
    ), $attributes, 'token-ico' );
    $options = $Token_ICO_options;
    $tokenName = ( !empty($attributes['tokenname']) ? $attributes['tokenname'] : (( !empty($options['tokenname']) ? esc_attr( $options['tokenname'] ) : "TESTCOIN" )) );
    $label = ( !empty($attributes['label']) ? $attributes['label'] : __( 'Account', 'token-ico' ) );
    $placeholder = ( !empty($attributes['placeholder']) ? $attributes['placeholder'] : __( 'Input your Network account address', 'token-ico' ) );
    $js = '';
    $ret = '<div class="twbs"><div class="container-fluid token-ico-purchases-shortcode">
<div class="row token-ico-purchases-account-chk-wrapper">
    <div class="col-12">
        <div class="form-check form-check-inline">
            <input type="checkbox"
                    value=""
                    id="token-ico-purchases-account-chk"
                    class="form-check-input">
            <label class="form-check-label" for="token-ico-purchases-account-chk">' . __( 'My account only', 'token-ico' ) . '</label>
        </div>
    </div>
</div>
<div class="row token-ico-purchases-account-wrapper hidden" hidden>
    <div class="col-12">
        <div class="form-group">
            <label class="control-label" for="token-ico-purchases-account">' . $label . '</label>
            <div class="input-group" style="margin-top: 8px">
                <input type="text"
                        value=""
                        placeholder="' . $placeholder . '"
                        id="token-ico-purchases-account"
                        class="form-control">
            </div>
        </div>
    </div>
</div>
<div class="row token-ico-purchases-table-wrapper">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped table-condensed token-ico-purchases-table">
                <thead>
                    <tr>
                        <th>' . __( '#', 'token-ico' ) . '</th>
                        <th>' . __( 'Amount', 'token-ico' ) . '</th>
                        <th>' . sprintf( __( 'Amount, %s', 'token-ico' ), $tokenName ) . '</th>
                        <th>' . __( 'Date', 'token-ico' ) . '</th>
                        <th>' . __( 'Tx', 'token-ico' ) . '</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div></div>';
    Token_ICO_enqueue_scripts_();
    return $js . str_replace( "\n", " ", str_replace( "\r", " ", str_replace( "\t", " ", $js . $ret ) ) );
}

add_shortcode( 'token-ico-purchases', 'Token_ICO_purchases_shortcode' );
function Token_ICO_enqueue_scripts_()
{
    wp_enqueue_style( 'token-ico' );
    //        wp_enqueue_script('web3');
    wp_enqueue_script( 'token-ico-main' );
}

function Token_ICO_stylesheet()
{
    global  $Token_ICO_plugin_url_path ;
    $deps = array( 'font-awesome', 'bootstrap-token-ico' );
    $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
    
    if ( !wp_style_is( 'font-awesome', 'queue' ) && !wp_style_is( 'font-awesome', 'done' ) ) {
        wp_dequeue_style( 'font-awesome' );
        wp_deregister_style( 'font-awesome' );
        wp_register_style(
            'font-awesome',
            $Token_ICO_plugin_url_path . "/css/font-awesome{$min}.css",
            array(),
            '4.7.0'
        );
    }
    
    
    if ( !wp_style_is( 'bootstrap-token-ico', 'queue' ) && !wp_style_is( 'bootstrap-token-ico', 'done' ) ) {
        wp_dequeue_style( 'bootstrap-token-ico' );
        wp_deregister_style( 'bootstrap-token-ico' );
        wp_register_style(
            'bootstrap-token-ico',
            $Token_ICO_plugin_url_path . "/css/bootstrap-ns{$min}.css",
            array(),
            '4.0.0'
        );
    }
    
    
    if ( !wp_style_is( 'token-ico', 'queue' ) && !wp_style_is( 'token-ico', 'done' ) ) {
        wp_dequeue_style( 'token-ico' );
        wp_deregister_style( 'token-ico' );
        wp_register_style(
            'token-ico',
            $Token_ICO_plugin_url_path . '/token-ico.css',
            $deps,
            '2.3.9'
        );
    }

}

add_action( 'wp_enqueue_scripts', 'Token_ICO_stylesheet', 20 );
function Token_ICO_get_rate_data_fiat()
{
    global  $Token_ICO_options ;
    if ( empty($Token_ICO_options['openexchangeratesAppId']) ) {
        return "null";
    }
    $openexchangeratesAppId = esc_attr( $Token_ICO_options['openexchangeratesAppId'] );
    $openexchangeratesEndpoint = "https://openexchangerates.org/api/latest.json?app_id=" . $openexchangeratesAppId;
    $rateDataFiat = get_transient( "openexchangerates_rate_data-token-ico" );
    
    if ( false === $rateDataFiat ) {
        $rateDataFiat = "null";
        $response = wp_remote_get( $openexchangeratesEndpoint, array(
            'sslverify' => false,
        ) );
        ?>
        <!-- LOG: openexchangerates.org API is called -->
        <?php 
        
        if ( !is_wp_error( $response ) ) {
            $http_code = wp_remote_retrieve_response_code( $response );
            
            if ( 200 == $http_code ) {
                $body = wp_remote_retrieve_body( $response );
                
                if ( $body ) {
                    $j = json_decode( $body, true );
                    
                    if ( isset( $j["rates"] ) ) {
                        //  "rates": {
                        //    "AED": 3.672896,
                        //    "AFN": 69.496503,
                        //    "ALL": 107.678684,
                        $rateDataFiat = json_encode( $j["rates"] );
                        set_transient( "openexchangerates_rate_data-token-ico", $rateDataFiat, 60 * 60 );
                        ?>
                        <!-- LOG: openexchangerates.org API call result is saved -->
                        <?php 
                    }
                
                }
            
            }
        
        }
    
    }
    
    return $rateDataFiat;
}

function Token_ICO_get_rate_data()
{
    //        global $Token_ICO_options;
    $url = Token_ICO_get_ethprice_api_url();
    $rateData = get_transient( "rate_data-token-ico" );
    if ( false !== $rateData ) {
        return [ null, $rateData ];
    }
    //        Token_ICO_log("Token_ICO_get_rate_data:" . $url);
    $response = wp_remote_get( $url, array(
        'sslverify' => false,
    ) );
    ?>
    <!-- LOG: etherscan.io total_received API is called -->
    <?php 
    
    if ( is_wp_error( $response ) ) {
        $error_string = $response->get_error_message();
        Token_ICO_log( "Token_ICO_get_rate_data:" . $error_string );
        return array( $error_string, null );
    }
    
    $http_code = wp_remote_retrieve_response_code( $response );
    
    if ( 200 != $http_code ) {
        Token_ICO_log( "Token_ICO_get_rate_data: Result code is not 200" . $http_code );
        return array( __( "Result code is not 200", 'token-ico' ), null );
    }
    
    $body = wp_remote_retrieve_body( $response );
    //        Token_ICO_log("Token_ICO_get_rate_data: body=" . $body);
    
    if ( !$body ) {
        Token_ICO_log( "Token_ICO_get_rate_data: Empty body" );
        return array( __( "Empty body", 'token-ico' ), null );
    }
    
    $j = json_decode( $body, true );
    
    if ( !isset( $j["status"] ) ) {
        Token_ICO_log( "Token_ICO_get_rate_data: No status field" . $body );
        return array( __( "No status field", 'token-ico' ), null );
    }
    
    
    if ( "1" !== $j["status"] ) {
        Token_ICO_log( "Token_ICO_get_rate_data: Bad status field" . $body );
        return array( __( "Bad status field", 'token-ico' ), null );
    }
    
    
    if ( !isset( $j["result"] ) ) {
        Token_ICO_log( "Token_ICO_get_rate_data: No result field" . $body );
        return array( __( "No result field", 'token-ico' ), null );
    }
    
    $rateData = json_encode( $j["result"] );
    if ( empty($rateData) ) {
        $rateData = "null";
    }
    set_transient( "rate_data-token-ico", $rateData, 5 * 60 );
    ?>
    <!-- LOG: etherscan.io total_received API call result is stored -->
    <?php 
    return array( null, $rateData );
}

function Token_ICO_getBlockchainNetwork()
{
    global  $Token_ICO_options ;
    $blockchainNetwork = 'mainnet';
    if ( !isset( $Token_ICO_options['blockchain_network'] ) ) {
        return $blockchainNetwork;
    }
    if ( empty($Token_ICO_options['blockchain_network']) ) {
        return $blockchainNetwork;
    }
    $blockchainNetwork = esc_attr( $Token_ICO_options['blockchain_network'] );
    return $blockchainNetwork;
}

function Token_ICO_getWeb3Endpoint()
{
    global  $Token_ICO_options ;
    
    $web3Endpoint = ( isset( $Token_ICO_options['web3Endpoint'] ) ? esc_attr( $Token_ICO_options['web3Endpoint'] ) : '' );
    if ( !empty($web3Endpoint) ) {
        return $web3Endpoint;
    }
    
    $infuraApiKey = ( isset( $Token_ICO_options['infuraApiKey'] ) ? esc_attr( $Token_ICO_options['infuraApiKey'] ) : '' );
    $blockchainNetwork = Token_ICO_getBlockchainNetwork();
    if ( empty($blockchainNetwork) ) {
        $blockchainNetwork = 'mainnet';
    }
    $web3Endpoint = "https://" . esc_attr( $blockchainNetwork ) . ".infura.io/v3/" . esc_attr( $infuraApiKey );
    return $web3Endpoint;
}

function Token_ICO_getBlockchainCurrencyTicker()
{
    global  $Token_ICO_options ;
    $currency_ticker = "ETH";
    
    $currency_ticker = ( isset( $Token_ICO_options['currency_ticker'] ) ? esc_attr( $Token_ICO_options['currency_ticker'] ) : 'ETH' );
    if ( !empty($currency_ticker) ) {
        return $currency_ticker;
    }
    
    return $currency_ticker;
}

function Token_ICO_getBlockchainCurrencyTickerName()
{
    global  $Token_ICO_options ;
    $currency_ticker_name = "Ether";
    
    $currency_ticker_name = ( isset( $Token_ICO_options['currency_ticker_name'] ) ? esc_attr( $Token_ICO_options['currency_ticker_name'] ) : 'Ether' );
    if ( !empty($currency_ticker_name) ) {
        return $currency_ticker_name;
    }
    
    return $currency_ticker_name;
}

function Token_ICO_getTokenStandardName()
{
    global  $Token_ICO_options ;
    $token_standard_name = "ERC20";
    
    $token_standard_name = ( isset( $Token_ICO_options['token_standard_name'] ) ? esc_attr( $Token_ICO_options['token_standard_name'] ) : 'ERC20' );
    if ( !empty($token_standard_name) ) {
        return $token_standard_name;
    }
    
    return $token_standard_name;
}

function Token_ICO_get_txhash_path( $txHash )
{
    return sprintf( Token_ICO_get_txhash_path_template(), $txHash );
}

function Token_ICO_get_txhash_path_template()
{
    global  $Token_ICO_options ;
    
    $view_transaction_url = ( isset( $Token_ICO_options['view_transaction_url'] ) ? esc_attr( $Token_ICO_options['view_transaction_url'] ) : '' );
    if ( !empty($view_transaction_url) ) {
        return $view_transaction_url;
    }
    
    $blockchainNetwork = Token_ICO_getBlockchainNetwork();
    $txHashPath = '%s';
    switch ( $blockchainNetwork ) {
        case 'mainnet':
            $txHashPath = 'https://etherscan.io/tx/%s';
            break;
        case 'ropsten':
            $txHashPath = 'https://ropsten.etherscan.io/tx/%s';
            break;
        case 'rinkeby':
            $txHashPath = 'https://rinkeby.etherscan.io/tx/%s';
            break;
        case 'goerli':
            $txHashPath = 'https://goerli.etherscan.io/tx/%s';
        case 'kovan':
            $txHashPath = 'https://kovan.etherscan.io/tx/%s';
            break;
        case 'bsc':
            $txHashPath = 'https://bscscan.com/tx/%s';
            break;
        case 'bsctest':
            $txHashPath = 'https://testnet.bscscan.com/tx/%s';
            break;
        default:
            break;
    }
    return $txHashPath;
}

function Token_ICO_get_address_path( $address )
{
    return sprintf( Token_ICO_get_address_path_template(), $address );
}

function Token_ICO_get_address_path_template()
{
    global  $Token_ICO_options ;
    
    $view_address_url = ( isset( $Token_ICO_options['view_address_url'] ) ? esc_attr( $Token_ICO_options['view_address_url'] ) : '' );
    if ( !empty($view_address_url) ) {
        return $view_address_url;
    }
    
    $blockchainNetwork = Token_ICO_getBlockchainNetwork();
    $addressPath = '%s';
    switch ( $blockchainNetwork ) {
        case 'mainnet':
            $addressPath = 'https://etherscan.io/address/%s';
            break;
        case 'ropsten':
            $addressPath = 'https://ropsten.etherscan.io/address/%s';
            break;
        case 'rinkeby':
            $addressPath = 'https://rinkeby.etherscan.io/address/%s';
            break;
        case 'goerli':
            $addressPath = 'https://goerli.etherscan.io/address/%s';
        case 'kovan':
            $addressPath = 'https://kovan.etherscan.io/address/%s';
            break;
        case 'bsc':
            $txHashPath = 'https://bscscan.com/address/%s';
            break;
        case 'bsctest':
            $txHashPath = 'https://testnet.bscscan.com/address/%s';
            break;
        default:
            break;
    }
    return $addressPath;
}

function Token_ICO_get_tx_list_api_url_template()
{
    global  $Token_ICO_options ;
    $blockchainNetwork = Token_ICO_getBlockchainNetwork();
    
    $tx_list_api_url = ( isset( $Token_ICO_options['tx_list_api_url'] ) ? esc_attr( $Token_ICO_options['tx_list_api_url'] ) : '' );
    if ( !empty($tx_list_api_url) ) {
        return $tx_list_api_url;
    }
    
    $options = stripslashes_deep( $Token_ICO_options );
    $etherscanApiKey = ( !empty($options['etherscanApiKey']) ? esc_attr( $options['etherscanApiKey'] ) : '' );
    $blockchain_network = '';
    if ( 'mainnet' !== $blockchainNetwork ) {
        $blockchain_network = '-' . $blockchainNetwork;
    }
    $tx_list_api_url = 'https://api' . $blockchain_network . '.etherscan.io/api?module=account&action=txlist&address=%s&startblock=0&endblock=99999999&sort=desc&apikey=' . $etherscanApiKey;
    return $tx_list_api_url;
}

function Token_ICO_get_internal_tx_list_api_url_template()
{
    global  $Token_ICO_options ;
    $blockchainNetwork = Token_ICO_getBlockchainNetwork();
    
    $internal_tx_list_api_url = ( isset( $Token_ICO_options['internal_tx_list_api_url'] ) ? esc_attr( $Token_ICO_options['internal_tx_list_api_url'] ) : '' );
    if ( !empty($internal_tx_list_api_url) ) {
        return $internal_tx_list_api_url;
    }
    
    $options = stripslashes_deep( $Token_ICO_options );
    $etherscanApiKey = ( !empty($options['etherscanApiKey']) ? esc_attr( $options['etherscanApiKey'] ) : '' );
    $blockchain_network = '';
    if ( 'mainnet' !== $blockchainNetwork ) {
        $blockchain_network = '-' . $blockchainNetwork;
    }
    $internal_tx_list_api_url = 'https://api' . $blockchain_network . '.etherscan.io/api?module=account&action=txlistinternal&address=%s&startblock=0&endblock=99999999&sort=desc&apikey=' . $etherscanApiKey;
    return $internal_tx_list_api_url;
}

function Token_ICO_get_token_tx_list_api_url_template()
{
    global  $Token_ICO_options ;
    $blockchainNetwork = Token_ICO_getBlockchainNetwork();
    
    $token_tx_list_api_url = ( isset( $Token_ICO_options['token_tx_list_api_url'] ) ? $Token_ICO_options['token_tx_list_api_url'] : '' );
    if ( !empty($token_tx_list_api_url) ) {
        return $token_tx_list_api_url;
    }
    
    $options = stripslashes_deep( $Token_ICO_options );
    $etherscanApiKey = ( !empty($options['etherscanApiKey']) ? esc_attr( $options['etherscanApiKey'] ) : '' );
    $blockchain_network = '';
    if ( 'mainnet' !== $blockchainNetwork ) {
        $blockchain_network = '-' . $blockchainNetwork;
    }
    $token_tx_list_api_url = 'https://api' . $blockchain_network . '.etherscan.io/api?module=account&action=tokentx&address=%s&startblock=0&endblock=99999999&sort=desc&apikey=' . $etherscanApiKey;
    return $token_tx_list_api_url;
}

function Token_ICO_get_ethprice_api_url()
{
    global  $Token_ICO_options ;
    
    $ethprice_api_url = ( isset( $Token_ICO_options['ethprice_api_url'] ) ? $Token_ICO_options['ethprice_api_url'] : '' );
    if ( !empty($ethprice_api_url) ) {
        return $ethprice_api_url;
    }
    
    $options = stripslashes_deep( $Token_ICO_options );
    $etherscanApiKey = ( !empty($options['etherscanApiKey']) ? esc_attr( $options['etherscanApiKey'] ) : '' );
    $ethprice_api_url = "https://api.etherscan.io/api?module=stats&action=ethprice&apikey=" . $etherscanApiKey;
    return $ethprice_api_url;
}

function Token_ICO_enqueue_script()
{
    global  $Token_ICO_plugin_url_path ;
    global  $Token_ICO_plugin_dir ;
    global  $Token_ICO_options ;
    $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
    //        if (!wp_script_is('web3', 'queue') && !wp_script_is('web3', 'done')) {
    //            wp_dequeue_script('web3');
    //            wp_deregister_script('web3');
    //            wp_register_script(
    //                    'web3',
    //                    $Token_ICO_plugin_url_path . "/web3{$min}.js", array('jquery'), '0.20.6'
    //            );
    //        }
    //        if (!wp_script_is('token-ico', 'queue') && !wp_script_is('token-ico', 'done')) {
    //            wp_dequeue_script('token-ico');
    //            wp_deregister_script('token-ico');
    //            wp_register_script(
    //                    'token-ico',
    //                    $Token_ICO_plugin_url_path . '/token-ico.js', array('jquery', 'web3'), '2.3.9'
    //            );
    //        }
    // 1. runtime~main
    // 2. vendors
    // 3. main
    $runtimeMain = null;
    $vendors = null;
    $main = null;
    $files = scandir( $Token_ICO_plugin_dir . "/js/" );
    foreach ( $files as $filename ) {
        // @see https://stackoverflow.com/a/173876/4256005
        $ext = pathinfo( $filename, PATHINFO_EXTENSION );
        if ( 'js' !== $ext ) {
            continue;
        }
        $parts = explode( '.', $filename );
        if ( count( $parts ) < 3 ) {
            continue;
        }
        list( $name, $versionHash, $ext ) = $parts;
        switch ( $name ) {
            case 'runtime~main':
                $runtimeMain = [ $filename, $versionHash ];
                break;
            case 'vendors':
                $vendors = [ $filename, $versionHash ];
                break;
            case 'main':
                $main = [ $filename, $versionHash ];
                break;
            default:
                break;
        }
    }
    
    if ( !wp_script_is( 'token-ico-runtime-main', 'queue' ) && !wp_script_is( 'token-ico-runtime-main', 'done' ) ) {
        wp_dequeue_script( 'token-ico-runtime-main' );
        wp_deregister_script( 'token-ico-runtime-main' );
        wp_register_script(
            'token-ico-runtime-main',
            $Token_ICO_plugin_url_path . "/js/" . $runtimeMain[0],
            array( 'jquery' ),
            $runtimeMain[1]
        );
    }
    
    
    if ( !wp_script_is( 'token-ico-vendors', 'queue' ) && !wp_script_is( 'token-ico-vendors', 'done' ) ) {
        wp_dequeue_script( 'token-ico-vendors' );
        wp_deregister_script( 'token-ico-vendors' );
        wp_register_script(
            'token-ico-vendors',
            $Token_ICO_plugin_url_path . "/js/" . $vendors[0],
            array( 'token-ico-runtime-main' ),
            $vendors[1]
        );
    }
    
    
    if ( !wp_script_is( 'token-ico-main', 'queue' ) && !wp_script_is( 'token-ico-main', 'done' ) ) {
        wp_dequeue_script( 'token-ico-main' );
        wp_deregister_script( 'token-ico-main' );
        wp_register_script(
            'token-ico-main',
            $Token_ICO_plugin_url_path . "/js/" . $main[0],
            array( 'token-ico-vendors', 'wp-i18n' ),
            $main[1]
        );
    }
    
    //        wp_enqueue_script('token-ico-main');
    $attributes = shortcode_atts( array(), array(), 'token-ico' );
    $options = $Token_ICO_options;
    $tokenName = ( !empty($attributes['tokenname']) ? $attributes['tokenname'] : (( !empty($options['tokenname']) ? esc_attr( $options['tokenname'] ) : "TESTCOIN" )) );
    $blockchain_network = Token_ICO_getBlockchainNetwork();
    $coinList = ( !empty($options['coinList']) ? esc_attr( $options['coinList'] ) : '' );
    // remove all whitespaces
    $coinList = preg_replace( '/\\s+/', '', $coinList );
    $softcap = ( !empty($options['softcap']) ? esc_attr( $options['softcap'] ) : "" );
    $hardcap = ( !empty($options['hardcap']) ? esc_attr( $options['hardcap'] ) : "10000" );
    $private_sale_seed = "0";
    $private_sale_seed = ( !empty($options['private_sale_seed']) ? esc_attr( $options['private_sale_seed'] ) : "0" );
    $base_currency = ( !empty($options['base_currency']) ? esc_attr( $options['base_currency'] ) : Token_ICO_getBlockchainCurrencyTicker() );
    $base_symbol = ( !empty($options['base_symbol']) ? esc_attr( $options['base_symbol'] ) : '' );
    $icoperiod = ( !empty($options['icoperiod']) ? esc_attr( $options['icoperiod'] ) : '30' );
    $icostart = ( !empty($options['icostart']) ? $options['icostart'] : '' );
    $tokenAddress = ( !empty($options['tokenAddress']) ? $options['tokenAddress'] : '' );
    $crowdsaleAddress = ( !empty($options['crowdsaleAddress']) ? $options['crowdsaleAddress'] : '' );
    $decimals = ( !empty($options['decimals']) ? esc_attr( $options['decimals'] ) : '1000000000000000000' );
    $tokenRate = ( !empty($options['tokenRate']) ? esc_attr( $options['tokenRate'] ) : '1' );
    $contractABI = trim( ( !empty($options['contractABI']) ? $options['contractABI'] : '[]' ) );
    if ( empty($contractABI) ) {
        $contractABI = '[]';
    }
    $bounty = '[]';
    $txData = '0x';
    $referralArgumentName = 'icoreferral';
    
    $bounty = trim( ( !empty($options['bounty']) ? $options['bounty'] : '[]' ) );
    if ( empty($bounty) ) {
        $bounty = '[]';
    }
    if ( !empty($options['txData']) ) {
        $txData = $options['txData'];
    }
    if ( !empty($options['referralArgumentName']) ) {
        $referralArgumentName = $options['referralArgumentName'];
    }
    
    $gaslimit = ( !empty($options['gaslimit']) ? esc_attr( $options['gaslimit'] ) : "200000" );
    $gasprice = ( !empty($options['gasprice']) ? esc_attr( $options['gasprice'] ) : "21" );
    $rateData = Token_ICO_get_rate_data();
    $rateDataEmpty = 'null';
    $rateDataFiat = Token_ICO_get_rate_data_fiat();
    $coins = array();
    if ( $coinList ) {
        $coins = explode( ',', str_replace( " ", "", $coinList ) );
    }
    wp_localize_script( 'token-ico-main', 'ico', array(
        'coins'                        => esc_html( json_encode( $coins ) ),
        'tokenName'                    => esc_html( $tokenName ),
        'rateData'                     => esc_html( ( is_null( $rateData[0] ) ? ( !empty($rateData[1]) ? $rateData[1] : $rateDataEmpty ) : $rateDataEmpty ) ),
        'rateDataFiat'                 => esc_html( $rateDataFiat ),
        'tokenRate'                    => esc_html( $tokenRate ),
        'percents'                     => esc_html( $bounty ),
        'icoperiod'                    => esc_html( $icoperiod ),
        'start'                        => esc_html( $icostart ),
        'web3Endpoint'                 => esc_html( Token_ICO_getWeb3Endpoint() ),
        'blockchain_network'           => esc_html( $blockchain_network ),
        'tokenAddress'                 => esc_html( $tokenAddress ),
        'crowdsaleAddress'             => esc_html( $crowdsaleAddress ),
        'decimals'                     => esc_html( $decimals ),
        'txData'                       => esc_html( $txData ),
        'referralArgumentName'         => esc_html( $referralArgumentName ),
        'gasLimit'                     => esc_html( $gaslimit ),
        'gasPrice'                     => esc_html( $gasprice ),
        'softcap'                      => esc_html( $softcap ),
        'hardcap'                      => esc_html( $hardcap ),
        'private_sale_seed'            => esc_html( $private_sale_seed ),
        'base_currency'                => esc_html( $base_currency ),
        'base_symbol'                  => esc_html( $base_symbol ),
        'currency_ticker'              => esc_html( Token_ICO_getBlockchainCurrencyTicker() ),
        'tokenTxListAPIURLTemplate'    => esc_html( Token_ICO_get_token_tx_list_api_url_template() ),
        'internalTxListAPIURLTemplate' => esc_html( Token_ICO_get_internal_tx_list_api_url_template() ),
        'txListAPIURLTemplate'         => esc_html( Token_ICO_get_tx_list_api_url_template() ),
        'txHashPathTemplate'           => esc_html( Token_ICO_get_txhash_path_template() ),
        'addressPathTemplate'          => esc_html( Token_ICO_get_address_path_template() ),
        'ethprice_api_url'             => esc_html( Token_ICO_get_ethprice_api_url() ),
        'str_download_metamask'        => __( 'Download MetaMask', 'token-ico' ),
        'str_unlock_metamask_account'  => __( 'Unlock your MetaMask account please', 'token-ico' ),
        'str_account_balance_failure'  => __( 'Failed to get account balance', 'token-ico' ),
        'str_mm_network_mismatch'      => __( 'MetaMask network mismatch. Choose another network or ask site administrator.', 'token-ico' ),
        'str_network_unknown'          => __( 'This is an unknown network.', 'token-ico' ),
        'str_copied_msg'               => __( 'Copied to clipboard', 'token-ico' ),
        'str_tx_rejected'              => __( 'You have rejected the token buy operation.', 'token-ico' ),
        'str_tx_success'               => __( 'Success! Tx hash: ', 'token-ico' ),
        'str_contract_get_failure'     => __( 'Failed to get contract', 'token-ico' ),
        'str_empty_address_error'      => __( 'Empty address requested for load_transactions!', 'token-ico' ),
        'str_table_days_label'         => __( ' days', 'token-ico' ),
        'str_table_hours_label'        => __( ' hours', 'token-ico' ),
        'str_table_minutes_label'      => __( ' minutes', 'token-ico' ),
        'str_kilo_label'               => __( 'K', 'token-ico' ),
        'str_mega_label'               => __( 'M', 'token-ico' ),
        'str_currency_display_format'  => __( '%1$s%2$s%3$s', 'token-ico' ),
        'str_percent_complete_format'  => __( '%s%% Complete', 'token-ico' ),
        'str_table_recently_label'     => __( 'recently', 'token-ico' ),
        'str_hard_cap_label'           => __( 'Hard:&nbsp;', 'token-ico' ),
        'str_soft_cap_label'           => __( 'Soft:&nbsp;', 'token-ico' ),
        'str_currency_unknown_error'   => sprintf( __( 'Currency is unknown or the %s is not configured correctly.', 'token-ico' ), __( "openexchangerates.org App Id", 'token-ico' ) ),
    ) );
}

add_action( 'wp_enqueue_scripts', 'Token_ICO_enqueue_script' );
/**
 * Admin Options
 */

if ( is_admin() ) {
    include_once $Token_ICO_plugin_dir . '/settings/ico.php';
    include_once $Token_ICO_plugin_dir . '/settings/widget.php';
    include_once $Token_ICO_plugin_dir . '/settings/blockchain.php';
    include_once $Token_ICO_plugin_dir . '/settings/api_keys.php';
    include_once $Token_ICO_plugin_dir . '/settings/advanced_blockchain.php';
    include_once $Token_ICO_plugin_dir . '/token-ico.admin.php';
}

function Token_ICO_add_menu_link()
{
    $page = add_options_page(
        __( 'TokenICO Settings', 'token-ico' ),
        __( 'TokenICO', 'token-ico' ),
        'manage_options',
        'token-ico',
        'Token_ICO_options_page'
    );
}

add_filter( 'admin_menu', 'Token_ICO_add_menu_link' );
// Place in Option List on Settings > Plugins page
function Token_ICO_actlinks( $links, $file )
{
    // Static so we don't call plugin_basename on every plugin row.
    static  $this_plugin ;
    if ( !$this_plugin ) {
        $this_plugin = plugin_basename( __FILE__ );
    }
    
    if ( $file == $this_plugin ) {
        $settings_link = '<a href="options-general.php?page=token-ico">' . __( 'Settings', 'token-ico' ) . '</a>';
        array_unshift( $links, $settings_link );
        // before other links
    }
    
    return $links;
}

add_filter(
    'plugin_action_links',
    'Token_ICO_actlinks',
    10,
    2
);
