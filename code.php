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
    'status'     => 'publish'
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











/* Get Sub category by parent category */


$selected_category = get_queried_object();
$subcategories_of_selected_categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => $selected_category->term_id, 'orderby' => 'count', 'order' => 'desc')); 
$cat_id = $selected_category->term_id;

<?php 	if ($subcategories_of_selected_categories) { ?>
<div class="main-tabs">
	<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

	<!-- <li class="nav-item" role="presentation">
					<button class="nav-link active" id="pills-0-tab" data-bs-toggle="pill" data-bs-target-ids="0" data-bs-target="#pills-0" type="button" role="tab" aria-controls="pills-0" aria-selected="true">את כל</button>
				</li> -->

	<?php 
		//echo "<pre>"; print_r($subcategories); echo "</pre>"; die("Herre");

		//Loop through the subcategories and display them
	
			$count= 0;
				foreach ( $subcategories_of_selected_categories as $subcategory ) {
					$subcategory_url  = get_term_link( $subcategory );
	
					//echo '<a href="' . get_term_link( $subcategory ) . '">' . $subcategory->name . '</a>';
					if($count == 0){
					echo '<li class="nav-item test" role="presentation">
					<a class="nav-link tesat" href="'.$subcategory_url.'">' . $subcategory->name . '</a>
				</li>';
					}else{
					echo '<li class="nav-item" role="presentation">
					<a class="nav-link" href="'.$subcategory_url.'">' . $subcategory->name . '</a>
				</li>';
					}
				$count++;
				}	?>

		<!-- <li class="nav-item" role="presentation">
		<button class="nav-link" id="pills-first-tab" data-bs-toggle="pill" data-bs-target="#pills-first" type="button" role="tab" aria-controls="pills-first" aria-selected="true">קוטלי פטריות</button>
		</li>
		<li class="nav-item" role="presentation">
		<button class="nav-link" id="pills-second-tab" data-bs-toggle="pill" data-bs-target="#pills-second" type="button" role="tab" aria-controls="pills-second" aria-selected="false">קוטלי עשבים</button>
		</li>
		<li class="nav-item" role="presentation">
		<button class="nav-link" id="pills-third-tab" data-bs-toggle="pill" data-bs-target="#pills-third" type="button" role="tab" aria-controls="pills-third" aria-selected="false">קוטלי חרקים</button>
		</li> -->
	</ul>
</div>
<?php } ?>



// Porfolio Archive page - Add Description
add_action( 'ukiyo_qodef_after_container_open', 'ukiyo_qodef_add_dec_portfolio_archive_dec' );
function ukiyo_qodef_add_dec_portfolio_archive_dec() {

  if ( is_tax('portfolio-category') ) {
    $current_category = get_queried_object();
    $description = term_description( $current_category->term_id, 'portfolio-category' );

      $id= $current_category->term_id; 
      $add_pic = get_field( "add_picture", 'term_'.$id );

      if ($add_pic){ 
        ?>
        
        <img src="<?php echo $add_pic; ?>" width="100%" height="auto">
      <?  }

    echo '<div class="qodef-container-inner extra portfolio-subtitle-wrapper">';
    echo '<div class="qodef-grid-row flex">';
    echo '<div class="qodef-grid-col-12">';
    echo '<div class="portfolio-subtitle">';
    echo $description; // Output the category description
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
}
