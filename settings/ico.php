<?php


add_filter( 'token_ico_settings_tabs', 'Token_ICO_ico_settings_tabs_hook', 30, 1 );
function Token_ICO_ico_settings_tabs_hook( $possible_screens ) {
    $possible_screens['ico'] = esc_html(__( 'ICO', 'token-ico' ));
    return $possible_screens;
}

add_filter( 'token_ico_get_save_options', 'Token_ICO_ico_get_save_options_hook', 30, 2 );
function Token_ICO_ico_get_save_options_hook( $new_options, $current_screen ) {
    if ('ico' !== $current_screen) {
        return $new_options;
    }

    // ICO options screen

    $new_options['softcap'] = (!empty($_POST['Token_ICO_softcap']) && is_numeric($_POST['Token_ICO_softcap']) ) ? floatval(sanitize_text_field($_POST['Token_ICO_softcap'])) : '';
    $new_options['hardcap'] = (!empty($_POST['Token_ICO_hardcap']) && is_numeric($_POST['Token_ICO_hardcap']) ) ? floatval(sanitize_text_field($_POST['Token_ICO_hardcap'])) : '';
    $new_options['private_sale_seed'] = (!empty($_POST['Token_ICO_private_sale_seed']) && is_numeric($_POST['Token_ICO_private_sale_seed']) ) ? floatval(sanitize_text_field($_POST['Token_ICO_private_sale_seed'])) : '';
    $new_options['icostart'] = (!empty($_POST['Token_ICO_icostart']) /* && is_numeric( $_POST['Token_ICO_icostart'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_icostart']) : '';
    $new_options['icoperiod'] = (!empty($_POST['Token_ICO_icoperiod']) && is_numeric($_POST['Token_ICO_icoperiod']) ) ? intval(sanitize_text_field($_POST['Token_ICO_icoperiod'])) : 30;
    $new_options['tokenAddress'] = (!empty($_POST['Token_ICO_tokenAddress']) /* && is_numeric( $_POST['Token_ICO_tokenAddress'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_tokenAddress']) : '';
    $new_options['crowdsaleAddress'] = (!empty($_POST['Token_ICO_crowdsaleAddress']) /* && is_numeric( $_POST['Token_ICO_crowdsaleAddress'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_crowdsaleAddress']) : '';
    // it should be int, but usually, it is a very big int PHP can not handle, like 1000000000000000000
    $new_options['decimals'] = (!empty($_POST['Token_ICO_decimals']) && is_numeric($_POST['Token_ICO_decimals']) ) ? sanitize_text_field($_POST['Token_ICO_decimals']) : '';
    $new_options['tokenRate'] = (!empty($_POST['Token_ICO_tokenRate']) && is_numeric($_POST['Token_ICO_tokenRate']) ) ? doubleval(sanitize_text_field($_POST['Token_ICO_tokenRate'])) : 1;
//        $new_options['contractABI']      = ( ! empty( $_POST['Token_ICO_contractABI'] )      /*&& is_numeric( $_POST['Token_ICO_contractABI'] )*/ )      ? stripcslashes($_POST['Token_ICO_contractABI'])      : '';
    $new_options['bounty'] = (!empty($_POST['Token_ICO_bounty']) /* && is_numeric( $_POST['Token_ICO_bounty'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_bounty']) : '';
    $new_options['txData'] = (!empty($_POST['Token_ICO_txData']) /* && is_numeric( $_POST['Token_ICO_txData'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_txData']) : '';
    $new_options['referralArgumentName'] = (!empty($_POST['Token_ICO_referralArgumentName']) /* && is_numeric( $_POST['Token_ICO_txData'] ) */ ) ? sanitize_text_field($_POST['Token_ICO_referralArgumentName']) : 'icoreferral';

    return $new_options;
}

add_filter( 'token_ico_print_options', 'Token_ICO_ico_print_options_hook', 30, 2 );
function Token_ICO_ico_print_options_hook( $options, $current_screen ) {
    if ('ico' !== $current_screen) {
        return;
    }
?>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s20" target="_blank"><?php _e("The ICO token address", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_tokenAddress" id="Token_ICO_tokenAddress" type="text" maxlength="45" placeholder="<?php _e("Put your ICO token address here", 'token-ico'); ?>" value="<?php echo!empty($options['tokenAddress']) ? esc_attr($options['tokenAddress']) : ''; ?>">
                    <p><?php _e('The Network address of your ICO ERC20 token.', 'token-ico') ?></p>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s21" target="_blank"><?php _e("The ICO crowdsale contract address", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_crowdsaleAddress" id="Token_ICO_crowdsaleAddress" type="text" maxlength="45" placeholder="<?php _e("Put your ICO crowdsale token address here", 'token-ico'); ?>" value="<?php echo!empty($options['crowdsaleAddress']) ? esc_attr($options['crowdsaleAddress']) : ''; ?>">
                    <p><?php _e('The Network address of your ICO crowdsale contract. You can input a simple Network address here instead of the Crowdsale contract address. In this case Ether would be sent directly to this your address, but note that you’ll need to send tokens to your customers manually then.', 'token-ico') ?></p>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s23" target="_blank"><?php _e("The ICO token decimals number", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_decimals" id="Token_ICO_decimals" type="number" min="0" step="100000000000000000" maxlength="60" placeholder="<?php _e("Put the decimals field of your ICO token contract", 'token-ico'); ?>" value="<?php echo!empty($options['decimals']) ? esc_attr($options['decimals']) : '1000000000000000000'; ?>">
                    <p><?php _e('The decimals field of your ICO ERC20 token. The typical value is 1000000000000000000.', 'token-ico') ?></p>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s24" target="_blank"><?php echo sprintf(__("The ICO token rate, in %s", 'token-ico'), __("ETH", 'token-ico')); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_tokenRate" id="Token_ICO_tokenRate" type="text" placeholder="<?php _e("Put the token rate here", 'token-ico'); ?>" value="<?php echo!empty($options['tokenRate']) ? esc_attr($options['tokenRate']) : '1'; ?>">
                    <p><?php echo sprintf(__('The number of tokens per 1 %1$s, i.e. the token to %1$s exchange rate', 'token-ico'), __("ETH", 'token-ico')) ?></p>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s25" target="_blank"><?php _e("ICO start date", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_icostart" id="Token_ICO_icostart" type="date" placeholder="<?php _e("Set the ICO date", 'token-ico'); ?>" value="<?php echo!empty($options['icostart']) ? esc_attr($options['icostart']) : ''; ?>">
                    <p><?php _e('The date when your ICO would start from.', 'token-ico') ?></p>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s22" target="_blank"><?php _e("ICO period in days", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_icoperiod" id="Token_ICO_icoperiod" type="number" min="0" step="1" maxlength="3" placeholder="30" value="<?php echo!empty($options['icoperiod']) ? esc_attr($options['icoperiod']) : '30'; ?>">
                    <p><?php _e('The number of days your ICO would be opened.', 'token-ico') ?></p>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s26" target="_blank"><?php echo sprintf(__("ICO soft cap, in %s", 'token-ico'), __("ETH", 'token-ico')); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_softcap" id="Token_ICO_softcap" type="text" placeholder="" value="<?php echo!empty($options['softcap']) ? esc_attr($options['softcap']) : ''; ?>">
                    <?php
                        echo sprintf(__('<p>A soft cap is the amount received at which your crowdsale will be considered successful. It is the minimal amount required by your project for success. You are expected to refund all money if this cap would not be reached.</p><p>This feature uses %1$s `API`. Install some of the %2$spersistent cache WP plugins%3$s to overcome the %4$s API limits. In this case the API would be queried only once per 5 minutes.</p>', 'token-ico')
                            , 'https://blockcypher.com'
                            , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                            , '</a>'
                            , 'blockcypher.com')
                    ?>
                </label>
            </fieldset></td>
    </tr>

    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s27" target="_blank"><?php echo sprintf(__("ICO hard cap, in %s", 'token-ico'), __("ETH", 'token-ico')); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_hardcap" id="Token_ICO_hardcap" type="text" placeholder="10000" value="<?php echo!empty($options['hardcap']) ? esc_attr($options['hardcap']) : '10000'; ?>">
                    <?php
                        echo sprintf(__('<p>A hard cap is defined as the maximum amount a crowdsale will receive. The crowdsale is expected to stop after this cap is reached.</p><p>This feature uses %1$s `API`. Install some of the %2$spersistent cache WP plugins%3$s to overcome the %4$s API limits. In this case the API would be queried only once per 5 minutes.</p>', 'token-ico')
                            , 'https://blockcypher.com'
                            , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                            , '</a>'
                            , 'blockcypher.com')
                    ?>
                </label>
            </fieldset></td>
    </tr>
    <tr valign="top">
        <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s28" target="_blank"><?php _e("Private sale seed", 'token-ico'); ?></a></th>
        <td><fieldset>
                <label>
                    <input class="text" name="Token_ICO_private_sale_seed" id="Token_ICO_private_sale_seed" type="text" placeholder="0" value="<?php echo!empty($options['private_sale_seed']) ? esc_attr($options['private_sale_seed']) : '0'; ?>">
                    <p><?php echo sprintf(__('An amount of funds gained in fiat or non-Ether cryptocurrency in the %s.', 'token-ico')
                        , __("Base currency", 'token-ico'))
                    ?></p>
                </label>
            </fieldset></td>
    </tr>
    <tr valign="top">
      <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s12" target="_blank"><?php _e("Transaction data", 'token-ico'); ?></a></th>
      <td><fieldset>
        <label>
          <input
            class="text" name="Token_ICO_txData" id="Token_ICO_txData" type="text" maxlength="1024" placeholder="0x" value="<?php echo!empty($options['txData']) ? esc_attr($options['txData']) : ''; ?>">
          <p><?php _e('Data to be sent in the transaction. It should starts with \'0x\' without quotes.', 'token-ico'); ?></p>
        </label>
      </fieldset></td>
    </tr>

    <tr valign="top">
      <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s13" target="_blank"><?php _e("Bounty", 'token-ico'); ?></a></th>
      <td><fieldset>
        <textarea
          class="large-text" name="Token_ICO_bounty" id="Token_ICO_bounty" type="text" maxlength="1024000" placeholder="<?php _e("Put your bounty JSON code here", 'token-ico'); ?>"><?php echo!empty($options['bounty']) ? esc_textarea($options['bounty']) : ''; ?></textarea>
        <p><?php _e('The optional JSON array of your bounty values. Note that it should be supported in your crowdsale contract. Example: <code>[[7, 40], [7, 30], [7, 20], [7, 10], [7, 5]]</code>. The 7 number is for days count. The 40, 30, 20, 10, 5 are percents of additional tokens to be sent to buyer for free.', 'token-ico'); ?></p>
      </fieldset></td>
    </tr>

    <tr valign="top">
      <th scope="row"><a href="https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s131" target="_blank"><?php _e("Referral argument name", 'token-ico'); ?></a></th>
      <td><fieldset>
        <label>
          <input
            class="text" name="Token_ICO_referralArgumentName" id="Token_ICO_referralArgumentName" type="text" maxlength="1024" placeholder="0x" value="<?php echo!empty($options['referralArgumentName']) ? esc_attr($options['referralArgumentName']) : 'icoreferral'; ?>">
          <p><?php echo sprintf(__('The name of the argument used in referral links, like icoreferral here: %s.', 'token-ico'), 'https://ethereumico.io?icoreferral=0x476Bb28Bc6D0e9De04dB5E19912C392F9a76535d'); ?></p>
        </label>
      </fieldset></td>
    </tr>

<?php
}
