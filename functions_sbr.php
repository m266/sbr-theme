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

?>