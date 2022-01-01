<?php


add_filter( 'token_ico_settings_tabs', 'Token_ICO_blockchain_settings_tabs_hook', 30, 1 );
function Token_ICO_blockchain_settings_tabs_hook( $possible_screens ) {
    $possible_screens['blockchain'] = esc_html(__( 'Blockchain', 'token-ico' ));
    return $possible_screens;
}

add_filter( 'token_ico_get_save_options', 'Token_ICO_blockchain_get_save_options_hook', 30, 2 );
function Token_ICO_blockchain_get_save_options_hook( $new_options, $current_screen ) {
    if ('blockchain' !== $current_screen) {
        return $new_options;
    }

    // Blockchain options screen

    $new_options['gaslimit'] = (!empty($_POST['Token_ICO_gaslimit']) && is_numeric($_POST['Token_ICO_gaslimit']) ) ? intval(sanitize_text_field($_POST['Token_ICO_gaslimit'])) : 200000;
    $new_options['gasprice'] = (!empty($_POST['Token_ICO_gasprice']) && is_numeric($_POST['Token_ICO_gasprice']) ) ? intval(sanitize_text_field($_POST['Token_ICO_gasprice'])) : 200;
    $new_options['blockchain_network'] = (!empty($_POST['Token_ICO_blockchain_network']) ) ? sanitize_text_field($_POST['Token_ICO_blockchain_network']) : '';
    $new_options['infuraApiKey'] = (!empty($_POST['Token_ICO_infuraApiKey']) ) ? sanitize_text_field($_POST['Token_ICO_infuraApiKey']) : '';

    return $new_options;
}

add_filter( 'token_ico_print_options', 'Token_ICO_blockchain_print_options_hook', 30, 2 );
function Token_ICO_blockchain_print_options_hook( $options, $current_screen ) {
    if ('blockchain' !== $current_screen) {
        return;
    }
?>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s14" target="_blank"><?php _e("Blockchain", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_blockchain_network" id="Token_ICO_blockchain_network" type="text" maxlength="128" placeholder="mainnet" value="<?php echo!empty($options['blockchain_network']) ? esc_attr($options['blockchain_network']) : 'mainnet'; ?>">
                <p><?php _e("The blockchain used: mainnet or ropsten. Use mainnet in production, and ropsten in test mode. See plugin documentation for the testing guide.", 'token-ico') ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s16" target="_blank"><?php _e("Infura.io Api Key", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_infuraApiKey" id="Token_ICO_infuraApiKey" type="text" maxlength="35" placeholder="<?php _e("Put your Infura.io Api Key here", 'token-ico'); ?>" value="<?php echo!empty($options['infuraApiKey']) ? esc_attr($options['infuraApiKey']) : ''; ?>">
                <p><?php echo sprintf(
                    __('The API key for the %1$s. You need to register on this site to obtain it. Follow the %2$sGet infura API Key%3$s guide please.', 'token-ico')
                    , '<a target="_blank" href="https://infura.io/register">https://infura.io/</a>'
                    , '<a target="_blank" href="https://ethereumico.io/knowledge-base/infura-api-key-guide/">'
                    , '</a>'
                )?></p>
                <p><strong><?php echo sprintf(
                    __('Note that this setting is ignored if the "%1$s" setting is set', 'token-ico')
                    , __("Network Node JSON-RPC Endpoint", 'token-ico')
                )?></strong></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s2" target="_blank"><?php _e("Gas Limit", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_gaslimit" id="Token_ICO_gaslimit" type="number" min="0" step="10000" maxlength="8" placeholder="200000" value="<?php echo!empty($options['gaslimit']) ? esc_attr($options['gaslimit']) : '200000'; ?>">
                <p><?php _e("The default gas limit to buy your ICO token. 200000 is a reasonable default value.", 'token-ico') ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s3" target="_blank"><?php _e("Gas Price, Gwei", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_gasprice" id="Token_ICO_gasprice" type="number" min="0" step="1" maxlength="8" placeholder="21" value="<?php echo!empty($options['gasprice']) ? esc_attr($options['gasprice']) : '21'; ?>">
                <p><?php _e("The gas price in Gwei. Reasonable values are in a 50-250 ratio. The default value is 200 to ensure that your tx would be sent in most of the time.", 'token-ico') ?></p>
                <p><?php _e("The actual gas price used would be this value or less, depending on the current reasonable gas price in the blockchain.", 'token-ico') ?></p>
            </label>
        </fieldset></td>
</tr>



<?php
}
