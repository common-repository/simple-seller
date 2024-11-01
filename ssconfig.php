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

function sseller_settings_init() {
  register_setting ('sseller_options', 'sseller_options');

  $default_opts = array (
      'slug' => ''
    , 'category' => 'products'
    , 'disclaimer' => ''
  );

  add_option ('sseller_options', $default_opts);

  add_settings_section ('sseller_sect_slug', 'Simple Seller Slug',
      'sseller_gensect_slug', 'simpleseller');
  add_settings_field ('sseller_opt_slug', 'Slug', 'sseller_disp_slug',
      'simpleseller', 'sseller_sect_slug');

   add_settings_section ('sseller_sect_category', 'Simple Seller Category',
      'sseller_gensect_category', 'simpleseller');
  add_settings_field ('sseller_opt_category', 'Category', 'sseller_disp_category',
      'simpleseller', 'sseller_sect_category');

  add_settings_section ('sseller_sect_disclaimer', 'Simple Seller Disclaimer',
      'sseller_gensect_disclaimer', 'simpleseller');
  add_settings_field ('sseller_opt_disclaimer', 'Disclaimer', 'sseller_disp_disclaimer',
      'simpleseller', 'sseller_sect_disclaimer');
}

function sseller_gensect_slug() {
  echo "This is the base part of the URL under which products will appear.<br/>";
  $options = get_option ('sseller_options');
  if ($options['slug']) {
    # in future, we'll allow a change
    echo "<em>This is already set, and can't be changed.</em>";
  } else {
    echo "<strong>You <em>MUST</em> make an entry here before Simple Seller will operate</strong>. ";
    echo "Choose your slug carefully, as it is difficult to change.";
  }
}

function sseller_disp_slug() {
  $options = get_option ('sseller_options');
  if ($options['slug']) {
    echo "<span style='width: 40em; border: 1px gray;'>{$options['slug']}</span>";
    echo "<input name='sseller_options[slug]' ";
    echo "size='40' type='hidden' value='{$options['slug']}' ";
  } else {
    echo "<input name='sseller_options[slug]' ";
    echo "size='40' type='text' value='{$options['slug']}' ";
  }
  echo "/>";
}

function sseller_gensect_category() {
  echo "<p>All products posted for sale by Simple Seller will be automatically placed into this category.</p>";
}
function sseller_disp_category() {
  $options = get_option ('sseller_options');
  echo "<input name='sseller_options[category]' ";
  echo "size='40' type='text' value='{$options['category']}' />";
}

function sseller_gensect_disclaimer() {
  echo "<p>Product posts will have this disclaimer added to them.  Leave it blank to disable the disclaimer.</p>";
}
function sseller_disp_disclaimer() {
  $options = get_option ('sseller_options');
  echo "<textarea name='sseller_options[disclaimer]' ";
  echo "rows='5' cols='50'>{$options['disclaimer']}</textarea>";
}

function sseller_admin_menu() {
  add_options_page ('Simple Seller Settings', 'Simple Seller', 'manage_options', 
    __FILE__, 'sseller_admin_optionsform', 99);
}

function sseller_admin_optionsform () {
?>
<div class="wrap">
  <h2>Simple Seller Configuration Options</h2>
  <form method="post" action="options.php">  <!-- options.php -->
    <?php settings_fields ('sseller_options'); ?>
    <?php do_settings_sections('simpleseller'); ?>
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </form>
</div>

<?php 
}

?>
