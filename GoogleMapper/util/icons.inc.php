<?php

// takes a marker type, a set of icons (ostensibly with the icons called by the marker), and optional argument 'large' or 'small'
function gm_admin_render_marker_type($marker,$icons,$opt = NULL) {

  $rendered = '';
  $sm_icon_r = $lg_icon_r = FALSE; 

  if ( $opt == 'small' || !$opt ) {
    $sm_icon = $icons[ $marker->sm_icon_id ];
    if ( $sm_icon ) {
      $sm_icon_r = gm_render_icon_with_shadow($sm_icon);
    }
  }

  if ( $opt == 'large' || !$opt ) {
    $lg_icon = $icons[ $marker->lg_icon_id ];
    if ( $lg_icon ) {
      $lg_icon_r = gm_render_icon_with_shadow($lg_icon);
    }
  }
  
  if ( $sm_icon && $lg_icon ) {
    $rendered = $sm_icon_r . ' <div style="width:16px;text-align:center;display:inline">|</div> ' . $lg_icon_r;
  } elseif ( $sm_icon ) {
    $rendered = $sm_icon_r;
  } else {
    $rendered = $lg_icon_r;
  }

  return $rendered;
}

function gm_render_icon_image ($icon) {
  $icon_path = get_option(GM_OPTION_ICON_PATH);
  $icon_r =
    '<img src="' . $icon_path . '/' . $icon->image_path . '" width="'.$icon->image_width. '" height="'.$icon->image_height. '" alt="'.$icon->title.'">'
    . "\n";
  return $icon_r;
}

function gm_render_icon_shadow ($icon) {
  $icon_path = get_option(GM_OPTION_ICON_PATH);
  $icon_r =
    '<img src="' . $icon_path . '/' . $icon->shadow_path . '" width="'.$icon->shadow_width. '" height="'.$icon->shadow_height. '" alt="'.$icon->title.'">'
    . "\n";
  return $icon_r;
}

function gm_render_icon_with_shadow ($icon) {
  $icon_path = get_option(GM_OPTION_ICON_PATH);
  $div_width = ($icon->shadow_width) ? $icon->shadow_width : $icon->image_width;
  $div_width = intval($div_width / 2);
  $icon_r =
    "\n" . '<div style="display:inline;width:'.$div_width.'px; margin: 0px -'.$div_width.'px 0px 0px">'. "\n" .
    '  <img src="' . $icon_path . '/' . $icon->image_path . '" width="'.$icon->image_width. '" height="'.$icon->image_height. '">' . "\n";
  if ( $icon->shadow_path ) { 
    $icon_r .=
      '  <img src="' . $icon_path . '/' . $icon->shadow_path . '" width="'.$icon->shadow_width. '" height="'.$icon->shadow_height. '"'.
      ' style="position:relative;left:-'.$icon->image_width.'px">' . "\n";
  }
  $icon_r .= '</div>' . "\n";
  return $icon_r;
}