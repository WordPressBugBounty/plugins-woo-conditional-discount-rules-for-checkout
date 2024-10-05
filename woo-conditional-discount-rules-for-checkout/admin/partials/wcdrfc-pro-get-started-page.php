<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php' );
?>
<div class="wdpad-main-table res-cl element-shadow">
    <div class="dots-getting-started-main">
        <div class="getting-started-content">
            <span><?php esc_html_e( 'How to Get Started', 'woo-conditional-discount-rules-for-checkout' ); ?></span>
            <h3><?php esc_html_e( 'Welcome to Dynamic Pricing Plugin', 'woo-conditional-discount-rules-for-checkout' ); ?></h3>
            <p><?php esc_html_e( 'Thank you for choosing our top-rated WooCommerce Dynamic Pricing and Discount Rules plugin. Our user-friendly interface makes it easy to set up different conditional discount rules.', 'woo-conditional-discount-rules-for-checkout' ); ?></p>
            <p>
                <?php 
                echo sprintf(
                    esc_html__('To help you get started, watch the quick tour video on the right. For more help, explore our help documents or visit our %s for detailed video tutorials.', 'woo-conditional-discount-rules-for-checkout'),
                    '<a href="' . esc_url('https://www.youtube.com/@Dotstore16') . '" target="_blank">' . esc_html__('YouTube channel', 'woo-conditional-discount-rules-for-checkout') . '</a>',
                );
                ?>
            </p>
            <div class="getting-started-actions">
                <a href="<?php echo esc_url(add_query_arg(array('page' => 'wcdrfc-rules-list'), admin_url('admin.php'))); ?>" class="quick-start"><?php esc_html_e( 'Manage Discount Rules', 'woo-conditional-discount-rules-for-checkout' ); ?><span class="dashicons dashicons-arrow-right-alt"></span></a>
                <a href="https://docs.thedotstore.com/article/960-beginners-guide-for-dynamic-pricing" target="_blank" class="setup-guide"><span class="dashicons dashicons-book-alt"></span><?php esc_html_e( 'Read the Setup Guide', 'woo-conditional-discount-rules-for-checkout' ); ?></a>
            </div>
        </div>
        <div class="getting-started-video">
            <iframe width="960" height="600" src="<?php echo esc_url('https://www.youtube.com/embed/oeytloz7bVo'); ?>" title="<?php esc_attr_e( 'Plugin Tour', 'woo-conditional-discount-rules-for-checkout' ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-footer.php' ); ?>