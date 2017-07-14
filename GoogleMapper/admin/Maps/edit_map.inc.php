<?php
$mm_groups = GMap_Factory::getAllMapGroups();
$mm_map_id = $_REQUEST['map_id'];
if ( $mm_map_id ) { $mm_map = GMap_Factory::getMap($mm_map_id); }
?>

<fieldset>
  <legend>Edit Map</legend>
<?php include('form_map.inc.php'); ?>
</fieldset>
<fieldset>
  <legend>Preview:</legend>
<?php if ($mm_map_id) { ?>
  <iframe src="<?php echo GM_CONFIG_SINGLE_MAP_SRC ?>?map_id=<?php echo $mm_map_id ?>" width="<?php echo $mm_map->width ?>" height="<?php echo $mm_map->height ?>" frameborder="0" scrolling="no"></iframe>
<?php } ?>
</fieldset>