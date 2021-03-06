<?php
/**
 * Plugin extras/overrides for the UCF Degree Search Plugin
 */

if ( ! function_exists( 'ucf_degree_external_list_card_order' ) ) {
	/**
	 * Overrides the sorting of the degrees
	 *
	 * @since 1.0.0
	 * @author Jim Barnes
	 * @param object $items The decoded JSON of degree data
	 * @param array $args Any additional arguments passed in from the shortcode
	 */
	function ucf_degree_external_list_card_order( $items, $args ) {
		if ( ! $items ) {
			return $items;
		}

		$retval = clone $items;
		$retval->types = array();
		$colleges = array();

		foreach( $items->types as $type ) {
			$current_type = $type->alias;

			foreach( $type->degrees as $degree ) {
				foreach( $degree->colleges as $college ) {
					$degree->type = $current_type;

					if ( ! isset( $colleges[$college->slug] ) ) {
						$colleges[$college->slug] = (object)array(
							'alias'  => str_replace( 'College of ', '', $college->name ),
							'slug'  => $college->slug,
							'count' => 1,
							'degrees' => array(
								$degree
							)
						);
					} else {
						$colleges[$college->slug]->degrees[] = $degree;
						$colleges[$college->slug]->count++;
					}
				}
			}
		}

		ksort( $colleges );

		$retval->types = array_values( $colleges );

		return $retval;
	}

	add_filter( 'ucf_degree_external_list_sort_colleges', 'ucf_degree_external_list_card_order', 10, 3);
}


if ( ! function_exists( 'ucf_degree_external_list_twocol_layout' ) ) {
	/**
	 * Defines a new "twocol" layout for the [ucf-external-degree-list] shortcode
	 *
	 * @since 1.0.0
	 * @author Jim Barnes
	 * @param object $items The decoded JSON of degree data
	 * @param array $args Any additional arguments passed in from the shortcode
	 * @param string $retval The default returned markup
	 */
	function ucf_degree_external_list_twocol_layout( $items, $args, $retval ) {
		$heading_element = isset( $args['group_heading'] ) ? $args['group_heading'] : 'h3';
		$item_count = 0;

		// Get accuracte count of items
		if ( $items ) {
			foreach( $items->types as $group ) {
				$item_count += count( $group->degrees );
			}
		}

		// Figure out where we're going to split the columns
		$col_split = ceil( $item_count / 2 );
		$col_index = 0;
		$split = false;

		// Reset item count variable
		// We're going to use it to keep track of where we are now.
		$item_count = 0;

		ob_start();

		if ( $items && isset( $items->types ) && is_array( $items->types ) ):
			foreach( $items->types as $index => $group ) :
				$item_count += count( $group->degrees );

				if ( $index === 0 ) :
			?>
				<div class="row"><div class="col-lg-6">
			<?php elseif ( $col_index === 1 && $split === false ) : $split = true; ?>
				</div><div class="col-lg-6">
			<?php endif;  ?>
				<<?php echo $heading_element; ?>><?php echo $group->alias; ?></<?php echo $heading_element; ?>>
				<ul>
			<?php foreach( $group->degrees as $degree ) : ?>
					<li><a href="<?php echo $degree->url; ?>"><?php echo $degree->title; ?></a>
			<?php endforeach; ?>
				</ul>
		<?php
			// If we're over our split point,
			// move onto the next column.
			if ( $item_count > $col_split )
				$col_index = 1;

			endforeach;
		?>
		</div></div>
		<?php
		else:
			echo '<p>No results found.</p>';
		endif;

		return ob_get_clean();
	}

	add_filter( 'ucf_degree_external_list_twocol', 'ucf_degree_external_list_twocol_layout', 10, 3 );
}
