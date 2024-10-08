<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Dynamic_Pricing_And_Discount_Pro
 * @subpackage Woocommerce_Dynamic_Pricing_And_Discount_Pro/includes
 * @author     Multidots <inquiry@multidots.in>
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Woocommerce_Dynamic_Pricing_And_Discount_Pro {
    const WDPAD_VERSION = WDPAD_PLUGIN_VERSION;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->plugin_name = 'woocommerce-dynamic-pricing-and-discount';
        $this->version = WDPAD_PLUGIN_VERSION;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_freemius_actions();
        $prefix = ( is_network_admin() ? 'network_admin_' : '' );
        add_filter(
            "{$prefix}plugin_action_links_" . WDPAD_PLUGIN_BASENAME,
            array($this, 'plugin_action_links'),
            10,
            4
        );
        add_filter(
            'plugin_row_meta',
            array($this, 'plugin_row_meta_action_links'),
            20,
            3
        );
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader. Orchestrates the hooks of the plugin.
     * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_i18n. Defines internationalization functionality.
     * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin. Defines all hooks for the admin area.
     * - Woocommerce_Dynamic_Pricing_And_Discount_Pro_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-dynamic-pricing-and-discount-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-dynamic-pricing-and-discount-public.php';
        /**
         * The class responsible for defining all actions that occur for Freemius related things
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-dynamic-pricing-and-discount-freemius-api.php';
        $this->loader = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woocommerce_Dynamic_Pricing_And_Discount_Pro_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_i18n();
        $plugin_i18n->set_domain( 'woo-conditional-discount-rules-for-checkout' );
        $this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        global $plugin_admin;
        $plugin_admin = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'wdpad_dot_store_menu_conditional' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wdpad_active_menu' );
        $this->loader->add_action( 'wp_ajax_wdpad_product_dpad_conditions_values_ajax', $plugin_admin, 'wdpad_product_dpad_conditions_values_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_wdpad_product_dpad_conditions_values_ajax', $plugin_admin, 'wdpad_product_dpad_conditions_values_ajax' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wdpad_send_wizard_data_after_plugin_activation' );
        $this->loader->add_action( 'wp_ajax_wdpad_plugin_setup_wizard_submit', $plugin_admin, 'wdpad_plugin_setup_wizard_submit' );
        $this->loader->add_action( 'wp_ajax_wdpad_product_dpad_conditions_values_product_ajax', $plugin_admin, 'wdpad_product_dpad_conditions_values_product_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_wdpad_product_dpad_conditions_values_product_ajax', $plugin_admin, 'wdpad_product_dpad_conditions_values_product_ajax' );
        $this->loader->add_action( 'wp_ajax_wdpad_product_dpad_conditions_varible_values_product_ajax', $plugin_admin, 'wdpad_product_dpad_conditions_varible_values_product_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_wdpad_product_dpad_conditions_varible_values_product_ajax', $plugin_admin, 'wdpad_product_dpad_conditions_varible_values_product_ajax' );
        $this->loader->add_action( 'wp_ajax_wdpad_simple_and_variation_product_list_ajax', $plugin_admin, 'wdpad_simple_and_variation_product_list_ajax' );
        $this->loader->add_action( 'wp_ajax_wdpad_product_discount_conditions_sorting', $plugin_admin, 'conditional_discount_sorting' );
        $this->loader->add_action( 'wp_ajax_wdpad_change_status_from_list_section', $plugin_admin, 'wdpad_change_status_from_list_section' );
        $this->loader->add_action( 'admin_post_dpad_save_general_settings', $plugin_admin, 'wdpad_save_general_settings' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'wdpad_remove_admin_submenus' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wdpad_welcome_conditional_dpad_screen_do_activation_redirect' );
        $this->loader->add_filter(
            'set-screen-option',
            $plugin_admin,
            'wdpad_set_screen_options',
            10,
            3
        );
        $this->loader->add_filter(
            'hidden_columns',
            $plugin_admin,
            'wdpad_default_hidden_columns',
            10,
            3
        );
        $page_menu = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty( $page_menu ) && false !== strpos( $page_menu, 'wcdrfc' ) ) {
            $this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'wdpad_admin_footer_review' );
        }
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        global $plugin_public;
        $plugin_public = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action(
            'woocommerce_cart_totals_get_fees_from_cart_taxes',
            $plugin_public,
            'conditional_wdpad_exclude_cart_fees_taxes',
            10,
            3
        );
        //fee taxes are removed from here need to check
        $this->loader->add_filter(
            'woocommerce_locate_template',
            $plugin_public,
            'woocommerce_locate_template_product_wdpad_conditions',
            1,
            3
        );
        $this->loader->add_filter(
            'woocommerce_cart_shipping_method_full_label',
            $plugin_public,
            'wdpad_change_shipping_title',
            10,
            2
        );
        $this->loader->add_action( 'woocommerce_before_add_to_cart_button', $plugin_public, 'wdpad_content_after_addtocart_button' );
        $this->loader->add_filter( 'woocommerce_checkout_fields', $plugin_public, 'wdpad_trigger_update_checkout_on_change' );
        //This hook will help us to add discount fee as this hook can not calculate subtotal of items into cart subtotal
        $this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'conditional_wdpad_add_to_cart' );
    }

    /**
     * Register all of the hooks related to the freemius functionality
     * of the plugin.
     *
     * @since    4.2.0
     * @access   private
     */
    private function define_freemius_actions() {
        $plugin_freemius = new Woocommerce_Dynamic_Pricing_And_Discount_Pro_Freemius_API();
        $this->loader->add_action( 'wp_ajax_wdpad_freemius_activate', $plugin_freemius, 'wdpad_freemius_activate' );
        $this->loader->add_action( 'wp_ajax_wdpad_freemius_deactivate', $plugin_freemius, 'wdpad_freemius_deactivate' );
        $this->loader->add_action( 'wp_ajax_wdpad_freemius_sync', $plugin_freemius, 'wdpad_freemius_sync' );
    }

    /**
     * Return the plugin action links.  This will only be called if the plugin
     * is active.
     *
     * @since 1.0.0
     *
     * @param array $actions associative array of action names to anchor tags
     *
     * @return array associative array of plugin action links
     */
    public function plugin_action_links(
        $actions,
        $plugin_file,
        $plugin_data,
        $context
    ) {
        $custom_actions = array(
            'configure' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wcdrfc-page-get-started' ), __( 'Settings', 'woo-conditional-discount-rules-for-checkout' ) ),
            'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://docs.thedotstore.com/category/323-premium-plugin-settings' ), __( 'Docs', 'woo-conditional-discount-rules-for-checkout' ) ),
            'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://www.thedotstore.com/support' ), __( 'Support', 'woo-conditional-discount-rules-for-checkout' ) ),
        );
        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
    }

    /**
     * Add review stars in plugin row meta
     *
     * @since 1.0.0
     */
    public function plugin_row_meta_action_links( $plugin_meta, $plugin_file, $plugin_data ) {
        if ( isset( $plugin_data['TextDomain'] ) && $plugin_data['TextDomain'] !== 'woo-conditional-discount-rules-for-checkout' ) {
            return $plugin_meta;
        }
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/woo-conditional-discount-rules-for-checkout/#reviews' );
        $plugin_meta[] = sprintf( '<a href="%s" target="_blank" style="color:#f5bb00;">%s</a>', $url, esc_html( '★★★★★' ) );
        return $plugin_meta;
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woocommerce_Dynamic_Pricing_And_Discount_Pro_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

}
