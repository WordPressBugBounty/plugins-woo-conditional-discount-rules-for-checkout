<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * DPAD_Rule_Listing_Page class.
 */
if ( !class_exists( 'DPAD_Rule_Listing_Page' ) ) {
    class DPAD_Rule_Listing_Page {
        /**
         * Output the Admin UI
         *
         * @since 3.5
         */
        const post_type = 'wc_dynamic_pricing';

        private static $admin_object = null;

        /**
         * Display output
         *
         * @since 3.5
         *
         * @uses Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin
         * @uses dpad_sj_save_method
         * @uses dpad_sj_add_shipping_method_form
         * @uses dpad_sj_delete_method
         * @uses dpad_sj_duplicate_method
         * @uses dpad_sj_list_methods_screen
         * @uses Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin::dpad_updated_message()
         *
         * @access   public
         */
        public static function dpad_sj_output() {
            self::$admin_object = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin('', '');
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $post_id_request = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_whsm_add = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $message ) && !empty( $message ) ) {
                self::$admin_object->dpad_updated_message( $message, "" );
            }
            if ( isset( $action ) && !empty( $action ) ) {
                if ( 'add' === $action ) {
                    self::dpad_sj_save_method();
                    self::dpad_sj_add_shipping_method_form();
                } elseif ( 'edit' === $action ) {
                    if ( isset( $cust_nonce ) && !empty( $cust_nonce ) ) {
                        $getnonce = wp_verify_nonce( $cust_nonce, 'edit_' . $post_id_request );
                        if ( isset( $getnonce ) && 1 === $getnonce ) {
                            self::dpad_sj_save_method( $post_id_request );
                            self::dpad_sj_edit_method();
                        } else {
                            wp_safe_redirect( add_query_arg( array(
                                'page' => 'wcdrfc-rules-list',
                            ), admin_url( 'admin.php' ) ) );
                            exit;
                        }
                    } elseif ( isset( $get_whsm_add ) && !empty( $get_whsm_add ) ) {
                        if ( !wp_verify_nonce( $get_whsm_add, 'whsm_add' ) ) {
                            $message = 'nonce_check';
                        } else {
                            self::dpad_sj_save_method( $post_id_request );
                            self::dpad_sj_edit_method();
                        }
                    }
                } elseif ( 'delete' === $action ) {
                    self::dpad_sj_delete_method( $post_id_request );
                } elseif ( 'duplicate' === $action ) {
                    self::dpad_sj_duplicate_method( $post_id_request );
                } else {
                    self::dpad_sj_list_methods_screen();
                }
            } else {
                self::dpad_sj_list_methods_screen();
            }
        }

        /**
         * Delete shipping method
         *
         * @param int $id
         *
         * @access   public
         * @uses Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin::dpad_updated_message()
         *
         * @since    3.5
         *
         */
        public static function dpad_sj_delete_method( $id ) {
            global $plugin_public;
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $getnonce = wp_verify_nonce( $cust_nonce, 'del_' . $id );
            if ( isset( $getnonce ) && 1 === $getnonce ) {
                $post_deleted = wp_delete_post( $id );
                //Refresh our cache
                if ( $post_deleted ) {
                    $plugin_public->wdpad_action_on_discount_list( true );
                } else {
                    self::$admin_object->dpad_updated_message( 'validated', esc_html__( "There was an error with deleting data.", 'woo-conditional-discount-rules-for-checkout' ) );
                }
                //Redirect after deletion completed
                wp_safe_redirect( add_query_arg( array(
                    'page'    => 'wcdrfc-rules-list',
                    'message' => 'deleted',
                ), admin_url( 'admin.php' ) ) );
                exit;
            } else {
                self::$admin_object->dpad_updated_message( 'nonce_check', "" );
                exit;
            }
        }

        /**
         * Duplicate shipping method
         *
         * @param int $id
         *
         * @access   public
         * @uses Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin::dpad_updated_message()
         *
         * @since    1.0.0
         *
         */
        public static function dpad_sj_duplicate_method( $id ) {
            global $plugin_public;
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $getnonce = wp_verify_nonce( $cust_nonce, 'duplicate_' . $id );
            $whsm_add = wp_create_nonce( 'whsm_add' );
            $post_id = ( isset( $id ) ? absint( $id ) : '' );
            $new_post_id = '';
            if ( isset( $getnonce ) && 1 === $getnonce ) {
                if ( !empty( $post_id ) || "" !== $post_id ) {
                    $post = get_post( $post_id );
                    $current_user = wp_get_current_user();
                    $new_post_author = $current_user->ID;
                    if ( isset( $post ) && null !== $post ) {
                        $args = array(
                            'comment_status' => $post->comment_status,
                            'ping_status'    => $post->ping_status,
                            'post_author'    => $new_post_author,
                            'post_content'   => $post->post_content,
                            'post_excerpt'   => $post->post_excerpt,
                            'post_name'      => $post->post_name,
                            'post_parent'    => $post->post_parent,
                            'post_password'  => $post->post_password,
                            'post_status'    => 'publish',
                            'post_title'     => $post->post_title . '-duplicate',
                            'post_type'      => self::post_type,
                            'to_ping'        => $post->to_ping,
                            'menu_order'     => $post->menu_order,
                        );
                        $new_post_id = wp_insert_post( $args );
                        if ( $new_post_id ) {
                            //Copy original post meta to dumplicate post meta
                            $post_meta_data = get_post_meta( $post_id );
                            if ( 0 !== count( $post_meta_data ) ) {
                                foreach ( $post_meta_data as $meta_key => $meta_data ) {
                                    if ( '_wp_old_slug' === $meta_key ) {
                                        continue;
                                    }
                                    $meta_value = maybe_unserialize( $meta_data[0] );
                                    update_post_meta( $new_post_id, $meta_key, $meta_value );
                                }
                            }
                            //Refresh our cache after dumplcate discount offer.
                            $plugin_public->wdpad_action_on_discount_list( true );
                        } else {
                            wp_safe_redirect( add_query_arg( array(
                                'page'    => 'wcdrfc-rules-list',
                                'message' => 'failed',
                            ), admin_url( 'admin.php' ) ) );
                            exit;
                        }
                    }
                    wp_safe_redirect( add_query_arg( array(
                        'page'     => 'wcdrfc-rules-list',
                        'action'   => 'edit',
                        'post'     => $new_post_id,
                        '_wpnonce' => esc_attr( $whsm_add ),
                        'message'  => 'duplicated',
                    ), admin_url( 'admin.php' ) ) );
                    exit;
                } else {
                    wp_safe_redirect( add_query_arg( array(
                        'page'    => 'wcdrfc-rules-list',
                        'message' => 'failed',
                    ), admin_url( 'admin.php' ) ) );
                    exit;
                }
            } else {
                self::$admin_object->dpad_updated_message( 'nonce_check', "" );
                exit;
            }
        }

        /**
         * Count total shipping method
         *
         * @return int $count_method
         * @since    3.5
         *
         */
        public static function dpad_sm_count_method() {
            $shipping_method_args = array(
                'post_type'      => self::post_type,
                'post_status'    => array('publish', 'draft'),
                'posts_per_page' => -1,
                'orderby'        => 'ID',
                'order'          => 'DESC',
            );
            $sm_post_query = new WP_Query($shipping_method_args);
            $shipping_method_list = $sm_post_query->posts;
            return count( $shipping_method_list );
        }

        /**
         * Save shipping method when add or edit
         *
         * @param int $method_id
         *
         * @return bool false when nonce is not verified, $zone id, $zone_type is blank, Country also blank, Postcode field also blank, saving error when form submit
         * @uses dpad_sm_count_method()
         *
         * @since    3.5
         *
         * @uses Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin::dpad_updated_message()
         */
        private static function dpad_sj_save_method( $method_id = 0 ) {
            global $plugin_public;
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $submitDiscount = filter_input( INPUT_POST, 'submitDiscount', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $dpad_save_method_nonce = filter_input( INPUT_POST, 'dpad_save_method_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $action ) && !empty( $action ) ) {
                if ( isset( $submitDiscount ) ) {
                    if ( empty( $dpad_save_method_nonce ) || !wp_verify_nonce( sanitize_text_field( $dpad_save_method_nonce ), 'dpad_save_method' ) ) {
                        self::$admin_object->dpad_updated_message( 'nonce_check', '' );
                        exit;
                    }
                    $dpad_settings_product_dpad_title = filter_input( INPUT_POST, 'dpad_settings_product_dpad_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $shipping_method_count = self::dpad_sm_count_method();
                    settype( $method_id, 'integer' );
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        $fee_post = array(
                            'ID'          => $method_id,
                            'post_title'  => sanitize_text_field( $dpad_settings_product_dpad_title ),
                            'post_status' => 'publish',
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_update_post( $fee_post );
                    } else {
                        $fee_post = array(
                            'post_title'  => sanitize_text_field( $dpad_settings_product_dpad_title ),
                            'post_status' => 'publish',
                            'menu_order'  => $shipping_method_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_insert_post( $fee_post );
                    }
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        if ( $method_id > 0 ) {
                            //New
                            $get_dpad_settings_status = filter_input( INPUT_POST, 'dpad_settings_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_settings_select_dpad_type = filter_input( INPUT_POST, 'dpad_settings_select_dpad_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_settings_product_cost = filter_input(
                                INPUT_POST,
                                'dpad_settings_product_cost',
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                            );
                            $get_dpad_chk_qty_price = filter_input( INPUT_POST, 'dpad_chk_qty_price', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_per_qty = filter_input( INPUT_POST, 'dpad_per_qty', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_extra_product_cost = filter_input(
                                INPUT_POST,
                                'extra_product_cost',
                                FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION
                            );
                            $get_dpad_settings_start_date = filter_input( INPUT_POST, 'dpad_settings_start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_settings_end_date = filter_input( INPUT_POST, 'dpad_settings_end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_time_from = filter_input( INPUT_POST, 'dpad_time_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_time_to = filter_input( INPUT_POST, 'dpad_time_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_chk_discount_msg = filter_input( INPUT_POST, 'dpad_chk_discount_msg', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            // phpcs:disable
                            $get_dpad_discount_msg_text = filter_input( INPUT_POST, 'dpad_discount_msg_text', FILTER_UNSAFE_RAW );
                            // We need to use this filter as we are storing tinyMCE data
                            // phpcs:enable
                            $get_dpad_discount_msg_bg_color = filter_input( INPUT_POST, 'dpad_discount_msg_bg_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_discount_msg_text_color = filter_input( INPUT_POST, 'dpad_discount_msg_text_color', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_chk_discount_msg_selected_product = filter_input( INPUT_POST, 'dpad_chk_discount_msg_selected_product', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad_selected_product_list = filter_input(
                                INPUT_POST,
                                'dpad_selected_product_list',
                                FILTER_SANITIZE_NUMBER_INT,
                                FILTER_REQUIRE_ARRAY
                            );
                            $getdpad_select_day_of_week = filter_input(
                                INPUT_POST,
                                'dpad_select_day_of_week',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_dpad_sale_product = filter_input( INPUT_POST, 'dpad_sale_product', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $get_dpad = filter_input(
                                INPUT_POST,
                                'dpad',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_condition_key = filter_input(
                                INPUT_POST,
                                'condition_key',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            //New
                            $get_dpad_settings_status = ( isset( $get_dpad_settings_status ) ? sanitize_text_field( $get_dpad_settings_status ) : 'off' );
                            $get_dpad_settings_select_dpad_type = ( isset( $get_dpad_settings_select_dpad_type ) ? sanitize_text_field( $get_dpad_settings_select_dpad_type ) : '' );
                            $get_dpad_settings_product_cost = ( isset( $get_dpad_settings_product_cost ) ? floatval( $get_dpad_settings_product_cost ) : '' );
                            $get_dpad_chk_qty_price = ( isset( $get_dpad_chk_qty_price ) ? sanitize_text_field( $get_dpad_chk_qty_price ) : 'off' );
                            $get_dpad_per_qty = ( isset( $get_dpad_per_qty ) ? sanitize_text_field( $get_dpad_per_qty ) : '' );
                            $get_extra_product_cost = ( isset( $get_extra_product_cost ) ? floatval( $get_extra_product_cost ) : '' );
                            $get_dpad_settings_start_date = ( isset( $get_dpad_settings_start_date ) ? sanitize_text_field( $get_dpad_settings_start_date ) : '' );
                            $get_dpad_settings_end_date = ( isset( $get_dpad_settings_end_date ) ? sanitize_text_field( $get_dpad_settings_end_date ) : '' );
                            $get_dpad_time_from = ( isset( $get_dpad_time_from ) ? sanitize_text_field( $get_dpad_time_from ) : '' );
                            $get_dpad_time_to = ( isset( $get_dpad_time_to ) ? sanitize_text_field( $get_dpad_time_to ) : '' );
                            $get_dpad_chk_discount_msg = ( isset( $get_dpad_chk_discount_msg ) ? sanitize_text_field( $get_dpad_chk_discount_msg ) : 'off' );
                            $get_dpad_discount_msg_text = ( isset( $get_dpad_discount_msg_text ) ? $get_dpad_discount_msg_text : '' );
                            $get_dpad_discount_msg_bg_color = ( isset( $get_dpad_discount_msg_bg_color ) ? sanitize_text_field( $get_dpad_discount_msg_bg_color ) : '#ffcaca' );
                            $get_dpad_discount_msg_text_color = ( isset( $get_dpad_discount_msg_text_color ) ? sanitize_text_field( $get_dpad_discount_msg_text_color ) : '#000000' );
                            $get_dpad_chk_discount_msg_selected_product = ( isset( $get_dpad_chk_discount_msg_selected_product ) ? sanitize_text_field( $get_dpad_chk_discount_msg_selected_product ) : 'off' );
                            $get_dpad_selected_product_list = ( isset( $get_dpad_selected_product_list ) ? array_map( 'intval', $get_dpad_selected_product_list ) : array() );
                            $get_dpad_select_day_of_week = ( isset( $getdpad_select_day_of_week ) ? array_map( 'sanitize_text_field', $getdpad_select_day_of_week ) : array() );
                            $get_dpad_sale_product = ( isset( $get_dpad_sale_product ) ? sanitize_text_field( $get_dpad_sale_product ) : '' );
                            $get_dpad = ( isset( $get_dpad ) ? $get_dpad : array() );
                            $get_condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                            //New
                            update_post_meta( $method_id, 'dpad_settings_status', $get_dpad_settings_status );
                            update_post_meta( $method_id, 'dpad_settings_select_dpad_type', $get_dpad_settings_select_dpad_type );
                            update_post_meta( $method_id, 'dpad_settings_product_cost', $get_dpad_settings_product_cost );
                            update_post_meta( $method_id, 'dpad_chk_qty_price', $get_dpad_chk_qty_price );
                            update_post_meta( $method_id, 'dpad_per_qty', $get_dpad_per_qty );
                            update_post_meta( $method_id, 'extra_product_cost', $get_extra_product_cost );
                            update_post_meta( $method_id, 'dpad_settings_start_date', $get_dpad_settings_start_date );
                            update_post_meta( $method_id, 'dpad_settings_end_date', $get_dpad_settings_end_date );
                            update_post_meta( $method_id, 'dpad_time_from', $get_dpad_time_from );
                            update_post_meta( $method_id, 'dpad_time_to', $get_dpad_time_to );
                            update_post_meta( $method_id, 'dpad_chk_discount_msg', $get_dpad_chk_discount_msg );
                            update_post_meta( $method_id, 'dpad_discount_msg_text', $get_dpad_discount_msg_text );
                            update_post_meta( $method_id, 'dpad_discount_msg_bg_color', $get_dpad_discount_msg_bg_color );
                            update_post_meta( $method_id, 'dpad_discount_msg_text_color', $get_dpad_discount_msg_text_color );
                            update_post_meta( $method_id, 'dpad_chk_discount_msg_selected_product', $get_dpad_chk_discount_msg_selected_product );
                            update_post_meta( $method_id, 'dpad_selected_product_list', $get_dpad_selected_product_list );
                            update_post_meta( $method_id, 'dpad_select_day_of_week', $get_dpad_select_day_of_week );
                            update_post_meta( $method_id, 'dpad_sale_product', $get_dpad_sale_product );
                            $condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                            $dpad_conditions = $get_dpad['product_dpad_conditions_condition'];
                            $conditions_is = $get_dpad['product_dpad_conditions_is'];
                            $conditions_values = ( isset( $get_dpad['product_dpad_conditions_values'] ) && !empty( $get_dpad['product_dpad_conditions_values'] ) ? $get_dpad['product_dpad_conditions_values'] : array() );
                            $size = count( $dpad_conditions );
                            foreach ( array_keys( $condition_key ) as $key ) {
                                if ( !array_key_exists( $key, $conditions_values ) ) {
                                    $conditions_values[$key] = array();
                                }
                            }
                            $dpadArray = array();
                            $conditions_values_array = array();
                            uksort( $conditions_values, 'strnatcmp' );
                            foreach ( $conditions_values as $v ) {
                                $conditions_values_array[] = $v;
                            }
                            for ($i = 0; $i < $size; $i++) {
                                $dpadArray[] = array(
                                    'product_dpad_conditions_condition' => $dpad_conditions[$i],
                                    'product_dpad_conditions_is'        => $conditions_is[$i],
                                    'product_dpad_conditions_values'    => $conditions_values_array[$i],
                                );
                            }
                            update_post_meta( $method_id, 'dynamic_pricing_metabox', $dpadArray );
                        }
                    } else {
                        echo '<div class="updated error"><p>' . esc_html__( 'Error saving discount rule.', 'woo-conditional-discount-rules-for-checkout' ) . '</p></div>';
                        return false;
                    }
                    if ( is_network_admin() ) {
                        $admin_url = admin_url( 'admin.php' );
                    } else {
                        $admin_url = admin_url( 'admin.php' );
                    }
                    $whsm_add = wp_create_nonce( 'whsm_add' );
                    if ( 'add' === $action ) {
                        //Refresh our cache after add new discount
                        $plugin_public->wdpad_action_on_discount_list( true );
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'wcdrfc-rules-list',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $whsm_add ),
                            'message'  => 'created',
                        ), $admin_url ) );
                        exit;
                    }
                    if ( 'edit' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'wcdrfc-rules-list',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $whsm_add ),
                            'message'  => 'saved',
                        ), $admin_url ) );
                        exit;
                    }
                }
            }
        }

        /**
         * Edit discount rule
         *
         * @since    3.5
         */
        private static function dpad_sj_edit_method() {
            include plugin_dir_path( __FILE__ ) . 'wcdrfc-pro-add-new-page.php';
        }

        /**
         * Add discount rule
         *
         * @since    3.5
         */
        public static function dpad_sj_add_shipping_method_form() {
            include plugin_dir_path( __FILE__ ) . 'wcdrfc-pro-add-new-page.php';
        }

        /**
         * list_shipping_methods function.
         *
         * @since    3.5
         *
         * @uses WC_Discount_Rules_Table class
         * @uses WC_Discount_Rules_Table::process_bulk_action()
         * @uses WC_Discount_Rules_Table::prepare_items()
         * @uses WC_Discount_Rules_Table::search_box()
         * @uses WC_Discount_Rules_Table::display()
         *
         * @access public
         *
         */
        public static function dpad_sj_list_methods_screen() {
            if ( !class_exists( 'WC_Discount_Rules_Table' ) ) {
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/class-wc-discount-rules-table.php';
            }
            $link = add_query_arg( array(
                'page'   => 'wcdrfc-rules-list',
                'action' => 'add',
            ), admin_url( 'admin.php' ) );
            require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
            ?>
                <div class="wrap">
                    <form method="post" enctype="multipart/form-data">
                        <div class="wdpad-main-table res-cl">
                            <h1 class="wp-heading-inline">
                                <?php 
            esc_html_e( 'Discount Rules', 'woo-conditional-discount-rules-for-checkout' );
            ?>
                            </h1>
                            <a href="<?php 
            echo esc_url( $link );
            ?>" class="page-title-action dots-btn-with-brand-color">
                                <?php 
            echo esc_html__( 'Add New', 'woo-conditional-discount-rules-for-checkout' );
            ?>
                            </a>
                            <?php 
            $request_s = filter_input( INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $request_s ) && !empty( $request_s ) ) {
                echo sprintf( '<span class="subtitle">' . esc_html__( 'Search results for &#8220;%s&#8221;', 'woo-conditional-discount-rules-for-checkout' ) . '</span>', esc_html( $request_s ) );
            }
            ?>
                            <?php 
            wp_nonce_field( 'sorting_conditional_fee_action', 'sorting_conditional_fee' );
            $WC_Discount_Rules_Table = new WC_Discount_Rules_Table();
            $WC_Discount_Rules_Table->process_bulk_action();
            $WC_Discount_Rules_Table->prepare_items();
            $WC_Discount_Rules_Table->search_box( esc_html__( 'Search', 'woo-conditional-discount-rules-for-checkout' ), 'discount-search' );
            $WC_Discount_Rules_Table->display();
            ?>
                        </div>
                    </form>
                </div>
                <?php 
            require_once plugin_dir_path( __FILE__ ) . 'header/plugin-footer.php';
            ?>
			<?php 
        }

    }

}