<?php

?>
<fieldset>
  <legend>Edit Markers</legend>
<?php include('form_marker.inc.php'); ?>
</fieldset>
<fieldset>
  <legend>Preview:</legend>
<?php if ($map) { ?>
  <iframe src="<?php echo GM_CONFIG_SINGLE_MAP_SRC ?>?map_id=<?php echo $map_id ?>" width="<?php echo $map->width ?>" height="<?php echo $map->height ?>" frameborder="0" scrolling="no"></iframe>
<?php } ?>
</fieldset>