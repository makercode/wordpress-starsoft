<?php

class ProductSyncDTO {
  public $sku;
  public $postid;
  public $sync;

  public function __construct(string $sku, string $postid, string $sync) {
    $this->sku = $sku;
    $this->postid = $postid;
    $this->sync = $sync;
  }
}

class ProductSkuDTO {
  public $sku;

  public function __construct(string $sku) {
    $this->sku = $sku;
  }
}

class ProductsDatabase {

  public function __construct() {
    global $wpdb;
    $this->table = "{$wpdb->prefix}sync_products";
  }


  public function createTable () {
    global $wpdb;

    $setProductsTable = "CREATE TABLE IF NOT EXISTS {$this->table}(
      `ProductId` INT NOT NULL AUTO_INCREMENT,
      `Sku` VARCHAR(45) NOT NULL,
      `PostId` VARCHAR(45) NOT NULL,
      `Sync` INT(11) NULL,
      PRIMARY KEY (`ProductId`),
      UNIQUE KEY (`Sku`)
    )";
    return $wpdb->query($setProductsTable);
  }


  public function getWCProductsSyncData () {
      // Send 
      $args = array(
          'post_type' => 'product',
          'posts_per_page' => -1
      );
      $loop = new WP_Query( $args );

      $productsStack = array();

      if ( $loop->have_posts() ): 
        while ( $loop->have_posts() ): $loop->the_post();
          global $product;

          if ($product->get_type() == "grouped") {
              continue;
          }
          if ($product->get_type() == "external") {
              continue;
          }
          if ($product->get_type() == "variable") {
            foreach ( $product->get_children( false ) as $child_id ) {
                // get an instance of the WC_Variation_product Object
                $variation = wc_get_product( $child_id ); 

                if ( ! $variation || ! $variation->exists() ) {
                    continue;
                }

                $_temp_product = new ProductSyncDTO($variation->get_sku(), $variation->get_id() ,'0');
                array_push($productsStack, $_temp_product);
            }
          } 
          if ($product->get_type() == "simple")  {
            $_temp_product = new ProductSyncDTO($product->get_sku(), $product->get_id() ,'0');
            array_push($productsStack, $_temp_product);
          }
        endwhile; 
      endif; 
      return $productsStack;
      wp_reset_query();
  }


  public function getWCProductsData () {
      // Send 
      $args = array(
          'post_type' => 'product',
          'posts_per_page' => -1
      );
      $loop = new WP_Query( $args );

      $productsStack = array();

      if ( $loop->have_posts() ): 
        while ( $loop->have_posts() ): $loop->the_post();
          global $product;

          if ($product->get_type() == "grouped") {
              continue;
          }
          if ($product->get_type() == "external") {
              continue;
          }
          if ($product->get_type() == "variable") {
            foreach ( $product->get_children( false ) as $child_id ) {
                // get an instance of the WC_Variation_product Object
                $variation = wc_get_product( $child_id ); 

                if ( ! $variation || ! $variation->exists() ) {
                    continue;
                }

                $_temp_product = new ProductSkuDTO($variation->get_sku());
                array_push($productsStack, $_temp_product);
            }
          } else {
            $_temp_product = new ProductSkuDTO($product->get_sku());
            array_push($productsStack, $_temp_product);
          }
        endwhile; 
      endif;

      return $productsStack;
      wp_reset_query();
  }


  public function setProductsSyncData ($productsStack) {
    global $wpdb;

    $values = '';

    foreach($productsStack as $key => $value) {
      if ($key !== 0) {
        $values .= ',';
      }
      $values .= '("';
      $values .= $value->sku;
      $values .= '"';
      $values .= ',';
      $values .= '"';
      $values .= $value->postid;
      $values .= '"';
      $values .= ',';
      $values .= $value->sync;
      $values .= ')';
    }

    $query = "
      INSERT INTO {$this->table} 
        (Sku, PostId, Sync)
      VALUES 
        {$values}
      ON DUPLICATE KEY UPDATE 
        PostId=VALUES(PostId)
    ";
    /*
      INSERT INTO {$this->table} 
        (Sku, PostId, Sync)
      VALUES 
        {$values}
      ON DUPLICATE KEY UPDATE 
        Postid=VALUES(PostId),
        Sync=VALUES(Sync)
    */

    return $wpdb->query($query);
  }

  public function setProductSyncData () {
  }


}
