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

<div class="stepper">
  <div class="stepbar">
    <div href="#" class="step step-login active">
      <span class="step-position">
        ✓
      </span>
      Identificado
    </div>
    <div href="#" class="step step-validation processing">
      <span class="step-position">
        !
      </span>
      Validando...
    </div>
    <div href="#" class="step step-synchronization">
      <span class="step-position">
        !
      </span>
      Sincronizado
    </div>
  </div>
</div>

<div class="wrap">
  <div class="">
    <h1 class="wp-heading-inline">
      No hay nada que sincronizar
    </h1>
  </div>
  <div class="">
    <?php echo "complete los productos en su tienda."; ?>
  </div>
</div>