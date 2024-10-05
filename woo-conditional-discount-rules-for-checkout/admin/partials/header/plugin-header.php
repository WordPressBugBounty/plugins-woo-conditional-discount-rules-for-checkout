<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$wdpad_plugin_admin = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin('', '');
?>
<div class="wrap">
    <div id="dotsstoremain">
        <div class="all-pad">
            <?php 
$wdpad_plugin_admin->wdpad_get_promotional_bar( WDPAD_PROMOTIONAL_SLUG );
?>
            <hr class="wp-header-end" />
            <header class="dots-header">
                <div class="dots-plugin-details">
                    <div class="dots-header-left">
                        <div class="dots-logo-main">
                            <img src="<?php 
echo esc_url( WDPAD_PLUGIN_URL ) . 'admin/images/wc-conditional-product-dpad.png';
?>">
                        </div>
                        <div class="plugin-name">
                            <div class="title"><?php 
echo esc_html( WDPAD_PLUGIN_NAME );
?></div>
                        </div>
                        <span class="version-label <?php 
echo esc_attr( WDPAD_PROMOTIONAL_SLUG );
?>"><?php 
echo esc_html( WDPAD_VERSION_LABEL );
?></span>
                        <span class="version-number"><?php 
echo esc_html( WDPAD_PLUGIN_VERSION );
?></span>
                    </div>
                    <div class="dots-header-right">
                        <div class="button-dots">
                            <a target="_blank" href="<?php 
echo esc_url( 'https://www.thedotstore.com/support/' );
?>">
                                <?php 
esc_html_e( 'Support', 'woo-conditional-discount-rules-for-checkout' );
?>
                            </a>
                        </div>
                        <div class="button-dots">
                            <a target="_blank" href="<?php 
echo esc_url( 'https://www.thedotstore.com/feature-requests/' );
?>">
                                <?php 
esc_html_e( 'Suggest', 'woo-conditional-discount-rules-for-checkout' );
?>
                            </a>
                        </div>
                        <div class="button-dots <?php 
echo ( wcdrfc_fs()->is__premium_only() && wcdrfc_fs()->can_use_premium_code() ? '' : 'last-link-button' );
?>">
                            <a target="_blank" href="<?php 
echo esc_url( 'https://docs.thedotstore.com/category/323-premium-plugin-settings' );
?>">
                                <?php 
esc_html_e( 'Help', 'woo-conditional-discount-rules-for-checkout' );
?>
                            </a>
                        </div>

                        <?php 
?>
                            <div class="button-dots">
                                <a target="_blank" class="dots-upgrade-btn" href="javascript:void(0);">
                                    <?php 
esc_html_e( 'Upgrade Now', 'woo-conditional-discount-rules-for-checkout' );
?>
                                </a>
                            </div>
                        <?php 
?>
                    </div>
                </div>
                <?php 
$menu_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$dpad_free_dashboard = ( isset( $menu_page ) && $menu_page === 'wcdrfc-upgrade-dashboard' ? 'active' : '' );
$dpad_list = ( isset( $menu_page ) && $menu_page === 'wcdrfc-rules-list' ? 'active' : '' );
$dpad_getting_started = ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-get-started' ? 'active' : '' );
$dpad_licenses = ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-get-started-account' ? 'active' : '' );
$dpad_import_export = ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-import-export' ? 'active' : '' );
$dpad_general_setting = ( isset( $menu_page ) && $menu_page === 'wcdrfc-page-general-settings' ? 'active' : '' );
$dpad_settings_menu = ( isset( $menu_page ) && ('wcdrfc-page-import-export' === $menu_page || 'wcdrfc-page-general-settings' === $menu_page) ? 'active' : '' );
$dpad_display_submenu = ( !empty( $dpad_settings_menu ) && 'active' === $dpad_settings_menu ? 'display:inline-block' : 'display:none' );
if ( is_network_admin() ) {
    $admin_url = admin_url();
} else {
    $admin_url = admin_url( 'admin.php' );
}
$dpad_settings_page_url = add_query_arg( array(
    'page' => 'wcdrfc-page-general-settings',
), $admin_url );
?>
                <div class="dots-bottom-menu-main">
                    <div class="dots-menu-main">
                        <nav>
                            <ul>
                                <li>
                                    <a class="dotstore_plugin <?php 
echo esc_attr( $dpad_list );
?>"
                                    href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdrfc-rules-list',
), $admin_url ) );
?>"><?php 
esc_html_e( 'Manage Rules', 'woo-conditional-discount-rules-for-checkout' );
?></a>
                                </li>
                                <li>
                                    <a class="dotstore_plugin <?php 
echo esc_attr( $dpad_settings_menu );
?>" href="<?php 
echo esc_url( $dpad_settings_page_url );
?>"><?php 
esc_html_e( 'Settings', 'woo-conditional-discount-rules-for-checkout' );
?></a>
                                </li>
                                <?php 
if ( wcdrfc_fs()->is__premium_only() && wcdrfc_fs()->can_use_premium_code() ) {
    ?>
                                    <li>
                                        <a class="dotstore_plugin <?php 
    echo esc_attr( $dpad_licenses );
    ?>" href="<?php 
    echo esc_url( wcdrfc_fs()->get_account_url() );
    ?>"><?php 
    esc_html_e( 'License', 'woo-conditional-discount-rules-for-checkout' );
    ?></a>
                                    </li>
                                    <?php 
} else {
    ?>
                                    <li>
                                        <a class="dotstore_plugin dots_get_premium <?php 
    echo esc_attr( $dpad_free_dashboard );
    ?>" href="<?php 
    echo esc_url( add_query_arg( array(
        'page' => 'wcdrfc-upgrade-dashboard',
    ), admin_url( 'admin.php' ) ) );
    ?>"><?php 
    esc_html_e( 'Get Premium', 'woo-conditional-discount-rules-for-checkout' );
    ?></a>
                                    </li>
                                    <?php 
}
?>
                            </ul>
                        </nav>
                    </div>
                    <div class="dots-getting-started">
                        <a href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdrfc-page-get-started',
), admin_url( 'admin.php' ) ) );
?>" class="<?php 
echo esc_attr( $dpad_getting_started );
?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 4.75a7.25 7.25 0 100 14.5 7.25 7.25 0 000-14.5zM3.25 12a8.75 8.75 0 1117.5 0 8.75 8.75 0 01-17.5 0zM12 8.75a1.5 1.5 0 01.167 2.99c-.465.052-.917.44-.917 1.01V14h1.5v-.845A3 3 0 109 10.25h1.5a1.5 1.5 0 011.5-1.5zM11.25 15v1.5h1.5V15h-1.5z" fill="#a0a0a0"></path></svg></a>
                    </div>
                </div>
            </header>
            <!-- Upgrade to pro popup -->
            <?php 
if ( !(wcdrfc_fs()->is__premium_only() && wcdrfc_fs()->can_use_premium_code()) ) {
    require_once WDPAD_PLUGIN_DIR_PATH . 'admin/partials/dots-upgrade-popup.php';
}
?>
            <div class="dots-settings-inner-main">
                <div class="wcdrfc-section-left">
                    <div class="dotstore-submenu-items" style="<?php 
echo esc_attr( $dpad_display_submenu );
?>">
                        <ul>
                            <li><a class="<?php 
echo esc_attr( $dpad_general_setting );
?>" href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdrfc-page-general-settings',
), $admin_url ) );
?>"><?php 
esc_html_e( 'General Settings', 'woo-conditional-discount-rules-for-checkout' );
?></a></li>
                            <li><a class="<?php 
echo esc_attr( $dpad_import_export );
?>" href="<?php 
echo esc_url( add_query_arg( array(
    'page' => 'wcdrfc-page-import-export',
), $admin_url ) );
?>"><?php 
esc_html_e( 'Import / Export', 'woo-conditional-discount-rules-for-checkout' );
?></a></li>
                        </ul>
                    </div>