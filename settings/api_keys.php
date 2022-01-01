<?php


add_filter( 'token_ico_settings_tabs', 'Token_ICO_api_keys_settings_tabs_hook', 30, 1 );
function Token_ICO_api_keys_settings_tabs_hook( $possible_screens ) {
    $possible_screens['api_keys'] = esc_html(__( 'API Keys', 'token-ico' ));
    return $possible_screens;
}

add_filter( 'token_ico_get_save_options', 'Token_ICO_api_keys_get_save_options_hook', 30, 2 );
function Token_ICO_api_keys_get_save_options_hook( $new_options, $current_screen ) {
    if ('api_keys' !== $current_screen) {
        return $new_options;
    }

    $new_options['etherscanApiKey'] = (!empty($_POST['Token_ICO_etherscanApiKey']) /* && is_numeric( $_POST['Token_ICO_etherscanApiKey'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_etherscanApiKey']) : '';
    $new_options['openexchangeratesAppId'] = (!empty($_POST['Token_ICO_openexchangeratesAppId']) /* && is_numeric( $_POST['Token_ICO_openexchangeratesAppId'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_openexchangeratesAppId']) : '';

    return $new_options;
}

add_filter( 'token_ico_print_options', 'Token_ICO_api_keys_print_options_hook', 30, 2 );
function Token_ICO_api_keys_print_options_hook( $options, $current_screen ) {
    if ('api_keys' !== $current_screen) {
        return;
    }
?>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s15" target="_blank"><?php _e("Etherscan Api Key", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_etherscanApiKey" id="Token_ICO_etherscanApiKey" type="text" maxlength="35" placeholder="<?php _e("Put your Etherscan Api Key here", 'token-ico'); ?>" value="<?php echo!empty($options['etherscanApiKey']) ? esc_attr($options['etherscanApiKey']) : ''; ?>">
                <p><?php
                    echo sprintf(__('The API key for the %1$s. You need to %2$sregister%3$s on this site to obtain it.', 'token-ico')
                        , '<a target="_blank" href="https://etherscan.io/myapikey">https://etherscan.io</a>'
                        , '<a target="_blank" href="https://etherscan.io/register">'
                        , '</a>')
                ?></p>
                <p><?php
                    echo sprintf(__('Install some of the %1$spersistent cache WP plugins%2$s to overcome the etherscan API limits. In this case the API would be queried only once per 5 minutes.', 'token-ico')
                        , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                        , '</a>')
                ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s17" target="_blank"><?php _e("openexchangerates.org App Id", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_openexchangeratesAppId" id="Token_ICO_openexchangeratesAppId" type="text" maxlength="35" placeholder="<?php _e("Put your openexchangerates.org App Id here", 'token-ico'); ?>" value="<?php echo!empty($options['openexchangeratesAppId']) ? esc_attr($options['openexchangeratesAppId']) : ''; ?>">
                <?php
                    echo sprintf(__('<p>The App Id for the %1$s. You need to register on this site to obtain it.</p><p>This API is used to show rates for different currencies you want to display.</p><p><strong>Note:</strong> you do not need it if you want to display only your token, <strong>BTC</strong> and/or <strong>USD</strong>.</p><p>Install some of the %2$spersistent cache WP plugins%3$s to overcome the free account API limits in a 1000 requests per month. In this case the API would be queried only once per 1 hour.</p>', 'token-ico')
                        , '<a target="_blank" href="https://openexchangerates.org/signup/free">https://openexchangerates.org</a>'
                        , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                        , '</a>'
                    )
                ?>
            </label>
        </fieldset></td>
</tr>

<?php
}
