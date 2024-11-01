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

function DoPaypalButtonAction ($post, $price) {
  $auth_ppname = get_user_meta ($post->post_author, 'sseller_paypal_name', true);
  $auth_pppwd = get_user_meta ($post->post_author, 'sseller_paypal_pwd', true);
  $auth_ppsig = get_user_meta ($post->post_author, 'sseller_paypal_sig', true);

  $ppbuttonid = get_post_meta ($post->ID, "paypal_buttonid", true);

  $paypal_params = array ( "USER" => $auth_ppname
                         , "PWD" => $auth_pppwd
                         , "SIGNATURE" => $auth_ppsig
                         , "VERSION" => "56.0"
                         );

  if ($ppbuttonid) {
    # if we do have one, update the button
    $paypal_params['METHOD'] = 'BMUpdateButton';
    $paypal_params['HOSTEDBUTTONID'] = $ppbuttonid;
  } else {
    # if we don't have one, create a button
    $paypal_params['METHOD'] = 'BMCreateButton';
    $paypal_params['BUTTONIMAGE'] = 'CC';
  }
  $paypal_params['BUTTONTYPE'] = 'BUYNOW';
  $paypal_params['BUTTONCODE'] = 'HOSTED';
  $paypal_params['L_BUTTONVAR1'] = "item_name={$post->post_title}";
  $paypal_params['L_BUTTONVAR2'] = "amount=$price";
  
#  $endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
  $endpoint = 'https://api-3t.paypal.com/nvp';
  $resp = wp_remote_post ($endpoint, array ('sslverify' => false, 'timeout' => 30
                            , 'body' => $paypal_params));
  if (is_wp_error ($resp)) {
    # FIXME: error back from HTTP request -- what to do, what to do?
  } else {
    $body = array();
    parse_str (wp_remote_retrieve_body ($resp), $body);

    if ($body['ACK'] == 'Success') {
      update_post_meta ($post->ID, "paypal_buttonid", $body['HOSTEDBUTTONID']);
      update_post_meta ($post->ID, "paypal_buttoncode", $body['WEBSITECODE']);
    } else {
      # FIXME: error back from paypal -- what to do, what to do?
    }
  }
}

?>
