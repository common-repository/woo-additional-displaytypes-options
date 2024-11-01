<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>
		
		<?php

			$term = get_queried_object();
			$parent_display_type = get_woocommerce_term_meta($term->parent, 'display_type', true);
		
			switch ($parent_display_type) {
				case 'products':
				case 'subcategories':
				case 'both':
				case '':
					do_action( 'woocommerce_archive_description' );
					break;
				case 'subcategoriesindexed':
				case 'bothsubcategoriesindexed':
					wado_archive_description();
					break;
			}
		?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php
				$term = get_queried_object();
				$display_type = get_woocommerce_term_meta($term->term_id, 'display_type', true);
				
				switch ($display_type) {
					case 'products':
					case 'subcategories':
					case 'both':
					case '':
						woocommerce_product_loop_start();
						woocommerce_product_subcategories();
						while ( have_posts() ) : the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile;
						woocommerce_product_loop_end();
						break;
					
					case 'subcategoriesindexed':
						woocommerce_product_loop_start();
						wado_product_subcategories_indexed();
						woocommerce_product_loop_end();
						break;
						
					case 'bothsubcategoriesindexed':
						woocommerce_product_loop_start();
						wado_product_subcategories_indexed();
						woocommerce_product_loop_end();
						
						woocommerce_product_loop_start();
						while ( have_posts() ) : the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile;
						woocommerce_product_loop_end();
						break;
				}
			?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>

