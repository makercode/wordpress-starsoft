<?php 
  require_once dirname(__file__).'/../../business/database/invoices.database.php';
  require_once dirname(__file__).'/../../business/database/products.database.php';
  
  require_once dirname(__file__).'/../../business/api/products.api.php';

  // get invoices
  $invoicesDatabase = new InvoicesDatabase();
  $invoicesArray = $invoicesDatabase->getInvoices();

?>
<?php // print_r($_POST); ?>

<div class="wrap">
  <h1 class="wp-heading-inline">
    <?php echo get_admin_page_title(); ?>
  </h1>
  <a href="#exampleModal" data-bs-toggle="modal" data-bs-target="#exampleModal" class="page-title-action">
    Añadir nueva
  </a>
  <div>
    <?php
      // Gg
      /*
      $order_object = wc_get_order(58);
      $order_data = $order_object->get_data();
      foreach ( $order_object->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $product_sku = $item->get_product()->get_sku(); 
        $variation_id = $item->get_variation_id();
        $product = $item->get_product(); // see link above to get $product info
        $product_name = $item->get_name();
        $quantity = $item->get_quantity();
        $subtotal = $item->get_subtotal();
        $total = $item->get_total();
        $tax = $item->get_subtotal_tax();
        $tax_class = $item->get_tax_class();
        $tax_status = $item->get_tax_status();
        $allmeta = $item->get_meta_data();
        $somemeta = $item->get_meta( '_whatever', true );
        $item_type = $item->get_type();
        var_dump($quantity);
        var_dump($product_sku);
      }*/
    ?>
  </div>
  <table class="wp-list-table widefat fixed striped pages">
    <thead>
      <th style="width:5%">
        ID
      </th>
      <th>
        Fecha
      </th>
      <th>
        NRO Orden WP
      </th>
      <th>
        DNI/RUC cliente
      </th>
      <th>
        Completado
      </th>
      <th>
        Sincronizado
      </th>
    </thead>
    <tbody>
      <?php 
        foreach ($invoicesArray as $key => $value) {
          $id = $value['InvoiceId'];
          $date = date('m/d/Y', $value['Date']);
          $orderid = $value['OrderId'];
          $customerid = $value['CustomerId'];
          $paid = ($value['Paid']) ? "SI": "x";
          $sync = ($value['Sync']) ? "SI": "x";
          $valid = ($value['Valid']) ? "SI": "x";
          $canceled = ($value['Canceled']) ? "SI": "x";
          echo "
            <tr>
              <td>
                {$id}
              </td>
              <td>
                {$date}
              </td>
              <td>
                {$orderid}
              </td>
              <td>
                {$customerid}
              </td>
              <td>
                {$paid}
              </td>
              <td>
                {$sync}
              </td>
            </tr>
          ";
        };
      ?>
    </tbody>
  </table>
</div>
