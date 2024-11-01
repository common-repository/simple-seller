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

function sseller_admin_init() {

  $options = get_option ('sseller_options');
  $slug = $options['slug']; 
  add_meta_box ('ssellerInfo', 'Item Details', 'sseller_metabox', $slug, 'normal', 'high');
}

function sseller_metabox () {
  global $post;
  $price = get_post_meta($post->ID, 'price', true);
  echo '<label>Price: $</label><input name="price" value="' . $price .'" />';
}

function sseller_save_post () {
  global $post;
  global $post_ID;
  
  if (wp_is_post_autosave ($post_ID))
	  return;  # we don't want autosave to bork us!
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	  return;  # we don't want autosave to bork us!

  $options = get_option ('sseller_options');
  $slug = $options['slug']; 
  
  if ($post->post_type == $slug) {
    $price = $_POST["price"];
    # FIXME: Sanitize the price; must be '0,000,000.00'-ish
    update_post_meta ($post->ID, "price", $price);

    # Add a category to the post based on the plugin config
    $options = get_option ('sseller_options');
    $mycat = $options['category'];
    if ($mycat) {
      # find the cat by name
      $mycatid = get_cat_ID ($mycat);

      # if it doesn't exist, add it
      if (0 == $mycatid)
        $mycatid = wp_create_category ($mycat);

      $mycats = array($mycatid);
      wp_set_post_categories ($post->ID, $mycats);
    }

    # If the author is using PayPal, create/update the button
    if (get_user_meta ($post->post_author, 'sseller_use_paypal', true)) {
      require ('sspaypal.php');
      DoPaypalButtonAction ($post, $price);
    }
    # TODO: If the author is using Google Checkout, create/update the button
  }
}

function sseller_columndef ($columns) {
  $columns = array ( "cb" => "<input type=\"checkbox\" />"
                   , "title" => "Item"
                   , "author" => "Seller"
                   , "price" => "Price"
                   );
  return $columns;
}

function sseller_column_display ($column) {
  global $post;
  if ("price" == $column) {
    echo get_post_meta ($post->ID, "price", true);
  }
}

function sseller_favorite_actions ($actions) {
  $options = get_option ('sseller_options');
  $slug = $options['slug'];
  $actions["post-new.php?post_type=$slug"] = array ('Add Item For Sale', 'sell_items');
  return $actions;
}

/****************************************************************************************
* User Settings
****************************************************************************************/

function sseller_user_admin () {
  global $current_user;
  if (current_user_can ('sell_items')) {
    $sseller_use_paypal = get_user_meta ($current_user->ID, 'sseller_use_paypal', true); 
    $sseller_paypal_name = get_user_meta ($current_user->ID, 'sseller_paypal_name', true); 
    $sseller_paypal_pwd = get_user_meta ($current_user->ID, 'sseller_paypal_pwd', true); 
    $sseller_paypal_sig = get_user_meta ($current_user->ID, 'sseller_paypal_sig', true); 
?>
  <h3>Simple Seller Configuration</h3>
  <table class="form-table">
    <tr>
      <th scope="row">Paypal Account</th>
      <td><label for="sseller_use_paypal">
        <input name="sseller_use_paypal" type="checkbox" <?php if ($sseller_use_paypal) echo "checked" ?>
          value="1" /><em> Check here to offer PayPal as a payment option.</em></label>
      </td>
    </tr>
    <tr><th scope="row">Paypal User Name</th><td><label for="sseller_paypal_name">
      <input name="sseller_paypal_name" type="input" size="60" value="<?php echo $sseller_paypal_name ?>"/>
      <em> Your PayPal API User Name.</em></label></td></tr>
    <tr><th scope="row">Paypal Password</th><td><label for="sseller_paypal_pwd">
      <input name="sseller_paypal_pwd" type="input" size="60" value="<?php echo $sseller_paypal_pwd ?>"/>
      <em> Your PayPal API Password.</em></label></td></tr>
    <tr><th scope="row">Paypal Signature</th><td><label for="sseller_paypal_sig">
      <input name="sseller_paypal_sig" type="input" size="60" value="<?php echo $sseller_paypal_sig ?>"/>
      <em> Your PayPal API Signature.</em></label></td></tr>
  </table>
<?php  
  } else {
?>
  <p><em>You are not currently able to create items for sale.
  Contact the admin if you want to change that.</em></p>
<?php
  }
} 

function sseller_update_user_options () {
  global $current_user;
  update_user_meta ($current_user->ID, 'sseller_use_paypal', $_POST['sseller_use_paypal']);
  update_user_meta ($current_user->ID, 'sseller_paypal_name', $_POST['sseller_paypal_name']);
  update_user_meta ($current_user->ID, 'sseller_paypal_pwd', $_POST['sseller_paypal_pwd']);
  update_user_meta ($current_user->ID, 'sseller_paypal_sig', $_POST['sseller_paypal_sig']);
}
?>
