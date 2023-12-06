<?php
$pattern = array(
	'id'       => 'youtube_feed_grid',
	'title'       => __( 'Youtube Feed 3 Grid', 'enspire' ),
	'description' => _x( '', 'Block pattern description', 'enspire' ),
	'categories' => array('media_text'),
	'content'     => '<!-- wp:group {"className":"block block-youtube-feed","backgroundColor":"bg_black","textColor":"gray_alabaster"} -->
<div class="wp-block-group block block-youtube-feed has-gray-alabaster-color has-bg-black-background-color has-text-color has-background"><div class="wp-block-group__inner-container"><!-- wp:group {"className":"heading-absolute"} -->
<div class="wp-block-group heading-absolute"><div class="wp-block-group__inner-container"><!-- wp:heading {"className":"block-title","fontSize":"huge"} -->
<h2 class="block-title has-huge-font-size">NEW <br>VIDEOS</h2>
<!-- /wp:heading --></div></div>
<!-- /wp:group -->

<!-- wp:sby/sby-feed-block {"executed":true} /--></div></div>
<!-- /wp:group -->'
);