<?php
/*
  Simple Seller Plugin for WordPress
  Copyright 2010 Todd D. Esposito <todd@toddesposito.com>

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  version 2 as published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

function sseller_buyitblock ($post) {
  $price = get_post_meta ($post->ID, 'price', true); 

  $add = array();
  $add[] = '<div class="sseller_buyitblock">';
  $add[] = "<p>Price: $price</p>";
  if ('publish' == $post->post_status) {
    $buttoncode = get_post_meta ($post->ID, "paypal_buttoncode", true);
    if ($buttoncode) {
      $add[] = $buttoncode;
    } else {
      $add[] = "<em>Sorry, not available for sale right now.</em>";
    }
  } else {
    $add[] = "<pre>No payment links, this is draft</pre>\n";
  }
  $add[] = '</div><!-- sseller_buyitblock -->';

  if ($post->is_single) {
    $disclaimer = $options['disclaimer'];
    if ($disclaimer) {
      $add[] = "<div class='sseller_disclaimer'>$disclaimer</div>\n";
    }
  }
  return join ('', $add);
}

function sseller_buyitblock_filter ($content) {
  global $post;
  $options = get_option ('sseller_options');
  $slug = $options['slug'];
  if ($post->post_type == $slug) {
    $content .= sseller_buyitblock ($post);
  }
  return $content;
}

function sseller_sellablelist_filter ($content) {
  global $post;

  $add = array();
  $options = get_option ('sseller_options');
  $slug = $options['slug'];
  if ($post->post_name == $slug and $post->post_type == 'page') {
    $q = new WP_Query ('post_type=' . $slug);
    while ($q->have_posts()) {
      $p = $q->next_post();
      $add[] = '<div class="sseller_item">';
      $add[] = '<h2><a href="' . get_permalink($p->ID) . '" rel="bookmark">' . $p->post_title . '</a></h2>';
      $add[] = '<p class="post_author"><em>Offered By</em> ';
      $add[] = get_the_author_meta ('display_name', $p->author_id) . '</p>';
      $add[] = '<div class="format_text">';
      $add[] = $p->post_content;
      $add[] = sseller_buyitblock ($p);
      $add[] = '</div>';
      $add[] = '</div>';
    }
  }
  $content .= join ("\n", $add);
  return $content;
}
?>
