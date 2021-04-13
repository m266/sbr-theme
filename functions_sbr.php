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
/*
Plugin Name:   WP H-Insert External Content
Plugin URI:    https://github.com/m266/wp-insert-external-content
Description:   Plugin zum Einbinden externer Inhalte in WordPress. Nach der Aktivierung k&ouml;nnen externe Inhalte in Seiten bzw. Beitr&auml;gen integriert werden. Dazu wird im Inhaltsbereich mit dem WordPress-Editor folgender Shortcode eingef&uuml;gt: [wpiec]URL[/wpiec]  (URL ist durch die richtige Web-Adresse zu ersetzen). Update- und Alarm-Intervall lassen sich ab Zeile 45 anpassen.
Author:        Hans M. Herbrand
Author URI:    https://www.web266.de
Version:       1.2
Date:          2020-10-27
License:       GNU General Public License v2 or later
License URI:   http://www.gnu.org/licenses/gpl-2.0.html
Credits:       Daniel Gruber, http://zeit-zu-handeln.net/?p=739
GitHub Plugin URI: https://github.com/m266/wp-insert-external-content
 */

/*
// Anpassungen für SBR-Theme:
- Plugin-Updater entfernt
- Klasse wpiec geändert in sbriec (Zeile 102, 155)
*/

// Zeit-Definition
$wpiec_intervall = (60 * 60); // Update-Intervall: Zeit in Sekunden [Standard 1 Std (60*60)]
$wpiec_alarm = (24 * 60 * 60); // Alarm-Intervall: Zeit in Sekunden [Standard 1 Tag (24*60*60)]

class sbriec {

/**
 * Constructor.
 */
    function __construct() {
// empty for now
    }
    function displayShortcode($atts, $content = null) {
        // Variablen als Global deklarieren
        global $wpiec_intervall;
        global $wpiec_alarm;

        extract(shortcode_atts(array('pattern' => '#(.*)#s', 'before' => '', 'after' => '', ), $atts));
        if ($websitecontent = @file($content)) {
            $data = join("", $websitecontent);
        }
        $before = str_replace('{', '<', $before);
        $before = str_replace('}', '>', $before);
        $before = str_replace('°', '"', $before);
        $after = str_replace('{', '<', $after);
        $after = str_replace('}', '>', $after);
        $after = str_replace('°', '"', $after);
        $pattern = str_replace('{', '<', $pattern);
        $pattern = str_replace('}', '>', $pattern);
        $pattern = str_replace('°', '"', $pattern);
        $ID = md5($pattern . $content);
        $db = get_option($ID);
// Meldung an Admin über Ausfall des verlinkten Contents
        if ((time() - $db[0]) > ($wpiec_alarm) && $websitecontent == false && $db[2] != true) {
            wp_mail(get_option("admin_email"), "Warnung: Veralteter Inhalt - Website nicht erreichbar", "Dies ist eine Mail des Wordpress-Plugins WP Insert External Content zum Einbinden und Filtern von Inhalten aus externen Websites. Die betroffene Website ist: " . get_option("blogname") . " (" . get_option("siteurl") . "). Die Website " . $content . " ist aktuell nicht mehr erreichbar. Die gecachte Version ist u. U. veraltet!");
            update_option($ID, array($db[0], $db[1], true));
        }
// Update-Intervall Content
        if ((!$db || $db[0] + $wpiec_intervall < time()) && $websitecontent != false) {
            preg_match($pattern, $data, $matches);
            preg_match('#(https?://[^/]*)/#', $content, $matches2);
            $base_url = $matches2[1] . "/";
            $matches[1] = preg_replace('#href="\.?/#', 'href="' . $base_url, $matches[1]);
            preg_match('#(.*/)#', $content, $matches1);
            $url = $matches1[1];
            $matches[1] = preg_replace('#href="(?!https?://|ftp://|mailto:|news:|\#)([^"]*)"#', 'href="' . $url . '${1}"', $matches[1]);
            if (!$db) {
                add_option($ID, array(time(), $matches[1]));
            } else {
                update_option($ID, array(time(), $matches[1]));
            }
        } else {
            $matches[1] = $db[1];
        }
        return $before . $matches[1] . $after;
    }
}
$wpiec = new sbriec();
add_shortcode('wpiec', array($wpiec, 'displayShortcode'));

//////////////////////////////////////////////////////////////////////////////////////////
//Eigene Blacklist von GitHub einfuegen
add_filter( 'cblm_sources', 'rkv_cblm_replace_blacklist_sources' );
function rkv_cblm_replace_blacklist_sources( $list ) {
return array(
'https://raw.githubusercontent.com/m266/wordpress-comment-blacklist/master/blacklist.txt'
);
}

//////////////////////////////////////////////////////////////////////////////////////////
// Allow HTML-Code for HappyForms at multi selection fields
if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// Is Plugin WP H-HappyForms inactiv? Dann folgenden Code ausfuehren
if (is_plugin_inactive('wp-h-happyforms-tools/wphhft.php')) {
// Is Plugin HappyForms activ?
if (is_plugin_active('happyforms/happyforms.php')) {  // Plugin HappyForms is activ
//  Change Strings in frontend-checkbox.php row (Zeile) 34
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
} else {
    register_activation_hook(__FILE__, 'wphhft_inactiv'); // Funktions-Name anpassen
    function wphhft_inactiv() //Plugin HappyForms is inactiv
    { // Funktions-Name anpassen
        $subject = 'Plugin "WP H-HappyForms Tools"'; // Plugin-Name anpassen
        $message = 'Bitte das Plugin "<a href="https://de.wordpress.org/plugins/happyforms/">HappyForms</a>" installieren und aktivieren!';
        wp_mail(get_option("admin_email"), $subject, $message);
    }
}
}

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
?>