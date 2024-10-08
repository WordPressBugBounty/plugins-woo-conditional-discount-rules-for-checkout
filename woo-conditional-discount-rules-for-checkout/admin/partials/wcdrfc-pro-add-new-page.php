<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
$admin_object = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin('', '');
$allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
$submitDiscount = filter_input( INPUT_POST, 'submitDiscount', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
if ( isset( $submitDiscount ) && !empty( $submitDiscount ) ) {
    $post_data = filter_input_array( INPUT_POST, array(
        'post_type'                              => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_post_id'                           => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_product_dpad_title'       => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_select_dpad_type'         => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_product_cost'             => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_chk_qty_price'                     => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_per_qty'                           => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'extra_product_cost'                     => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_start_date'               => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_end_date'                 => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_time_from'                         => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_time_to'                           => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_status'                   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'total_row'                              => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'submitDiscount'                         => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_chk_discount_msg'                  => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_chk_discount_msg_selected_product' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'first_order_for_user'                   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_buy_product'              => FILTER_SANITIZE_NUMBER_INT,
        'dpad_settings_get_product'              => FILTER_SANITIZE_NUMBER_INT,
        'dpad_settings_adjustment_type'          => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_settings_adjustment_cost'          => FILTER_SANITIZE_NUMBER_INT,
        'dpad_settings_get_category'             => FILTER_SANITIZE_NUMBER_INT,
        'user_login_status'                      => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_discount_msg_text'                 => FILTER_DEFAULT,
        'dpad_discount_msg_bg_color'             => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_discount_msg_text_color'           => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'dpad_sale_product'                      => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ) );
    $post_data['dpad'] = filter_input(
        INPUT_POST,
        'dpad',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        FILTER_REQUIRE_ARRAY
    );
    $post_data['dpad_selected_product_list'] = filter_input(
        INPUT_POST,
        'dpad_selected_product_list',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        FILTER_REQUIRE_ARRAY
    );
    $post_data['condition_key'] = filter_input(
        INPUT_POST,
        'condition_key',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        FILTER_REQUIRE_ARRAY
    );
    $post_data['dpad_select_day_of_week'] = filter_input(
        INPUT_POST,
        'dpad_select_day_of_week',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        FILTER_REQUIRE_ARRAY
    );
}
$paction = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$paction_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
if ( isset( $paction ) && $paction === 'edit' ) {
    $btnValue = __( 'Update', 'woo-conditional-discount-rules-for-checkout' );
    $dpad_title = __( get_the_title( $paction_id ), 'woo-conditional-discount-rules-for-checkout' );
    $getFeesCost = __( get_post_meta( $paction_id, 'dpad_settings_product_cost', true ), 'woo-conditional-discount-rules-for-checkout' );
    $getFeesType = __( get_post_meta( $paction_id, 'dpad_settings_select_dpad_type', true ), 'woo-conditional-discount-rules-for-checkout' );
    $getFeesStartDate = get_post_meta( $paction_id, 'dpad_settings_start_date', true );
    $getFeesEndDate = get_post_meta( $paction_id, 'dpad_settings_end_date', true );
    $dpad_time_from = get_post_meta( $paction_id, 'dpad_time_from', true );
    $dpad_time_to = get_post_meta( $paction_id, 'dpad_time_to', true );
    $getFeesStatus = get_post_meta( $paction_id, 'dpad_settings_status', true );
    $productFeesArray = get_post_meta( $paction_id, 'dynamic_pricing_metabox', true );
    $getMsgChecked = get_post_meta( $paction_id, 'dpad_chk_discount_msg', true );
    $getFeesPerQtyFlag = '';
    $getFeesPerQty = '';
    $extraProductCost = '';
    $getFirstOrderUser = '';
    $getBuyProduct = 0;
    $getGetProduct = 0;
    $getAdjustmentType = '';
    $getAdjustmentCost = 0;
    $getGetCategory = 0;
    $getUserLoginStatus = '';
    $getDiscountMsg = ( get_post_meta( $paction_id, 'dpad_discount_msg_text', true ) ? __( get_post_meta( $paction_id, 'dpad_discount_msg_text', true ), 'woo-conditional-discount-rules-for-checkout' ) : '' );
    $getDiscountMsgBgColor = ( get_post_meta( $paction_id, 'dpad_discount_msg_bg_color', true ) ? get_post_meta( $paction_id, 'dpad_discount_msg_bg_color', true ) : '#ffcaca' );
    $getDiscountMsgTextColor = ( get_post_meta( $paction_id, 'dpad_discount_msg_text_color', true ) ? get_post_meta( $paction_id, 'dpad_discount_msg_text_color', true ) : '#000000' );
    $getSaleProduct = ( get_post_meta( $paction_id, 'dpad_sale_product', true ) ? __( get_post_meta( $paction_id, 'dpad_sale_product', true ), 'woo-conditional-discount-rules-for-checkout' ) : '' );
    $getSelectedflg = ( get_post_meta( $paction_id, 'dpad_chk_discount_msg_selected_product', true ) ? __( get_post_meta( $paction_id, 'dpad_chk_discount_msg_selected_product', true ), 'woo-conditional-discount-rules-for-checkout' ) : '' );
    $getSelectedpd_lt = get_post_meta( $paction_id, 'dpad_selected_product_list', true );
    $get_select_dow = get_post_meta( $paction_id, 'dpad_select_day_of_week', true );
} else {
    $paction_id = '';
    $btnValue = __( 'Submit', 'woo-conditional-discount-rules-for-checkout' );
    $dpad_title = '';
    $getFeesCost = '';
    $getFeesPerQtyFlag = '';
    $getFeesPerQty = '';
    $extraProductCost = '';
    $getFeesType = '';
    $getFeesStartDate = '';
    $getFeesEndDate = '';
    $dpad_time_from = '';
    $dpad_time_to = '';
    $getFeesStatus = '';
    $getMsgChecked = '';
    $getFirstOrderUser = '';
    $getUserLoginStatus = '';
    $getDiscountMsg = '';
    $getDiscountMsgBgColor = '#ffcaca';
    $getDiscountMsgTextColor = '#000000';
    $getSelectedflg = '';
    $getSaleProduct = '';
    $getSelectedpd_lt = array();
    $get_select_dow = '';
    $productFeesArray = array();
}
if ( $getSelectedflg === 'on' ) {
    $selected_style_display = 'display:block;';
} else {
    $selected_style_display = 'display:none;';
}
?>
<div class="text-condtion-is" style="display:none;">
    <select class="text-condition">
        <option value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
        <option value="less_equal_to"><?php 
esc_html_e( 'Less or Equal to ( <= )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
        <option value="less_then"><?php 
esc_html_e( 'Less than ( < )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
        <option value="greater_equal_to"><?php 
esc_html_e( 'Greater or Equal to ( >= )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
        <option value="greater_then"><?php 
esc_html_e( 'Greater than ( > )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
        <option value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
    </select>
    <select class="select-condition">
        <option value="is_equal_to"><?php 
esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
        <option value="not_in"><?php 
esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
?></option>
    </select>
</div>
<div class="default-country-box" style="display:none;">
    <?php 
echo wp_kses( $admin_object->wdpad_get_country_list(), allowed_html_tags() );
?>
</div>
<div class="wdpad-main-table res-cl">
    <form method="POST" name="dpadfrm" action="">
        <!-- <input type="hidden" name="post_type" value="wc_dynamic_pricing"> -->
        <input type="hidden" name="dpad_post_id" value="<?php 
echo esc_attr( $paction_id );
?>">
        <div class="dpad-configuration element-shadow">
            <h2><?php 
esc_html_e( 'Discount Configuration', 'woo-conditional-discount-rules-for-checkout' );
?></h2>
            <table class="form-table table-outer product-fee-table wcdrfc-table-tooltip">
                <tbody>
                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="onoffswitch">
                            <?php 
esc_html_e( 'Status', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'This discount will be visible to customers only if it is enabled.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <label class="switch">
                            <input type="checkbox" name="dpad_settings_status" value="on" <?php 
echo ( isset( $getFeesStatus ) && $getFeesStatus === 'off' ? '' : 'checked' );
?>>
                            <div class="slider round"></div>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="dpad_settings_product_dpad_title">
                            <?php 
esc_html_e( 'Discount rule title', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <span class="required-star">*</span>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'This discount rule title is visible to the customer at the time of checkout.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <input type="text" name="dpad_settings_product_dpad_title" class="text-class" id="dpad_settings_product_dpad_title" value="<?php 
echo ( isset( $dpad_title ) ? esc_attr( $dpad_title ) : '' );
?>" required="1" placeholder="<?php 
esc_attr_e( 'Enter product discount title', 'woo-conditional-discount-rules-for-checkout' );
?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="dpad_settings_select_dpad_type">
                            <?php 
esc_html_e( 'Select discount type', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'You can give discount on fixed price, percentage, BOGO(Buy One Get One) and Adjustment type.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <div>
                            <select name="dpad_settings_select_dpad_type" id="dpad_settings_select_dpad_type" class="">
                                <option value="fixed" <?php 
echo ( isset( $getFeesType ) && $getFeesType === 'fixed' ? 'selected="selected"' : '' );
?>><?php 
esc_html_e( 'Fixed', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                                <option value="percentage" <?php 
echo ( isset( $getFeesType ) && $getFeesType === 'percentage' ? 'selected="selected"' : '' );
?>><?php 
esc_html_e( 'Percentage', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                                <?php 
?>
                            </select>
                        </div>
                        <a href="javascript:void(0);" class="dpad_chk_advanced_settings"><?php 
esc_html_e( 'Advance settings', 'woo-conditional-discount-rules-for-checkout' );
?></a>
                    </td>
                </tr>
                <tr valign="top" class="type-section fp-section">
                    <th class="titledesc" scope="row">
                        <label for="dpad_settings_product_cost">
                            <?php 
esc_html_e( 'Discount value', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <span class="required-star">*</span>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'If you select fixed discount type then : you have add fixed discount value. (Eg. 10, 20)', 'woo-conditional-discount-rules-for-checkout' ) . '<br/>' . esc_html__( 'If you select percentage based discount type then : you have add percentage of discount (Eg. 10, 15.20)', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <div class="product_cost_left_div">
                            <input type="text" id="dpad_settings_product_cost" name="dpad_settings_product_cost" class="text-class" id="dpad_settings_product_cost" value="<?php 
echo ( isset( $getFeesCost ) ? esc_attr( $getFeesCost ) : 0 );
?>" placeholder="<?php 
echo esc_attr( get_woocommerce_currency_symbol() );
?>">
                        </div>
                        <?php 
?>
                    </td>
                </tr>
                <?php 
?>
                <tr valign="top" class="dpad_advanced_setting_section">
                    <th class="titledesc" scope="row">
                        <label for="dpad_settings_start_date">
                            <?php 
esc_html_e( 'Start date', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'Select start date which date product discount rules will enable on website.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <input type="text" name="dpad_settings_start_date" class="text-class" id="dpad_settings_start_date" value="<?php 
echo ( isset( $getFeesStartDate ) ? esc_attr( $getFeesStartDate ) : '' );
?>" placeholder="<?php 
esc_attr_e( 'Select start date', 'woo-conditional-discount-rules-for-checkout' );
?>" autocomplete="off" />
                    </td>
                </tr>
                <tr valign="top" class="dpad_advanced_setting_section">
                    <th class="titledesc" scope="row">
                        <label for="dpad_settings_end_date"><?php 
esc_html_e( 'End date', 'woo-conditional-discount-rules-for-checkout' );
?>
                        <?php 
echo wp_kses( wc_help_tip( esc_html__( 'Select ending date which date product discount rules will disable on website', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                    </label>
                    </th>
                    <td class="forminp">
                        <input type="text" name="dpad_settings_end_date" class="text-class" id="dpad_settings_end_date" value="<?php 
echo ( isset( $getFeesEndDate ) ? esc_attr( $getFeesEndDate ) : '' );
?>" placeholder="<?php 
esc_attr_e( 'Select end date', 'woo-conditional-discount-rules-for-checkout' );
?>" autocomplete="off" />
                    </td>
                </tr>
                <tr valign="top" class="dpad_advanced_setting_section">
                    <th class="titledesc" scope="row">
                        <label for="sm_time">
                            <?php 
esc_html_e( 'Time', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
$html = sprintf(
    '%s<a href=%s target="_blank">%s</a>',
    esc_html__( 'Select time on which time product discount rules will enable on the website. This rule match with current time which is set by wordpress ', 'woo-conditional-discount-rules-for-checkout' ),
    esc_url( admin_url( 'options-general.php' ) ),
    esc_html__( 'Timezone', 'woo-conditional-discount-rules-for-checkout' )
);
echo wp_kses( wc_help_tip( esc_html( $html ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <input type="text" class="dpad_time_input" name="dpad_time_from" class="text-class" id="dpad_time_from" value="<?php 
echo esc_attr( $dpad_time_from );
?>" placeholder='<?php 
esc_attr_e( 'Select start time', 'woo-conditional-discount-rules-for-checkout' );
?>' autocomplete="off" />
                        <span><?php 
esc_html_e( '-', 'woo-conditional-discount-rules-for-checkout' );
?></span>
                        <input type="text" class="dpad_time_input" name="dpad_time_to" class="text-class" id="dpad_time_to" value="<?php 
echo esc_attr( $dpad_time_to );
?>" placeholder='<?php 
esc_attr_e( 'Select end time', 'woo-conditional-discount-rules-for-checkout' );
?>' autocomplete="off" />
                        <a href="javascript:void(0)" class="dpad_reset_time"><span class="dashicons dashicons-update"></span></a>
                    </td>
                </tr>
                <?php 
?>
                    <tr valign="top" class="dpad_advanced_setting_section">
                        <th class="titledesc" scope="row">
                            <label for="dpad_select_day_of_week">
                                <?php 
esc_html_e( 'Days of the Week', 'woo-conditional-discount-rules-for-checkout' );
?>
                                <span class="wdpad-pro-label"></span>
                                <?php 
$html = sprintf(
    '%s<a href=%s target="_blank">%s</a>',
    esc_html__( 'Select days on which day discount will enable on the website. This rule match with current day which is set by wordpress ', 'woo-conditional-discount-rules-for-checkout' ),
    esc_url( admin_url( 'options-general.php' ) ),
    esc_html__( 'Timezone', 'woo-conditional-discount-rules-for-checkout' )
);
echo wp_kses( wc_help_tip( esc_html( $html ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                            </label>
                        </th>
                        <td class="forminp">
                            <select name="dpad_select_day_of_week[]" id="dpad_select_day_of_week" class="dpad_select_day_of_week" disabled>
                                <option value="sun"><?php 
echo esc_html__( 'Sunday', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top" class="dpad_advanced_setting_section">
                        <th class="titledesc" scope="row">
                            <label for="first_order_for_user">
                                <?php 
esc_html_e( 'Enable for first order', 'woo-conditional-discount-rules-for-checkout' );
?>
                                <span class="wdpad-pro-label"></span>
                                <?php 
echo wp_kses( wc_help_tip( esc_html__( 'Only apply when user will place first order.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                            </label>
                        </th>
                        <td class="forminp">
                            <input type="checkbox" name="first_order_for_user" id="first_order_for_user" value="on" disabled>
                        </td>
                    </tr>
                    <tr valign="top" class="dpad_advanced_setting_section">
                        <th class="titledesc" scope="row">
                            <label for="user_login_status">
                                <?php 
esc_html_e( 'Enable for logged in user', 'woo-conditional-discount-rules-for-checkout' );
?>
                                <span class="wdpad-pro-label"></span>
                                <?php 
echo wp_kses( wc_help_tip( esc_html__( 'Only apply when user is login into their account.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                            </label>
                        </th>
                        <td class="forminp">
                            <input type="checkbox" name="user_login_status" id="user_login_status" value="on" disabled>
                        </td>
                    </tr>
                	<?php 
?>
                <tr valign="top" class="dpad_advanced_setting_section">
                    <th class="titledesc" scope="row">
                        <label for="dpad_sale_product">
                            <?php 
esc_html_e( 'Sale products', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'You can include/exclude sale product from discount rules', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <select name="dpad_sale_product" id="dpad_sale_product" class="">
                            <option value="include" <?php 
selected( $getSaleProduct, 'include' );
?>><?php 
esc_html_e( 'Include', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                            <option value="exclude" <?php 
selected( $getSaleProduct, 'exclude' );
?>><?php 
esc_html_e( 'Exclude', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="dpad_chk_discount_msg">
                            <?php 
esc_html_e( 'Enable discount message', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'Display discount message on product details page above add to cart button', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <input type="checkbox" name="dpad_chk_discount_msg" id="dpad_chk_discount_msg" class="chk_qty_price_class" value="on" <?php 
checked( $getMsgChecked, 'on' );
?>>
                    </td>
                </tr>
                <tr valign="top" class="display_discount_message_text">
                    <th class="titledesc" scope="row">
                        <label for="dpad_discount_msg_text">
                            <?php 
esc_html_e( 'Discount message', 'woo-conditional-discount-rules-for-checkout' );
?>
                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'This discount message will visible to the customer at the product details page below addd to cart button.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                        </label>
                    </th>
                    <td class="forminp">
                        <div>
                            <?php 
$settings = array(
    'editor_height' => 100,
    'textarea_rows' => 3,
);
wp_editor( ( isset( $getDiscountMsg ) ? $getDiscountMsg : '' ), 'dpad_discount_msg_text', $settings );
?>
                        </div>
                        <div class="wdpad_color_picker_wrap">
                    		<div class="wdpad_color_picker">
	                            <div class="wdpad_background_color"><?php 
esc_html_e( 'Background Color', 'woo-conditional-discount-rules-for-checkout' );
?></div>
	                            <input type="text" name="dpad_discount_msg_bg_color" id="dpad_discount_msg_bg_color" value="<?php 
echo esc_attr( $getDiscountMsgBgColor );
?>" />
	                        </div>
	                        <div class="wdpad_color_picker">
	                            <div class="wdpad_text_color"><?php 
esc_html_e( 'Text Color', 'woo-conditional-discount-rules-for-checkout' );
?></div>
	                            <input type="text" name="dpad_discount_msg_text_color" id="dpad_discount_msg_text_color" value="<?php 
echo esc_attr( $getDiscountMsgTextColor );
?>" />
	                        </div>
                        </div>
                        <div class="wdpad-selected-product-main">
                            <input type="checkbox" name="dpad_chk_discount_msg_selected_product" id="dpad_chk_discount_msg_selected_product" class="chk_qty_price_class" value="on" <?php 
checked( $getSelectedflg, 'on' );
?>>
                            <label for="dpad_chk_discount_msg_selected_product"><?php 
esc_html_e( 'Only for specific products', 'woo-conditional-discount-rules-for-checkout' );
?></label>
                            <div class="wdpad-selected-product-list" style="<?php 
echo esc_attr( $selected_style_display );
?>">
                            <?php 
echo wp_kses( $admin_object->wdpad_get_selected_product_list( 'ofsp', $getSelectedpd_lt, 'edit' ), allowed_html_tags() );
?>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php 
?>
        <div class="conditional_rule-section element-shadow">
            <div class="sub-title section-title">
                <h2><?php 
esc_html_e( 'Discount Rules for checkout', 'woo-conditional-discount-rules-for-checkout' );
?></h2>
                <div class="tap">
                    <a id="fee-add-field" class="button" href="javascript:void(0);"><?php 
esc_html_e( '+ Add Rule', 'woo-conditional-discount-rules-for-checkout' );
?></a>
                </div>
                <?php 
?>
            </div>
            <div class="tap">
                <table id="tbl-product-fee" class="tbl_product_fee table-outer tap-cas form-table product-fee-table">
                    <tbody>
                    
                    <?php 
if ( isset( $productFeesArray ) && !empty( $productFeesArray ) ) {
    $i = 2;
    foreach ( $productFeesArray as $productdpad ) {
        $dpad_conditions = ( isset( $productdpad['product_dpad_conditions_condition'] ) ? $productdpad['product_dpad_conditions_condition'] : '' );
        $condition_is = ( isset( $productdpad['product_dpad_conditions_is'] ) ? $productdpad['product_dpad_conditions_is'] : '' );
        $condtion_value = ( isset( $productdpad['product_dpad_conditions_values'] ) ? $productdpad['product_dpad_conditions_values'] : '' );
        ?>
                                <tr id="row_<?php 
        echo esc_attr( $i );
        ?>" valign="top">
                                    <td class="titledesc th_product_dpad_conditions_condition" scope="row">
                                        <select rel-id="<?php 
        echo esc_attr( $i );
        ?>" id="product_dpad_conditions_condition_<?php 
        echo esc_attr( $i );
        ?>" name="dpad[product_dpad_conditions_condition][]"
                                                id="product_dpad_conditions_condition" class="product_dpad_conditions_condition">
                                            <optgroup label="<?php 
        esc_attr_e( 'Location Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <option value="country" <?php 
        echo ( $dpad_conditions === 'country' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Country', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="city_in_pro"><?php 
        esc_html_e( 'City 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="state_in_pro"><?php 
        esc_html_e( 'State 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="postcode_in_pro"><?php 
        esc_html_e( 'Postcode 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="zone_in_pro"><?php 
        esc_html_e( 'Zone 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                            <optgroup label="<?php 
        esc_attr_e( 'Product Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <option value="product" <?php 
        echo ( $dpad_conditions === 'product' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Product', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="variableproduct_in_pro"><?php 
        esc_html_e( 'Variable Product 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="category" <?php 
        echo ( $dpad_conditions === 'category' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Category', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="tag_in_pro"><?php 
        esc_html_e( 'Tag 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="product_qty_in_pro"><?php 
        esc_html_e( 'Product\'s quantity 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="product_count" <?php 
        selected( $dpad_conditions, 'product_count' );
        ?>><?php 
        esc_html_e( 'Product\'s count', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                            <optgroup label="<?php 
        esc_attr_e( 'User Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <option value="user" <?php 
        echo ( $dpad_conditions === 'user' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'User', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="user_role_in_pro"><?php 
        esc_html_e( 'User Role 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="user_mail_in_pro"><?php 
        esc_html_e( 'User Email 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                            <optgroup label="<?php 
        esc_attr_e( 'Purchase History', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <option value="last_spent_order_in_pro"><?php 
        esc_html_e( 'Last order spent 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="total_spent_order_in_pro"><?php 
        esc_html_e( 'Total order spent (all time) 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="spent_order_count_in_pro"><?php 
        esc_html_e( 'Number of orders (all time) 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="user_repeat_product_in_pro"><?php 
        esc_html_e( 'User repeat product 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                            <optgroup label="<?php 
        esc_attr_e( 'Cart Specific ', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <?php 
        $weight_unit = get_option( 'woocommerce_weight_unit' );
        $weight_unit = ( !empty( $weight_unit ) ? '(' . $weight_unit . ')' : '' );
        ?>
                                                <option value="cart_total" <?php 
        echo ( $dpad_conditions === 'cart_total' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Cart Subtotal', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="cart_totalafter_in_pro"><?php 
        esc_html_e( 'Cart Subtotal (After Discount) 🔒 ', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="quantity" <?php 
        echo ( $dpad_conditions === 'quantity' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Quantity', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="weight_in_pro"><?php 
        esc_html_e( 'Weight 🔒 ', 'woo-conditional-discount-rules-for-checkout' );
        echo esc_html( $weight_unit );
        ?></option>
                                                <option value="coupon_in_pro"><?php 
        esc_html_e( 'Coupon 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="shipping_class_in_pro"><?php 
        esc_html_e( 'Shipping Class 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                            <optgroup label="<?php 
        esc_attr_e( 'Payment Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <option value="payment_in_pro"><?php 
        esc_html_e( 'Payment Gateway 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                            <optgroup label="<?php 
        esc_attr_e( 'Shipping Specific', 'woo-conditional-discount-rules-for-checkout' );
        ?>">
                                                <option value="shipping_method_in_pro"><?php 
        esc_html_e( 'Shipping Method 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="shipping_total_in_pro"><?php 
        esc_html_e( 'Shipping Total 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                            </optgroup>
                                        </select>
                                    </td>
                                    <td class="select_condition_for_in_notin">
                                        <?php 
        if ( 'cart_total' === $dpad_conditions || 'cart_totalafter' === $dpad_conditions || 'quantity' === $dpad_conditions || 'weight' === $dpad_conditions || 'product_count' === $dpad_conditions ) {
            ?>
                                            <select name="dpad[product_dpad_conditions_is][]" class="product_dpad_conditions_is_<?php 
            echo esc_attr( $i );
            ?>">
                                                <option value="is_equal_to" <?php 
            echo ( 'is_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                                <option value="less_equal_to" <?php 
            echo ( 'less_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Less or Equal to ( <= )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                                <option value="less_then" <?php 
            echo ( 'less_then' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Less than ( < )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                                <option value="greater_equal_to" <?php 
            echo ( 'greater_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Greater or Equal to ( >= )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                                <option value="greater_then" <?php 
            echo ( 'greater_then' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Greater than ( > )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                                <option value="not_in" <?php 
            echo ( 'not_in' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                            </select>
                                        <?php 
        } else {
            ?>
                                            <select name="dpad[product_dpad_conditions_is][]" class="product_dpad_conditions_is_<?php 
            echo esc_attr( $i );
            ?>">
                                                <option value="is_equal_to" <?php 
            echo ( 'is_equal_to' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
            ?></option>
                                                <option value="not_in" <?php 
            echo ( 'not_in' === $condition_is ? 'selected' : '' );
            ?>><?php 
            esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
            ?> </option>
                                            </select>
                                        <?php 
        }
        ?>
                                    </td>
                                    <td class="condition-value" id="column_<?php 
        echo esc_attr( $i );
        ?>" <?php 
        if ( $i <= 2 ) {
            echo 'colspan="2"';
        }
        ?>>
                                        <?php 
        $html = '';
        if ( 'country' === $dpad_conditions ) {
            $html .= $admin_object->wdpad_get_country_list( $i, $condtion_value );
        } elseif ( 'product' === $dpad_conditions ) {
            $html .= $admin_object->wdpad_get_product_list( $i, $condtion_value, 'edit' );
        } elseif ( 'category' === $dpad_conditions ) {
            $html .= $admin_object->wdpad_get_category_list( $i, $condtion_value );
        } elseif ( 'user' === $dpad_conditions ) {
            $html .= $admin_object->wdpad_get_user_list( $i, $condtion_value );
        } elseif ( 'cart_total' === $dpad_conditions ) {
            $html .= '<input type = "text" name = "dpad[product_dpad_conditions_values][value_' . $i . ']" id = "product_dpad_conditions_values" class = "product_dpad_conditions_values price-class" value = "' . $condtion_value . '">';
        } elseif ( 'quantity' === $dpad_conditions ) {
            $html .= '<input type = "text" name = "dpad[product_dpad_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_dpad_conditions_values" class = "product_dpad_conditions_values qty-class" value = "' . $condtion_value . '">';
        } elseif ( $dpad_conditions === 'product_count' ) {
            $html .= '<input type = "text" name = "dpad[product_dpad_conditions_values][value_' . $i . ']" id = "product_dpad_conditions_values" class = "product_dpad_conditions_values qty-class" value = "' . $condtion_value . '">';
            $html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s</p>', esc_html__( 'Note: ', 'woo-conditional-discount-rules-for-checkout' ), esc_html__( 'This rule will work if you have selected any one Product Specific option or it will apply to all products.', 'woo-conditional-discount-rules-for-checkout' ) ) );
        }
        echo wp_kses( $html, allowed_html_tags() );
        ?>
                                        <input type="hidden" name="condition_key[<?php 
        echo 'value_' . esc_attr( $i );
        ?>]" value="">
                                    </td>
                                    <?php 
        if ( $i > 2 ) {
            ?>
                                    <td>
                                        <a id="fee-delete-field" rel-id="<?php 
            echo esc_attr( $i );
            ?>" class="delete-row" href="javascript:;" title="Delete">
                                            <i class="dashicons dashicons-trash"></i>
                                        </a>
                                    </td>
                                    <?php 
        }
        ?>
                                </tr>
                            <?php 
        $i++;
    }
    ?>
                        <?php 
} else {
    $i = 1;
    ?>
                            <tr id="row_1" valign="top">
                                <td class="titledesc th_product_dpad_conditions_condition" scope="row">
                                    <select rel-id="1" id="product_dpad_conditions_condition_1" name="dpad[product_dpad_conditions_condition][]"
                                            id="product_dpad_conditions_condition"
                                            class="product_dpad_conditions_condition">
                                        <optgroup label="<?php 
    esc_attr_e( 'Location Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <option value="country"><?php 
    esc_html_e( 'Country', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="city_in_pro"><?php 
    esc_html_e( 'City 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="state_in_pro"><?php 
    esc_html_e( 'State 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="postcode_in_pro"><?php 
    esc_html_e( 'Postcode 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="zone_in_pro"><?php 
    esc_html_e( 'Zone 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                        <optgroup label="<?php 
    esc_attr_e( 'Product Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <option value="product"><?php 
    esc_html_e( 'Product', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="variableproduct_in_pro"><?php 
    esc_html_e( 'Variable Product 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="category"><?php 
    esc_html_e( 'Category', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="tag_in_pro"><?php 
    esc_html_e( 'Tag 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="product_qty_in_pro"><?php 
    esc_html_e( 'Product\'s quantity 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="product_count"><?php 
    esc_html_e( 'Product\'s count', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                        <optgroup label="<?php 
    esc_attr_e( 'User Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <option value="user"><?php 
    esc_html_e( 'User', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="user_role_in_pro"><?php 
    esc_html_e( 'User Role 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="user_mail_in_pro"><?php 
    esc_html_e( 'User Email 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                        <optgroup label="<?php 
    esc_attr_e( 'Purchase History', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <option value="last_spent_order_in_pro"><?php 
    esc_html_e( 'Last order spent 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="total_spent_order_in_pro"><?php 
    esc_html_e( 'Total order spent (all time) 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="spent_order_count_in_pro"><?php 
    esc_html_e( 'Number of orders (all time) 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="user_repeat_product_in_pro"><?php 
    esc_html_e( 'User repeat product 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                        <optgroup label="<?php 
    esc_attr_e( 'Cart Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <?php 
    $weight_unit = get_option( 'woocommerce_weight_unit' );
    $weight_unit = ( !empty( $weight_unit ) ? '(' . $weight_unit . ')' : '' );
    ?>
                                            <option value="cart_total"><?php 
    esc_html_e( 'Cart Subtotal ', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="cart_totalafter_in_pro"><?php 
    esc_html_e( 'Cart Subtotal (After Discount) 🔒 ', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="quantity"><?php 
    esc_html_e( 'Quantity', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="weight_in_pro"><?php 
    esc_html_e( 'Weight 🔒 ', 'woo-conditional-discount-rules-for-checkout' );
    echo esc_html( $weight_unit );
    ?></option>
                                            <option value="coupon_in_pro"><?php 
    esc_html_e( 'Coupon 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="shipping_class_in_pro"><?php 
    esc_html_e( 'Shipping Class 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                        <optgroup label="<?php 
    esc_attr_e( 'Payment Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <option value="payment_in_pro"><?php 
    esc_html_e( 'Payment Gateway 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                        <optgroup label="<?php 
    esc_attr_e( 'Shipping Specific', 'woo-conditional-discount-rules-for-checkout' );
    ?>">
                                            <option value="shipping_method_in_pro"><?php 
    esc_html_e( 'Shipping Method 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="shipping_total_in_pro"><?php 
    esc_html_e( 'Shipping Total 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        </optgroup>
                                    </select>
                                </td>
                                <td class="select_condition_for_in_notin">
                                    <select name="dpad[product_dpad_conditions_is][]" class="product_dpad_conditions_is product_dpad_conditions_is_1">
                                        <option value="is_equal_to"><?php 
    esc_html_e( 'Equal to ( = )', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                        <option value="not_in"><?php 
    esc_html_e( 'Not Equal to ( != )', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                    </select>
                                </td>
                                <td id="column_1" class="condition-value" colspan="2">
                                    <?php 
    echo wp_kses( $admin_object->wdpad_get_country_list( 1 ), allowed_html_tags() );
    ?>
                                    <input type="hidden" name="condition_key[value_1][]" value="">
                                </td>
                            </tr>
                        <?php 
}
?>
                    </tbody>
                </table>
                <input type="hidden" name="total_row" id="total_row" value="<?php 
echo esc_attr( $i );
?>">
            </div>
        </div>
        <?php 
// Advanced Pricing Section start
?>
            <div id="apm_wrap" class="fees-pricing-rules type-section fp-section element-shadow">
                <div class="ap_title section-title">
                    <h2><?php 
esc_html_e( 'Advanced Discount Price Rules', 'woo-conditional-discount-rules-for-checkout' );
?><span class="wdpad-pro-label"></span></h2>
                    <label class="switch">
                        <input type="checkbox" name="ap_rule_status" value="on">
                        <div class="slider round"></div>
                    </label>
                    <?php 
echo wp_kses( wc_help_tip( esc_html__( 'If enabled this Advanced Pricing button only than below all rule\'s will go for apply to discount rule', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                </div>
                <div class="fees_pricing_rules wdpad-upgrade-pro-to-unlock">
                    <div class="fees_pricing_rules_tab">
                        <ul class="tabs">
                            <?php 
$tab_array = array(
    'tab-1'  => esc_html__( 'Cost on Product', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-2'  => esc_html__( 'Cost on Product Subtotal', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-3'  => esc_html__( 'Cost on Product Weight', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-4'  => esc_html__( 'Cost on Category', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-5'  => esc_html__( 'Cost on Category Subtotal', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-6'  => esc_html__( 'Cost on Category Weight', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-7'  => esc_html__( 'Cost on Total Cart Qty', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-8'  => esc_html__( 'Cost on Total Cart Weight', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-9'  => esc_html__( 'Cost on Total Cart Subtotal', 'woo-conditional-discount-rules-for-checkout' ),
    'tab-10' => esc_html__( 'Cost on Shipping Class Subtotal', 'woo-conditional-discount-rules-for-checkout' ),
);
if ( !empty( $tab_array ) ) {
    foreach ( $tab_array as $data_tab => $tab_title ) {
        if ( 'tab-1' === $data_tab ) {
            $class = ' current';
        } else {
            $class = '';
        }
        ?>
                                    <li class="tab-link<?php 
        echo esc_attr( $class );
        ?>"
                                        data-tab="<?php 
        echo esc_attr( $data_tab );
        ?>">
                                        <?php 
        echo esc_html( $tab_title );
        ?>
                                    </li>
                                    <?php 
    }
}
?>
                        </ul>
                    </div>
                    <div class="fees_pricing_rules_tab_content">
                        <?php 
// Advanced Pricing Product Section start here
?>
                        <div class="ap_product_container advance_pricing_rule_box tab-content current" id="tab-1" data-title="<?php 
esc_attr_e( 'Cost on Product', 'woo-conditional-discount-rules-for-checkout' );
?>">
                            <div class="tap-class">
                                <div class="predefined_elements">
                                    <div id="all_product_list"></div>
                                </div>
                                <div class="sub-title">
                                    <h2 class="ap-title"><?php 
esc_html_e( 'Cost on Product', 'woo-conditional-discount-rules-for-checkout' );
?></h2>
                                    <div class="tap">
                                        <a id="ap-product-add-field" class="button" href="javascript:;"><?php 
esc_html_e( '+ Add Rule', 'woo-conditional-discount-rules-for-checkout' );
?></a>
                                        <div class="switch_status_div">
                                            <label class="switch switch_in_pricing_rules">
                                                <input type="checkbox" name="cost_on_product_status" value="on">
                                                <div class="slider round"></div>
                                            </label>
                                            <?php 
echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this discount amount.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                                        </div>
                                    </div>
                                    <div class="dpad_ap_match_type">
                                        <p class="switch_in_pricing_rules_description_left">
                                            <?php 
esc_html_e( 'below', 'woo-conditional-discount-rules-for-checkout' );
?>
                                        </p>
                                        <select name="cost_rule_match[cost_on_product_rule_match]" id="cost_on_product_rule_match" class="arcmt_select">
                                            <option value="any"><?php 
esc_html_e( 'Any One', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                                            <option value="all"><?php 
esc_html_e( 'All', 'woo-conditional-discount-rules-for-checkout' );
?></option>
                                        </select>
                                        <p class="switch_in_pricing_rules_description">
                                            <?php 
esc_html_e( 'rule match', 'woo-conditional-discount-rules-for-checkout' );
?>
                                        </p>
                                    </div>
                                </div>
                                <table id="tbl_ap_product_method" class="tbl_product_fee table-outer tap-cas form-table discounts-table">
                                    <tbody>
                                        <tr class="heading">
                                            <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                <span><?php 
esc_html_e( 'Product', 'woo-conditional-discount-rules-for-checkout' );
?></span>
                                                <?php 
echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the discount amount to when the min/max quantity match.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                                            </th>
                                            <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                <span><?php 
esc_html_e( 'Min Quantity ', 'woo-conditional-discount-rules-for-checkout' );
?></span>
                                                <?php 
echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product quantity per row before the discount amount is applied.<br/>Leave empty to not set a minimum.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                                            </th>
                                            <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                <span><?php 
esc_html_e( 'Max Quantity ', 'woo-conditional-discount-rules-for-checkout' );
?></span>
                                                <?php 
echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product quantity per row before the discount amount is applied.<br/>Leave empty to not set a maximum.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                                            </th>
                                            <th class="titledesc th_product_fees_conditions_condition" scope="row" colspan="2">
                                                <span><?php 
esc_html_e( 'Discount Amount', 'woo-conditional-discount-rules-for-checkout' );
?></span>
                                                <?php 
echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a discount. Percentage and minus amount will apply based on cart subtotal.', 'woo-conditional-discount-rules-for-checkout' ) ), array(
    'span' => $allowed_tooltip_html,
) );
?>
                                            </th>
                                        </tr>
                                        <tr id="ap_product_row_1" valign="top" class="ap_product_row_tr">
                                            <td class="titledesc" scope="row">
                                                <select rel-id="1"
                                                        id="ap_product_fees_conditions_condition_1"
                                                        name="dpad[ap_product_fees_conditions_condition][1][]"
                                                        class="wdpad_select ap_product product_dpad_conditions_values multiselect2"
                                                        multiple="multiple">
                                                </select>
                                            </td>
                                            <td class="column_1 condition-value">
                                                <input type="number" name="dpad[ap_fees_ap_prd_min_qty][]" class="text-class qty-class" id="ap_fees_ap_prd_min_qty[]" placeholder="<?php 
esc_attr_e( 'Min Quantity', 'woo-conditional-discount-rules-for-checkout' );
?>"  value="" min="1">
                                            </td>
                                            <td class="column_1 condition-value">
                                                <input type="number" name="dpad[ap_fees_ap_prd_max_qty][]" class="text-class qty-class" id="ap_fees_ap_prd_max_qty[]" placeholder="<?php 
esc_attr_e( 'Max Quantity', 'woo-conditional-discount-rules-for-checkout' );
?>" value="" min="1">
                                            </td>
                                            <td class="column_1 condition-value">
                                                <input type="text" name="dpad[ap_fees_ap_price_product][]" class="text-class number-field" id="ap_fees_ap_price_product[]" placeholder="<?php 
esc_attr_e( 'Amount', 'woo-conditional-discount-rules-for-checkout' );
?>" value="">
                                            </td>
                                            <td class="column_1 condition-value">
                                                <a id="ap_product_delete_field"
                                                    rel-id="1"
                                                    title="Delete" class="delete-row" href="javascript:;">
                                                    <i class="dashicons dashicons-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php 
// Advanced Pricing Product Section end here
?>
                    </div>
                </div>
            </div>
            <?php 
// Advanced Pricing Section end
?>
        <p class="submit">
            <input type="submit" name="submitDiscount" class="submitDiscount button button-primary" value="<?php 
echo esc_attr( $btnValue );
?>">
        </p>
        <?php 
wp_nonce_field( 'dpad_save_method', 'dpad_save_method_nonce' );
?>
    </form>
</div>
<?php 
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-footer.php';