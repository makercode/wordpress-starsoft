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
      La sincronización aún no está funcionando.
    </h1>
  </div>

  <div class="d-flex w-100">
    <p>
      Debes co-relacionar los siguientes SKU con Starsoft o ponerlos en borrador (no se podrán vender) momentaneamente.
    </p>
    <a href="javascript:window.location.reload(true)" data-bs-toggle="modal" data-bs-target="#exampleModal" class="page-title-action ml-auto">
      Comprobar nuevamente
    </a>
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
                  <button href='{$product->postLinkToEdit}' type='submit' name='btndraft' id='btndraft' value='btndraft' class='page-title-action d-inline-block'>
                    Convertir a Borrador
                  </button>
                  <a href='{$product->postLinkToEdit}' class='page-title-action d-inline-block'>
                    Editar
                  </a>
                </form>
              </td>
            </tr>
            ";
          }
        ?>
      </tbody>
    </table>
    <div class="tablenav bottom">
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
    </div>
  </div>
</div>