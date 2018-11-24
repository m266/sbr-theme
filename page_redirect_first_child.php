<?php

/*
Template Name: Auf erste Unterseite weiterleiten
Template URI: https://www.web266.de/software/seiten-templates/auf-erste-unterseite-weiterleiten/

Description:
Das Template wird zur Weiterleitung auf die erste Unterseite verwendet.
Bei fehlenden Unterseiten erfolgt keine Weiterleitung.

Installation:
Diese Datei muss in das Root des verwendeten Themes hochgeladen werden.
Beim Wechsel des Themes ist die Datei in das neue Theme wieder einzufügen.

Author: Hans-M. Herbrand
Version: 1.3 - 29.12.2017
Author URI: http://www.web266.de/

INSPIRATIONS/CREDITS:
http://www.perun.net/2012/08/15/wordpress-weiterleitung-auf-die-unterseite/
http://notes.pandaweb.de/wordpress/conditional-tag-has-page-child-pages/

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

/* Pruefen, ob Unterseiten vorhanden sind */
$children = get_pages('child_of=' . $post->ID);
if (count($children) != 0) {
  if (have_posts()) {
    while (have_posts()) {
      the_post();
      $pagekids = get_pages("child_of=" . $post->ID . "&sort_column=menu_order");
      $firstchild = $pagekids[0];

/* Unterseiten vorhanden */
      wp_redirect(get_permalink($firstchild->ID));
    }
  }
}
?>
<!-- Keine Unterseiten vorhanden -->
<?php get_header(); ?>
      <?php get_sidebar('top'); ?>
      <?php
      if (have_posts()) {

      /* Start the Loop */
        while (have_posts()) {
          the_post();
          get_template_part('content', 'page');
        }
      }
      else {
        theme_404_content();
      }
      ?>
      <?php get_sidebar('bottom'); ?>
<?php get_footer(); ?>