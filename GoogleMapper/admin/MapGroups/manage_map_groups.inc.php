<?php
// need map groups
$mmg_groups       = GMap_Factory::getAllMapGroups();
?>
<fieldset>
  <legend>Map Groups</legend>
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
$alt_counter = 0;
if ( is_array($mmg_groups) && count($mmg_groups) > 0 ) {
  foreach ( $mmg_groups as $mg ) {
    $map_group_title = $mg->title;
    $edit_mg_link  = $page_url . '&state=' . EDIT_MAP_GROUP . '&map_group_id=' . $mg->id;
    $del_mg_link   = $page_url . '&state=' . DELETE_MAP_GROUP . '&map_group_id=' . $mg->id;
?>
      <tr<?php if ( $alt_counter % 2 ) echo ' class="alternate"' ?>>
        <td><?php echo $map_group_title ?></td>
        <td align="center"><a href="<?php echo $edit_mg_link  ?>">edit</a></td>
        <td align="center"><a href="<?php echo $del_mg_link   ?>">delete</a></td>
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
  <legend>Add Map Group</legend>
<?php include('form_map_group.inc.php'); ?>
</fieldset>
