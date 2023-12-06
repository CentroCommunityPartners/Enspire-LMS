<?php

class Adlivetech_Social_profile extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Enspire_Social_profile',
			__( 'Social Profiles', 'enspire' ), // Name
			array( 'description' => __( 'Links to Author social media profile', 'enspire' ), )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * @see WP_Widget::widget()
	 *
	 */
	public function widget( $args, $instance ) {

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$facebook  = $instance['facebook'];
		$instagram = $instance['instagram'];
		$youtube   = $instance['youtube'];

		// social profile link
		$instagram_profile = '<a target="_blank" class="instagram" href="' . $instagram . '"><i class="icon-instagram"></i></a>';
		$facebook_profile  = '<a target="_blank" class="facebook" href="' . $facebook . '"><i class="icon-facebook"></i></a>';
		$youtube_profile   = '<a target="_blank" class="youtube" href="' . $youtube . '"><i class="icon-youtube"></i></a>';


		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<div class="social-icons">';
		echo ( ! empty( $instagram ) ) ? $instagram_profile : null;
		echo ( ! empty( $facebook ) ) ? $facebook_profile : null;
		echo ( ! empty( $youtube ) ) ? $youtube_profile : null;
		echo '</div>';

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @see WP_Widget::form()
	 *
	 */
	public function form( $instance ) {
		isset( $instance['title'] ) ? $title = $instance['title'] : null;
		//empty($instance['title']) ? $title = '' : null;

		isset( $instance['facebook'] ) ? $facebook = $instance['facebook'] : null;
		isset( $instance['youtube'] ) ? $youtube = $instance['youtube'] : null;
		isset( $instance['instagram'] ) ? $instagram = $instance['instagram'] : null;
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>"
                   type="text" value="<?php echo esc_attr( $facebook ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>"
                   type="text" value="<?php echo esc_attr( $instagram ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>"
                   type="text" value="<?php echo esc_attr( $youtube ); ?>">
        </p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 * @see WP_Widget::update()
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['title']     = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['facebook']  = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : '';
		$instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ) : '';
		$instance['youtube']   = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : '';

		return $instance;
	}

}

// register Adlivetech_Social_profile widget
function register_Adlivetech_Social_profile() {
	register_widget( 'Adlivetech_Social_profile' );
}

add_action( 'widgets_init', 'register_Adlivetech_Social_profile' );
