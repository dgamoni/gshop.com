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


add_action( 'homepage', 'galibelle_add_slider_storefront',      5 );

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
add_action( 'storefront_before_content', 'galibelle_add_slider_storefront', 5);
}


/* Remove default footer credits */
add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
}
