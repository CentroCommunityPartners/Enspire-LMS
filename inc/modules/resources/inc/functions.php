<?php

function ensp_get_resource_thumbnail( $resource ) {
	$resource_layout  = $resource['acf_fc_layout'];
	$extension        = '';
	$background_image = '';
	$icon             = '';

	$thumbnail = get_the_post_thumbnail_url('large');
	$thumbnail = !empty($thumbnail) ? $thumbnail : get_the_post_thumbnail_url();

	if(empty($thumbnail)):

		switch ( $resource_layout ) {
			case 'document':
				if ( isset( $resource['input']['url'] ) ) {
					$path_parts = pathinfo( $resource['input']['url'] );
					$extension  = 'data-extension=".' . $path_parts['extension'] . '"';
				}
				$icon = '<span class="icon-resources post-card__icon"></span>';
				break;
			case 'video':
				$icon         = '<span class="icon-play post-card__icon"></span>';
				$video_poster = $resource['poster_image']['sizes']['medium_large'];
				break;

			case 'external_video':
				$icon  = '<span class="icon-play post-card__icon"></span>';
				$video = $resource['input'];
				if ( preg_match( '/src="([^"]+)/', $video, $matches ) ) {
					$video_url    = $matches[1];
					$video_poster = get_video_thumbnail_uri( $video_url );
				}
				break;

			case 'external_link':
				$icon = '<span class="icon-link post-card__icon"></span>';
				break;
			default:
				$background_image = sprintf( 'style="background-image: url(%s)"', IMAGE_PLACEHOLDER );
		}
	else:
		$background_image = sprintf( 'style="background-image: url(%s)"', $thumbnail );
	endif;

	if ( ! empty( $video_poster ) ) {
		$background_image = sprintf( 'style="background-image: url(%s)"', $video_poster );
	}


	return "<div class='post-thumb' $background_image $extension> $icon </div>";
}

function ensp_get_resource_button( $resource ) {
	$resource_layout = $resource['acf_fc_layout'];

	if ( $resource_layout == 'document' ) {
		$button_label = 'Download';
	} elseif ( $resource_layout == 'video' || $resource_layout == 'external_video' ) {
		$button_label = 'View video';
	} else {
		$button_label = 'View resource';
	}

	return sprintf( '<a  class="btn btn--transparent" href="%s">%s</a>', get_the_permalink(), $button_label );
}