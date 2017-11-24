<?php
/*
Plugin Name: PostType Widget
Plugin URI:
Description: It displays the date in the format of the last "N" posts with a given PostType (date format, number of posts, description and PostType set in the widget settings).
Version: 1.1
Author: Dmitry Ivanenko	
Text Domain: PostType-Widget
Domain Path: /lang/
License: GPL2
Copyright 10.10.2016 Dmitry Ivanenko  (email: ivanenko.ds@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 
 


*/

//Класс нового виджета
    class trueTopPostsWidget extends WP_Widget {

	/*
	 * создание виджета
	 */
	function __construct() {
		parent::__construct(
			'true_top_widget', 
			'PostType Widget', 
			array( 'description' => __('It allows you to display the specified date format posts. ', 'PostType-Widget' ) )
			);
	}

	/*
	 Отображение виджета
	 */
	 public function widget( $args, $instance ) {
	 	


	 	$title = apply_filters( 'widget_title', $instance['title'] ); 
	 	$posts_per_page = $instance['posts_per_page'];
	 	$lengcontent = $instance['lengcontent'];
	 	$ctposttyp = $instance['ctposttyp'];
	 	$datetyp = $instance['datetyp'];
	 	$vievtitelpost = $instance['vievtitelpost'];
	 	$vievmore = $instance['vievmore'];
	 	echo $args['before_widget'];

	 	if ( ! empty( $title ) )
	 		echo $args['before_title'] . $title . $args['after_title'];

	 	$q = new WP_Query("post_type=$ctposttyp&posts_per_page=$posts_per_page&orderby=comment_count");

	 	if( $q->have_posts() ): ?>

	 	<ul class="ptwds-posts">

	 		<?php while( $q->have_posts() ): $q->the_post();?>

	 			<li>

	 				<?php if ($vievtitelpost == true){?>
	 				<h3><a href="<?php the_permalink(); ?>"> <?php get_the_title() ? the_title() : the_ID(); ?> </a></h3>
	 				<?php }?>


	 				<?php if ( $datetyp == 'style1' ) {

	 					echo '<h3">'.the_time('j F Y');'</h3>';
	 					if ($vievmore == true){ echo '<p>';
	 					ptwdsShortDesc(get_the_excerpt(), $lengcontent);
	 					echo '</p>';
	 				}

	 			}
	 			elseif ( $datetyp == 'style2' ) {

	 				echo '<h3>'.the_time('m Y');'</h3>';
	 				if ($vievmore == true){ echo '<p>';
	 				ptwdsShortDesc(get_the_excerpt(), $lengcontent);
	 				echo '</p>';
	 			}

	 		} 

	 		elseif ( $datetyp == 'style3' ) {

	 			echo '<h3>'.the_time('j F');'</h3>';
	 			if ($vievmore == true){ echo '<p>';
	 			ptwdsShortDesc(get_the_excerpt(), $lengcontent);
	 			echo '</p>';
	 		}
	 	} 
	 	else if ( $datetyp == 'style4' ) {

	 		echo '<h3>'.the_time('d m y');'</h3>';
	 		if ($vievmore == true){ echo '<p>';
	 		ptwdsShortDesc(get_the_excerpt(), $lengcontent);
	 		echo '</p>';
	 	}
	 } 
	 ?>


	</li>
<?php endwhile;	?>
</ul>

<?php endif;
wp_reset_postdata();

echo $args['after_widget'];

}

	/*
	 Отображение полей настройки виджета
	 */
	 public function form( $instance ) {
	 	if ( isset( $instance[ 'title' ] ) ) {
	 		$title = $instance[ 'title' ];
	 	}
	 	if ( isset( $instance[ 'posts_per_page' ] ) ) {
	 		$posts_per_page = $instance[ 'posts_per_page' ];
	 	}
	 	if ( isset( $instance[ 'lengcontent' ] ) ) {
	 		$lengcontent = $instance[ 'lengcontent' ];
	 	}
	 	$ctposttyp = $instance['ctposttyp'];
	 	$vievtitelpost = isset( $instance['vievtitelpost'] ) ? (bool) $instance['vievtitelpost'] : false;
	 	$vievmore = isset( $instance['vievmore'] ) ? (bool) $instance['vievmore'] : false;
	 	$datetyp = $instance['datetyp'];

	 	?>

	 	<p>
	 		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'PostType-Widget');?></label> 
	 		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	 	</p>
	 	<p>
	 		<label for="<?php echo $this->get_field_id( 'ctposttyp' ); ?>"><?php _e('Select the type of post', 'PostType-Widget');?></label> 

	 		<select name="<?php echo esc_attr( $this->get_field_name( 'ctposttyp' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'ctposttyp' ) ); ?>" class="widefat" type="text">		
	 			<?php

	 			$ctpost = get_post_types('','names');

	 			foreach ($ctpost as $ctpos) {
	 				echo '<option value="' . $ctpos . '" id="' . $ctpos . '"', $ctposttyp == $ctpos ? ' selected="selected"' : '', '>', $ctpos, '</option>';

	 			}

	 			?>

	 		</select>

	 	</p>

	 	<p>

	 		<label for="<?php echo $this->get_field_id('vievtitelpost'); ?>"><?php _e('Show title?', 'PostType-Widget');?></label>
	 		<input class="checkbox" type="checkbox"<?php checked( $vievtitelpost ); ?> id="<?php echo $this->get_field_id( 'vievtitelpost' ); ?>" name="<?php echo $this->get_field_name( 'vievtitelpost' ); ?>" />

	 	</p>
	 	<p>

	 		<label for="<?php echo $this->get_field_id('vievmore'); ?>"><?php _e('Show description?', 'PostType-Widget');?></label>
	 		<input class="checkbox" type="checkbox"<?php checked( $vievmore ); ?> id="<?php echo $this->get_field_id( 'vievmore' ); ?>" name="<?php echo $this->get_field_name( 'vievmore' ); ?>" />

	 	</p>

	 	<p>
	 		<label for="<?php echo $this->get_field_id( 'datetyp' ); ?>"><?php _e('Select the type of date display:', 'PostType-Widget')?></label> 
	 		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'datetyp' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'datetyp' ) ); ?>"  type="text">

	 			<option value='style1'<?php echo ($datetyp =='style1')?'selected':''; ?>> <?php _e('The first type', 'PostType-Widget');?></option>
	 			<option value='style2'<?php echo ($datetyp=='style2')?'selected':''; ?>> <?php _e('The second type', 'PostType-Widget');?></option>
	 			<option value='style3'<?php echo ($datetyp=='style3')?'selected':''; ?>> <?php _e('The third type', 'PostType-Widget');?></option>
	 			<option value='style4'<?php echo ($datetyp=='style4')?'selected':''; ?>> <?php _e('The fourth type', 'PostType-Widget');?></option>

	 		</select>

	 	</p>

	 	<p>
	 		<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Number of posts:', 'PostType-Widget');?></label> 
	 		<input id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo ($posts_per_page) ? esc_attr( $posts_per_page ) : '5'; ?>" size="3" />
	 	</p>

	 	<p>
	 		<label for="<?php echo $this->get_field_id( 'lengcontent' ); ?>"><?php _e('Description length (number of words):', 'PostType-Widget');?></label> 
	 		<input id="<?php echo $this->get_field_id( 'lengcontent' ); ?>" name="<?php echo $this->get_field_name( 'lengcontent' ); ?>" type="text" value="<?php echo ($lengcontent) ? esc_attr( $lengcontent ) : '5'; ?>" size="3" />
	 	</p>

	 	<?php 
	 }

	/*
	 Сохранение настроек виджета в панели настроек
	 */
	 public function update( $new_instance, $old_instance ) {
	 	$instance = $old_instance;
	 	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	 	$instance['posts_per_page'] = ( is_numeric( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : '5'; 
	 	$instance['lengcontent'] = ( is_numeric( $new_instance['lengcontent'] ) ) ? $new_instance['lengcontent'] : '5';

	 	$instance['vievtitelpost'] = isset( $new_instance['vievtitelpost'] ) ? (bool) $new_instance['vievtitelpost'] : false;
	 	$instance['vievmore'] = isset( $new_instance['vievmore'] ) ? (bool) $new_instance['vievmore'] : false;
	 	$instance['ctposttyp'] = $new_instance['ctposttyp'];
	 	$instance['datetyp'] = $new_instance['datetyp'];

	 	return $instance;
	 }
	}

	function ptwdsShortDesc($string, $word_limit) {
		$words = explode(' ', $string, ($word_limit + 1));
		if (count($words) > $word_limit)
			array_pop($words);
		echo implode(' ', $words).' ...';
	}


/*
 * регистрация виджета
 */

function true_top_posts_widget_load() {
	register_widget( 'trueTopPostsWidget' );
	load_plugin_textdomain( 'PostType-Widget', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'widgets_init', 'true_top_posts_widget_load' );
