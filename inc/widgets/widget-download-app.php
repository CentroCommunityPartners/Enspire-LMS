<?php

class Download_Mobile_App extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Download_Mobile_App',
			__( 'Download Mobile App', 'enspire' ), // Name
			array( 'description' => '', )
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

		$title   = apply_filters( 'widget_title', $instance['title'] );
		$apple   = $instance['apple'];
		$android = $instance['android'];

		$apple_img_src   = THEME_URI . '/assets/dist/img/download-apple.png';
		$android_img_src = THEME_URI . '/assets/dist/img/download-android.png';

		// download app link
		$apple_profile   = '<a target="_blank" class="apple" href="' . $apple . '"><img src="' . $apple_img_src . '" alt="App Store"></a>';
		$android_profile = '<a target="_blank" class="android" href="' . $android . '"><img src="' . $android_img_src . '" alt="Google Play"></a>';

		echo $args['before_widget'];


		echo '<div class="download-apps">';
		if ( ! empty( $title ) ) {
			echo "<strong>" . $title . "</strong>";
		}
		echo ( ! empty( $apple ) ) ? $apple_profile : null;
		echo ( ! empty( $android ) ) ? $android_profile : null;
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
		empty( $instance['title'] ) ? $title = 'Download the App' : null;

		isset( $instance['apple'] ) ? $apple = $instance['apple'] : null;
		isset( $instance['android'] ) ? $android = $instance['android'] : null;
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'apple' ); ?>"><?php _e( 'Apple:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'apple' ); ?>" name="<?php echo $this->get_field_name( 'apple' ); ?>"
                   type="text" value="<?php echo esc_attr( $apple ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'android' ); ?>"><?php _e( 'Android:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'android' ); ?>" name="<?php echo $this->get_field_name( 'android' ); ?>"
                   type="text" value="<?php echo esc_attr( $android ); ?>">
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
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['android'] = ( ! empty( $new_instance['android'] ) ) ? strip_tags( $new_instance['android'] ) : '';
		$instance['apple']   = ( ! empty( $new_instance['apple'] ) ) ? strip_tags( $new_instance['apple'] ) : '';

		return $instance;
	}

}

// register Adlivetech_Social_profile widget
function register_Download_Mobile_App() {
	register_widget( 'Download_Mobile_App' );
}

add_action( 'widgets_init', 'register_Download_Mobile_App' );