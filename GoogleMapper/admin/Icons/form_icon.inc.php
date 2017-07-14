<?php
/*
 * form_icon.inc.php - form to add/edit icons
 * 
 * - requires the constants defined in admin/Maps.inc.php
 * - requires classes/GMap_Factory.inc.php (included from admin/Maps.inc.php)
 * 
 */
$icon_id = $_REQUEST['icon_id'];
$form_type = ( $_REQUEST['state'] == EDIT_ICON ) ? 'edit' : 'add';

$icon_attributes = array(
                        'icon_id', 'title',
                        'image_path', 'image_width', 'image_height',
                        'shadow_path', 'shadow_width', 'shadow_height',
                        'anchor_point', 'info_anchor_point'
                       );
$icon_values = array();
foreach ($icon_attributes as $att_name ) {
  $icon_values[$att_name] = FALSE;
}

if ( $form_type == 'edit' && !empty($icon_id) ) {
  $icon = GMap_Factory::getIcon($icon_id);
  $icon_values['icon_id']                 = $icon->id;
  $icon_values['title']                   = $icon->title;
  $icon_values['image_path']              = $icon->image_path;
  $icon_values['image_width']             = $icon->image_width;
  $icon_values['image_height']            = $icon->image_height;
  $icon_values['shadow_path']             = $icon->shadow_path;
  $icon_values['shadow_width']            = $icon->shadow_width;
  $icon_values['shadow_height']           = $icon->shadow_height;
  $icon_values['anchor_point']            = $icon->anchor_point;
  $icon_values['info_anchor_point']     = $icon->info_anchor_point;
}
// find all the .png images in the icons directory
function _find_pngs ($root_path, $middle_path) {
  $png_files = array();
  $path = ( $middle_path ) ? $root_path.'/'.$middle_path : $root_path;

  $dir_handle = opendir($path);
  if ( $dir_handle !== FALSE ) {
    while ( FALSE !== ($readdir = readdir($dir_handle)) ) {
      if ( is_file($path.'/'.$readdir) && preg_match('/\.png$/i', $readdir ) ) {
        $add_file = $readdir;
        if ( $middle_path ) {
          $add_file = $middle_path.'/'.$add_file;
        }
        $png_files[] = $add_file; 
      } elseif ( ($readdir != '.' && $readdir != '..') && is_dir($path.'/'.$readdir ) ) {
        $new_middle_path = ($middle_path) ? $middle_path.'/'.$readdir : $readdir;
        $more_pngs = _find_pngs($root_path, $new_middle_path);
        $png_files = array_merge($png_files, $more_pngs);
      }
    }
  }
  return $png_files;
}

$icon_path = get_option(GM_OPTION_ICON_PATH);
$full_icon_path = $_SERVER['DOCUMENT_ROOT'].$icon_path;
$icon_files = _find_pngs( $full_icon_path, FALSE);

?>

<form name="icon" action="<?php $page_url ?>" method="POST">
<input type="hidden" name="icon_id" value="<?php echo $icon_values['icon_id'] ?>" />
<input type="hidden" name="state" value="<?php echo ($form_type == 'edit') ? EDIT_ICON_SUBMIT : ADD_ICON_SUBMIT ?>" />
<table width="100%">

  <!-- title -->
  <tr>
    <td>Title</td>
    <td><input type="text" size="30" maxlength="255" name="title" value="<?php echo $icon_values['title']?>" /></td>
  </tr>

  <!-- icon image file -->
  <tr>
    <td>Icon Image File</td>
    <td>
      <select name="image_path">
<?php if ( is_array($icon_files) && count($icon_files) > 0 ) {
  foreach ($icon_files as $i_file) {
    $opt = "        <option value=\"$i_file\"";
    if ($icon_values['image_path'] == $i_file) $opt .= ' selected="selected" ';
    $opt .= ">$i_file</option>\n";
    echo $opt;
  }
}
?>
      </select>
    </td>
  </tr>

  <!-- image width, height -->
  <tr>
    <td>Icon Image Width and Height</td>
    <td>
      <input type="text" size="4" maxlength="4" name="image_width" value="<?php echo $icon_values['image_width'] ?>" />w x
      <input type="text" size="4" maxlength="4" name="image_height" value="<?php echo $icon_values['image_height'] ?>" />h
    </td>
  </tr>

  <!-- shadow image file -->
  <tr>
    <td>Shadow Image File</td>
    <td>
      <select name="shadow_path">
<?php if ( is_array($icon_files) && count($icon_files) > 0 ) {
  foreach ($icon_files as $i_file) {
    $opt = "        <option value=\"$i_file\"";
    if ($icon_values['shadow_path'] == $i_file) $opt .= ' selected="selected" ';
    $opt .= ">$i_file</option>\n";
    echo $opt;
  }
}
?>
      </select>
    </td>
  </tr>


  <!-- shadow width, height -->
  <tr>
    <td>Icon Shadow Width and Height</td>
    <td>
      <input type="text" size="4" maxlength="4" name="shadow_width" value="<?php echo $icon_values['shadow_width'] ?>" />w x
      <input type="text" size="4" maxlength="4" name="shadow_height" value="<?php echo $icon_values['shadow_height'] ?>" />h
    </td>
  </tr>

  <!-- anchor point -->
  <?php list($ap_ol,$ap_dt) = explode(',', $icon_values['anchor_point']) ?>
  <tr>
    <td>Anchor Point</td>
    <td>
from left <input type="text" size="3" maxlength="3" name="anchor_point_ol" value="<?php echo $ap_ol?>" />, 
from top <input type="text" size="3" maxlength="3" name="anchor_point_dt" value="<?php echo $ap_dt?>" />
    </td>
  </tr>

  <!-- info window anchor point -->
  <?php list($iap_ol,$iap_dt) = explode(',', $icon_values['info_anchor_point']) ?>
  <tr>
    <td>Info Window Anchor Point</td>
    <td>
from left <input type="text" size="3" maxlength="3" name="info_anchor_point_ol" value="<?php echo $iap_ol?>" />, 
from top <input type="text" size="3" maxlength="3" name="info_anchor_point_dt" value="<?php echo $iap_dt?>" />
    </td>
  </tr>

  <tr>
    <td colspan="2"><div class="submit">
      <input type="submit" name="submit" value="<?php echo ($form_type == 'edit') ? 'Update' : 'Add' ?> Icon" />
      <input type="reset"  name="cancel" value="Cancel" <?php if ($form_type == 'edit') echo ' onclick="window.history.back()"' ?>/>
    </td>
  </tr>

</table></form>
