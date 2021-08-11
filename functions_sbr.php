<?php
// Anpassungen fuer SBR-Websites

/*
Copyright-Zeile
Dieser Shortcode fügt eine Copyright-Zeile, z. B. im Footer, ein.
Wenn vorhanden, wird das Erstellungsjahr des Blogs/der Website dem aktuellen Jahr
vorangestellt (z. B. 2014 - 2020).
*/
// Erstellungsjahr aus Datenbank auslesen
$blog_created = !empty(get_option('wpht_settings')['wpht_text_field_0']);
// Notice: Undefined index (leere Variable) beseitigt
function copyright_zeile() {
    global $blog_created; // Variable "$blog_created" als global definieren
    if ($blog_created == "") {  // Erstellungsjahr fehlt
    $h_copyright = "&copy " . date('Y') . " " . get_option('blogname')
    ." " . get_bloginfo('description');
    }
    else {  // Erstellungsjahr vorhanden
    $h_copyright = "&copy " . $blog_created . " - " . date('Y') . " " . get_option('blogname')
    ." " . get_bloginfo('description');
    }
    return $h_copyright;
}
add_shortcode('h_copyright', 'copyright_zeile');

// Erstellungsjahr aus WP H-Tools in Datenbank schreiben
$blog_created_year =  $blog_created;
$sbr = "Erstellungsjahr";
update_option($sbr, $blog_created_year);

//////////////////////////////////////////////////////////////////////////////////////////

/*
Modified Page
Dieser Shortcode zeigt je nach Einstellung das Datum der Erstellung und der letzten
Änderung einer Seite, z. B. im Footer, an.
Ohne Änderung an der Seite wird nur das Erstellungsdatum angezeigt.
*/
function h_modified_page() {
        $h_created = get_the_time('d.m.Y'); // Datum der Erstellung
        $h_modified = get_the_modified_date('d.m.Y'); // Datum der letzten Bearbeitung
        if ($h_created == $h_modified) { // Ist Datum der Erstellung gleich wie Änderung?
            return "Erstellt: " . $h_created;
        }
        else
        {
            return "Erstellt: " . $h_created . " | Letzte Änderung: " . $h_modified;
                // Bei Bedarf nur Erstellung oder letzte Änderung anzeigen
        }
}
add_shortcode('h_modified', 'h_modified_page');

//////////////////////////////////////////////////////////////////////////////////////////

/*
Remove query strings from static ressources
Dieser Shortcode entfernt die Datenbank-Strings von statischen Ressourcen und verbessert
damit die Performance der Website.
Quelle: https://kinsta.com/de/wissensdatenbank/entfernst-du-abfragezeichenfolgen-
aus-statischen-ressourcen/
*/
if (!function_exists('remove_query_strings')) { // Prüfung, ob Funktion bereits vorhanden
function remove_query_strings() {
   if(!is_admin()) {
       add_filter('script_loader_src', 'remove_query_strings_split', 15);
       add_filter('style_loader_src', 'remove_query_strings_split', 15);
   }
}
function remove_query_strings_split($src){
   $output = preg_split("/(&ver|\?ver)/", $src);
   return $output[0];
}
add_action('init', 'remove_query_strings');
}

//////////////////////////////////////////////////////////////////////////////////////////
/* Externe HTML-Seite einfügen
 * Shortcode [wpiec]URL[/wpiec] in Seite/Beitrag einfügen
*/
function wpiec_shortcode( $atts = array(), $content = null ) {
    $content = file_get_contents($content);
    return $content;
}
add_shortcode( 'wpiec', 'wpiec_shortcode' );

//////////////////////////////////////////////////////////////////////////////////////////
//Eigene Blacklist von GitHub einfuegen
add_filter( 'cblm_sources', 'rkv_cblm_replace_blacklist_sources' );
function rkv_cblm_replace_blacklist_sources( $list ) {
return array(
'https://raw.githubusercontent.com/m266/wordpress-comment-blacklist/master/blacklist.txt'
);
}

//////////////////////////////////////////////////////////////////////////////////////////
// Erlaubt HTML-Code für Happyforms in Mehrfachauswahl-Feldern
if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// Ist Plugin WP H-Happyforms inaktiv? Dann folgenden Code ausfuehren
if (is_plugin_inactive('wp-h-happyforms-tools/wphhft.php')) {
// Ist Plugin Happyforms aktiv?
if (is_plugin_active('happyforms/happyforms.php')) {  // Plugin Happyforms ist aktiv
// Ersetzt String in der Datei frontend-checkbox.php Zeile 34 (Plugin Happyforms)
    $wphhft_string_orig = "<?php echo esc_attr( \$option['label'] ); ?>";
    $wphhft_string_new = "<?php echo html_entity_decode( \$option['label'] ); ?>";
    $wphhft_path_to_file = ABSPATH . 'wp-content/plugins/happyforms/core/templates/parts/frontend-checkbox.php';
    $wphhft_file_contents = file_get_contents($wphhft_path_to_file); // Inhalt frontend-checkbox.php einlesen
if(strpos($wphhft_file_contents, $wphhft_string_orig) !== false) { // Original-String vorhanden?
    $wphhft_file_contents = str_replace($wphhft_string_orig, $wphhft_string_new, $wphhft_file_contents);
    file_put_contents($wphhft_path_to_file, $wphhft_file_contents); // Replace strings
    add_filter('happyforms_part_frontend_template_path_checkbox', function ($wphhft_template) {
        $wphhft_template = ABSPATH . 'wp-content/plugins/happyforms/core/templates/parts/frontend-checkbox.php';
        return $wphhft_template;
    });
}
}
}

//////////////////////////////////////////////////////////////////////////////////////////
// Verbesserung Bestätigungs-E-Mail (Block der Zustimmung wird ausgeblendet)
// Der Inhalt der Variable "$label" muss exakt dem Text im Formular entsprechen; bei Bedarf in Zeile 196 anpassen.
add_filter( 'happyforms_email_part_visible', function( $visible, $part, $form ) {
    $label = 'Das Formular kann nur mit der Zustimmung zur Datenschutzerklärung gesendet werden*';
    if ( isset( $part['label'] ) && $label === $part['label'] ) {
        $visible = false;
    }

    return $visible;
}, 10, 3 );

//////////////////////////////////////////////////////////////////////////////////////////
/*
Feld "Nachricht" wird mit Kommentar-Blacklist abgeglichen
Credits/Special thanks: Ignazio Setti https://thethemefoundry.com/
*/
add_filter( 'happyforms_validate_submission', function( $is_valid, $request, $form ) {
    $mod_keys = trim( get_option( 'disallowed_keys' ) );

    if ( '' === $mod_keys ) {
        return $is_valid;
    }

    foreach( $form['parts'] as $part ) {
        if ( $part['type'] === 'multi_line_text' ) {
            $part_name = happyforms_get_part_name( $part, $form );
            $part_value = $request[$part_name];

            foreach ( explode( "\n", $mod_keys ) as $word ) {
                $word = trim( $word );
                $length = strlen( $word );

                if ( $length < 2 or 256 < $length ) {
                    continue;
                }

                $pattern = sprintf( '#%s#i', preg_quote( $word, '#' ) );

                if ( preg_match( $pattern, $part_value ) ) {
                    $is_valid = false;
                }
            }
        }
    }

    return $is_valid;
}, 10, 3 );

//////////////////////////////////////////////////////////////////////////////////////////
// Beiträge in Seiten einfügen
// Quelle: https://ostrich.de/wordpress-beitraege-auf-seite-anzeigen/
// Version 1.0
// 09.03.2021
// Tag [posts] in der Start-Seite einfügen
function shortcode_posts_function(){
    //Parameter für Posts
    $args = array(
        'category' => 'news', // Kategorie
        'numberposts' => 5  // Anzahl der Beiträge
    );
    //Posts holen
    $posts = get_posts($args);
    //Inhalte sammeln
    $content = '<div class="posts">';
    $content .= '<hr>
    <h1 class="page-title">Letzte &Auml;nderungen:</h1>';
    foreach ($posts as $post) {
        $content .= '<div class="post">';
        $content .= '<b><a href="'.get_permalink($post->ID).'"><div class="title">'.$post->post_title.'</div></b></a>';
        $content .= '<div class="post-date">'.mysql2date('d. F Y', $post->post_date).'</div>';
        $content .= '<div class="post-entry">'.wp_trim_words($post->post_content).'</div>';
        $content .= '<a href="'.get_permalink($post->ID).'"><div class="post-entry">'."Weiterlesen...".'<hr></div></a>';
        $content .= '</div>';
    }
    $content .= '</div>';
    //Inhalte übergeben
    return $content;
}
add_shortcode('posts', 'shortcode_posts_function');

//////////////////////////////////////////////////////////////////////////////////////////
// Restoring the classic Widgets Editor
function cancel_theme_support() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'cancel_theme_support' );
?>