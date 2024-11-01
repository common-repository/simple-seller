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

class SimpleSellerCategoriesWidget extends WP_Widget {
  function SimpleSellerCategoriesWidget() {
    $this->WP_Widget ('sscatwidget', 'Simple Seller Categories'
                      , array ('classname' => 'sscatwidget'
                              ,'description' => 'Displays the categories for sellable items'
                              )
                      , array ('width' => 300, 'height' => 350, 'id_base' => 'sscatwidget')
                      );
  }
  
  function widget ($args, $instance) {
    extract ($args);
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    echo $before_title . $title . $after_title;

    $ss_options = get_option ('sseller_options');
    $tax = get_terms ($ss_options['category']);
    echo "<ul>";
    echo walk_category_tree ($tax, 99
                            , array( 'echo' => 1
                                   , 'hierarchical' => true
                                   , 'depth' => 99
                                   , 'hide_empty' => 0
                                   , 'show_count' => 1
                                   , 'style' => 'list'
                                   )
                            );
    echo "</ul>";
    echo $after_widget;
  }

  function update ($new_inst, $old_inst) {
    $inst = $old_inst;
    $inst['title'] = strip_tags($new_inst['title']);
    return $inst;
  }

  function form ($instance) {
    $title = esc_attr($instance['title']);
    $titleid = $this->get_field_id('title');
    $titlename = $this->get_field_name('title');
    echo "<p><label>Title: ";
    echo "<input class='widefat' id='$titleid' name='$titlename' type='text' value='$title' />";
    echo "</label></p>"; 
  }
}

function sseller_widgets_init () {
  register_widget ('SimpleSellerCategoriesWidget');
}

?>
