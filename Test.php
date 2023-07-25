Cart Pop Up Show while click on Single product page Add to cart Button

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
