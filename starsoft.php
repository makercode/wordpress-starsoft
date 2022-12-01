<?php
/*
	Plugin Name: Control de pedidos Starsoft
	Author: Starsoft
	Author URI: https://starsoft.com.pe
	Description: Version alpha de sincronizador para el modulo de inventarios de el ERP Starsoft
	Version: 0.0.1
*/

require_once dirname(__file__).'/_business/services/install.service.php';


// Required objects
include dirname(__file__).'/imports.php';

// Admin hooks
include dirname(__file__).'/admin/app/cronjob.php';
include dirname(__file__).'/admin/app/requirements.php';
include dirname(__file__).'/admin/app/listeners.php';
include dirname(__file__).'/admin/app/views.php';
include dirname(__file__).'/admin/app/ajax.php';

// Front hooks
include dirname(__file__).'/public/app/requirements.php';
include dirname(__file__).'/public/app/listeners.php';


/*
**************************************

			Plugin hooks

**************************************
*/

// Detect when plugin is activated
function ActivePlugin() {
	$installService = new InstallService;
	$installService->init();

    if( !wp_next_scheduled( 'starsoft_order_sync_cron_hook' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'minutely', 'starsoft_order_sync_cron_hook' );
        // twicedaily
    }
}
register_activation_hook(__file__, 'ActivePlugin');


add_filter( 'cron_schedules', 'wpshout_add_cron_interval' );
function wpshout_add_cron_interval( $schedules ) {
    $schedules['minutely'] = array(
            'interval'  => 60, // time in seconds
            'display'   => 'minutely'
    );
    return $schedules;
}


// Detect when plugin is disabled
function DisablePlugin() {
    wp_clear_scheduled_hook( 'starsoft_order_sync_cron_hook' );
}
register_deactivation_hook(__file__, 'DisablePlugin');

