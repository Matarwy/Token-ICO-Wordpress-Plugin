=== TokenICO ===
Contributors: ethereumicoio, freemius
Tags: ethereum, erc20, ICO, initial coin offering, crypto, cryptocurrency
Requires at least: 3.7
Tested up to: 5.8.1
Stable tag: 2.3.9
Donate link: https://etherscan.io/address/0x476Bb28Bc6D0e9De04dB5E19912C392F9a76535d
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 5.6

Sell your Ethereum ERC20 ICO tokens from your WordPress site.

== Description ==

Ethereum ICO Wordpress plugin can be used to sell your Ethereum ERC20 ICO tokens from your Wordpress site.

> It is the only available WP plugin to sell your Ethereum ERC20 ICO tokens directly from your WordPress site.

> Binance Smart Chain (BSC), Polygon (MATIC) and any other EVM-compatible blockchain is supported in the [PRO version](https://checkout.freemius.com/mode/dialog/plugin/4553/plan/7330/?trial=paid "The Ethereum ICO Professional plugin")!

https://youtu.be/2lOZw_ov_uY

== FREE Features ==

* To show tokens sell widget insert a simple `[token-ico]` shortcode wherever you like.
* Can be customized with attributes supported: `buybuttontext`, `minimum`, `maximum`, `step`, `placeholder`, `gaslimit`, `tokenname`, `description`
* You also can use fine grained shortcodes for easier customization: `[token-ico-limit label="%s LIMIT!" gaslimit="200001"]`, `[token-ico-input placeholder="Test placeholder"]`, `[token-ico-input-currency showIcons="true" baseCurrency="USD"]`, `[token-ico-buy-button buyButtonText="BUY ME!"]`, `[token-ico-currency-list showIcons="false"  coinList="ETH,BTC"]`
* To show an ICO progress bar widget insert a simple `[token-ico-progress]` shortcode wherever you like. This feature uses https://blockcypher.com `API`. You must use any of the [persistent hash WP plugins](https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins "The persistent hash WP plugins") to overcome its rate limits.
* Use shortcodes to display the current progress value `[token-ico-progress-value]` and percent `[token-ico-progress-percent]`. This feature uses https://blockcypher.com `API`. You must use any of the [persistent hash WP plugins](https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins "The persistent hash WP plugins") to overcome its rate limits.
* To show tokens balance on the current user account use the `[token-ico-balance]` shortcode. If MetaMask is not installed or account is not unlocked, an input field is provided for user account address. `tokenname`, `label` and `placeholder` attributes are supported.
* There is also a shortcode `[token-ico-purchases]` to display a table of recent token purchases by anyone, or by the current user. In the last case if MetaMask is not installed or account is not unlocked, an input field is provided for user account address. `tokenname`, `label` and `placeholder` attributes are supported.
* Use shortcode `[token-ico-referral]` to display a referral address field. User can copy it and send to friends. If they buy tokens while opened this referral link, your `Crowdsale` contract would get a referral address in the `Data` field. Your `Crowdsale` contract should be able to work with it. `url`, `label` and `placeholder` attributes are supported.
* Airdrop is also supported. Just set the minimum allowed setting to zero and the Crowdsale address to your airdrop contract. Note that your airdrop contract should be able to accept zero payments and send some tokens in return.
* This plugin uses Metamask to safely perform the ERC20 token sell operation
* It will show user a link to the Metamask site if the user doesn’t have the Metamask installed
* We use a well known https://etherscan.io API to provide your client an automatic rate calculations to USD and BTC
* Select a list of any currencies supported by the openexchangerates.org to convert the price to.
* You can provide a comma separated list of coins to convert ETH amount inputted by user. This list is shown under the ETH input field.
* Minimum ether amount can be specified to workaround some legal issues.
* Test networks like ropsten or rinkeby are supported. User is warned if he tries to buy tokens from one network, while having MetaMask to point to another network, effectively preventing any losses here.
* This plugin is l10n ready
* Ability to set base currency to display in token sell widget, progress bar and tx table

== PRO Features ==

* You can use a bounty program, if your ERC20 crowdsale contract supports it
* The transaction data to send to your crowdsale contract is supported
* You can use any of the [persistent hash WP plugins](https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins "The persistent hash WP plugins") to overcome the etherscan.io and openexchangerates.org API rate limits. We use the cache to limit the API calling rate to a reasonable value
* Coins and token icons display can be switched on
* Maximum ether amount can be specified to workaround some legal issues
* Private sale seed setting to count funds obtained in a non-Crowdsale contract way
* Custom/private blockchain feature: `Ethereum Node JSON-RPC Endpoint` and other related settings to use the Binance Smart Chain (BSC), Polygon (MATIC) or any other EVM compatible blockchain
* The `[token-ico-referral]` shortcode's default argument name produced in a link is `icoreferral`, like in this example - `https://ethereumico.io?icoreferral=0x476Bb28Bc6D0e9De04dB5E19912C392F9a76535d`, but it can be renamed with the [Referral argument name](https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-configuration/#s131) setting.

> See the [Free vs PRO version differences](https://ethereumico.io/knowledge-base/token-ico-wordpress-plugin-free-vs-pro-version-differences/) if need more info on this topic.

== Live Demo ==

See the official site for a Live Demo: [https://ethereumico.io/](https://ethereumico.io/knowledge-base/ico-demo-token-ico-wordpress-plugin/ "The TokenICO Wordpress plugin")

> You can accept fiat money or Bitcoin for your ICO tokens with the [Cryptocurrency Product for WooCommerce](https://wordpress.org/plugins/cryptocurrency-product-for-woocommerce/ "Cryptocurrency Product for WooCommerce") plugin.

== Disclaimer ==

**By using this plugin you accept all responsibility for handling the account balances for all your users.**

Under no circumstances is **ethereumico.io** or any of its affiliates responsible for any damages incurred by the use of this plugin.

Every effort has been made to harden the security of this plugin, but its safe operation depends on your site being secure overall. You, the site administrator, must take all necessary precautions to secure your WordPress installation before you connect it to any live wallets.

You are strongly advised to take the following actions (at a minimum):

- [Educate yourself about cold and hot cryptocurrency storage](https://en.bitcoin.it/wiki/Cold_storage)
- Obtain hardware wallet to store your coins, like [Ledger Nano S](https://www.ledgerwallet.com/r/4caf109e65ab?path=/products/ledger-nano-s) or [TREZOR](https://shop.trezor.io?a=ethereumico.io)
- [Educate yourself about hardening WordPress security](https://codex.wordpress.org/Hardening_WordPress)
- [Install a security plugin such as Jetpack](https://jetpack.com/pricing/?aff=9181&cid=886903) or any other security plugin
- **Enable SSL on your site** if you have not already done so.

> By continuing to use the Ethereum ICO Wordpress plugin, you indicate that you have understood and agreed to this disclaimer.

== Screenshots ==

1. This is how the plugin looks like if the Show icons feature is enabled.
2. The `[token-ico-progress]` display
3. The `[token-ico-balance]` display
4. The `[token-ico-purchases]` display
5. The token symbol and GAS settings
6. The base currency settings
7. Token sale widget settings
8. Advanced settings
9. API keys
10. Displayed currencies list and Show icons flag
11. The ICO Token and Crowdsale contract addresses
12. The ICO Crowdsale contract properties
13. The `[token-ico-progress]` shortcode settings
14. The `[token-ico-referral]` display

== Installation ==

Enter your settings in admin pages and place the `[token-ico]`, `[token-ico-progress]` and other shortcodes wherever you need it.

> Read this step by step guide for more information: [Install and Configure](https://ethereumico.io/knowledge-base/ico-website-install-configure/)

= Main shortcodes example =

`
[token-ico-referral url="https://example.com/crowdsale-ico-page" label="Referal link" placeholder="Input your BSC account address"]

[token-ico-balance tokenname="TSX" label="Referal link" placeholder="Input your BSC account address"]

[token-ico]

[token-ico-progress]

[token-ico-purchases tokenname="TSX" label="Referal link" placeholder="Input your BSC account address"]
`

= Fine grained shortcodes example =

`
[token-ico-limit label="%s LIMIT!" gaslimit="200001"]

[token-ico-input placeholder="Test placeholder"]

[token-ico-input-currency showIcons="true" baseCurrency="USD"]

[token-ico-buy-button buyButtonText="BUY ME!"]

[token-ico-currency-list showIcons="false"  coinList="ETH,BTC"]

[token-ico-progress-value]

[token-ico-progress-percent]

`

= Placeholder =

It is a helper string displayed in the Ether input field for your customer to know where to input Ether amount to buy your tokens.

= Infura.io Api Key =

Register for an infura.io API key and put it in admin settings. It is required to interact with Ethereum blockchain. Use this guide to obtain it: [Get infura API Key](https://ethereumico.io/knowledge-base/infura-api-key-guide/)

= Description =

The `Description` text is displayed immediately after the token purchase widget. It is a good place for some warnings or bounty information.

= Transaction data =

It is an advanced feature. It can be required if your Crowdsale contract can not just accept Ether by send, but need some `payable` method to be called. Do not use if unsure.

= The ICO crowdsale contract address =

The ethereum address of your ICO crowdsale contract. It is the address TokenICO plugin sends Ether to.

> You can input a simple Ethereum address here instead of the Crowdsale contract address. In this case Ether would be sent directly to this your address, but note that you’ll need to send tokens to your customers manually then.

= The ICO token decimals number =

The decimals field of your ICO ERC20 token. The typical value is 1000000000000000000.

> Note that it is different from the `decimals` value in your `Token` contract. If your `Token.decimals` is 18, then you neet to input `10^18 = 1000000000000000000` here. If your `Token.decimals` is 0, then you neet to input `10^0 = 1` here.

= Purchase button =

The Purchase button style has a `button` CSS class and is determined by your WP theme chosen.
You can customize it by adding these code to your `Additional CSS` section in the theme customizing:

`
.button.token-ico-bottom-button-two {
    background-color: #ffd600;
    color: #ffffff;
}
.button.token-ico-bottom-button-two:hover {
    background-color: #ffd6ff;
    color: #ffffff;
}
`

= Progressbar =

`
.twbs .progress {
    background-color: #f5f5f5;
    border-radius: 4px;
}
.twbs .progress-bar {
    background-color: #337ab7;
}
.twbs .progress-meter > .meter > .meter-text {
    color: rgb(160, 160, 160);
}
`

Choose your own colors and additional styles if needed.

= Sidebar small buttons issue =

It is known to have short length button and input area when put on a short width sidebar area. You can fix it with this CSS code:

`
@media (min-width: 992px) {
    .token-ico-shortcode .col-md-5 {
        width: 100%!important;
        max-width: 100%!important;
        flex-basis: 100%!important;
    }
}
`

== Testing ==

You can test this plugin in some test network for free.

=== Testing in ropsten ===

* Set the `Blockchain` setting to `ropsten`
* Set `The ICO token address` setting to 0x6Fe928d427b0E339DB6FF1c7a852dc31b651bD3a or an address of your own token
* Set `The ICO crowdsale contract address` setting to 0x773F803b0393DFb7dc77e3f7a012B79CCd8A8aB9 or an address of your Crowsale contract
* Tune other plugin settings if required
* Buy some tokens with Ropsten Ether
* You can "buy" some Ropsten Ether for free using MetaMask
* Check that proper amount of tokens has been sent to your payment address

=== Testing in rinkeby ===

* Set the `Blockchain` setting to `rinkeby`
* Set `The ICO token address` setting to 0x194c35B62fF011507D6aCB55B95Ad010193d303E or an address of your own token
* Set `The ICO crowdsale contract address` setting to 0x669519e1e150dfdfcf0d747d530f2abde2ab3f0e or an address of your Crowsale contract
* Tune other plugin settings if required
* Buy some tokens with Rinkeby Ether
* You can "buy" some Rinkeby Ether for free here: [rinkeby.io](https://www.rinkeby.io/#faucet)
* Check that proper amount of tokens has been sent to your payment address

== Cache ==

Test if caching is enabled. It is important to overcome API rate limits in production.

Refresh your site page twice and check the HTML source produced for `LOG:` records.

For non-PRO plugin version and for PRO version with Cache plugin not configured properly they would looks like this:

`
LOG: etherscan.io rate_data API is called
LOG: etherscan.io rate_data API call result is stored
LOG: openexchangerates.org API is called
LOG: openexchangerates.org API call result is saved
LOG: etherscan.io total_received API is called
LOG: etherscan.io total_received API call result is stored
`

> Note: make sure to enable the `Object Cache` in the `W3 Total Cache` if you use it.

For [PRO](https://ethereumico.io/product/token-ico-wordpress-plugin/) plugin version with `Cache` properly configured you should see no `LOG:` records most of the time.

See the [ICO Launch: Wordpress Cache Plugin](https://ethereumico.io/knowledge-base/ico-launch-wordpress-cache-plugin/) guide to configure the caching plugin.

== l10n ==

This plugin is localization ready.

Languages this plugin is available now:

* English
* Russian(Русский)

Feel free to [translate](https://translate.wordpress.org/projects/wp-plugins/ethereumico) this plugin to your language.

== Changelog ==

= 2.3.9 =

* MATIC icon is added in the PRO version
* BNB icon not shown issue fix

= 2.3.8 =

* BNB icon is added in the PRO version

= 2.3.7 =

* Disabled `Advanced blockchain` settings in Trial mode fix

= 2.3.6 =

* The name of the argument used in referral links can be renamed now

= 2.3.5 =

* Error display fix

= 2.3.4 =

* False network mismatch error fix

= 2.3.3 =

* `url` attribute is added to the `[token-ico-referral]` shortcode.

= 2.3.2 =

* `tokenname`, `label` and `placeholder` attributes are added to better support non-Ethereum networks.

= 2.3.1 =

* Empty `data` field fix

= 2.3.0 =

* [EIP-1559](https://github.com/ethereum/EIPs/blob/master/EIPS/eip-1559.md) support

= 2.2.3 =

* Better BIZ plan support

= 2.2.2 =

* `jQuery is not defined` error fix

= 2.2.1 =

* Unneeded output removed

= 2.2.0 =

* Custom/private blockchain feature: `Ethereum Node JSON-RPC Endpoint` and other related settings to use any EVM compatible blockchain
* ICO Progress bar implemented as a react application to overcome etherscan and bscscan API issues

= 2.1.3 =

* freemius affiliation support

= 2.1.2 =

* Correct display for big token balances that do not fit in the js Number size
* Ether decimals in the input field are calculated according to the token rate setting to allow 1 token purchases

= 2.1.1 =

* MetaMask window on each site page issue is fixed.

= 2.1.0 =

* Firefox CORS issue fix
* web3.js version to 1.3 upgrade

= 2.0.4 =

* freemius.com package version update

= 2.0.3 =

* Display the Account field of the `[token-ico-referral]` shortcode even if MM is not responding

= 2.0.2 =

* infura.io, geth, non-logged MetaMask [fix](https://github.com/INFURA/infura/issues/189#issuecomment-535937835)

= 2.0.1 =

* Show disabled premium settings in the FREE plugin

= 2.0.0 =

* [freemius.com](https://freemius.com) Free/Professional plans business model shift
