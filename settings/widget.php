<?php


add_filter( 'token_ico_settings_tabs', 'Token_ICO_widget_settings_tabs_hook', 30, 1 );
function Token_ICO_widget_settings_tabs_hook( $possible_screens ) {
    $possible_screens['widget'] = esc_html(__( 'Widget', 'token-ico' ));
    return $possible_screens;
}

add_filter( 'token_ico_get_save_options', 'Token_ICO_widget_get_save_options_hook', 30, 2 );
function Token_ICO_widget_get_save_options_hook( $new_options, $current_screen ) {
    if ('widget' !== $current_screen) {
        return $new_options;
    }

    // Standard options screen

    $new_options['tokenname'] = (!empty($_POST['Token_ICO_token_name']) /* && is_numeric( $_POST['Token_ICO_token_name'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_token_name']) : 'TESTCOIN';
    $new_options['base_currency'] = (!empty($_POST['Token_ICO_base_currency']) ) ? sanitize_text_field($_POST['Token_ICO_base_currency']) : __('ETH', 'token-ico');
    $new_options['base_symbol'] = (!empty($_POST['Token_ICO_base_symbol']) ) ? sanitize_text_field($_POST['Token_ICO_base_symbol']) : 'Ξ';
    $new_options['placeholder'] = (!empty($_POST['Token_ICO_placeholder']) /* && is_numeric( $_POST['Token_ICO_placeholder'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_placeholder']) : __('Input ETH amount', 'token-ico');
    $new_options['step'] = (!empty($_POST['Token_ICO_step']) && is_numeric($_POST['Token_ICO_step']) ) ? floatval(sanitize_text_field($_POST['Token_ICO_step'])) : 0.1;
    $new_options['min'] = (!empty($_POST['Token_ICO_min']) && is_numeric($_POST['Token_ICO_min']) ) ? floatval(sanitize_text_field($_POST['Token_ICO_min'])) : 0;
    $new_options['max'] = (!empty($_POST['Token_ICO_max']) && is_numeric($_POST['Token_ICO_max']) ) ? floatval(sanitize_text_field($_POST['Token_ICO_max'])) : '';
    $new_options['buyButtonText'] = (!empty($_POST['Token_ICO_buyButtonText']) /* && is_numeric( $_POST['Token_ICO_buyButtonText'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_buyButtonText']) : __('Buy token with Metamask', 'token-ico');
    $new_options['description'] = (!empty($_POST['Token_ICO_description']) /* && is_numeric( $_POST['Token_ICO_description'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_description']) : '';
    $new_options['coinList'] = (!empty($_POST['Token_ICO_coinList']) /* && is_numeric( $_POST['Token_ICO_coinList'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_coinList']) : '';
    $new_options['showIcons'] = (!empty($_POST['Token_ICO_showIcons']) /* && is_numeric( $_POST['Token_ICO_showIcons'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_showIcons']) : '';
    

    return $new_options;
}

add_filter( 'token_ico_print_options', 'Token_ICO_widget_print_options_hook', 30, 2 );
function Token_ICO_widget_print_options_hook( $options, $current_screen ) {
    if ('widget' !== $current_screen) {
        return;
    }
?>


<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s1" target="_blank"><?php _e("Token Symbol", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_token_name" id="Token_ICO_token_name" type="text" maxlength="32" placeholder="TESTCOIN" value="<?php echo!empty($options['tokenname']) ? esc_attr($options['tokenname']) : 'TESTCOIN'; ?>">
                <p><?php _e('The symbol of your ICO token. E.g. TSX, not "Test Coin".', 'token-ico'); ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s4" target="_blank"><?php _e("Base currency", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_base_currency" id="Token_ICO_base_currency" type="text" maxlength="128" placeholder="<?php _e("Input the base currency code", 'token-ico'); ?>" value="<?php echo!empty($options['base_currency']) ? esc_attr($options['base_currency']) : __('ETH', 'token-ico'); ?>">
                <p><?php
echo sprintf(__('The base currency code to show in the token sell widget, progress bar, e.t.c. In most cases the default ETH is OK. Use USD or other fiat currency three letter code if you have implemented %1$s interface in your %2$sCrowdsale%3$s smart contract. Make sure to configure %4$s if non-USD fiat currency is used here.', 'token-ico')
        , '<a href="http://www.oraclize.it/" target="_blank">oraclize.it</a>'
        , '<a href="https://www.ethereum.org/crowdsale" target="_blank">'
        , '</a>'
        , __("openexchangerates.org App Id", 'token-ico'));
?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s5" target="_blank"><?php _e("Base symbol", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_base_symbol" id="Token_ICO_base_symbol" type="text" maxlength="128" placeholder="<?php _e("Input the base currency symbol", 'token-ico'); ?>" value="<?php echo!empty($options['base_symbol']) ? esc_attr($options['base_symbol']) : 'Ξ'; ?>">
                <p><?php
                    echo sprintf(__('The base currency symbol to show in the progress bar widget. In most cases the default empty is OK. Use $ or other fiat currency symbol if you have implemented %1$s interface in your %2$sCrowdsale%3$s smart contract. Make sure to configure %4$s if non-USD fiat currency is used here.', 'token-ico')
                            , '<a href="http://www.oraclize.it/" target="_blank">oraclize.it</a>'
                            , '<a href="https://www.ethereum.org/crowdsale" target="_blank">'
                            , '</a>'
                            , __("openexchangerates.org App Id", 'token-ico'));
                    ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s6" target="_blank"><?php _e("Placeholder", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_placeholder" id="Token_ICO_placeholder" type="text" maxlength="128" placeholder="<?php echo sprintf(__("Input %s amount", 'token-ico'), __("ETH", 'token-ico')); ?>" value="<?php echo!empty($options['placeholder']) ? esc_attr($options['placeholder']) : sprintf(__("Input %s amount", 'token-ico'), __("ETH", 'token-ico')); ?>">
                <p><?php echo sprintf(__('It is a helper string displayed in the %1$s input field for your customer to know where to input %1$s amount to buy your tokens.', 'token-ico'), __("ETH", 'token-ico')); ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s7" target="_blank"><?php echo sprintf(__("Increase/Decrease step, in %s", 'token-ico'), __("ETH", 'token-ico')); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_step" id="Token_ICO_step" type="text" placeholder="0.1" value="<?php echo!empty($options['step']) ? esc_attr($options['step']) : '0.1'; ?>">
                <p><?php echo sprintf(__("The step to adjust %s amount with up/down buttons", 'token-ico'), __("ETH", 'token-ico')); ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s8" target="_blank"><?php echo sprintf(__("Min allowed value, in %s", 'token-ico'), __("ETH", 'token-ico')); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_min" id="Token_ICO_min" type="text" placeholder="0" value="<?php echo!empty($options['min']) ? esc_attr($options['min']) : ''; ?>">
                <p><?php echo sprintf(__("The minimum %s amount allowed for token purchase. Can be used to workaround some legal circumstances.", 'token-ico'), __("ETH", 'token-ico')); ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s9" target="_blank"><?php echo sprintf(__("Max allowed value, in %s", 'token-ico'), __("ETH", 'token-ico')); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_max" id="Token_ICO_max" type="text" placeholder="10" value="<?php echo!empty($options['max']) ? esc_attr($options['max']) : ''; ?>">
                <p><?php echo sprintf(__("The maximum %s amount allowed for token purchase. Can be used to workaround some legal circumstances.", 'token-ico'), __("ETH", 'token-ico')); ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s10" target="_blank"><?php _e("Buy Button Text", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_buyButtonText" id="Token_ICO_buyButtonText" type="text" maxlength="128" placeholder="<?php _e("Buy token with Metamask", 'token-ico'); ?>" value="<?php echo!empty($options['buyButtonText']) ? esc_attr($options['buyButtonText']) : __('Buy token with Metamask', 'token-ico'); ?>">
                <p><?php _e('The text to display on the BUY button', 'token-ico'); ?></p>
            </label>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s11" target="_blank"><?php _e("Description", 'token-ico'); ?></a></th>
    <td><fieldset>
            <textarea class="large-text" name="Token_ICO_description" id="Token_ICO_description" type="text" maxlength="10240" placeholder="<?php _e("Add some notes", 'token-ico'); ?>"><?php echo!empty($options['description']) ? esc_textarea($options['description']) : ''; ?></textarea>
        </fieldset></td>
</tr>

<tr valign="top">
    <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s18" target="_blank"><?php _e("List of coins", 'token-ico'); ?></a></th>
    <td><fieldset>
            <label>
                <input class="text" name="Token_ICO_coinList" id="Token_ICO_coinList" type="text" maxlength="1024" placeholder="<?php _e("Your_token,BTC,USD,...", 'token-ico'); ?>" value="<?php echo!empty($options['coinList']) ? esc_attr($options['coinList']) : ''; ?>">
<?php _e('<p>The comma separated list of coins to convert the amount inputted by user.</p><p>Typically, it is your token symbol, USD, BTC.</p><p><strong>Note:</strong> if you want to show icons for coins, make sure that the folder <strong>icons</strong> has <strong>png</strong> files with the same names as coins you want to display, e.g. <strong>USD.png</strong>, <strong>BTC.png</strong>, <strong>YourCoinName.png</strong></p>', 'token-ico') ?>
            </label>
        </fieldset></td>
</tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s19" target="_blank"><?php _e("Show icons?", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_showIcons" id="Token_ICO_showIcons" type="checkbox" <?php echo (!empty($options['showIcons']) ? 'checked' : ''); ?> >
<?php _e('Check to show icons for coins you display.<p><strong>Note:</strong> make sure that the folder <strong>icons</strong> has <strong>PNG</strong> files with the same names as coins you want to display, e.g. <strong>USD.png</strong>, <strong>BTC.png</strong>, <strong>YourCoinName.png</strong></p>', 'token-ico') ?>
                </label>
            </fieldset></td>
    </tr>

<?php
}
