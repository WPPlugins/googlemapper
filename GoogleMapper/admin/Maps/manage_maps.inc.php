<?php
// need an array of maps listed by map group
$mm_groups = GMap_Factory::getAllMapGroups();
$mm_maps   = GMap_Factory::getAllMaps();

?>
<fieldset>
  <legend>Maps</legend>
  <table width="100%">
    <thead>
      <tr>
        <td rowspan="2" width="60">Map ID</td>
        <td rowspan="2">Name</td>
        <td rowspan="2">Map Group</td>
        <td colspan="3" align="center">Actions</td></tr>
      <tr>
        <td width="60" align="center">edit</td>
        <td width="60" align="center">manage markers</td>
        <td width="60" align="center">delete</td>
      </tr>
    </thead>
    <tr><td colspan="6"><div style="width:100%;height:0px;margin: -3px 0px 0px 0px;border-bottom:2px solid #666"></div></td></tr>
    <tbody>
<?php
$alt_counter = 0;
if ( is_array($mm_maps) && count($mm_maps) > 0 ) {
  foreach ( $mm_maps as $m ) {
    $map_group_title = $mm_groups[$m->map_group_id]->title;
    $edit_map_link  = $page_url . '&state=' . EDIT_MAP . '&map_id=' . $m->id;
    $mng_mrkr_link  = $page_url . '&state=' . MANAGE_MAP_MARKERS . '&map_id=' . $m->id;
    $del_map_link   = $page_url . '&state=' . DELETE_MAP . '&map_id=' . $m->id;
?>
      <tr<?php if ( $alt_counter % 2 ) echo ' class="alternate"' ?>>
        <td align="center"><?php echo $m->id  ?></td>
        <td><?php echo $m->title ?></td>
        <td><?php echo $map_group_title ?></td>
        <td align="center"><a href="<?php echo $edit_map_link  ?>">edit</a></td>
        <td align="center"><a href="<?php echo $mng_mrkr_link  ?>">markers</a></td>
        <td align="center"><a href="<?php echo $del_map_link   ?>">delete</a></td>
      </tr>
<?php
    $alt_counter++;
  }
}
?>
    </tbody>
  </table>
</fieldset>
<fieldset>
  <legend>Add Map</legend>
<?php include('form_map.inc.php'); ?>
</fieldset>
