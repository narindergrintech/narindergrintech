<?php
    /*
    *   Template Name: Wishlist Page 
    */
    $clickedIds = array();
    if (isset($_COOKIE['clickedIds'])) {
        $clickedIds = json_decode($_COOKIE['clickedIds']);
    }
    get_header();
    
    global $product;
    //echo "<pre>"; print_r($clickedIds); echo "</pre>";
?>
<section id="mywishlist">
    <div class="container">
        <div class="row">
            <div class="col-12">
            <h2>רשימת המשאלות שלי</h2>
                <ul class="list-group">
                    <?php 
                     if (empty($clickedIds)):
                        echo '<h4>רשימת המשאלות שלך ריקה</h4>'; 
                    else: 
                        $clickedIds = array_unique($clickedIds);
                        foreach($clickedIds as $idkey=>$idval) {
                            //$productPosts = get_post($idval);
                            $products = wc_get_product($idval);
                            $id = $products->get_id();
                            $title = $products->get_name();

                            //echo "<pre>"; print_r($products); echo "</pre>";

                            echo '<li class="list-group-item">
                                        <div class="media">
                                            <div class="imagewish">';
                                                $image = get_the_post_thumbnail_url($id);
                                                if($image){
                                                echo '<img src="'.$image.'" class="mr-3" alt="Item 1" width="200">';
                                                }else{
                                                    echo '<img src="'.get_site_url().'/wp-content/uploads/woocommerce-placeholder.png" class="mr-3" alt="Item 1" width="200">';
                                                }
                                            echo '</div>
                                        <div class="media-body">
                                            <h5 class="mt-0"><a href="' . get_permalink($id) . '">' . $products->get_name() . '</a></h5>';

                                            $currency = get_woocommerce_currency_symbol();
                                               // Check if the product is a variable product
                                            if (get_post_meta($id, '_product_attributes', true)) {
                                                // Product has variations
                                                $product_variations = new WC_Product_Variable($id);
                                                $variations = $product_variations->get_available_variations();

                                                // Initialize minimum and maximum prices
                                                $min_price = null;
                                                $max_price = null;

                                                // Loop through each variation
                                                foreach ($variations as $variation) {
                                                    $variation_id = $variation['variation_id'];
                                                    $variation_obj = new WC_Product_Variation($variation_id);
                                                    $regular_price = $variation_obj->get_regular_price();
                                                    $sale_price = $variation_obj->get_sale_price();

                                                    // Calculate minimum and maximum prices
                                                    if ($sale_price) {
                                                        $price = floatval($sale_price);
                                                    } else {
                                                        $price = floatval($regular_price);
                                                    }

                                                    if ($min_price === null || $price < $min_price) {
                                                        $min_price = $price;
                                                    }

                                                    if ($max_price === null || $price > $max_price) {
                                                        $max_price = $price;
                                                    }
                                                }

                                                // Display price range
                                                if ($min_price !== null && $max_price !== null) {
                                                    $price_range = $currency . ' ' . $min_price . ' - ' . $currency . ' ' . $max_price;
                                                    echo '<p class="product-price-tickr">' . $price_range . '</p>';
                                                }
                                            } else {
                                                // Product is a simple product
                                                $price = get_post_meta($id, '_regular_price', true);
                                                $sale = get_post_meta($id, '_sale_price', true);

                                                if ($sale) {
                                                    echo '<p class="product-price-tickr"><del>' . $currency . ' ' . $price . '</del> ' . $currency . ' ' . $sale . '</p>';
                                                } else {
                                                    echo '<p class="product-price-tickr">' . $currency . ' ' . $price . '</p>';
                                                }
                                            }
                                            // $price = get_post_meta( $productPosts->ID, '_regular_price', true);
                                            // $sale = get_post_meta( $productPosts->ID, '_sale_price', true);
                                            // if($sale) {
                                            //     echo '<p class="product-price-tickr"><del>'.$currency.' '.$price.'</del> '.$currency.' '.$sale.'</p>'; 
                                            // } else {
                                            //     echo '<p class="product-price-tickr">'. $currency.' '.$price.'</p>';
                                            // }

                                           //if($product->is_in_stock()) {
                                            //echo'<pre>'; print_r($product);echo "</pre>"; 
                                             if ($products->stock_status === 'instock') {
                                           //echo $product->stock_status;
                                        
                                                echo '<div class="hover-box add_to_cart" data-product-id="'.$id.'">';
                                                    if($products->is_type('variable')){
                                                    $variations = $products->get_available_variations();
                                                        if ($variations) {
                                                            $first_variation_id = $variations[0]['variation_id'];
                                                            $variation_product = wc_get_product($first_variation_id);
                                                            //$add_to_cart_url = $variation_product->add_to_cart_url();
                                                            $add_to_cart_text = __("הוספה לסל", "woocommerce");
                                                            echo '<a class="hover-text" href="javascript:void(0)" data-quantity="1" data-product_var_id="'.$first_variation_id.'" class="button product_type_variable add_to_cart_button ajax_add_to_cart"><i class="fa fa-plus" aria-hidden="true"></i><span>'.$add_to_cart_text.'</span></a>';
                                                        }
                                                    } else {
                                                        $product_stock_quantity = $products->get_stock_quantity();
                                                        if ($product_stock_quantity > 0 || $products->is_in_stock()) {
                                                        echo '<a class="hover-text" href="javascript:void(0)"><i class="fa fa-plus" aria-hidden="true"></i><span>הוספה לסל</span></a>';
                                                        } else {
                                                            echo '<p class="out-of-stock-message" style="color: red; ">'.__("המלאי אזל", "woocommerce").'</p>';
                                                        }
                                                }
                                                echo '</div>';
                                            } else{
                                                echo '<div class="hover-box" ><p class="out-of-stock-message test" style= "color: red; ">'.__("המלאי אזל", "woocommerce").'</p></div>';
                                            }
                                            echo ' <div class="remove-button"><button class="btn btn-danger remove-favorite" data-id="'.$id.'"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                        </div>
                                    </div>
                                </li>';
                        }
                    ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
