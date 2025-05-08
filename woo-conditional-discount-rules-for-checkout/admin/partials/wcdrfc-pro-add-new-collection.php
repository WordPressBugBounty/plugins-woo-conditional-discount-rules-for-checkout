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
    $dpad_title = get_the_title( $paction_id );
    $getFeesCost = get_post_meta( $paction_id, 'dpad_settings_product_cost', true );
    $getFeesType = get_post_meta( $paction_id, 'dpad_settings_select_dpad_type', true );
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
    $getDiscountMsg = ( get_post_meta( $paction_id, 'dpad_discount_msg_text', true ) ? get_post_meta( $paction_id, 'dpad_discount_msg_text', true ) : '' );
    $getDiscountMsgBgColor = ( get_post_meta( $paction_id, 'dpad_discount_msg_bg_color', true ) ? get_post_meta( $paction_id, 'dpad_discount_msg_bg_color', true ) : '#ffcaca' );
    $getDiscountMsgTextColor = ( get_post_meta( $paction_id, 'dpad_discount_msg_text_color', true ) ? get_post_meta( $paction_id, 'dpad_discount_msg_text_color', true ) : '#000000' );
    $getSaleProduct = ( get_post_meta( $paction_id, 'dpad_sale_product', true ) ? get_post_meta( $paction_id, 'dpad_sale_product', true ) : '' );
    $getSelectedflg = ( get_post_meta( $paction_id, 'dpad_chk_discount_msg_selected_product', true ) ? get_post_meta( $paction_id, 'dpad_chk_discount_msg_selected_product', true ) : '' );
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
                
                </tbody>
            </table>
        </div>

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
                                                <option value="variableproduct" <?php 
        echo ( $dpad_conditions === 'variableproduct' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Variable Product', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="category" <?php 
        echo ( $dpad_conditions === 'category' ? 'selected' : '' );
        ?>><?php 
        esc_html_e( 'Category', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="tag_in_pro"><?php 
        esc_html_e( 'Tag 🔒', 'woo-conditional-discount-rules-for-checkout' );
        ?></option>
                                                <option value="brand_in_pro"><?php 
        esc_html_e( 'Brand 🔒', 'woo-conditional-discount-rules-for-checkout' );
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
        } elseif ( $dpad_conditions === 'variableproduct' ) {
            $html .= $admin_object->wdpad_get_varible_product_list( $i, $condtion_value, 'edit' );
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
                                            <option value="variableproduct"><?php 
    esc_html_e( 'Variable Product', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="category"><?php 
    esc_html_e( 'Category', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="tag_in_pro"><?php 
    esc_html_e( 'Tag 🔒', 'woo-conditional-discount-rules-for-checkout' );
    ?></option>
                                            <option value="brand_in_pro"><?php 
    esc_html_e( 'Brand 🔒', 'woo-conditional-discount-rules-for-checkout' );
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