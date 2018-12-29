<?php
/**
 * Weclome Page Class
 *
 * @since       1.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists ( 'wpinked_so_admin_page' ) ) :
function wpinked_so_admin_page() {
	add_menu_page(
		'Widgets for SiteOrigin',
		__( 'WPinked Widgets', 'wpinked-widgets' ),
		'manage_options',
		'wpinked-widgets',
		'wpinked_so_admin_page_content',
		plugin_dir_url(__FILE__) . 'img/menu-icon.png',
		99
	);

	add_submenu_page(
		'wpinked-widgets',
		'Welcome to Widgets for SiteOrigin',
		__( 'Get Addons', 'wpinked-widgets' ),
		'manage_options',
		'wpinked-widgets',
		'wpinked_so_admin_page_content'
	);
}
endif;
add_action( 'admin_menu', 'wpinked_so_admin_page' );


if ( ! function_exists ( 'wpinked_so_admin_page_content' ) ) :
function wpinked_so_admin_page_content() {
	wp_enqueue_style( 'iw-admin-css' );
	wp_enqueue_script( 'iw-admin-js' );
	wp_enqueue_script( 'iw-admin-icons-js' );
	?>
	<style>
	.main-buttons .uk-button { background: #fff; margin-bottom: 15px; }
	.ink-boxes h4 { font-size: 15px }
	.ink-boxes .uk-icon { color: #3EB0F7; padding-right: 15px; }
	.ink-boxes .uk-card-footer, .ink-boxes .uk-card-header { border-width: 0 !important; }
	.ink-boxes .uk-card-body { padding: 20px 40px }
	.ink-boxes .uk-card-footer a { font-size: 12px; background: #3EB0F7; color: #fff; text-decoration: none; padding: 8px 15px; }
	.sale-notice { background: #fff; padding: 20px 30px; margin-bottom: 50px !important; border-left: 10px solid #3EB0F7 }
	.sale-notice p { font-size: 14px; margin: 0; }
	.sale-notice p .uk-icon { color: #3EB0F7; position: relative; bottom: 3px;}
	.uk-updates { margin-bottom: 50px; }
	.uk-updates h4 { font-size: 13px; }
	.uk-updates .uk-grid .uk-card { padding: 15px; background: #fff }
	.uk-updates .uk-grid .uk-card a { width: 100%; height: 100%; display: inline-block; text-decoration: none !important; }
	.uk-updates .uk-grid .uk-card p { font-size: 11px; color: #333; max-width: 100px; border-bottom: 2px solid transparent; }
	.uk-updates .uk-grid .uk-card { border: 2px solid transparent; }
	.uk-updates .uk-grid .uk-card:hover { border: 2px solid #D0ECFD; }
	.toplevel_page_wpinked-widgets #wpcontent { background: #f1f1f1; }
	.uk-tile { display: none; }
	.upgrade-plugin-form .uk-tile { display: block; }
	</style>
	<div class="uk-section uk-padding-remove wpinked-widgets-admin-wrapper" style="background: #f1f1f1;">
		<div class="uk-container uk-container-expand">
			<div class="uk-width-1-1 sale-notice">
				<p><span uk-icon="bolt"></span> &nbsp;&nbsp; <b>FLASH SALE : </b> &nbsp;&nbsp;Use the coupon code <b>SALE10</b> to redeem a 10% discount on Widgets for SiteOrign Premium.</p>
			</div>
			<div uk-grid>
				<div class="uk-width-2-3@m">
					<div class="main-buttons">
						<a class="uk-button uk-button-default uk-button-large" href="<?php echo admin_url( 'admin.php?page=wpinked-widgets' ); ?>">General</a>
						<a class="uk-button uk-button-default uk-button-large" href="<?php echo admin_url( 'plugins.php?page=so-widgets-plugins' ); ?>">Manage Widgets</a>
						<a class="uk-button uk-button-default uk-button-large" href="http://widgets.wpinked.com" target="_blank">Demo</a>
						<a class="uk-button uk-button-default uk-button-large" href="http://widgets.wpinked.com/docs/" target="_blank">Documentation</a>
					</div>
					<div class="" uk-grid>
						<div class="uk-width-5-6@xl">
							<div class="uk-child-width-1-2@l ink-boxes uk-grid-match uk-grid-small" uk-grid>
								<div>
									<img class="uk-box-shadow-medium uk-width-1-1 uk-height-1-1" data-src="<?php echo plugin_dir_url(__FILE__); ?>img/plugin-banner.png" width="" height="" alt="" uk-img>
								</div>
								<div>
									<div class="uk-card uk-card-default">
										<div class="uk-card-header">
											<div class="uk-grid-small uk-flex-middle" uk-grid>
												<h4 class="uk-card-title uk-margin-remove-bottom uk-text-uppercase"><span uk-icon="icon: info; ratio: 0.8"></span>Documentation</h4>
											</div>
										</div>
										<div class="uk-card-body">
											<p>Have a look at our documentation to get to know our widgets. Build awesome pages with page builders.</p>
										</div>
										<div class="uk-card-footer">
											<a href="http://widgets.wpinked.com/docs/" target="_blank" class="uk-button uk-button-text uk-button-small">See now</a>
										</div>
									</div>
								</div>
								<div>
									<div class="uk-card uk-card-default">
										<div class="uk-card-header">
											<div class="uk-grid-small uk-flex-middle" uk-grid>
												<h4 class="uk-card-title uk-margin-remove-bottom uk-text-uppercase"><span uk-icon="icon: question; ratio: 0.8"></span>Need Help</h4>
											</div>
										</div>
										<div class="uk-card-body">
											<p>Need help troubleshooting. Get help at the <a href="https://wordpress.org/support/plugin/widgets-for-siteorigin" target="_blank" >WordPress Support Forum</a>. Pro users can email us help@wpinked.com for quicker response.</p>
										</div>
										<div class="uk-card-footer">
											<a href="https://wordpress.org/support/plugin/widgets-for-siteorigin" target="_blank" class="uk-button uk-button-small uk-button-text">Get Support</a>
										</div>
									</div>
								</div>
								<div>
									<div class="uk-card uk-card-default">
										<div class="uk-card-header">
											<div class="uk-grid-small uk-flex-middle" uk-grid>
												<h4 class="uk-card-title uk-margin-remove-bottom uk-text-uppercase"><span uk-icon="icon: heart; ratio: 0.8"></span>Rate the plugin</h4>
											</div>
										</div>
										<div class="uk-card-body">
											<p>If you liked your experience with Widgets for SiteOrigin please take a couple of minutes to leave a review. Thanks.</p>
										</div>
										<div class="uk-card-footer">
											<a href="https://wordpress.org/support/plugin/widgets-for-siteorigin/reviews/#new-post" target="_blank" class="uk-button uk-button-small uk-button-text">Review Now</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="uk-updates uk-padding-large uk-padding-remove-horizontal">
						<div class="uk-width-1-1"><h4 class="uk-text-uppercase">New in this update</h4></div>
						<div class="uk-child-width-auto uk-grid-match" uk-grid>
							<div>
								<div class="uk-card">
									<a href="http://widgets.wpinked.com/flip-carousel-widget/" target="_blank">
										<p class="uk-text-center uk-text-uppercase">Premium</p>
										<center><img src="<?php echo plugin_dir_url(__FILE__); ?>img/flip-carousel.svg" width="75" height="75"></center>
										<p class="uk-text-center uk-text-uppercase">Flip Carousel Widget</p>
									</a>
								</div>
							</div>
							<div>
								<div class="uk-card">
									<a href="http://widgets.wpinked.com/image-compare-widget/" target="_blank">
										<p class="uk-text-center uk-text-uppercase">Premium</p>
										<center><img src="<?php echo plugin_dir_url(__FILE__); ?>img/image-compare.svg" width="75" height="75"></center>
										<p class="uk-text-center uk-text-uppercase">Image Compare Widget</p>
									</a>
								</div>
							</div>
							<div>
								<div class="uk-card">
									<a href="http://widgets.wpinked.com/content-toggle-widget/" target="_blank">
										<p class="uk-text-center uk-text-uppercase">Premium</p>
										<center><img src="<?php echo plugin_dir_url(__FILE__); ?>img/content-toggle.svg" width="75" height="75"></center>
										<p class="uk-text-center uk-text-uppercase">Content Toggle Widget</p>
									</a>
								</div>
							</div>
							<div>
								<div class="uk-card">
									<a href="http://widgets.wpinked.com/advanced-info-box-widget/" target="_blank">
										<p class="uk-text-center uk-text-uppercase">Premium</p>
										<center><img src="<?php echo plugin_dir_url(__FILE__); ?>img/adv-info-box.svg" width="75" height="75"></center>
										<p class="uk-text-center uk-text-uppercase">Advanced Info Box Widget</p>
									</a>
								</div>
							</div>
							<div>
								<div class="uk-card">
									<a href="http://widgets.wpinked.com/person-widget/" target="_blank">
										<p class="uk-text-center uk-text-uppercase"></p>
										<center><img src="<?php echo plugin_dir_url(__FILE__); ?>img/person.svg" width="75" height="75"></center>
										<p class="uk-text-center uk-text-uppercase">Image Flip for person and person slider widgets</p>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="uk-width-1-3@m">
					<div>
						<style>
							#add-to-cart { background: #3EB0F7; max-width: 350px; border-radius: 3px; }
							#add-to-cart h2 { font-size: 18px; background: #098ddf; padding: 30px; color: #fff; margin-bottom: 30px; font-weight: 300; border-radius: 3px; }
							#add-to-cart .bare-list { list-style: none; margin: 30px; padding: 20px 0; }
							#add-to-cart .pill-selectors li { text-align: center; }
							#add-to-cart .pill-selectors li label { padding: 15px 25px; font-size: 15px; border-radius: 3px; border: #098ddf 3px solid; color: #fff; position: relative; }
							#add-to-cart .pill-selectors li label input { opacity: 0; position: absolute; top: 0; left: 0; }
							#add-to-cart .pill-selectors li .pill-selectors__button--active { background: #098ddf }
							#add-to-cart .payment-options__disclaimer { padding: 0 30px; }
							#add-to-cart .payment-options__disclaimer .uk-list { font-size: 15px; color: #fff; margin:0; }
							#add-to-cart .payment-options__prices li { font-size: 15px; color: #fff; padding-top: 0; margin-bottom: 20px; }
							#add-to-cart .payment-options__sites { padding: 5px; background: #098ddf; border-radius: 3px; }
							.wpinked-plugin-logo { transition: all 400ms ease; width: 256px; height: auto; }
							.upgrade-plugin-form .wpinked-plugin-logo { width: 25%; }
							#add-to-cart { display: none; }
							.upgrade-plugin-form #add-to-cart { display: block; }
							.premium-description { color: #bbb; font-style: italic; font-size: 12px;margin: 10px 30px; }
							.upgrade-plugin-form .premium-description { display: none; }
						</style>
						<center><img class="wpinked-plugin-logo" data-src="<?php echo plugin_dir_url(__FILE__); ?>img/logo-plugin.png" width="" height="" alt="" uk-img></center>
						<a class="uk-button upgrade-to-pro uk-button-default uk-button-large uk-text-center uk-text-uppercase uk-width-1-1" href="">Upgrade to Premium</a>
						<p class="premium-description uk-text-center">( Get access to an ever growing library of widgets, email support and updates for the duration of the license. Renewals also come with a 25% discount )</p>
						<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
						<script src="https://checkout.freemius.com/checkout.min.js"></script>
						<div id="add-to-cart" class="payment-options payment-options--theme uk-margin-auto uk-light">
							<h2 class="uk-light">Purchase a premium license</h2>
							<ul class="payment-options__billing-cycle bare-list inline-list pill-selectors uk-child-width-1-2 uk-grid-collapse" uk-grid>
								<li class="pill-selectors__item">
									<label class="pill-selectors__button pill-selectors__button--active">
										<input type="radio" name="billing_cycle" value="annual" checked="checked" class="iconic-billing-cycle-selector">
										<strong class="payment-options__label-text">Annually</strong>
									</label>
								</li>
								<li class="pill-selectors__item">
									<label class="pill-selectors__button">
										<input type="radio" name="billing_cycle" value="lifetime" class="iconic-billing-cycle-selector">
										<strong class="payment-options__label-text">Lifetime</strong>
									</label>
								</li>
							</ul>
							<div class="payment-options__disclaimer">
								<div class="payment-options__disclaimer-annual">
									<ul class="uk-list uk-list-divider uk-light">
										<li class="uk-text-center">37+ widgets</li>
										<li class="uk-text-center">1 year of updates</li>
										<li class="uk-text-center">1 year of support</li>
										<li class="uk-text-center">25% renewal discount</li>
									</ul>
								</div>
								<div class="payment-options__disclaimer-lifetime" style="display: none;">
									<ul class="uk-list uk-list-divider uk-light">
										<li class="uk-text-center">37+ widgets</li>
										<li class="uk-text-center">Lifetime updates</li>
										<li class="uk-text-center">Lifetime support</li>
										<li class="uk-text-center">One-time payment</li>
									</ul>
								</div>
							</div>
							<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
							<ul class="payment-options__prices bare-list">
								<li>
									<label>
										<input type="radio" name="licence" value="1" checked="checked">
										<span class="payment-options__label-text">
											<span class="payment-options__sites">1 Site</span>
											<span class="payment-options__price" data-annual="23.99" data-lifetime="69.99">
												<span itemprop="priceCurrency" content="USD">$</span><span itemprop="price">23.99</span>
											</span>
										</span>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="licence" value="5">
										<span class="payment-options__label-text">
											<span class="payment-options__sites">5 Sites</span>
												<span class="payment-options__price" data-annual="39.99" data-lifetime="119.99">
													$<span>39.99</span>
												</span>
											<span class="payment-options__save">( Save 66% )</span>
										</span>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="licence" value="45">
										<span class="payment-options__label-text">
											<span class="payment-options__sites">45 Sites</span>
											<span class="payment-options__price" data-annual="59.99" data-lifetime="179.99">
												$<span>59.99</span>
											</span>
											<span class="payment-options__save">( Save 94% )</span>
										</span>
									</label>
								</li>
							</ul>
						</div>
						<style>
							#add-to-cart .btn--buy-now { border-radius: 3px; color: #fff; background: #333; border: 0; padding: 15px 25px; margin-bottom: 45px; cursor: pointer; font-size: 16px; }
							#add-to-cart .btn--buy-now .uk-icon { padding-right: 5px; }
						</style>
						<p class="clearfix u-push-bottom--zero uk-text-center">
							<button class="btn btn--primary btn--buy-now" data-fbq="BuyPlugin" data-type="buy-now-btn" data-plugin="Widgets for SiteOrigin">
							<span uk-icon="icon: cart; ratio: 0.9"></span> Buy Now
							</button>
						</p>
						<script>
							jQuery( document ).ready( function() {
								$( '.upgrade-to-pro' ).on( 'click', function(e) {
									e.preventDefault();
									$( '.wpinked-widgets-admin-wrapper' ).addClass( 'upgrade-plugin-form' );
									$( this ).hide();
								} );

								var handler = FS.Checkout.configure( {
									plugin_id: '',
									public_key: ''
								} );

								jQuery( '.pill-selectors__button' ).on( 'click', function( e ) {
									if ( $(this).hasClass( 'pill-selectors__button--active' ) ) return;
									if ( ! $(this).hasClass( 'pill-selectors__button--active' ) ) {
										$(this).closest( 'ul' ).find( '.pill-selectors__button' ).removeClass( 'pill-selectors__button--active' );
										$(this).addClass( 'pill-selectors__button--active' );
									}
								} );

								jQuery( '.btn--buy-now' ).on( 'click', function( e ) {
									var $btn = jQuery( this ),
										type = $btn.data( 'type' ),
										trial = false,
										billing_cycle = jQuery( '.iconic-billing-cycle-selector:checked' ).val() || 'annual',
										licence = jQuery( '[name="licence"]:checked' ).val();

										if ( typeof ga !== "undefined" ) {
											ga( 'send', 'event', 'plugin', type, 'Widgets for SiteOrigin' );
										}

										handler.open( {
											plugin_id: '2753',
											plan_id: '4308',
											public_key: 'pk_12d52e412c9845eb83c6c5c8bc3d3',
											image: 'http://widgets.wpinked.com/wp-content/uploads/2018/10/71529f7648511ce3a2b930cd274fbc7b.png',
											subtitle: 'Widgets for SiteOrigin',
											name: 'Widgets for SiteOrigin',
											licenses: licence,
											billing_cycle: billing_cycle,
										} );
										e.preventDefault();
									} );

									jQuery( '.iconic-billing-cycle-selector' ).on( 'change', function( e ) {
										var $selector = jQuery( this ),
											cycle = $selector.val(),
											$options = $selector.closest( '.payment-options' ),
											$prices = $options.find( '.payment-options__price' );

										jQuery( '.payment-options__disclaimer div' ).hide();
											jQuery( '.payment-options__disclaimer-' + cycle ).show();
											$prices.each( function( index, price ) {
											var $price = jQuery( price ),
												$number = $price.find( 'span:last' );
												$number.text( $price.data( cycle ) );
											} );
									} );
								} );
							</script>
						</div>
					</div>
					<div>
						<style>
						.uk-tile dt { font-size: 16px; margin-bottom: 15px; }
						.uk-tile dd { font-size: 11px; }
						</style>
						<div class="uk-tile uk-tile-muted">
							<dl class="uk-description-list">
								<dt class="uk-text-center">14-Day Money Back Guarantee</dt>
								<dd class="uk-text-center">You are fully protected by our 100% Money Back Guarantee. If during the next 14 days you experience an issue that makes the plugin unusable and we are unable to resolve it, we'll happily consider offering a full refund of your money.</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
endif;