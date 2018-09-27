<?php
// Theme foundation
include_once 'includes/config.php';
include_once 'includes/template-twocol-functions.php';

// Plugin extras/overrides
if ( ! function_exists( 'ucf_degree_external_list_card_order' ) ) {
	/**
	 * Overrides the sorting of the degrees
	 *
	 * @param Object $items The decoded JSON of degree data
	 * @param string $layout The layout currently being used
	 * @param array $args Any additional arguments passed in from the shortcode
	 */
	function ucf_degree_external_list_card_order( $items, $layout, $args ) {
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
	function ucf_degree_external_list_twocol_layout( $items, $args, $retval ) {
		$heading_element = isset( $args['group_heading'] ) ? $args['group_heading'] : 'h3';
		$item_count = 0;

		// Get accuracte count of items
		foreach( $items->types as $group ) {
			$item_count += count( $group->degrees );
		}

		$col_split = ceil( $item_count / 2 );
		$split = false;

		$item_count = 0;

		ob_start();

		if ( $items && isset( $items->types ) && is_array( $items->types ) ):
			foreach( $items->types as $index => $group ) :
				if ( $index === 0 ) :
			?>
				<div class="row"><div class="col-lg-6">
			<?php elseif ( $item_count + count( $group->degrees ) > $col_split && $split === false ) : $split = true; ?>
				</div><div class="col-lg-6">
			<?php elseif ( $index === count( $items->types ) - 1 ) : ?>
				</div></div>
			<?php endif; $item_count += count( $group->degrees ); ?>
				<<?php echo $heading_element; ?>><?php echo $group->alias; ?></<?php echo $heading_element; ?>>
				<ul>
			<?php foreach( $group->degrees as $degree ) : ?>
					<li><a href="<?php echo $degree->url; ?>"><?php echo $degree->title; ?></a>
			<?php endforeach; ?>
				</ul>
		<?php
			endforeach;
		else:
			echo '<p>No results found.</p>';
		endif;

		return ob_get_clean();
	}

	add_filter( 'ucf_degree_external_list_twocol', 'ucf_degree_external_list_twocol_layout', 10, 3 );
}
