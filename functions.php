<?php
//
// Recommended way to include parent theme styles.
//  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
//
// Your code goes below
//

// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_inactive')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

// Plugin "WP H-NetGo" inaktiv?
if (is_plugin_inactive('netgo-2/netgo-2.php')) {
    // Plugin ist inaktiv
    function sbr_missing_wp_h_netgo_notice() {; // Plugin "WP H-NetGo" fehlt
        ?>
    <div class="error notice">  <!-- Wenn ja, Meldung ausgeben -->
        <p><?php _e('Bitte das Plugin <a href="https://web266.de/software/eigene-plugins/netgo-2/" target="_blank">
        <b>"WP H-NetGo"</b></a> herunterladen, installieren und aktivieren.
        Ansonsten wird die Seiten-Navigation nicht angezeigt!');?></p>
    </div>
                        <?php
}
    add_action('admin_notices', 'sbr_missing_wp_h_netgo_notice');
}

// Plugin "WP H-Exclude Pages" inaktiv?
if (is_plugin_inactive('exclude-pages-2/exclude_pages.php')) {
    // Plugin ist inaktiv
    function sbr_missing_wp_h_exclude_pages_notice() {; // Plugin "WP H-Exclude Pages" fehlt
        ?>
    <div class="error notice">  <!-- Wenn ja, Meldung ausgeben -->
        <p><?php _e('Bitte das Plugin <a href="https://web266.de/software/eigene-plugins/exclude-pages-2/" target="_blank">
        <b>"WP H-Exclude Pages"</b></a> herunterladen, installieren und aktivieren.
        Ansonsten k&ouml;nnen keine Seiten in der Navigation ausgeblendet werden!');?></p>
    </div>
                        <?php
}
    add_action('admin_notices', 'sbr_missing_wp_h_exclude_pages_notice');
}

//
// Header Image
// Es wird das default-image eingeblendet, wenn kein eigenes Headerbild aktiviert wird
//
$args = array(
    'width' => 1280,
    'height' => 250,
    'default-image' => get_stylesheet_directory_uri() . '/images/sbr-header-image.jpg',
    'uploads' => true,
);
add_theme_support('custom-header', $args);

//
// Google Fonts
// Nur Merriweather Sans einbinden
function twentysixteen_fonts_url() {
    $fonts_url = '';
    $fonts = array();
    $subsets = 'latin,latin-ext';

    /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
    if ('off' !== _x('on', 'Merriweather Sans font: on or off', 'twentysixteen')) {
        $fonts[] = 'Merriweather Sans:400,700,400italic,700italic';
    }

    /* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
    /*if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
    $fonts[] = 'Montserrat:400,700';
    }*/

    /* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
    if ('off' !== _x('on', 'Inconsolata font: on or off', 'twentysixteen')) {
        $fonts[] = 'Inconsolata:400';
    }

    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family' => urlencode(implode('|', $fonts)),
            'subset' => urlencode($subsets),
        ), 'https://fonts.googleapis.com/css');
    }

    return $fonts_url;
}

//
// Read more aendern
//
function modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">mehr...</a>';
}
add_filter('the_content_more_link', 'modify_read_more_link');

//
// Registriert ein TinyMCE-Editor Stylesheet.
//
function wpdocs_theme_add_editor_styles() {
    add_editor_style('/css/custom-editor-style.css');
}
add_action('admin_init', 'wpdocs_theme_add_editor_styles');

// Template ohne Sidebar einbinden
function wpse_enqueue_page_template_styles() {
    if (is_page_template('page_without_sidebar.php')) {
        wp_enqueue_style('page-template', get_stylesheet_directory_uri() . '/css/h-without-sidebar.css');
    }
}
add_action('wp_enqueue_scripts', 'wpse_enqueue_page_template_styles');

// Anpassungen fuer SBR-Websites einbinden
require_once 'functions_sbr.php';

// Comment Blacklist Manager einbinden
//  Plugin "WP H-Tools inaktiv?
    if (is_plugin_inactive('wp-h-tools/wp_h_tools.php')) {
            require_once 'comment-blacklist-manager.php';
         }