<?php
/*
Template Name: Auf externe URL weiterleiten
Template URI: http://www.web266.de/

Description:
Weiterleitung auf eine externe URL, wenn das benutzerdefinierte Menü von
WordPress nicht verwendet wird.

Installation:
Diese Datei muss in das Root des verwendeten Themes hochgeladen werden.
Beim Wechsel des Themes ist die Datei in das neue Theme wieder einzufügen.
Ein benutzerdefiniertes Feld auf der Seite erstellen:
Name: ext-url
Wert: Ziel-URL eingeben (z. B. http://www.web266.de/)

Author: Hans-M. Herbrand
Version: 1.0 - 27.08.2015
Author URI: http://www.web266.de/

INSPIRATIONS/CREDITS:

License:
GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/by-nc-sa/4.0/deed.de/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 4 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

?>
<?php
$ext_url = get_post_meta($post->ID, 'ext-url', true);
header("Location: $ext_url");
exit;
?>