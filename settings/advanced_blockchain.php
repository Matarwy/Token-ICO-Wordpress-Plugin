<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter( 'token_ico_settings_tabs', 'Token_ICO_advanced_blockchain_settings_tabs_hook', 30, 1 );
function Token_ICO_advanced_blockchain_settings_tabs_hook( $possible_screens ) {
    $possible_screens['advanced_blockchain'] = esc_html(__( 'Advanced Blockchain', 'token-ico' ));
    return $possible_screens;
}

add_filter( 'token_ico_get_save_options', 'Token_ICO_advanced_blockchain_get_save_options_hook', 30, 2 );
function Token_ICO_advanced_blockchain_get_save_options_hook( $new_options, $current_screen ) {
    if ('advanced_blockchain' !== $current_screen) {
        return $new_options;
    }


    $new_options['web3Endpoint'] =
        ( ! empty( $_POST['Token_ICO_web3Endpoint'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_web3Endpoint']) : '';

    $new_options['token_standard_name'] =
        ( ! empty( $_POST['Token_ICO_token_standard_name'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_token_standard_name']) : '';

    $new_options['currency_ticker'] =
        ( ! empty( $_POST['Token_ICO_currency_ticker'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_currency_ticker']) : 'ETH';

    $new_options['currency_ticker_name'] =
        ( ! empty( $_POST['Token_ICO_currency_ticker_name'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_currency_ticker_name']) : __('Ether', 'token-ico');

    $new_options['view_transaction_url'] =
        ( ! empty( $_POST['Token_ICO_view_transaction_url'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_view_transaction_url']) : '';

    $new_options['view_address_url'] =
        ( ! empty( $_POST['Token_ICO_view_address_url'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_view_address_url']) : '';

    $new_options['ethprice_api_url'] =
        ( ! empty( $_POST['Token_ICO_ethprice_api_url'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_ethprice_api_url']) : '';

    $new_options['tx_list_api_url'] =
        ( ! empty( $_POST['Token_ICO_tx_list_api_url'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_tx_list_api_url']) : '';

    $new_options['internal_tx_list_api_url'] =
        ( ! empty( $_POST['Token_ICO_internal_tx_list_api_url'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_internal_tx_list_api_url']) : '';

    $new_options['token_tx_list_api_url'] =
        ( ! empty( $_POST['Token_ICO_token_tx_list_api_url'] ) ) ?
        sanitize_text_field($_POST['Token_ICO_token_tx_list_api_url']) : '';


    return $new_options;
}

add_filter( 'token_ico_print_options', 'Token_ICO_advanced_blockchain_print_options_hook', 30, 2 );
function Token_ICO_advanced_blockchain_print_options_hook( $options, $current_screen ) {
    if ('advanced_blockchain' !== $current_screen) {
        return;
    }
?>
<tr valign="top">
<td scope="row" colspan="2"><blockquote>
<?php _e("Use these settings only if you want to use Network node other than infura.io, or completely another EVM-compatible blockchain like Quorum, BSC, etc.", 'token-ico'); ?>
</blockquote></td>

<tr valign="top">
<th scope="row"><?php _e("Network Node JSON-RPC Endpoint", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_web3Endpoint" type="text" maxlength="1024" placeholder="<?php _e("Put your Network Node JSON-RPC Endpoint here", 'token-ico'); ?>" value="<?php echo ! empty( $options['web3Endpoint'] ) ? esc_attr( $options['web3Endpoint'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The Network Node JSON-RPC Endpoint URI. This is an advanced setting. Use with care. This setting supercedes the "%1$s" setting.', 'token-ico')
            , __("Infura.io API Key", 'token-ico')
        )?></p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Base crypto currency symbol", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input class="text" name="Token_ICO_currency_ticker" type="text" maxlength="42" placeholder="<?php _e("ETH for Ethereum, BNB for Binance Smart Chain", 'token-ico'); ?>" value="<?php echo ! empty( $options['currency_ticker'] ) ? esc_attr( $options['currency_ticker'] ) : 'ETH'; ?>">
        <p><?php echo sprintf(
                __('The base crypto currency ticker for the blockchain configured, like ETH for Ethereum or BNB for Binance Smart Chain. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
                , __("Network Node JSON-RPC Endpoint", 'token-ico')
            )?></p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Base crypto currency name", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input class="text" name="Token_ICO_currency_ticker_name" type="text" maxlength="42" placeholder="<?php _e("Ether or Binance Coin", 'token-ico'); ?>" value="<?php echo ! empty( $options['currency_ticker_name'] ) ? esc_attr( $options['currency_ticker_name'] ) : 'Ether'; ?>">
        <p><?php echo sprintf(
                __('The base crypto currency name for the blockchain configured, like Ether for Ethereum or Binance Coin for Binance Smart Chain. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
                , __("Network Node JSON-RPC Endpoint", 'token-ico')
            )?></p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Token standard name", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_token_standard_name" type="text" maxlength="1024" placeholder="<?php _e("Put token standard name here", 'token-ico'); ?>" value="<?php echo ! empty( $options['token_standard_name'] ) ? esc_attr( $options['token_standard_name'] ) : 'ERC20'; ?>">
        <p><?php echo sprintf(
            __('The crypto currency token standard name for the blockchain configured, like ERC20 for Ethereum or BEP20 for Binance Smart Chain. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Transaction explorer URL", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_view_transaction_url" type="text" maxlength="1024" placeholder="<?php _e("Put your Transaction explorer URL template here", 'token-ico'); ?>" value="<?php echo ! empty( $options['view_transaction_url'] ) ? esc_attr( $options['view_transaction_url'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The Network transaction explorer URL template. It should contain %%s pattern to insert tx hash to. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Address explorer URL", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_view_address_url" type="text" maxlength="1024" placeholder="<?php _e("Put your Address explorer URL template here", 'token-ico'); ?>" value="<?php echo ! empty( $options['view_address_url'] ) ? esc_attr( $options['view_address_url'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The Network address explorer URL template. It should contain %%s pattern to insert address hash to. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("ETH Price API URL", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_ethprice_api_url" type="text" maxlength="2048" placeholder="<?php _e("Put your ETH Price API URL here", 'token-ico'); ?>" value="<?php echo ! empty( $options['ethprice_api_url'] ) ? esc_attr( $options['ethprice_api_url'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The ETH Pice API URL. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
        <p>
            For the etherscan.io like APIs it looks like <pre>https://api.etherscan.io/api?module=stats&action=ethprice&apikey=1234567890</pre>
        </p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Transactions List API URL", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_tx_list_api_url" type="text" maxlength="2048" placeholder="<?php _e("Put your Transactions List API URL template here", 'token-ico'); ?>" value="<?php echo ! empty( $options['tx_list_api_url'] ) ? esc_attr( $options['tx_list_api_url'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The Network Transactions List API URL template. It should contain %%s pattern to insert address hash to. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
        <p>
            For the etherscan.io like APIs it looks like <pre>https://api-ropsten.etherscan.io/api?module=account&action=txlist&address=%s&startblock=0&endblock=99999999&sort=desc&apikey=1234567890</pre>
        </p>
    </label>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e("Internal Transactions List API URL", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_internal_tx_list_api_url" type="text" maxlength="2048" placeholder="<?php _e("Put your Internal Transactions List API URL template here", 'token-ico'); ?>" value="<?php echo ! empty( $options['internal_tx_list_api_url'] ) ? esc_attr( $options['internal_tx_list_api_url'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The Network Internal Transactions List API URL template. It should contain %%s pattern to insert address hash to. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
        <p>
            For the etherscan.io like APIs it looks like <pre>https://api-ropsten.etherscan.io/api?module=account&action=txlistinternal&address=%s&startblock=0&endblock=99999999&sort=desc&apikey=1234567890</pre>
        </p>
    </label>
</fieldset></td>
</tr>


<tr valign="top">
<th scope="row"><?php _e("Token Transactions List API URL", 'token-ico'); ?></th>
<td><fieldset>
    <label>
        <input  class="text" name="Token_ICO_token_tx_list_api_url" type="text" maxlength="2048" placeholder="<?php _e("Put your Token Transactions List API URL template here", 'token-ico'); ?>" value="<?php echo ! empty( $options['token_tx_list_api_url'] ) ? esc_attr( $options['token_tx_list_api_url'] ) : ''; ?>">
        <p><?php echo sprintf(
            __('The Network Token Transactions List API URL template. It should contain %%s pattern to insert address hash to. This is an advanced setting most commonly used with the "%1$s" setting. Use with care.', 'token-ico')
            , __("Network Node JSON-RPC Endpoint", 'token-ico')
        )?></p>
        <p>
            For the etherscan.io like APIs it looks like <pre>https://api-ropsten.etherscan.io/api?module=account&action=tokentx&address=%s&startblock=0&endblock=99999999&sort=desc&apikey=1234567890</pre>
        </p>
    </label>
</fieldset></td>
</tr>

<?php
}
