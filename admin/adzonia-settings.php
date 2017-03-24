<?php
/**
 * Add Settings Sub Menu
 * Thankfully with the assistance of Mark Otto.
 * 
 * @link http://ottopress.com/2009/wordpress-settings-api-tutorial/
 *
 * @since 1.2.0
 * ----------------------------------------------------
 */
function adzonia_settings_page() {
    add_submenu_page(
        'edit.php?post_type=adzonia',                   //$parent_slug
        __('Settings & Instructions', 'adzonia' ),      //$page_title
        __('Settings-Instructions', 'adzonia' ),        //$menu_title
        'manage_options',                               //$capability
        'adzonia-settings',                             //$menu_slug
        'adzonia_settings_page_callback'                //callback function
    );
}

add_action('admin_menu', 'adzonia_settings_page');

function adzonia_settings_page_callback() { ?>

    <div class="wrap">
        <h2><?php _e('Settings & Instructions', 'adzonia' ); ?></h2>
        <?php settings_errors(); ?>
        <div class="adzonia-left-column">
            <form action="options.php" method="post">
                <?php settings_fields('adzonia_options'); ?>
                <?php do_settings_sections('adzonia_settings'); ?>
                <?php submit_button(); ?>
            </form>            
        </div>
        <!-- /.adzonia-left-column -->
        <div class="adzonia-right-column">
            
        </div>
        <!-- /.adzonia-right-column -->
        <div class="clearfix"></div>
        <hr>
        <?php
        //get the manual from external file
        require_once( 'manual/inner-manual.php' );
        ?>
    </div> <!-- .wrap -->

<?php
}

function adzonia_options_init(){
    register_setting(
        'adzonia_options',                          // Option group*
        'adzonia_options',                          // Option Name*
        'adzonia_options_validate'                  // Sanitize Callback Function
    );
    add_settings_section(
        'adzonia_general',                          // ID/Slug*
        sprintf( __( '%s Settings', 'adzonia' ), '<span class="dashicons dashicons-admin-generic"></span>'),                                       // Name*
        'adzonia_gen_section_callback',             // Callback*
        'adzonia_settings'                          // Page on which to add this section of options*
    );
    add_settings_field(
        'adzonia_css',                              // ID*
        __( 'AdZonia CSS', 'adzonia' ),          // Title*
        'adzonia_setting_css_field',                // Callback Function*
        'adzonia_settings',                         // Page (Plugin)*
        'adzonia_general'                           // Section
    );
    add_settings_section(
        'adzonia_troubleshoot',                     // ID/Slug*
        sprintf( __( '%s Troubleshoot', 'adzonia' ), '<span class="dashicons dashicons-admin-tools"></span>'),                                       // Name*
        'adzonia_tr_section_callback',              // Callback*
        'adzonia_settings'                          // Page on which to add this section of options*
    );
    add_settings_field(
        'adzonia_jquery',                           // ID*
        __( 'AdZonia jQuery', 'adzonia' ),       // Title*
        'adzonia_setting_jquery_field',             // Callback Function*
        'adzonia_settings',                         // Page (Plugin)*
        'adzonia_troubleshoot'                      // Section
    );
}
add_action( 'admin_init', 'adzonia_options_init' );

// General Section
function adzonia_gen_section_callback() {
    _e('<p>Set these optional settings if you want. It&rsquo;s not mandatory, and even without the settings your advertisements will work just fine.</p>', 'adzonia');
}

function adzonia_setting_css_field() {
    $options = get_option('adzonia_options');
    echo "<input name='adzonia_options[adzonia_css_check]' id='adzonia_css' type='checkbox' value='1' ".checked( 1, $options['adzonia_css_check'], false ) . " /> <label for='adzonia_css'>". __( 'check the box to show Ads as inline element rather block element. It will load AdZonia CSS into the site&rsquo;s front-end', 'adzonia' ) ."</label>";
}

// Troubleshoot Section
function adzonia_tr_section_callback() {
    _e('<p>Getting trouble using the plugin? If the date picker and/or image uploader is not working, try enabling the jQuery from the plugin resources.</p>', 'adzonia');
}

function adzonia_setting_jquery_field() {
    $options = get_option('adzonia_options');
    echo "<input name='adzonia_options[adzonia_jquery_check]' id='adzonia_jquery' type='checkbox' value='1' ".checked( 1, $options['adzonia_jquery_check'], false ) . " /> <label for='adzonia_jquery'>". __( 'Load jQuery from plugin', 'adzonia' ) ."</label>";
}

// validate our options
function adzonia_options_validate( $input ) {
    $options = get_option('adzonia_options');

    //CSS Checkbox
    $css_check_val = (int) $input['adzonia_css_check'] === 1 ? (int) $input['adzonia_css_check'] : '';
    $options['adzonia_css_check'] = is_int( $css_check_val );

    //jQuery Checkbox
    $jquery_check_val = (int) $input['adzonia_jquery_check'] === 1 ? (int) $input['adzonia_jquery_check'] : '';
    $options['adzonia_jquery_check'] = is_int( $jquery_check_val );

    return $options;
}