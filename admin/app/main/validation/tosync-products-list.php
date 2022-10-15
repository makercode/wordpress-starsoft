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
  function setDraftByPostId($postid) {
    wp_update_post(array('ID' => $postid, 'post_status'   =>  'draft'));
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
    <a href="#exampleModal" data-bs-toggle="modal" data-bs-target="#exampleModal" class="page-title-action ml-auto">
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
        <th>
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
                  <a href='{$postLink}' class='page-title-action d-inline-block'>
                    Convertir a Borrador
                  </a>
                  <a href='{$postLink}' class='page-title-action d-inline-block'>
                    Editar
                  </a>
                </td>
              </tr>
              ";
            };
          }
        ?>
      </tbody>
    </table>
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