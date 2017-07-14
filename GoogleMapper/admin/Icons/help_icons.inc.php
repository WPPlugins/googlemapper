<?php

?>

<fieldset><legend>Explanation of Icon Fields</legend>
An <span class="bold">Icon</span> consists of two images (the icon image and the shadow image), the point on the image where it anchors to the map coordinates, and the point on the image where the info window pointer will anchor. Rules of thumb for making icon images:
<ul>
  <li>the icon image and shadow images should both be .png's</li>
  <li>both images anchor at the top left corner, so make the shadow image as tall as the icon image</li>
</ul>

<div class="helpItems">
  <div><span class="bold">Title</span>: The title of the icon as it appears in the admin controls</div>
  <div><span class="bold">Icon Image Filename</span>: Filename of the icon image, which is concatenated with the Icon Path (<span class="bold">Configuration</span> -> 'Icon Path') to make the image source. If you want to put a bunch of icon images in a subdirectory of the Icon Path, for example, 'Cuisine', no problem. Just make the filename for the Italian cuisine icon 'Cuisine/italian.png'</div>
  <div><span class="bold">Image Width and Height</span>: Width and height of the icon image.</div>
  <div><span class="bold">Shadow Image Filename</span>: Filename of the shadow image. Explanation of 'Icon Image Filename' above applies to this as well.</div>
  <div><span class="bold">Shadow Image Width and Height</span>: Width and height of the shadow image.</div>
  <div><span class="bold">Anchor Point</span>: The from-the-left-and-down coordinates of the icon image that the location point is anchored. If your image is 10w x 20h, and you want it anchored to the map halfway across the bottom of the image, the anchor point would be '5,20', as in '5 over from the left, 20 down'.</div>
  <div><span class="bold">Info Anchor Point</span>: The from-the-left-and-down coordinates of the icon image that the info window balloon is anchored. If your image is 10w x 20h, and you want the info window pointer to connect to your icon image halfway across the top and down a couple pixels, the info anchor point would be '5,3', as in '5 over from the left, 3 down'.</div>
</div>
</fieldset>
