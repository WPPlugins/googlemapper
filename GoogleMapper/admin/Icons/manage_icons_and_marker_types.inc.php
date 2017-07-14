<?php
// need all icons
$mimt_icons        = GMap_Factory::getAllIcons();
$mimt_marker_types = GMap_Factory::getAllMarkerTypes();
?>
<fieldset>
  <legend>Icons</legend>
  <table width="100%">
    <thead>
      <tr>
        <td rowspan="2">Name</td>
        <td rowspan="2" align="center" width="100">Image</td>
        <td rowspan="2" align="center" width="100">Shadow</td>
        <td colspan="2" align="center">Actions</td>
      </tr>
      <tr>
        <td width="60" align="center">edit</td>
        <td width="60" align="center">delete</td>
      </tr>
    </thead>
    <tr><td colspan="5"><div style="width:100%;height:0px;margin:-3px 0px 0px 0px;border-bottom:2px solid #666"></div></td></tr>
    <tbody>
<?php
if ( is_array($mimt_icons) && count($mimt_icons) > 0 ) {
  foreach ( $mimt_icons as $icon ) {
    $icon_title = $icon->title;
    $image  = gm_render_icon_image($icon);
    $shadow = gm_render_icon_shadow($icon);
    $edit_icon_link  = $page_url . '&state=' . EDIT_ICON . '&icon_id=' . $icon->id;
    $del_icon_link   = $page_url . '&state=' . DELETE_ICON . '&icon_id=' . $icon->id;
?>
      <tr>
        <td><?php echo $icon->title ?></td>
        <td align="center"><?php echo $image ?></td>
        <td align="center"><?php echo $shadow ?></td>
        <td align="center"><a href="<?php echo $edit_icon_link  ?>">edit</a></td>
        <td align="center"><a href="<?php echo $del_icon_link   ?>">delete</a></td>
      </tr>
<?php
    }
  }
?>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend>Add Icon</legend>
<?php include('form_icon.inc.php'); ?>
</fieldset>

<fieldset>
  <legend>Marker Types</legend>
  <table width="100%">
    <thead>
      <tr><td rowspan="2">Title</td>
          <td width="100" rowspan="2" align="center">Small Icon</td>
          <td width="100" rowspan="2" align="center">Large Icon</td>
          <td colspan="2" align="center">Actions</td>
      </tr>
      <tr>
        <td width="60" align="center">edit</td>
        <td width="60" align="center">delete</td>
      </tr>
    </thead>
    <tr><td colspan="5"><div style="width:100%;height:0px;margin:-3px 0px 0px 0px;border-bottom:2px solid #666"></div></td></tr>
    <tbody>
<?php
if ( is_array($mimt_marker_types) && count($mimt_marker_types) > 0 ) {
  foreach ( $mimt_marker_types as $mt_id => $mt) {
    $marker_type_title = $mt->title;
    $sm_icon_r = gm_admin_render_marker_type($mt, $mimt_icons, 'small');
    $lg_icon_r = gm_admin_render_marker_type($mt, $mimt_icons, 'large');
    
    $edit_mt_link  = $page_url . '&state=' . EDIT_MARKER_TYPE . '&marker_type_id=' . $mt_id;
    $del_mt_link   = $page_url . '&state=' . DELETE_MARKER_TYPE. '&marker_type_id=' . $mt_id;
?>
      <tr valign="bottom">
        <td><?php echo $marker_type_title ?></td>
        <td align="center"><?php echo $sm_icon_r ?></td>
        <td align="center"><?php echo $lg_icon_r ?></td>
        <td align="center"><a href="<?php echo $edit_mt_link  ?>">edit</a></td>
        <td align="center"><a href="<?php echo $del_mt_link   ?>">delete</a></td>
      </tr>
<?php
  }
}
?>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend>Add Marker Type</legend>
<?php include('form_marker_type.inc.php'); ?>
</fieldset>

<?php include('help_icons.inc.php'); ?>
<?php include('help_marker_types.inc.php'); ?>
