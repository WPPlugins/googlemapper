<?php
/*
 * form_marker_type.inc.php - form for editing/adding marker types
 *
 * - requires either that $marker_type_id is set, or passed into the request
 * - requires $mimt_icons to populate the icon dropdowns
 *
 */
if ( !$marker_type_id ) $marker_type_id = $_REQUEST['marker_type_id'];
$form_type = ( $_REQUEST['state'] == EDIT_MARKER_TYPE ) ? 'edit' : 'add';
$mt_attributes = array( 'marker_type_id', 'title', 'sm_icon_id', 'lg_icon_id' );
$mt_values = array();
foreach ($mt_attributes as $att_name ) {
  $mt_values[$att_name] = FALSE;
}

if ( !empty($marker_type_id) && $form_type == 'edit' ) {
  $marker_type = GMap_Factory::getMarkerType($marker_type_id);
  $mt_values['marker_type_id']             = $marker_type->id;
  $mt_values['title']                      = $marker_type->title;
  $mt_values['sm_icon_id']                 = $marker_type->sm_icon_id;
  $mt_values['lg_icon_id']                 = $marker_type->lg_icon_id;
}

if ( $form_type != 'edit' || !empty($marker_type_id) ) {
?>

<form name="marker_type" action="<?php $page_url ?>" method="POST">
<input type="hidden" name="marker_type_id" value="<?php echo $mt_values['marker_type_id'] ?>" />
<input type="hidden" name="state" value="<?php echo ($form_type == 'edit') ? EDIT_MARKER_TYPE_SUBMIT : ADD_MARKER_TYPE_SUBMIT ?>" />
<table width="100%">

  <!-- title -->
  <tr>
    <td>Title</td>
    <td><input type="text" size="30" maxlength="255" name="title" value="<?php echo $mt_values['title']?>" /></td>
  </tr>

  <tr>
    <td>Small Icon</td>
    <td> 
<?php if ( is_array($mimt_icons) && count($mimt_icons) > 0 ) { ?>
      <select name="sm_icon_id">
<?php    foreach ($mimt_icons as $icon_id => $icon) {
  $opt = '        <option value="'.$icon_id.'"';
  if ( $mt_values['sm_icon_id'] == $icon_id ) $opt.= ' selected="selected" ';
  $opt .= '>'.$icon->title."</option>\n";
  echo $opt;
         }
      } else { ?>
    <div>No Icons to select from!</div>
<?php } ?>
   </td>
  </tr>

  <tr>
    <td>Large Icon</td>
    <td> 
<?php if ( is_array($mimt_icons) && count($mimt_icons) > 0 ) { ?>
      <select name="lg_icon_id">
<?php    foreach ($mimt_icons as $icon_id => $icon) {
  $opt = '        <option value="'.$icon_id.'"';
  if ( $mt_values['lg_icon_id'] == $icon_id ) $opt.= ' selected="selected" ';
  $opt .= '>'.$icon->title."</option>\n";
  echo $opt;
         }
      } else { ?>
      <div>No Icons to select from!</div>
<?php } ?>
   </td>
  </tr>

  <tr>
    <td colspan="2"><div class="submit">
      <input type="submit" name="submit" value="<?php echo ($form_type == 'edit') ? 'Update' : 'Add' ?> Marker Type" />
      <input type="reset"  name="cancel" value="Cancel" <?php if ($form_type == 'edit') echo ' onclick="window.history.back()"'?>/>
    </td>
  </tr>

</table></form>
<?php
      } else {
?>
<div id="message" class="update fade">No Marker Type to Edit!</div>
<?php
   }
?>