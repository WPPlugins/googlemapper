<?php
// GoogleMapper configuration menu
include_once(dirname(__FILE__)."/../defines.inc.php");

$config_keys = array(GM_OPTION_VERSION, GM_OPTION_API_KEY, GM_OPTION_WIPEALL, GM_OPTION_ICON_PATH);

$configs = array();
foreach ($config_keys as $key) {
  $configs[$key] = get_option($key);
}

// if this is a post, need to update configs
$updated = 0;
$status_msg = FALSE;
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
  foreach ($config_keys as $key ) {
    $configs[$key] = $wpdb->escape( stripslashes( trim( $_POST[$key] ) ) );
  }
  // if there were updates, update 'em
  foreach ($config_keys as $key) {
    update_option($key, $configs[$key]);
  }
  // set a message
  $status_msg = "Configuration Updated";
}

// should have totally updated keys now
?>

<style type="text/css">
fieldset {
  border: 1px solid #ccc;
  padding: 5px;
  margin-bottom: 15px;
}
legend {
  font-family: "Lucida Sans", "Arial", "Trebuchet", "Tahoma", "Helvetica";
  font-size: 14px;
  font-weight: bold;
}
.smallbold {
  font-size: 12px;
  font-weight:bold;
  font-family:'Lucida Sans', 'Arial','Helvetica';
}
.small {
  font-size: 12px;
  font-weight: normal;
  font-family:'Lucida Sans', 'Arial','Helvetica';
}
.configDesc {
  margin-bottom: 12px;
  border-bottom: 1px solid #CCC;
}
</style>

<div class="wrap">
<h2>Google Mapper Configuration</h2>
<?php if ($status_msg != FALSE) { ?>
  <div id="message" class="updated fade"><p><?php echo $status_msg ?></p></div>
  <?php } ?>

  <form name="GMConfig" method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <!-- global configs -->
    <fieldset>
      <legend>Global Configs</legend>
      <table width="100%" class="widefat" cellspacing="2" cellpadding="5">
        <tr valign="bottom">
          <td width="200" class="smallbold">Version</td>
          <td><?php echo get_option(GM_OPTION_VERSION) ?></td>
        </tr>
        <tr valign="bottom">
          <td><span class="smallbold"> Wipe tables on deactivation</span></td>
          <td><input type="checkbox" name="<?php echo GM_OPTION_WIPEALL ?>" <?php echo ($configs[GM_OPTION_WIPEALL] == 'on') ? 'checked="checked" ' : '' ?>/></td>
        </tr>
        <tr><td colspan="2"><div class="small configDesc">If this is checked, deactivating the plugin will drop all the database tables.</div></td></tr>

        <tr valign="bottom">
          <td><span class="smallbold"> Google API Key</span></td>
          <td><input type="text" name="<?php echo GM_OPTION_API_KEY ?>" size="90" maxlength="255" value="<?php echo $configs[GM_OPTION_API_KEY] ?>" /></td>
        </tr>
        <tr><td colspan="2"><div class="small configDesc">Required to use Google Maps api, visit the <a href="http://www.google.com/apis/maps/signup.html">Google API signup page</a> to get one</div></td></tr>

        <tr valign="bottom">
          <td><span class="smallbold"> Icon Path</span></td>
          <td><input type="text" name="<?php echo GM_OPTION_ICON_PATH ?>" size="90" maxlength="255" value="<?php echo $configs[GM_OPTION_ICON_PATH] ?>" /></td>
        </tr>
        <tr><td colspan="2"><div class="small configDesc">Path to icons (relative to document root)</div></td></tr>

      </table>
    </fieldset>
    <p class="submit">
      <input type="submit" name="Submit" value="Update Configs" />
      <input type="reset"  name="Cancel" value="Cancel" />
    </p>

  </form>
</div>
