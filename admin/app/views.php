<?php 

// main view

function CrearMenu() {
	add_menu_page(
		'Control de ordenes Starsoft', // Titulo de la pagina
		'Starsoft', // Titulo del menu
		'manage_options', // Capability
		plugin_dir_path(__file__).'/main/index.php', // slug
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
add_action('admin_menu', 'CrearMenu');



function EncolarJs($hook) {
	if($hook != "starsoft/admin/invoices-list.php") {
		return;
	}
	wp_enqueue_script(
		'JsExterno',
		plugins_url('admin/js/lista_encuestas.js?v1.7',__FILE__),
		array('jquery')
	);
	wp_localize_script(
		'JsExterno',
		'SolicitudesAjax',
		[
			'url' => admin_url('admin-ajax.php'),
			'seguridad' => wp_create_nonce('seg')
		]
	);
};
add_action('admin_enqueue_scripts','EncolarJs');

