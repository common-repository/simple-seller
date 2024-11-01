<?php
/*
Plugin Name: Simple Seller
Plugin URI: http://www.wpsimpleseller.com
Description: Adds a very simple sell-a-product capability to a WP blog
Version: 1.1
Author: Todd D. Esposito
Author URI: http://www.toddesposito.com
 */

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

$options = get_option ('sseller_options');
$slug = $options['slug']; 

# we can only create the type (et al) if the slug is defined
if ($slug) {
  require ('typedef.php');
  require ('sswidgets.php');
  add_action ('init', 'sseller_typedef');
  add_action ('widgets_init', 'sseller_widgets_init');

  if ( is_admin ()) {
    # Admin pages get lots of extra stuff
    require ('ssadmin.php');
    add_action ('admin_init', 'sseller_admin_init');
    add_action ('show_user_profile', 'sseller_user_admin');
    add_action ('personal_options_update', 'sseller_update_user_options');
    add_action ('manage_posts_custom_column', 'sseller_column_display');
    add_action ('save_post', 'sseller_save_post');
  
    add_filter ('favorite_actions', 'sseller_favorite_actions');
    add_filter ('manage_edit-simplesellable_columns', 'sseller_columndef');
  } else {
    # Non-admin pages get display functions
    require ('functions.php');
    add_filter ('the_content', 'sseller_buyitblock_filter');
    add_filter ('the_content', 'sseller_sellablelist_filter');
  }
}

if (is_admin()) {
  require ('ssconfig.php');
  add_action ('admin_init', 'sseller_settings_init');
  add_action ('admin_menu', 'sseller_admin_menu');
}

register_activation_hook (__FILE__, 'sseller_chain_activation');

if (! function_exists ('sseller_chain_activation')) {
  function sseller_chain_activation () {
    require_once (dirname (__FILE__) . "/ssactivation.php");
    sseller_on_activation();
  }
}
?>
