<?php
/*
	Plugin Name: Control de pedidos Starsoft
	Author: Starsoft
	Author URI: https://starsoft.com.pe
	Description: Version alpha de sincronizador para el modulo de inventarios de el ERP Starsoft
	Version: 0.0.1
*/

require_once dirname(__file__).'/_business/services/install.service.php';
require_once dirname(__file__).'/_business/services/sync.service.php';


// Required objects
include dirname(__file__).'/imports.php';

// Admin hooks
include dirname(__file__).'/admin/app/listeners.php';
include dirname(__file__).'/admin/app/views.php';

// Front hooks
include dirname(__file__).'/public/app/requirements.php';
include dirname(__file__).'/public/app/listeners.php';


// Plugin hooks

// Detect when plugin is activated
function ActivePlugin() {
	$installService = new InstallService;
	$installService->init();

	$syncService = new SyncService;
	$syncService->init();
}


// Detect when plugin is disabled
function DisablePlugin() {
}
register_activation_hook(__file__, 'ActivePlugin');
register_deactivation_hook(__file__, 'DisablePlugin');