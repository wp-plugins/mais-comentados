<?php
/*
Plugin Name: Mais Comentados
Plugin URI: http://reitor.org/wp-plugins/mais-comentados/
Description: Exibe links das publicações que possuem mais comentários.
Author: Ronis Reitor
Version: 1.5
Author URI: http://reitor.org
*/
add_action("plugins_loaded", "init_mc");
function mais_comentados($args) {
	global $wpdb;
	$options = get_option("maiscomentados");
	if(!is_array( $options )){
		$options = array(
			'number' => '5', 'comment' => 'checked', 'title' => 'Mais Comentados'
		);
	}
	$posts = $wpdb->get_results("SELECT ID, post_title, comment_count FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='post' ORDER BY comment_count DESC LIMIT {$options['number']}");
	echo $args['before_widget'] . $args['before_title'] . $options['title'] . $args['after_title'];
	echo '<ul>';
?>
<?php
	foreach ($posts as $links) {
		if ($options['comment'] == 'checked'){
			$comments = ' (' . $links->comment_count . ')';
		}
		echo '<li><a href="' . get_permalink($links->ID) . '">' . $links->post_title . $comments . '</a></li>'."\n";
	}
?>
<?php
	echo '</ul>';
	echo $args['after_widget'];
}
function init_mc(){
	register_sidebar_widget("Mais Comentados", "mais_comentados");
	register_widget_control('Mais Comentados', 'mc_controle', 200, 300);
}
function mc_controle() {
	$options = get_option("maiscomentados");
	if(!is_array($options)){
		$options = array(
			'number' => '5', 'comment' => 'checked', 'title' => 'Mais Comentados'
		);
	}
	if($_POST['mc-enviar']){
		$options['title'] = htmlspecialchars($_POST['mc-titulo']);
		$options['number'] = htmlspecialchars($_POST['mc-numero']);
		$options['comment'] = htmlspecialchars($_POST['mc-comentario']);
		update_option("maiscomentados", $options);
	}
?>
<p>
	<label for="mc-titulo">Titulo: </label>
	<input type="text" id="mc-titulo" name="mc-titulo" value="<?php echo $options['title'];?>" />
</p>
<p>
	<label for="mc-numero">Número de publicações a ser exibido: </label>
	<input type="text" id="maiscomentados-numero" name="mc-numero" value="<?php echo $options['number'];?>" size="5" />
</p>
<p>
	<input type="checkbox" id="mc-comentario" name="mc-comentario" value="checked" <?php echo $options['comment'];?> />
	<label for="mc-comentario"> Exibir número de comentários.</label>
	<input type="hidden" id="mc-enviar" name="mc-enviar" value="1" />
</p>
<?php
}
?>