<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */


?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>


 <?php //test

	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 0;      // 1 for yes, 0 for no
	  $hierarchical = 1;      // 1 for yes, 0 for no  
	  $title        = '';  
	  $empty        = 0;

	  $args = array(
	         'taxonomy'     => $taxonomy,
	         'orderby'      => $orderby,
	         'show_count'   => $show_count,
	         'pad_counts'   => $pad_counts,
	         'hierarchical' => $hierarchical,
	         'title_li'     => $title,
	         'hide_empty'   => $empty
	  );
	 $all_categories = get_categories( $args );
//var_dump($all_categories);

//var_dump(woocommerce_mini_cart());

?>
</body>
</html>
