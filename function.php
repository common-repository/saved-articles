<?php 
//Виводимо посилання для зберігання запису
function ky_saved_articles_content($content){
	if(!is_user_logged_in() || !is_single()) return $content;
	$img_address=esc_url(plugins_url('/img/loader.gif', __FILE__ ));
	 global $post;
	 	if (ky_artikles_is_seved($post -> ID)){
			return '<p class="ky_saved_articles_link"><span class="ky_saved_articles_hide"><img src="'.$img_address.'" class="ky_saved_articles_img"></span> <a  href="#" data-action="delete" style=" box-shadow: none;">delete from saved</a></p>'.$content;
	 	}
	 	return '<p class="ky_saved_articles_link"><span class="ky_saved_articles_hide"><img src="'.$img_address.'" class="ky_saved_articles_img"></span> <a  href="#" data-action="add" style=" box-shadow: none;">read later</a></p>'.$content;
}

//підключаємо стилі і скріпти до користувацької частини
function ky_saved_articles_scripts(){
	if(!is_user_logged_in()) return;
		wp_enqueue_style( 'ky_saved_articles_style', esc_url(plugins_url('/css/ky_saved_articles_style.css', __FILE__)));
		wp_enqueue_script( 'ky_saved_articles_script', esc_url(plugins_url('/js/ky_saved_articles_script.js', __FILE__)), array('jquery'), null, true);
		global $post;
		wp_localize_script( 'ky_saved_articles_script', 'ky_sav_art', ['url'=>admin_url('admin-ajax.php'), 'nonce'=>wp_create_nonce( 'ky_saved_articles' ), 'postId' => $post->ID] );
}
//добавляє або видаляє запис в БД
function wp_ajax_ky_saved_articles_record(){
	if (!wp_verify_nonce(sanitize_text_field ($_POST['security']), sanitize_key('ky_saved_articles') )) {
		wp_die(esc_html('not safely'));
	}
	$img_address=esc_url(plugins_url('/img/loader.gif', __FILE__ ));
	$post_id=sanitize_text_field ((int)$_POST['postId']);
	$act=sanitize_text_field ($_POST['srt']);
	$user_id=wp_get_current_user();
		if ($act=='add') {
		if (ky_artikles_is_seved($post_id)) wp_die();
		if(add_user_meta(sanitize_text_field($user_id->ID), sanitize_key('ky_saved_articles'), $post_id )){
			wp_die(esc_html('added'));
		}
		wp_die(esc_html('not added'));
		}elseif ($act=='delete') {
			if (!ky_artikles_is_seved($post_id)) wp_die();
				if(delete_user_meta( sanitize_text_field($user_id->ID), sanitize_key('ky_saved_articles'), $post_id )){
					wp_die(esc_html('delete'));
		}
		wp_die(esc_html('not delete'));
		}
}

//визначаємо які пости вже збережені
function ky_artikles_is_seved($post_id){
	$user_id=wp_get_current_user();
	$saved = get_user_meta($user_id->ID, sanitize_key('ky_saved_articles') );
	foreach ($saved as $save) {
		if($save == $post_id) return true;
	}
	return false;
}
//підключаємо стилі для віджета
function ky_saved_articles_dashboard_scripts($hook){
	if ($hook != 'index.php') return;
		wp_enqueue_style( 'ky_saved_articles_dashboard_style', esc_url(plugins_url('/css/ky_saved_articles_dashboard_style.css', __FILE__)));
		wp_enqueue_script( 'ky_saved_articles_dashboard_script', esc_url(plugins_url('/JS/ky_saved_articles_dashboard_script.js', __FILE__)), array('jquery'), null, true);
		wp_localize_script( 'ky_saved_articles_dashboard_script', 'ky_sav_art', ['nonce'=>wp_create_nonce( 'ky_saved_articles' )]);
}
// підключаємо віджет
function ky_saved_articles_dashboard_widget(){
	wp_add_dashboard_widget('ky_saved_articles_dashboard', 'Your saved articles', 'ky_saved_articles_show_dashboard_widget' );
}
// наповнюємо віджет збереженими статтями
function ky_saved_articles_show_dashboard_widget(){
	$img_address=esc_url(plugins_url('/img/loader.gif', __FILE__ ));
	$user_id=wp_get_current_user();
	$saved = get_user_meta( $user_id->ID, sanitize_key('ky_saved_articles') );
		if (!$saved) {
			echo esc_attr("list is empty");
			return;
		}
			echo "<ul>";
				foreach ($saved as $save) {
					echo '<li class="cat-item cat-item-'.$save.'" ><a href="'.get_permalink($save).'" target="blank">'.get_the_title($save).'</a>
						<span><a href="#" data-post="' .$save. '" data-action="delete" class="ky_saved_articles_dashboard_delete" >Delete</a></span>
						<span class="ky_saved_articles_hide"><img src="'.$img_address.'" class="ky_saved_articles_img" ></span>
						</li>';}
			echo "</ul>";
			echo '<div class="ky_saved_articles_dashboard_delete_all"><button class="button-primary" id="ky_saved_articles_dashboard_delete_all">clear list</button>
						<span class="ky_saved_articles_hide_buttom"><img src="'.$img_src.'" alt="" class="ky_saved_articles_img" ></span></div>';
}	
// Видаленя всіх елементів зі списку 
function wp_ajax_ky_saved_articles_dashboard_delete_all(){
		if (!wp_verify_nonce( sanitize_text_field ($_POST['security']), sanitize_key('ky_saved_articles') )) {
			wp_die(esc_html('not safely'));
			}
		$user_id=wp_get_current_user();
			if(delete_metadata( 'user', $user_id->ID, sanitize_key('ky_saved_articles'))){
				wp_die(esc_html('list is empty'));	
				}
		wp_die(esc_html('delete error'));
}
// видалення плагіна
function ky_saved_articles_uninstall(){
	global $wpdb;
		$meta_key = sanitize_key('ky_saved_articles'); 
		$deleted_rows = $wpdb->delete('wp_usermeta', array('meta_key'=>'ky_saved_articles'));
}