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
        *
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
      Validando
    </h1>
  </div>

  <div class=" w-100">
    <p class="">
      Actualiza los codigos SKU de los siguientes productos en Starsoft o despublicalos de wordpress.
      <a href="javascript:window.location.reload(true)" data-bs-toggle="modal" data-bs-target="#exampleModal" class="page-title-action d-inline-block w-auto m-0 top-0">
        Comprobar nuevamente
      </a>
    </p>
  </div>

  <div class="">
    <table class="wp-list-table widefat fixed striped pages">
      <thead>
        <th>
          SKU
        </th>
        <th>
          Estado
        </th>
        <th width="10%">
          Id de Producto
        </th>
        <th width="10%">
          Id de Variante
        </th>
        <th>
          Acciones
        </th>
      </thead>
      <tbody>
        <?php 
          foreach ($productNotSyncList as $key => $product) {
            echo "
            <tr data-id='{$product->parentid}'>
              <td>
                {$product->sku}
              </td>
              <td>
                {$product->message}
              </td>
              <td>
                {$product->parentid}
              </td>
              <td>
                {$product->postid}
              </td>
              <td>
                <form method='POST'>
                  <input type='hidden' value='{$product->parentid}' href='#' name='postid' id='postid'>
                  <button href='{$product->postLinkToEdit}' type='submit' name='btndraft' id='btndraft' value='btndraft' class='page-title-action d-inline-block w-auto m-0 top-0'>
                    Despublicar
                  </button>
                </form>
              </td>
            </tr>
            ";
          }
        ?>
      </tbody>
    </table>
    <!--div class="tablenav bottom">
      <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-bottom" class="screen-reader-text">
          Seleccionar acción múltiple
        </label>
        <select name="action2" id="bulk-action-selector-bottom">
          <option value="">Acciones masivas</option>
          <option value="trash">Convertir todos a borrador</option>
        </select>
        <input type="submit" id="doaction2" class="button action" value="Aplicar">
      </div>
    </div-->
  </div>
</div>