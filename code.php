<div class="item_show_lowest_shipping">
<?php echo $post->ID; ?>
איסוף עצמי: <?php echo get_post_meta(get_the_ID(), 'sectionOneFormTitle', true)?>
</div>
<div class="item_delivery_time">
זמן אספקה: <?php echo get_post_meta(get_the_ID(), 'sectionOneTitle', true)?>
</div>

/* Get category*/
<section id="box-area">
	<div class="container-fluid px-4 px-md-5">
		<div class="row">
<?php 
$args = array(
    'taxonomy'   => "product_cat",
    'parent'     => 0,
    'number'     => $number,
    'orderby'    => $orderby,
    'order'      => $order,
    'hide_empty' => $hide_empty,
    'include'    => $ids,
    'status'     => 'publis'
);
$parent_categories = get_terms($args);
//echo "<pre>"; print_r($parent_categories); echo "</pre>";
$parent_categories = get_terms($args);

if ( ! empty( $parent_categories ) && ! is_wp_error( $parent_categories ) ) {
    foreach ( $parent_categories as $parent_category ) {
        $parent_category_name = $parent_category->name;
        $parent_category_id   = $parent_category->term_id;
        $custom_banner = get_term_meta( $parent_category_id, 'cat_logo', true );
        $cat_icon = wp_get_attachment_url( $custom_banner, 'full' );
        $link = get_term_link( $parent_category_id, 'product_cat' );
        ?>

        <div class="col gx-4 gx-md-3">
            <a class="cbox1 c-bg1" href="<?php echo $link; ?>">
            <img src="<?php echo $cat_icon; ?>" class="img-fluid"/>
                <h6><?php echo $parent_category_name; ?></h6>
                
            </a>
        </div> 
        <?php
        
        //echo "Parent Category ID: $parent_category_id<br>";
    }
}
?>                
		</div>
	</div>
</section>
/* End Get category*/
