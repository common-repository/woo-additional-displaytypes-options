<?php

function wado_archive_description() {
	if ( is_tax( array( 'product_cat', 'product_tag' ) ) && 0 === absint( get_query_var( 'paged' ) ) ) {
		$description = wc_format_content( term_description() );
		if ( $description ) {
			$term = get_queried_object();
			$thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
			$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'shop_thumbnail' );
			$image = wp_get_attachment_image_src($thumbnail_id, $small_thumbnail_size);
			$image ? $image_gui = '<img src="'.$image[0].'" width"'.$image[1].'" height="'.$image[2].'" />' : $image_gui = '';
			
			if (is_product_taxonomy() && 0 === absint( get_query_var('paged'))) {
				$description = $term->description; //wc_format_content(term_description());

				if ($description) {
					$noofchars = 550;
					$lessgui = mb_substr($description, 0, $noofchars, 'UTF-8');
					$more_text = mb_substr($description, $noofchars, strlen($description) - $noofchars, 'UTF-8');
					$moregui = '';
					if ($more_text) {
						$moregui =	'
									<span class="wado-read-more-dots"> ... </span>
									<span class="wado-read-more-target">'.$more_text.'</span>
									<span class="wado-read-more-target"><?php $more_text ?></span>
									';
						$buttongui =	'
										<button class="wado-read-trigger wado-more">'.__('Read more', 'wado').'</button>
										<button class="wado-read-trigger wado-less">'.__('Read less', 'wado').'</button>
										';
					}
					?>
					<div class="term-description wado-index wado-less">
						<p class="wado-read-more-wrap">
							<?php
							 echo $image_gui.$lessgui.$moregui;
							?>
						</p>
						<?php
						 echo $buttongui;
						?>
					</div>
					<?php
				}
			}
		}
	}
}	

function wado_product_subcategories_indexed() {
	global $woocommerce_loop;
	
	$term = get_queried_object();
	$categories = get_terms(array('parent' => $term->term_id, 'taxonomy' => 'product_cat', 'order' => 'ASC'));
	$cat_letters = array();
	$last_letter = '';
	foreach($categories as $category) {
		$letter = '';
		if (isset($category->name) && strlen($category->name) > 0) {
			$letter = normalizer_normalize(strtolower(substr($category->name, 0, 1)));
			if ($letter != $last_letter && $letter != '') $cat_letters[] = new WADO_Index_Letter($letter);
			$cat_letters[] = new WADO_Index_Category($category);
			$last_letter = $letter;
		}
	}
	$rows_total = count($cat_letters);
	$col_current = 0;
	$legal_letters = 'abcdefghijklmnopqrstuvwxyz';
	foreach($categories as $category) {
		?>
		<li <?php wc_product_cat_class( 'wado-indexed', $category ); ?>>
			<?php
			$cols = $woocommerce_loop['columns'];
			$rows = ceil($rows_total / $cols);
			?>
			<ul class="wado-index-list">
				<?php
				$row_current = 0;
				for ($c = $col_current * $rows; $c <= ($col_current + 1) * $rows - 1; $c++) {
					if (isset($cat_letters[$c]->type) && $cat_letters[$c]->type == 'letter') {
						?>
						<li class="wado-letter-group">
							<h2><?php echo strtoupper($cat_letters[$c]->name); ?></h2>
						</li>
						<?php
						$row_current++;
					} elseif (isset($cat_letters[$c]->type) && $cat_letters[$c]->type == 'category') {
						?>
						<li>
							<a href="<?php echo get_category_link($term->term_id).$cat_letters[$c]->category->slug; ?>" class="wado-index-link">
								<h2 class="woocommerce-loop-category__title" title="<?php echo $cat_letters[$c]->category->name.'('.($cat_letters[$c]->category ? $cat_letters[$c]->category->count : '0').')' ?>" ><?php echo $cat_letters[$c]->category->name; ?><mark class="count"> (<?php echo $cat_letters[$c]->category ? $cat_letters[$c]->category->count : '0'; ?>)</mark></h2>
							</a>
						</li>
						<?php
						$row_current++;
					}
				}
				?>
			</ul>
		</li>
		<?php
		if ($woocommerce_loop['loop'] + 1 > $woocommerce_loop['columns']) {
			break;
		}
		$col_current++;
	}
}

?>