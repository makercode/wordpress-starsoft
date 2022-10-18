<?php 
  require_once dirname(__file__).'/../../business/database/products.database.php';
  require_once dirname(__file__).'/../../business/api/products.api.php';

  // writting products from woocommerce to sync
  $productsDatabase = new ProductsDatabase();
  $create = $productsDatabase->createTable();
  // var_dump($create);
  $wcProductsSync = $productsDatabase->getWCProductsSyncData();
  $wcProducts = $productsDatabase->getWCProductsData();
  $syncProducts = $productsDatabase->setProductsSyncData($wcProductsSync);
  // var_dump($syncProducts);
  // var_dump($wcProductsSync);

  // sending api post data to verify sku list
  $productsApi = new ProductsApi();
  $responseSyncProds = $productsApi->verifyProducts($wcProducts);
  // var_dump($responseSyncProds);

  $responseSyncProdsObj = json_decode($responseSyncProds, true);

  function findPostIdBySku($sku, $wcProductsSync) {
    foreach ( $wcProductsSync as $product ) {
      if ( $sku == $product->sku ) {
        return $product->postid;
      }
    }
    return false;
  }


  if(isset($_POST['btndraft'])) {
    $postid = $_POST['postid'];
    echo $postid;

    $product = wc_get_product( $postid );

    $status = $product->set_status('draft');
    echo $status;
  }

?>

<style>
  .w-100 {
    width: 100%;
  }
  .d-none {
    display: none;
  }
  .d-flex {
    display: flex;
  }
  .d-inline-block {
    display: inline-block;
  }
  .my-auto {
    margin-top: auto!important;
    margin-bottom: auto!important;
  }
  .ml-auto {
    margin: auto!important;
    margin-right: 0!important;
  }
</style>

<div class="wrap">
  <div class="">
    <h1 class="wp-heading-inline">
      Verificación de productos en Starsoft
    </h1>
  </div>

  <div class="d-flex w-100">
    <p>
      Resultados (Solo productos simples y variables)
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
          PostId
        </th>
        <th>
          Acciones
        </th>
      </thead>
      <tbody>
        <?php 
          foreach ($responseSyncProdsObj as $key => $value) {
            $sku = $value['SKU'];
            $exist = $value['Exists'];
            $postid = findPostIdBySku($sku, $wcProductsSync);
            $empty = $value['SKU'] === '';
            $postLinkToEdit = get_edit_post_link($postid);
            // $postLinkToDraft = setDraftByPostId($postid);

            if(!$exist) {
              $message = "No se encontró este SKU en starsoft";
              if($empty) {
                $message = "No se admiten SKU vacios";
              }
              echo "
              <tr>
                <td>
                  {$sku}
                </td>
                <td>
                  {$message}
                </td>
                <td>
                  {$postid}
                </td>
                <td>
                  <form method='POST'>
                    <input type='hidden' value='{$postid}' href='#' name='postid' id='postid'>
                    <button href='{$postLinkToEdit}' type='submit' name='btndraft' id='btndraft' value='btndraft' class='page-title-action d-inline-block'>
                      Convertir a Borrador
                    </button>
                    <a href='{$postLinkToEdit}' class='page-title-action d-inline-block' target='_blank'>
                      Editar
                    </a>
                  </form>
                </td>
              </tr>
              ";
            };
          }
        ?>
      </tbody>
    </table>
    <div class="tablenav bottom">
      <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-bottom" class="screen-reader-text">Seleccionar acción múltiple</label>
        <select name="action2" id="bulk-action-selector-bottom">
          <option value="">Acciones masivas</option>
          <option value="trash">Convertir todos a borrador</option>
        </select>
        <input type="submit" id="doaction2" class="button action" value="Aplicar">
      </div>
    </div>
    <div class="d-none">
      <p>
        Entiendo que si los productos existen en Starsoft se duplicarán.
      </p>
      <a href="#exampleModal" data-bs-toggle="modal" data-bs-target="#exampleModal" class="page-title-action ml-auto">
        Exportar productos a Starsoft
      </a>
    </div>
  </div>
</div>