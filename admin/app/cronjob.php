<?php 


function starsoft_order_sync_cron_hook_action() {
	// if has orders with sync false, then send sync
	$syncronizerService = new SyncronizerService;
	$isSynced = $syncronizerService->syncronizeOrders();
	error_log('ejecutado resulto en cronjob.php '.$isSynced);

}

add_action( 'starsoft_order_sync_cron_hook', 'starsoft_order_sync_cron_hook_action' );
