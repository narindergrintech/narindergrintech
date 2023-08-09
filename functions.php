/* Change the check out field priority */
/* Put this code in js file*/
jQuery(document).ready(function($) {
    // Move City field after Street field
    $('#billing_address_1_field').insertAfter($('#billing_city_field'));

    // You can add further customization if needed
});

<?php
function enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');

function enqueue_custom_checkout_js() {
    wp_enqueue_script('custom-checkout', get_template_directory_uri() . '/custom-checkout.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_checkout_js');



/* Cart Pop Up Show while click on Single product page Add to cart Button */

function custom_after_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
?>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
    $(document).ready(function(){
    	$("#show-cart-box").trigger("click");
    });
    </script>
<?php
}
add_action( 'woocommerce_add_to_cart', 'custom_after_add_to_cart', 10, 6 );

function custom_track_product_view() {
    if ( ! is_singular( 'product' ) ) {
        return;
    }
    global $post;
    if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }
    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }
    wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'custom_track_product_view', 20 );
 function rc_woocommerce_recently_viewed_products( $atts, $content = null ) {
    // Get shortcode parameters
    extract(shortcode_atts(array(
        "per_page" => '5'
    ), $atts));
    // Get WooCommerce Global
    global $woocommerce;
    // Get recently viewed product cookies data
    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
    $viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
    // If no data, quit
    
    if ( empty( $viewed_products ) )
        return __( 'You have not viewed any product yet!', 'rc_wc_rvp' );
    // Create the object
    ob_start();
    // Get products per page
    if( !isset( $per_page ) ? $number = 5 : $number = $per_page )
    // Create query arguments array
    $query_args = array(
        'posts_per_page' => $number,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'post__in'       => $viewed_products,
        'orderby'        => 'rand'
        );
  // Add meta_query to query args
  $query_args['meta_query'] = array();
  // Check products stock status
  $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
  // Create a new query
  $r = new WP_Query($query_args);
  if (empty($r)) {
    return __( 'You have not viewed any product yet!', 'rc_wc_rvp' );
  } ?>
	<div class="container-fluid px-2 px-md-0">
	<div class="row">
    <?php while ( $r->have_posts() ) : $r->the_post(); 
    $product = wc_get_product(get_the_ID());
    $url= wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
    $sku = $product->get_sku(); // Get SKU of the product           
	?>
   <!-- //put your theme html loop hare -->
 	<div class="col">
	 	<div class="recent-box">
	    <a class="product-picture" href="<?php echo get_post_permalink(); ?>" title="Show details for Watches">
	       <img alt="Picture of Watches" src="<?php echo $url;?>" title="Show details for Watches" />
	    </a>

			<a class="product-name" href="<?php echo get_post_permalink(); ?>"><?php the_title()?></a>
      <div class="recent-sku">
      	<?php echo $sku; ?>
      </div>

    </div>
	</div>   
<!-- end html loop  -->
<?php endwhile; ?>
    <?php wp_reset_postdata();
	echo '</div> </div>';
    return '<div class="woocommerce columns-5 facetwp-template">' . ob_get_clean() . '</div>';
    // ----
    // Get clean object
    $content .= ob_get_clean();
    // Return whole content
    return $content;
}
// Register the shortcode
add_shortcode("woocommerce_recently_viewed_products", "rc_woocommerce_recently_viewed_products");
