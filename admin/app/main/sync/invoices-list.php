<?php
  // get invoices
  $invoicesDatabase = new InvoicesDatabase();
  $invoicesArray = $invoicesDatabase->getInvoices();

  $order_object = wc_get_order(58);
  $order_data = $order_object->get_data();

  $index = 0;
  foreach ( $order_object->get_items() as $item_id => $item ) {
    if ($index !== 0) {
      $products_list .= ',';
    }
    $sale_price = 0;
    var_dump($item->get_data());
    /*
    if($item->get_sale_price()){
      $sale_price = $item->get_sale_price();
    }
    $discount_money = $item->get_price()-$sale_price;
    $discount_percent = ($discount_money*100)/$item->get_subtotal();

      $products_list .= '
      {
        "Order_number": "'.$post_id.'", // id de orden
        "Identifier_Product": "'.$item->get_sku().'",
        "Quantity": '.$item->get_quantity().',
        "Price_Sale": '.$item->get_sale_price().',
        "Subtotal" : '.$item->get_subtotal().', // unid * cant
        "Discount_Value": '.$discount_item.', // Descuento total
        "Percentage_Discount": '.$discount_percent.' // Descuento porcentaje total
      }
    ';*/
    $index++;
  }
  // var_dump($product_list);
?>
<?php // print_r($order_object); ?>

<div class="wrap">
  <h1 class="wp-heading-inline">
    <?php echo get_admin_page_title(); ?>
  </h1>
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
        foreach ($invoicesArray as $key => $invoice) {
          $id = $invoice['InvoiceId'];
          $date = date('m/d/Y', $invoice['Date']);
          $orderid = $invoice['OrderId'];
          $customerid = $invoice['CustomerId'];
          $paid = ($invoice['Paid']) ? "SI": "x";
          $sync = ($invoice['Sync']) ? "SI": "x";
          $valid = ($invoice['Valid']) ? "SI": "x";
          $canceled = ($invoice['Canceled']) ? "SI": "x";
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
