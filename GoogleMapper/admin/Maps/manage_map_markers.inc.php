<?php
$map_id = $_REQUEST['map_id'];
// need the map:
$mmm_map   = GMap_Factory::getMap($map_id);
// need the markers
$mmm_markers = GMap_Factory::getMarkersByMap($map_id);

?>
<fieldset>
  <legend>Markers for Map: '<?php echo $mmm_map->title ?>'</legend>
  <table width="100%">
    <thead>
      <tr>
        <td rowspan="2">Title</td>
        <td colspan="2" align="center">Actions</td></tr>
      <tr>
        <td width="60" align="center">edit</td>
        <td width="60" align="center">delete</td>
      </tr>
    </thead>
    <tr><td colspan="3"><div style="width:100%;height:0px;margin:-3px 0px 0px 0px;border-bottom:2px solid #666"></div></td></tr>
    <tbody>
<?php
  if ( is_array($mmm_markers) && count($mmm_markers) > 0 ) {
    $alt_counter = 0;
    foreach ( $mmm_markers as $marker ) {
      $marker_title = $marker->title;
      $edit_marker_link  = $page_url . '&state=' . EDIT_MAP_MARKER   . '&map_id='.$map_id.'&marker_id=' . $marker->id;
      $del_marker_link   = $page_url . '&state=' . DELETE_MAP_MARKER . '&map_id='.$map_id.'&marker_id=' . $marker->id;
      $alt_class = ($alt_counter % 2) ? 'class="alternate"' : '';
      echo "
      <tr $alt_class>
        <td>$marker_title</td>
        <td align=\"center\"><a href=\"$edit_marker_link\">edit</a></td>
        <td align=\"center\"><a href=\"$del_marker_link\">delete</a></td>
      </tr>
";
      $alt_counter++;
    }
  }
?>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend>Add Marker</legend>
<?php include('form_marker.inc.php'); ?>
</fieldset>
<fieldset>
  <legend>Preview:</legend>
<?php if ($mmm_map) { ?>
  <iframe src="<?php echo GM_CONFIG_SINGLE_MAP_SRC ?>?map_id=<?php echo $mmm_map->id ?>" width="<?php echo $mmm_map->width ?>" height="<?php echo $mmm_map->height ?>" frameborder="0" scrolling="no"></iframe>
<?php } ?>
</fieldset>