<?php
/**
* Plugin Name: Referify for WooCommerce
* Description: Efficiently connect your WooCommerce shop with Referify and start tracking conversions driven by influencers and affiliates!
* Version: 1.0
* Author: Referify
* Author URI: https://referify.co/
**/

add_action('woocommerce_thankyou', 'referify_thankyou_script', 20, 1 );
function referify_thankyou_script( $order_id ) {
    if ( ! $order_id )
        return;

    // Get an instance of the WC_Product object
    $order = wc_get_order( $order_id );
    $original = $order->get_used_coupons();
    $couponused = $original[0];

    // Get Order total amount and Order transaction ID
    $transaction_id = $order->get_transaction_id();
    $order_items = array();

    foreach ( $order->get_items() as $item_id => $item ) {
        $product    = $item->get_product();
        $product_id = $item->get_product_id();

        // Set unprotected item data in an array
        $order_items[]  = $product_id;
    }
    $order_items = implode( ',', $order_items );

    ?>
    <script type="text/javascript" language="javascript" src="https://api.referify.co/js/conversion.js"></script>
    <script type="text/javascript" language="javascript">
         var ordertotal = '<?php echo $order->get_total(); ?>';
         var subttotal = '<?php echo $order->get_subtotal(); ?>';
         var coupon = '<?php echo $couponused;?>';
         var orderid = '<?php echo $order_id; ?>';
         var products = '<?php echo $order_items; ?>';
         createConversion(orderid,subttotal,ordertotal,coupon,products);
    </script>
  <?php
 }
add_action('wp_enqueue_scripts', 'referify_track_visitors');
function referify_track_visitors() {
  wp_enqueue_script('referify-tracking', 'https://api.referify.co/js/visits.js', array(), '3', true);
}
?>