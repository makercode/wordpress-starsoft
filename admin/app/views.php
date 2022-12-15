<?php 

// main view

function action_create_menu() {
	add_menu_page(
		'Control de ordenes Starsoft', // Titulo de la pagina
		'Starsoft Pedidos', // Titulo del menu
		'manage_options', // Capability
		plugin_dir_path(__file__).'/views/index.php', // slug
		null,
		plugin_dir_url(__file__).'/assets/img/icon.png'
	);

	add_submenu_page(
		'starsoft_menu', // Parent slug
		'Ajustes', // Titulo pagina
		'Ajustes', // Titulo menu
		'manage_options', // Capability
		'starsoft_menu_ajustes', // slug
		'MostrarSubContenido'
	);
}
function MostrarSubContenido() {
	echo '<h1>Ajustes de Facturador</h1>';
}
add_action('admin_menu', 'action_create_menu');
