<?php
/*
Plugin Name: Posts Mais Comentados
Plugin URI: http://www.dabliuerre.com/wordpress-plugins/mais-comentados/
Description: Exibe o Link das publicações que têm mais comentários.
Author: W. Ronis Nascimento
Version: 1.3
Author URI: http://www.dabliuerre.com
*/

add_action("plugins_loaded", "init_comentados");

function mais_comentados($arg) {
	global $wpdb;

$options = get_option("widget_maiscomentados");
if (!is_array( $options ))
	{
		$options = array(
      'number' => '5', 'comment' => 'checked', 'title' => 'Mais Comentados'
      );
  }    

//obter o post da base de dados
$posts = $wpdb->get_results("SELECT ID, post_title, comment_count FROM $wpdb->posts ORDER BY comment_count DESC LIMIT " . $options['number'] . "");

//determinar se utilizada como uma barra lateral ou função
if ($arg == '')
	echo '<li>';
?>
<h2><?php echo $options['title']; ?></h2>
<ul>
<?php
//mostrar cada página como um link
foreach ($posts as $links) {
	if ($options['comment'] == 'checked')
		$comments = ' (' . $links->comment_count . ')';

	echo '<li><a href="' . get_permalink($links->ID) . '">' . $links->post_title . '</a>' . $comments . '</li>';
	}
?>

</ul>
<?php
if ($arg == '')
	echo '</li>';
?>

<?php
}

function init_comentados(){
	//inicializar a widget
    register_sidebar_widget("Mais Comentados", "mais_comentados");     
	register_widget_control('Mais Comentados', 'maiscomentados_controle', 200, 300 );
}

function maiscomentados_controle() {
	//carregar traduções
load_plugin_textdomain('maiscomentados', "wp-content/plugins/mais-comentados/");
	//update_option("widget_maiscomentados-numero", "5");
	$options = get_option("widget_maiscomentados");

//se deixar opção em branco, inserir dados predefinidos.
if (!is_array( $options ))
	{
		$options = array(
      'number' => '5', 'comment' => 'checked', 'title' => 'Mais Comentados'
      );
  }    

//apresentar novos dados
if ($_POST['maiscomentados-Enviar'])
  {
	$options['title'] = htmlspecialchars($_POST['maiscomentados-titulo']);
    $options['number'] = htmlspecialchars($_POST['maiscomentados-numero']);
	$options['comment'] = htmlspecialchars($_POST['maiscomentados-comentario']);
	
    update_option("widget_maiscomentados", $options);
  }
//cria painel de configuração do widget
?>
	<p>
    <label for="maiscomentados-titulo">Titulo: </label>
    <input type="text" id="maiscomentados-titulo" name="maiscomentados-titulo" value="<?php echo $options['title'];?>" />
  </p>

	<p>
    <label for="maiscomentados-numero">Número de publicações a ser exibido: </label>
    <input type="text" id="maiscomentados-numero" name="maiscomentados-numero" value="<?php echo $options['number'];?>" size="5" />
  </p>

<p>
	 <input type="checkbox" id="maiscomentados-comentario" name="maiscomentados-comentario" value="checked" <?php echo $options['comment'];?> />
    <label for="maiscomentados-comentario"> Exibir número de comentários </label>

	<input type="hidden" id="maiscomentados-Enviar" name="maiscomentados-Enviar" value="1" />
  </p>
<?php
	}
?>