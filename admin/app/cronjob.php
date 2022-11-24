<?php 


function starsoft_order_sync_cron_hook_action() {
    error_log('Mi evento se ejecutó: '.Date("h:i:sa"));
    // if has orders with sync false, then send sync
    
}
add_action( 'starsoft_order_sync_cron_hook', 'starsoft_order_sync_cron_hook_action' );

