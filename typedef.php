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

function sseller_typedef() {
  global $wp_rewrite;

  $options = get_option ('sseller_options');
  $slug = $options['slug']; 
  $cat = $options['category'];
  if (! $options['pagecreated']) {
    # we need to create a page to display our list of products
    $post = array( 'post_title' => ucfirst ($slug)
                 , 'post_content' => "This is a placeholder.  Please add some appropriate overview content here."
                 , 'post_status' => 'draft'
                 , 'post_type' => 'page'
                 );
    $options['pagecreated'] = wp_insert_post ($post);
    update_option ('sseller_options', $options);
  }

  register_post_type ($slug,
    array ( 'label' => 'Sellable Items'
          , 'singular_label' => 'Sellable Item'
          , 'description' => 'Any item offered for sale.'
          , 'public' => true
          , 'show_ui' => true
          , 'capability_type' => 'sellable'
          , 'hierarchical' => true
          , 'menu_position' => 5
          , 'supports' => array ('title'
                                , 'editor'
                                , 'author'
                                , 'comments'
                                )
          ));
  if ($cat) {
    register_taxonomy ( $cat, $slug
                      , array ( 'hierarchical' => true
                              , 'label' => $cat
                              , 'query_var' => true
                              , 'rewrite' => true)
                      );
    register_taxonomy_for_object_type ($cat, $slug);
  }
  $wp_rewrite->flush_rules(false);
}
?>
