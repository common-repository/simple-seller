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

if (! function_exists ('sseller_on_activation')) {
  function sseller_on_activation () {
    global $wp_roles;
  
    $allcaps = array ( 'read_sellable');
    $sellercaps = array ( 'edit_sellable'
                        , 'edit_sellables'
                        , 'sell_items'
                        );
  
    $admcaps = array ( 'edit_others_sellables'
                     , 'publish_sellables'
                     , 'delete_sellable'
                     );

    $wp_roles->add_role ('seller', 'Seller', array ('read' => true));
    foreach ($allcaps as $cap) {
      $wp_roles->add_cap ('subscriber', $cap, true);
      $wp_roles->add_cap ('seller', $cap, true);
      $wp_roles->add_cap ('administrator', $cap, true);
    }
    foreach ($sellercaps as $cap) {
      $wp_roles->add_cap ('seller', $cap, true);
      $wp_roles->add_cap ('administrator', $cap, true);
    }
    foreach ($admcaps as $cap) {
      $wp_roles->add_cap ('administrator', $cap, true);
    }
  }
}

?>
