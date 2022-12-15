<?php 
	// Catch post
	if(isset($_POST['btndraft'])) {
		$postid = $_POST['postid'];
		// echo $postid;
		wp_update_post(array('ID' => intval($postid), 'post_status'   =>  'draft'));

		// Delete deleted old items 
		// Reload if the list is empty (So you will se the configured invoices view) 
		$script = "
			<script>
				jQuery( document ).ready(function() {
					jQuery(`.wp-list-table tr[data-id='{$postid}']`).fadeOut(1000, function() {
						jQuery(this).remove();
						let trLength = jQuery(`.wp-list-table tbody tr`).length;
						console.log(trLength);
						// If not more item, then reload website
						if(trLength<1) {
							location.reload();
						}
					});
					console.log({$postid});
				});
			</script>
		";
		echo $script;
	}
?>


<div class="wrap">
	<div class="">
		<h1 class="wp-heading-inline">
			No hay nada que sincronizar
		</h1>
		<?php include dirname(__file__).'/../../includes/stepbar.php'; ?>
	</div>
	<div class="">
		<?php echo "complete los productos en su tienda."; ?>
	</div>
</div>