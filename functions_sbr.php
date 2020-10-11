<?php
// Anpassungen fuer SBR-Websites

/*
Copyright-Zeile
Dieser Shortcode fügt eine Copyright-Zeile, z. B. im Footer, ein.
Wenn vorhanden, wird das Erstellungsjahr des Blogs/der Website dem aktuellen Jahr
vorangestellt (z. B. 2014 - 2020).
*/
// Erstellungsjahr aus Datenbank auslesen
$blog_created = get_option('wpht_settings')['wpht_text_field_0'];

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
?>