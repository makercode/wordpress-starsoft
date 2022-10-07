<?php 
  global $wpdb;

  $tabla  = "{$wpdb->prefix}invoices";

  $GetInvoicesQuery = "SELECT * FROM {$wpdb->prefix}sync_invoices";
  $invoices_array = $wpdb->get_results($GetInvoicesQuery, ARRAY_A);

  if( empty($invoices_array) ) {
    $invoices_array = array();
  }
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
      <th>
        Factura Validada
      </th>
      <th>
        Factura Anulada
      </th>
    </thead>
    <tbody>
      <?php 
        foreach ($invoices_array as $key => $value) {
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
              <td>
                {$valid}
              </td>
              <td>
                {$canceled}
              </td>
            </tr>
          ";
        };
      ?>
    </tbody>
  </table>
</div>
