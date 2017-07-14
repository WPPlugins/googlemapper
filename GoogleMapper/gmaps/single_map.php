<?php
include_once(dirname(__FILE__).'/../../../../wp-config.php');
include_once(dirname(__FILE__).'/../classes/GMap_Factory.inc.php');
$map_id = $_REQUEST['map_id'];
// get the map
$map = GMap_Factory::getMap($map_id);
// everything else depends on whether or not we got the map...
if ($map) {

  // map group and markers...
  $map_group    = GMap_Factory::getMapGroup( $map->map_group_id );
  $markers      = GMap_Factory::getMarkersByMap( $map->id );

  // list of unique marker_type_ids from the markers
  function _get_mt_id ($m) {
    return $m->marker_type_id;
  }
  $marker_type_ids = array_unique( array_map('_get_mt_id', $markers ) );
  $marker_types = GMap_Factory::getMarkerTypes($marker_type_ids);

  // list of unique icon ids from the marker types
  $icon_ids = array();
  if ( is_array($marker_types) && count($marker_types) > 0 ) {
    foreach ($marker_types as $mt) {
      $icon_ids[$mt->sm_icon_id] = TRUE;
      $icon_ids[$mt->lg_icon_id] = TRUE;
    }
  }
  $icon_ids     = array_unique( array_keys($icon_ids) );
  $icons        = GMap_Factory::getIcons( $icon_ids );

  // and of course, need the icon image path
  $image_path   = get_option(GM_OPTION_ICON_PATH);

  // map zoom level
  $lg_zoom = $map->switch_zoom_level;
  $sm_zoom = ($lg_zoom > 0) ? $lg_zoom - 1 : $lg_zoom;

  // map width and height
  $map_width    = ($map->width)  ? $map->width  : $map_group->default_width;
  $map_height   = ($map->height) ? $map->height : $map_group->default_height;

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<?php if ($map) { ?>

    <title>Map: <?php echo $map->title ?></title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo get_option(GM_OPTION_API_KEY) ?>"
            type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[

      // icon definitions
      var icon_definitions = [
<?php
if ( is_array($icons) && count($icons) > 0 ) {
  foreach( $icons as $icon ) {
    $anchor_points = explode(',', $icon->anchor_point);
    $iw_anchor_points  = explode(',', $icon->info_anchor_point);
?>
                                { icon_id:     '<?php echo $icon->id ?>',
                                  img_src:     '<?php echo $image_path.'/'.$icon->image_path ?>',
                                  img_size:    new GSize(<?php echo $icon->image_width ?>,<?php echo $icon->image_height ?>),
                                  shadow_src:  '<?php echo $image_path.'/'.$icon->shadow_path ?>',
                                  shadow_size: new GSize(<?php echo $icon->shadow_width ?>,<?php echo $icon->shadow_height ?>),
                                  anchor:      new GPoint(<?php echo $anchor_points[0].','.$anchor_points[1] ?>),
                                  iw_anchor:   new GPoint(<?php echo $iw_anchor_points[0].','.$iw_anchor_points[1] ?>)
                                },
<?php
  }
}
?>
      ];
      var marker_definitions = [
<?php
if ( is_array($markers) && count($markers) > 0 ) {
  foreach ($markers as $marker) {
    ?>
                                      { marker_id: '<?php echo $marker->id ?>',
                                        title:     '<?php echo $marker->title ?>',
                                        lat:       <?php echo $marker->glat ?>,
                                        lng:       <?php echo $marker->glng ?>,
                                        sm_icon:   <?php echo $marker_types[$marker->marker_type_id]->sm_icon_id ?>,
                                        lg_icon:   <?php echo $marker_types[$marker->marker_type_id]->lg_icon_id ?>,
                                        info_window: '<?php echo str_replace(array("\r\n", "\r", "\n"), "<br />", $marker->info_window) ?>',
                                      },
<?php
  }
}
?>
      ];


      // creates an indexed array of icons from the above definitions
      function createIcons (icon_defs) {
        var icons = new Array();
        for (i in icon_defs) {
          var this_idef = icon_defs[i];
          var icon_id            = this_idef['icon_id'];

          var icon_o = new GIcon();
          icon_o.image            = this_idef['img_src'];
          icon_o.iconSize         = this_idef['img_size'];
          icon_o.shadow           = this_idef['shadow_src'];
          icon_o.shadowSize       = this_idef['shadow_size'];
          icon_o.iconAnchor       = this_idef['anchor'];
          icon_o.infoWindowAnchor = this_idef['iw_anchor'];

          icons[icon_id]= icon_o;
        }
        return icons;
      }

      // Creates a marker at the given point with the given number label, called from load()
      function createMarker(point, icon, html) {
        var marker = new GMarker(point, icon);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }

      function load () {
        if (GBrowserIsCompatible()) {

        // create the icon set
        var icons = createIcons(icon_definitions);

        // create the map
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(<?php echo $map->center_glat ?>, <?php echo $map->center_glng ?>), <?php echo $map->zoom ?>);
<?php if ( $map_group->default_use_ctl_directions == 'on' ) { ?>
        map.addControl(new GLargeMapControl());
<?php } ?>
<?php if ( $map_group->default_use_ctl_zoom == 'on' ) { ?>
        map.addControl(new GMapTypeControl());
<?php } ?>

        // create arrays of small and large markers
        var sm_markers = Array();
        var lg_markers = Array();

        for (i in marker_definitions) {
          var m_def = marker_definitions[i];
          var m_def_point = new GLatLng(m_def.lat, m_def.lng);
          var m_def_sm_icon = icons[ m_def.sm_icon ];
          var m_def_lg_icon = icons[ m_def.lg_icon ];
          sm_markers.push( createMarker( m_def_point, m_def_sm_icon, m_def.info_window ) );
          lg_markers.push( createMarker( m_def_point, m_def_lg_icon, m_def.info_window ) );
        }

        // load the marker manager
        var mgr = new GMarkerManager(map);
        mgr.addMarkers(lg_markers, <?php echo $lg_zoom ?>, 17);
<?php if ( $lg_zoom != 0 ) { ?>
        mgr.addMarkers(sm_markers, 0, <?php echo $sm_zoom ?>);
<?php } ?>
        mgr.refresh();

        }

      }        

    //]]>
    </script>
    <style type="text/css">
body {
  margin: 0px;
  padding: 0px;
}
.infoWindowText { 
                  font-family: Arial, Tahoma, Helvetica, Sans-serif;
                  font-size: 10px;
                  
                  }

.infoWindowText b { 
                    font-size: 12px;
                    font-weight: bold;
                    }
    </style>
  </head>
  <body onload="load()" onunload="GUnload()">
    <div id="map" style="width: <?php echo $map_width ?>px; height: <?php echo $map_height ?>px"></div>
  </body>
</html>

<?php } else { /* if ($map) */ ?>
<title>No Map Found!</title></head>
<body><div style="width:100%; height:100%; text-align: center; vertical-align: middle; font-family: arial, helvetica, sans-serif; font-size: 16px">No Map Found!</div>
<?php } /* if ($map) */ ?>
</body>
</html>
