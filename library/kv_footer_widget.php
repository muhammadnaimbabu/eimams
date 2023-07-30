<?php 

class wpb_widget extends WP_Widget {

	function __construct() {
		parent::__construct('wpb_widget', __('Contact us', 'wpb_widget_domain'), 
				array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo ' <div class="fb-page" data-href="https://www.facebook.com/pages/eimams/849427151806098" data-width="400" data-height="250" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/pages/eimams/849427151806098"><a href="https://www.facebook.com/pages/eimams/849427151806098">eimams</a></blockquote></div>   </div>'; 

		echo $args['after_widget']; 
	}
			

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}	?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} 
// Class wpb_widget ends here

class jsd_wpb_widget extends WP_Widget {

	function __construct() {
		parent::__construct('jsd_wpb_widget', __('special offers', 'jsd_wpb_widget_domain'), 
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'jsd_wpb_widget_domain' ), ) );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo '<div class="twitter-widget"></div>
				<a href="#" class="button twitter-button">Follow us on twitter</a>';
		echo $args['after_widget'];
	}
			

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'jsd_wpb_widget_domain' );
		}?>
		<p> 
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} 
// Class jsd_wpb_widget ends here


// Class new_widget ends here 
class new_wpb_widget extends WP_Widget {

	function __construct() {
		parent::__construct('new_wpb_widget', __('News Letter ', 'new_wpb_widget_domain'), 
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'new_wpb_widget_domain' ), ) );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo "<p>We have job vacancies from all sectors including both, Muslim and mainstream organisations. </p> <p> If you find it difficult to complete the online form due to language barriers or any other issues, please contact us to help you to complete the online form on the following details:</p> 
			<p> <span class='fa fa-mobile fa-2x'> &nbsp;</span>075 0765 3582 &nbsp; &nbsp; &nbsp;   <br> <span class='fa fa-envelope'> </span> &nbsp; info@eimams.com </p> 
			";
		echo $args['after_widget'];
	}
			 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = __( 'New title', 'new_wpb_widget_domain' );
		} ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} 
// Class new_wpb_widget ends here
 
// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
	register_widget( 'jsd_wpb_widget' );
	register_widget( 'new_wpb_widget' );
	
}

add_action( 'widgets_init', 'wpb_load_widget' );
?>