<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

 // Removes picture compression - use with care!
add_filter('jpeg_quality', function($arg){return 100;});

// Switch off WP query
function _remove_script_version( $src ){
$parts = explode( '?ver', $src );
return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );


 
/* Add Raleway Google font */
add_action( 'storefront_header', 'jk_storefront_header_content', 40 );
function jk_storefront_header_content() { ?>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<?php
} 
 
 
 

/**
 * Add on customizer Frontpage Add Slider
 * Original author URI: http://atlantisthemes.com
 * Changed: handle and removed some parts
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'customize_register', 'galibelle_storefront_customize_register' );

function galibelle_storefront_customize_register( $wp_customize ) {

/**
 * Customizer Control For Pro Conversion
 */
class Custom_Subtitle extends WP_Customize_Control {

	public function render_content() { ?>

		<label>
		<?php if ( !empty( $this->label ) ) : ?>
			<span class="customize-control-title bellini-pro__title">
				<?php echo esc_html( $this->label ); ?>
			</span>
		<?php endif; ?>
		</label>

		<?php if ( !empty( $this->description ) ) : ?>
			<span class="description bellini-pro__description">
				<?php echo $this->description; ?>
			</span>
		<?php endif;
	}
}

	$wp_customize->add_setting('storefront_slider_shortcode_field', array(
			'type' 				=> 'theme_mod',
			'default'         	=> '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport' 		=> 'refresh',
	) );

			$wp_customize->add_control('storefront_slider_shortcode_field',array(
				'type' 			=>'text',
               'label'      	=> esc_html__( 'Slider Shortcode', 'storefront' ),
               'description' 	=> esc_html__( 'You can insert your Meta Slider, Smart Slider 3, Soliloquy, Revolution Slider, LayerSlider shortcode here.', 'storefront' ),
               'section'    	=> 'static_front_page',
               'settings'   	=> 'storefront_slider_shortcode_field',
			   'priority'  		=> 1,
			));

	// Show Frontpage Slider on All Pages
	$wp_customize->add_setting( 'storefront_slider_all_pages' ,
		array(
			'default' => false,
			'type' => 'theme_mod',
			'sanitize_callback' => 'sanitize_key',
			'transport' => 'refresh'
		)
	);

		$wp_customize->add_control( 'storefront_slider_all_pages',array(
				'label'      => esc_html__( 'Show Frontpage Slider on All Pages', 'storefront' ),
				'section'    => 'static_front_page',
				'settings'   => 'storefront_slider_all_pages',
			    'priority'   => 2,
			    'type'       => 'checkbox',
			)
		);

	$wp_customize->add_setting( 'bellini_front_block_pro_conversion',
		array(
			'type' 				=> 'theme_mod',
			'sanitize_callback' => 'sanitize_key',
			)
	);
			$wp_customize->add_control( new Custom_Subtitle ( $wp_customize, 'bellini_front_block_pro_conversion',
				array(
					'label' => esc_html__('','storefront'),
					'description' => $third_party_slider_description,
					'section' => 'static_front_page',
					'settings'    => 'bellini_front_block_pro_conversion',
					'priority'   => 3,
			)) );

}


add_action( 'homepage1', 'galibelle_add_slider_storefront',      5 );
//remove_action( 'homepage', 'galibelle_add_slider_storefront',      5);

function galibelle_add_slider_storefront() { ?>
	<section class="front__slider">
	<?php
	if (get_theme_mod( 'storefront_slider_shortcode_field')){
		echo do_shortcode( html_entity_decode(get_theme_mod( 'storefront_slider_shortcode_field')) );
	}else{
		esc_html_e( 'No Slider Shortcode Found! ', 'storefront' );
	}
	?>
	</section>
<?php
}

if (get_theme_mod('storefront_slider_all_pages') == true){
//add_action( 'storefront_before_content', 'galibelle_add_slider_storefront', 5);
}


/* Remove default footer credits */
add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
}

// dgamoni

require_once 'core/load.php'; 

function get_new_cut() {

	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 1;      // 1 for yes, 0 for no
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
	return $all_categories;
}



	function storefront_product_categories( $args ) {
		if ( storefront_is_woocommerce_activated() ) {
			$args = apply_filters( 'storefront_product_categories_args', array(
				'limit' 			=> 4,
				'columns' 			=> 4,
				'child_categories' 	=> 0,
				'orderby' 			=> 'name',
				// 'title'				=> __( 'Product categories', 'storefront' ),
				'title'				=> __( 'Collection', 'storefront' ),
			) );
			echo '<section class="storefront-product-section storefront-product-categories" aria-label="Product Categories">';
			do_action( 'storefront_homepage_before_product_categories' );
			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';
			do_action( 'storefront_homepage_after_product_categories_title' );
			
			// echo storefront_do_shortcode( 'product_categories', array(
			// 	'number'  => intval( $args['limit'] ),
			// 	'columns' => intval( $args['columns'] ),
			// 	'orderby' => esc_attr( $args['orderby'] ),
			// 	'parent'  => esc_attr( $args['child_categories'] ),
			// ) );


			echo '<div class="woocommerce columns-4"><ul class="products">';
			foreach (get_new_cut() as $key => $cat) {
				if ($key ==0 ) {
					$num = 'first';
				}else if($key ==1) {
					$num = '';
				}else if ($key ==3) {
					$num = 'last';
				}
				$params = array( 'width' => 330 , 'height' => 436);
			    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true ); 
			    $image = wp_get_attachment_url( $thumbnail_id );
			    //var_dump(get_term_link( $cat->term_id));
				echo '<li class="product-category product '.$num.'">
						 <a href="'.get_term_link( $cat->term_id).'">
							<img src="' . bfi_thumb( $image, $params ) . '"/>
							<h3>'.$cat->name.' <mark class="count">('.$cat->category_count.')</mark></h3>
						</a>
					</li>';
			}
			echo '</ul></div>';

			do_action( 'storefront_homepage_after_product_categories' );
			echo '</section>';
		}
	}




function storefront_featured_products( $args ) {
		if ( storefront_is_woocommerce_activated() ) {
			$args = apply_filters( 'storefront_featured_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'orderby' => 'date',
				'order'   => 'desc',
				'title'   => __( 'Highlights', 'storefront' ),
			) );
			echo '<section class="storefront-product-section storefront-featured-products" aria-label="Featured Products">';
			do_action( 'storefront_homepage_before_featured_products' );
			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';
			do_action( 'storefront_homepage_after_featured_products_title' );
			echo storefront_do_shortcode( 'featured_products', array(
			//echo storefront_do_shortcode( 'featured_product_highlights', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
				'orderby'  => esc_attr( $args['orderby'] ),
				'order'    => esc_attr( $args['order'] ),
			) );
			do_action( 'storefront_homepage_after_featured_products' );
			echo '</section>';
		}
}


add_action( 'homepage_news', 'homepage_news',      5 );

function homepage_news() {

		$args = array(
			'category_name'    => 'events',
			'post_type' => array(
				'post',
				),
			'posts_per_page'         => 1,
		);
	
	$events = new WP_Query( $args );
	$posts = $events->get_posts();

	if( $posts ): ?>

		<div class="col-md-12 cat_event">
	            	<h2>Next events</h2>
	            </div>

			    <?php foreach( $posts as $post): ?>
			        <?php setup_postdata($post); ?>
			        <?php $thumb_url =  wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
			        <?php $params_news_img = array( 'width' => 470, 'height' => 210 ); ?>

			        <div class="col-md-6 event-col">
			        	<h3><?php echo get_the_title($post->ID); ?></h3>
			        	<!-- <a href="<?php the_permalink(); ?>" class=""> -->
			        		<p><?php echo get_the_excerpt($post->ID); ?></p>
			        	<!-- </a> -->
			        </div>

			        <div class="col-md-6 event-col">
				        <a href="<?php echo get_post_permalink($post->ID); ?>" class="">
				        	<img class="related_posts-image w-100" src="<?php echo bfi_thumb( $thumb_url, $params_news_img  ); ?>">
				        </a>
			        </div>
				       

				<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</div>
	<?php
	endif;

}


// content -> excert 
// length -> 17
// more -> none
function wp_trim_all_excerpt($text) {
    global $post;
       $raw_excerpt = $text;
       if ( '' == $text ) {
          $text = get_the_content('');
          $text = strip_shortcodes( $text );
          $text = apply_filters('the_content', $text);
          $text = str_replace(']]>', ']]&gt;', $text);
       }
    $text = strip_tags($text);
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '');
    $text = wp_trim_words( $text, $excerpt_length, $excerpt_more ); 
    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt); 
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wp_trim_all_excerpt');



function woocommerce_template_loop_product_link_open_new_tab() {
	global $product;
	$id = $product->id;
	$design_url = vpc_get_configuration_url($id);
	echo '<a href="' . $design_url . '" class="woocommerce-LoopProduct-link ">';
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open_new_tab', 10 );


remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    } 
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {   
    function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce;

		//$thumb_url =  wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); 
		$thumb_url =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
		$thumb_url_full =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$params_zoom = array( 'width' => 445 , 'height' => 445);
        if ( has_post_thumbnail() ) {               
            //$output .= get_the_post_thumbnail( $post->ID, $size );
            //$output .= '<img class="attachment-shop_catalog size-shop_catalog wp-post-image" src="'.$thumb_url[0].'" data-zoom-image="'.$thumb_url_full[0].'">';              
        	$output .= '<img class="attachment-shop_catalog size-shop_catalog wp-post-image" src="'.$thumb_url[0].'" data-zoom-image="'.bfi_thumb( $thumb_url_full[0], $params_zoom ).'">';
        }                       

        return $output;
    }
}


/*
 * Hides the 'Free!' price notice
 */
add_filter( 'woocommerce_variable_free_price_html',  'hide_free_price_notice' );
add_filter( 'woocommerce_free_price_html',           'hide_free_price_notice' );
add_filter( 'woocommerce_variation_free_price_html', 'hide_free_price_notice' );
function hide_free_price_notice( $price ) {
  return '';
}

/* Add VAT FIELD */

// Hook in WooCommerce checkout fields and add new field
add_filter( 'woocommerce_checkout_fields' , 'add_field_to_checkout' );

// Our hooked in function - $fields is passed via the filter!
function add_field_to_checkout( $fields ) {

$fields['billing']['billing_fieldname'] = array(
    'label' => __('VAT number', 'woocommerce'),
    'placeholder' => _x('Your VAT number', 'placeholder', 'woocommerce'),
    'required' => false,
    'class' => array('form-row-wide'),
    'clear' => true
  );

return $fields;
}

//Add info on Admin
function add_field_to_admin($order){
  echo "<p><strong>VAT:</strong> " . $order->order_custom_fields['_billing_fieldname'][0] . "</p>";
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'add_field_to_admin', 10, 1 );


// add customlogo svg
function change_default_storefront_header() {
	remove_action( 'storefront_header', 'storefront_site_branding', 20 );
}
add_action( 'wp_head', 'change_default_storefront_header' );

add_action( 'storefront_header', 'storefront_site_branding_custom', 21 );
function storefront_site_branding_custom() {
	?>
	<div class="site-branding">
		<?php storefront_site_title_or_logo_custom(); ?>
	</div>
	<?php
}
function storefront_site_title_or_logo_custom( $echo = true ) {
	// dgamoni
		$html = '<a href="'.esc_url( home_url( '/' ) ).'" class="site-logo-link" rel="home" itemprop="url">';
		$html .= '<img width="216" height="62" src="'. get_stylesheet_directory_uri().'/assets/img/logo.svg" class="custom-logo" alt="" itemprop="logo">';
		$html .= '</a>';

	if ( ! $echo ) {
		return $html;
	}

	echo $html;
}

//add back to store button after cart
add_action('woocommerce_cart_collaterals', 'themeprefix_back_to_store');
function themeprefix_back_to_store() { ?>
<div class="cart_totals-left">
	<a class="button wc-backward" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php _e( 'Continue shopping', 'woocommerce' ) ?></a>
</div>
<?php
}

// fix v7

// mini cart filter fragmen
function my_child_theme_setup() {
    remove_filter( 'woocommerce_add_to_cart_fragments', 'storefront_cart_link_fragment' );
    add_filter( 'woocommerce_add_to_cart_fragments', 'storefront_cart_link_fragment_custom' );
}
add_action( 'after_setup_theme', 'my_child_theme_setup' );

function storefront_cart_link_fragment_custom( $fragments ) {
	global $woocommerce;

	ob_start();
	storefront_cart_link_new();
	$fragments['a.cart-contents'] = ob_get_clean();

	ob_start();
	storefront_handheld_footer_bar_cart_link();
	$fragments['a.footer-cart-contents'] = ob_get_clean();

	return $fragments;
}

function storefront_cart_link_new() {
	?>
		<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'storefront' ); ?>">
			<span class="amount">
				<?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?>
			</span> <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', custom_count(), 'storefront' ), custom_count() ) );?></span>
		</a>
	<?php
}

function custom_count() {
    $counnt =0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( !$cart_item['vpc-is-secondary-product'] ) :
			$counnt++;
		endif;
	}
	return $counnt;
}


