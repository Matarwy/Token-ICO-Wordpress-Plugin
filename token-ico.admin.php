<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function Token_ICO_options_page() {

    // Require admin privs
	if ( ! current_user_can( 'manage_options' ) )
        return false;

    $new_options = array();

    // Which tab is selected?
    $possible_screens = apply_filters('token_ico_settings_tabs', $possible_screens);
//    asort($possible_screens);

	$current_screen = ( isset( $_GET['tab'] ) && isset( $possible_screens[$_GET['tab']] ) ) ? $_GET['tab'] : 'ico';

	if ( isset( $_POST['Submit'] ) ) {

        // Nonce verification 
        check_admin_referer( 'token-ico-update-options' );

        $new_options = apply_filters('token_ico_get_save_options'
            , $new_options, $current_screen);

        // Get all existing TokenICO options
        $existing_options = get_option( 'token-ico_options', array() );

        // Merge $new_options into $existing_options to retain TokenICO options from all other screens/tabs
        if ( $existing_options ) {
            $new_options = array_merge( $existing_options, $new_options );
        }

        if ( get_option('token-ico_options') ) {
            update_option('token-ico_options', $new_options);
        } else {
            $deprecated = ' ';
            $autoload = 'no';
            add_option('token-ico_options', $new_options, $deprecated, $autoload);
        }

        ?>
        <div class="updated"><p><?php _e( 'Settings saved.' ); ?></p></div>
        <?php

    } else if ( isset( $_POST['Reset'] ) ) {
        // Nonce verification 
        check_admin_referer( 'token-ico-update-options' );

        delete_option( 'token-ico_options' );
    }

    $options = stripslashes_deep( get_option( 'token-ico_options', array() ) );

    ?>

    <div class="wrap">

        <h1><?php _e( 'TokenICO Settings', 'token-ico' ); ?></h1>


    <?php settings_errors(); ?>

    <section>
        <h1><?php _e('Install and Configure Guide', 'token-ico') ?></h1>
        <p><?php echo sprintf(__('Use the official %1$sInstall and Configure%2$s step by step guide to configure this plugin.', 'token-ico')
            , '<a href="https://ethereumico.io/knowledge-base/ico-website-install-configure/" target="_blank">'
            , '</a>') ?></p>
    </section>
	
    <?php

    $base_currency = !empty($options['base_currency']) ? esc_attr($options['base_currency']) : Token_ICO_getBlockchainCurrencyTicker();
    
    ?>

    <script type="text/javascript">
        function ICO_admin_change_Token_ICO_base_currency(e) {
            if (e) {
                e.preventDefault();
            }
            var currency = "<?php echo $base_currency; ?>";
            var $Token_ICO_base_currency = jQuery("#Token_ICO_base_currency");
            if ($Token_ICO_base_currency.length > 0) {
                currency = $Token_ICO_base_currency.val();
                if ("" === currency) {
                    currency = "<?php echo $base_currency; ?>";
                    $Token_ICO_base_currency.val(currency);
                }
            }
            var re = new RegExp(window.ico.initial_base_currency, 'g');

            var elems = [
                'Token_ICO_placeholder'
                        , 'Token_ICO_step'
                        , 'Token_ICO_min'
                        , 'Token_ICO_max'
                        , 'Token_ICO_tokenRate'
                        , 'Token_ICO_softcap'
                        , 'Token_ICO_hardcap'
            ];

            //var factor = ICO_admin_calc_factor (window.ico.initial_base_currency, currency);

            elems.forEach(function (el) {
                var $el = jQuery('#' + el);
                if (0 === $el.length) {
                    return;
                }
                var placeholder = $el.attr('placeholder');
                if ('undefined' !== typeof placeholder) {
                    if (!jQuery.isNumeric(placeholder)) {
                        $el.attr('placeholder', placeholder.replace(re, currency));
                    } else {
                        //$el.attr('placeholder', parseFloat(placeholder) * factor);
                    }
                }
                var value = $el.val();
                if (!(jQuery.isNumeric(value) || ("" === value && jQuery.isNumeric(placeholder)))) {
                    $el.val(value.replace(re, currency));
                } else {
                    if ("" === value) {
                        if ("" === placeholder || "0" === placeholder) {
                            $el.val("");
                        } else {
                            //$el.val(parseFloat(placeholder) * factor);
                        }
                    } else {
                        //$el.val(parseFloat(value) * factor);
                    }
                }
                $el.parent().find('p').each(function () {
                    var $p = jQuery(this);
                    $p.text($p.text().replace(re, currency));
                });
                var $title = jQuery($el.parent().parent().parent().parent().children()[0]);
                $title.html($title.html().replace(re, currency));
            });

            window.ico.initial_base_currency = currency;
        }
        function ICO_admin_init() {
            jQuery("#Token_ICO_base_currency").change(ICO_admin_change_Token_ICO_base_currency);
            if ('undefined' === typeof window['ico']) {
                window['ico'] = {};
            }
            window.ico.initial_base_currency = "<?php _e('ETH', 'token-ico'); ?>";
        <?php
            $rateData = Token_ICO_get_rate_data();
            $rateDataFiat = Token_ICO_get_rate_data_fiat();
        ?>
            window.ico.rateData = <?php echo $rateData; ?>;
            window.ico.rateDataFiat = <?php echo $rateDataFiat; ?>;
            ICO_admin_change_Token_ICO_base_currency();
        }
        jQuery(document).ready(ICO_admin_init);
//        // proper init if loaded by ajax
//        jQuery(document).ajaxComplete(function( event, xhr, settings ) {
//            ICO_admin_init();
//        });
    </script>

	<h2 class="nav-tab-wrapper">
        <?php
            if ($possible_screens) foreach($possible_screens as $s => $sTitle) {
        ?>
		<a href="<?php echo admin_url( 'options-general.php?page=token-ico&tab=' . esc_attr($s) ); ?>" class="nav-tab<?php if ( $s == $current_screen ) echo ' nav-tab-active'; ?>"><?php echo $sTitle; ?></a>
        <?php
            }
        ?>
	</h2>

        <form id="token-ico_admin_form" method="post" action="">

            <?php wp_nonce_field('token-ico-update-options'); ?>

            <table class="form-table">

            <?php 
                do_action('token_ico_print_options', $options, $current_screen);
            ?>

            </table>

            <h2><?php _e("Need help to develop a ERC20 token for your ICO?", 'token-ico'); ?></h2>
            <p><?php
                echo sprintf(
                    __('Feel free to %1$shire me!%2$s', 'token-ico')
                    , '<a target="_blank" href="https://ethereumico.io/product/crowdsale-contract-development/">'
                    , '</a>'
                )
            ?></p>

            <h2><?php _e("Need help to configure this plugin?", 'token-ico'); ?></h2>
            <p><?php
                echo sprintf(
                    __('Feel free to %1$shire me!%2$s', 'token-ico')
                    , '<a target="_blank" href="https://ethereumico.io/product/configure-wordpress-plugins/">'
                    , '</a>'
                )
            ?></p>

            <h2><?php _e("Want to accept paypal or Bitcoin for your ICO tokens?", 'token-ico'); ?></h2>
            <p><?php
                echo sprintf(
                    __('Try the %1$sCryptocurrency Product for WooCommerce%2$s plugin!', 'token-ico')
                    , '<a target="_blank" href="https://ethereumico.io/product/cryptocurrency-wordpress-plugin/">'
                    , '</a>'
                )
            ?></p>

            <h2><?php _e("Want to create Crypto wallets on your Wordpress site?", 'token-ico'); ?></h2>
            <p><?php
                echo sprintf(
                    __('Install the %1$sWordPress Crypto Wallet plugin%2$s!', 'token-ico')
                    , '<a target="_blank" href="https://ethereumico.io/product/wordpress-ethereum-wallet-plugin/">'
                    , '</a>'
                )
            ?></p>

            <h2><?php _e("Want to accept Ether or any ERC20/ERC223 token in your WooCommerce store?", 'token-ico'); ?></h2>
            <p><?php
                echo sprintf(
                    __('Install the %1$sEther and ERC20 tokens WooCommerce Payment Gateway%2$s plugin!', 'token-ico')
                    , '<a target="_blank" href="https://wordpress.org/plugins/ether-and-erc20-tokens-woocommerce-payment-gateway/">'
                    , '</a>'
                )
            ?></p>

            <p class="submit">
                <input class="button-primary" type="submit" name="Submit" value="<?php _e('Save Changes', 'token-ico') ?>" />
                <input id="Token_ICO_reset_options" type="submit" name="Reset" onclick="return confirm('<?php _e('Are you sure you want to delete all TokenICO options?', 'token-ico') ?>')" value="<?php _e('Reset', 'token-ico') ?>" />
            </p>

        </form>

        <p class="alignleft"><?php echo sprintf(
            __('If you like <strong>Token ICO</strong> plugin please leave us a %1$s rating. A huge thanks in advance!', 'token-ico')
            , '<a href="https://wordpress.org/support/plugin/ethereumico/reviews?rate=5#new-post" target="_blank">★★★★★</a>'
        )?></p>

    </div>

    <?php

}
