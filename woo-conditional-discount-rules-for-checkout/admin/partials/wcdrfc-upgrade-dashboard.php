<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package Woocommerce_Dynamic_Pricing_And_Discount_Pro
 * @since   2.4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );

// Get product details from Freemius via API
$annual_plugin_price = '';
$monthly_plugin_price = '';
$plugin_details = array(
    'product_id' => 43549,
);

$api_url = add_query_arg(wp_rand(), '', WDPAD_STORE_URL . 'wp-json/dotstore-product-fs-data/v2/dotstore-product-fs-data');
$final_api_url = add_query_arg($plugin_details, $api_url);

if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
    $api_response = vip_safe_wp_remote_get( $final_api_url, 3, 1, 20 );
} else {
    $api_response = wp_remote_get( $final_api_url ); // phpcs:ignore
}

if ( ( !is_wp_error($api_response)) && (200 === wp_remote_retrieve_response_code( $api_response ) ) ) {
	$api_response_body = wp_remote_retrieve_body($api_response);
	$plugin_pricing = json_decode( $api_response_body, true );

	if ( isset( $plugin_pricing ) && ! empty( $plugin_pricing ) ) {
		$first_element = reset( $plugin_pricing );
        if ( ! empty( $first_element['price_data'] ) ) {
            $first_price = reset( $first_element['price_data'] )['annual_price'];
        } else {
            $first_price = "0";
        }

        if( "0" !== $first_price ){
        	$annual_plugin_price = $first_price;
        	$monthly_plugin_price = round( intval( $first_price  ) / 12 );
        }
	}
}

// Set plugin key features content
$plugin_key_features = array(
    array(
        'title' => esc_html__( 'Bulk Discount', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Create bulk discounts in three simple steps. These discounts can be for the entire store/categories/products/attributes. They can also be based on dynamic product discounts. You can create unlimited bulk discount rules.', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-1.png' ),
        'popup_content' => array(
        	esc_html__( 'With our plugin, you have the freedom to set up advanced discount rules using product, category, country, coupon code, and more.', 'woo-conditional-discount-rules-for-checkout' ),
        	esc_html__( 'Enjoy the flexibility and enhance your customers\' shopping experience!', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Shop in the US and save big on "Jacket" with our exclusive bulk discount offer. Like, purchase 5 or more units of "Jacket" and the discounted prices of $20 will be automatically applied at checkout.', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'Purchase 10 or more shirts, to get a flat $10 discount on your location to the UK, and experience automatic discounts at checkout.', 'woo-conditional-discount-rules-for-checkout' ),
        )
    ),
    array(
        'title' => esc_html__( 'Quantity-Based Tiered Discounts', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Similar to the bulk discount, you can create tiered bulk discounts. You can add product quantity range and discounts based on that quantity range.', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-2.png' ),
        'popup_content' => array(
        	esc_html__( 'Set customized discounts based on specific product quantity ranges.', 'woo-conditional-discount-rules-for-checkout' ),
        	esc_html__( 'If you have a special product group that you want to sell in bulk quantity, this feature is very helpful.', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'To take advantage of the exclusive discounts for customers in the "US", Buy 5 to 10 units, and get 15% off; Buy 6 to 10 units, get 25% off. etc.', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'If a category is special arrivals then you can add rules like special arrivals buy 3+ at 30% discount.', 'woo-conditional-discount-rules-for-checkout' )
        )
    ),
    array(
        'title' => esc_html__( 'User Role-Based Discount', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Offer discounts to wholesalers, retail customers, or any user role you’ve defined for your store. Drill down on your discount offerings and watch your business grow!', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-3.png' ),
        'popup_content' => array(
        	esc_html__( 'It is easy to apply discounts based on customer types. Set discounts for consumers, sellers, shop managers, and premium customers.', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Enjoy an exclusive $20 discount on all your orders. Simply log in as a Vendor, and the discount will be automatically applied at checkout.', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'Enjoy a generous $50 discount on all orders. Simply log in as a sales manager, and the discount will be automatically applied at checkout.', 'woo-conditional-discount-rules-for-checkout' )
        )
    ),
    array(
        'title' => esc_html__( 'Percentage Discount On Product Quantity', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Unlock personalized discounts with our conditional percentage discount feature.', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-4.png' ),
        'popup_content' => array(
        	esc_html__( 'Unlock percentage discounts with flexible conditional rules. Personalize offers based on product quantity, user details, and more.', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Shop "Jacket" now and enjoy a 15% discount when purchasing 3 or more units. Save big on bulk orders and stock up on your favorite items!', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'Easy to apply user-specific percentage discount: a 3% discount for shop managers or a 2% discount for registered members.', 'woo-conditional-discount-rules-for-checkout' ),
        )
    ),
    array(
        'title' => esc_html__( 'Buy One Get One offer (BOGO)', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Create any type of BOGO offer. The plugin supports "Buy X Get X", "Buy X Get Y", and conditional BOGO offers like "Buy product A and get 50% off the cheapest item in your cart".', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-5.png' ),
        'popup_content' => array(
        	esc_html__( 'You can offer special discounts on special products that sell in your store.', 'woo-conditional-discount-rules-for-checkout' ),
        	esc_html__( 'Using this offer customers enjoy double the quantity at the price of one and store owners get revenue by 2x an attractive product discount.', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Buy any special product like Pizza and get one pizza free (BOGO Discount).', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'Purchase "Jacket" and receive another product "Jacket" absolutely free.', 'woo-conditional-discount-rules-for-checkout' )
        )
    ),
    array(
        'title' => esc_html__( 'Payment Gateway-Based Discount', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Charge discounts to the customers for choosing a specific payment gateway based on the order amount.', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-6.png' ),
        'popup_content' => array(
        	esc_html__( 'Provide exclusive discounts based on your preferred payment method!', 'woo-conditional-discount-rules-for-checkout' ),
        	esc_html__( 'Choose from a variety of payment options, and you\'ll automatically receive special savings at checkout.', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Customize discount based on payment type: 2% discount for payments made through credit cards.', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'Easily apply a discount for wire transfer payments: a $5 flat discount per transaction.', 'woo-conditional-discount-rules-for-checkout' )
        )
    ),
    array(
        'title' => esc_html__( 'Time-Based Discounts', 'woo-conditional-discount-rules-for-checkout' ),
        'description' => esc_html__( 'Enjoy exclusive savings during specified periods. Shop now and take advantage of limited-time offers.', 'woo-conditional-discount-rules-for-checkout' ),
        'popup_image' => esc_url( WDPAD_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-7.png' ),
        'popup_content' => array(
        	esc_html__( 'Embrace the moment with our time-based discount! For a limited period, enjoy special savings on select products.', 'woo-conditional-discount-rules-for-checkout' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Celebrate the holidays with us! Enjoy a jolly 25% discount on all products from Christmas to New Year.', 'woo-conditional-discount-rules-for-checkout' ),
            esc_html__( 'Enjoy a fantastic 20% discount on all products. This special offer is available for the next 24 hours.', 'woo-conditional-discount-rules-for-checkout' ),
        )
    ),
);
?>
	<div class="dotstore-upgrade-dashboard">
		<div class="premium-benefits-section">
			<h2><?php esc_html_e( 'Upgrade to Unlock Premium Features', 'woo-conditional-discount-rules-for-checkout' ); ?></h2>
			<p><?php esc_html_e( 'Check out the advanced features, Simplify fee management, drive more sales, and boost your revenue by upgrading to premium!', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
		</div>
		<div class="premium-plugin-details">
			<div class="premium-key-fetures">
				<h3><?php esc_html_e( 'Discover Our Top Key Features', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
				<ul>
					<?php 
					if ( isset( $plugin_key_features ) && ! empty( $plugin_key_features ) ) {
						foreach( $plugin_key_features as $key_feature ) {
							?>
							<li>
								<h4><?php echo esc_html( $key_feature['title'] ); ?><span class="premium-feature-popup"></span></h4>
								<p><?php echo esc_html( $key_feature['description'] ); ?></p>
								<div class="feature-explanation-popup-main">
									<div class="feature-explanation-popup-outer">
										<div class="feature-explanation-popup-inner">
											<div class="feature-explanation-popup">
												<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-conditional-discount-rules-for-checkout'); ?>"></span>
												<div class="popup-body-content">
													<div class="feature-content">
														<h4><?php echo esc_html( $key_feature['title'] ); ?></h4>
														<?php 
														if ( isset( $key_feature['popup_content'] ) && ! empty( $key_feature['popup_content'] ) ) {
															foreach( $key_feature['popup_content'] as $feature_content ) {
																?>
																<p><?php echo esc_html( $feature_content ); ?></p>
																<?php
															}
														}
														?>
														<ul>
															<?php 
															if ( isset( $key_feature['popup_examples'] ) && ! empty( $key_feature['popup_examples'] ) ) {
																foreach( $key_feature['popup_examples'] as $feature_example ) {
																	?>
																	<li><?php echo esc_html( $feature_example ); ?></li>
																	<?php
																}
															}
															?>
														</ul>
													</div>
													<div class="feature-image">
														<img src="<?php echo esc_url( $key_feature['popup_image'] ); ?>" alt="<?php echo esc_attr( $key_feature['title'] ); ?>">
													</div>
												</div>
											</div>		
										</div>
									</div>
								</div>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="premium-plugin-buy">
				<div class="premium-buy-price-box">
					<div class="price-box-top">
						<div class="pricing-icon">
							<img src="<?php echo esc_url( WDPAD_PLUGIN_URL . 'admin/images/premium-upgrade-img/pricing-1.svg' ); ?>" alt="<?php esc_attr_e( 'Personal Plan', 'woo-conditional-discount-rules-for-checkout' ); ?>">
						</div>
						<h4><?php esc_html_e( 'Personal', 'woo-conditional-discount-rules-for-checkout' ); ?></h4>
					</div>
					<div class="price-box-middle">
						<?php
						if ( ! empty( $annual_plugin_price ) ) {
							?>
							<div class="monthly-price-wrap"><?php echo esc_html( '$' . $monthly_plugin_price ); ?><span class="seprater">/</span><span><?php esc_html_e( 'month', 'woo-conditional-discount-rules-for-checkout' ); ?></span></div>
							<div class="yearly-price-wrap"><?php echo sprintf( esc_html__( 'Pay $%s today. Renews in 12 months.', 'woo-conditional-discount-rules-for-checkout' ), esc_html( $annual_plugin_price ) ); ?></div>
							<?php	
						}
						?>
						<span class="for-site"><?php esc_html_e( '1 site', 'woo-conditional-discount-rules-for-checkout' ); ?></span>
						<p class="price-desc"><?php esc_html_e( 'Great for website owners with a single WooCommerce Store', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
					</div>
					<div class="price-box-bottom">
						<a href="javascript:void(0);" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'woo-conditional-discount-rules-for-checkout' ); ?></a>
						<p class="trusted-by"><?php esc_html_e( 'Trusted by 100,000+ store owners and WP experts!', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
					</div>
				</div>
				<div class="premium-satisfaction-guarantee premium-satisfaction-guarantee-2">
					<div class="money-back-img">
						<img src="<?php echo esc_url(WDPAD_PLUGIN_URL . 'admin/images/premium-upgrade-img/14-Days-Money-Back-Guarantee.png'); ?>" alt="<?php esc_attr_e('14-Day money-back guarantee', 'woo-conditional-discount-rules-for-checkout'); ?>">
					</div>
					<div class="money-back-content">
						<h2><?php esc_html_e( '14-Day Satisfaction Guarantee', 'woo-conditional-discount-rules-for-checkout' ); ?></h2>
						<p><?php esc_html_e( 'You are fully protected by our 100% Satisfaction Guarantee. If over the next 14 days you are unhappy with our plugin or have an issue that we are unable to resolve, we\'ll happily consider offering a 100% refund of your money.', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
					</div>
				</div>
				<div class="plugin-customer-review">
					<h3><?php esc_html_e( 'Super And Easy To Setup!', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
					<p>
						<?php echo wp_kses( __( 'This plugin was the most complete and <strong>created any type of woocommerce dynamic discount</strong>. This is easy to understand, and with just a little effort you can already execute all the rules you want. They provide the <strong>most adequate and fastest support</strong>.', 'woo-conditional-discount-rules-for-checkout' ), array(
				                'strong' => array(),
				            ) ); 
			            ?>
		            </p>
					<div class="review-customer">
						<div class="customer-img">
							<img src="<?php echo esc_url(WDPAD_PLUGIN_URL . 'admin/images/premium-upgrade-img/customer-profile-img.jpeg'); ?>" alt="<?php esc_attr_e('Customer Profile Image', 'woo-conditional-discount-rules-for-checkout'); ?>">
						</div>
						<div class="customer-name">
							<span><?php esc_html_e( 'William Ramos', 'woo-conditional-discount-rules-for-checkout' ); ?></span>
							<div class="customer-rating-bottom">
								<div class="customer-ratings">
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
								</div>
								<div class="verified-customer">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Verified Customer', 'woo-conditional-discount-rules-for-checkout' ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="upgrade-to-pro-faqs">
			<h2><?php esc_html_e( 'FAQs', 'woo-conditional-discount-rules-for-checkout' ); ?></h2>
			<div class="upgrade-faqs-main">
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'Do you offer support for the plugin? What’s it like?', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p>
						<?php 
							echo sprintf(
							    esc_html__('Yes! You can read our %s or submit a %s. We are very responsive and strive to do our best to help you.', 'woo-conditional-discount-rules-for-checkout'),
							    '<a href="' . esc_url('https://docs.thedotstore.com/category/323-premium-plugin-settings') . '" target="_blank">' . esc_html__('knowledge base', 'woo-conditional-discount-rules-for-checkout') . '</a>',
							    '<a href="' . esc_url('https://www.thedotstore.com/support-ticket/') . '" target="_blank">' . esc_html__('support ticket', 'woo-conditional-discount-rules-for-checkout') . '</a>',
							);

						?>
						</p>
					</div>
				</div>
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'What payment methods do you accept?', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p><?php esc_html_e( 'You can pay with your credit card using Stripe checkout. Or your PayPal account.', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
					</div>
				</div>
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'What’s your refund policy?', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p><?php esc_html_e( 'We have a 14-day money-back guarantee.', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
					</div>
				</div>
				<div class="upgrade-faqs-list">
					<div class="upgrade-faqs-header">
						<h3><?php esc_html_e( 'I have more questions…', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
					</div>
					<div class="upgrade-faqs-body">
						<p>
						<?php 
							echo sprintf(
							    esc_html__('No problem, we’re happy to help! Please reach out at %s.', 'woo-conditional-discount-rules-for-checkout'),
							    '<a href="' . esc_url('mailto:hello@thedotstore.com') . '" target="_blank">' . esc_html('hello@thedotstore.com') . '</a>',
							);

						?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="upgrade-to-premium-btn">
			<a href="javascript:void(0);" target="_blank" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'woo-conditional-discount-rules-for-checkout' ); ?><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="crown" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-crown fa-w-20 fa-3x" width="22" height="20"><path fill="#000" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z" class=""></path></svg></a>
		</div>
	</div>
</div>
</div>
</div>
</div>
<?php 
