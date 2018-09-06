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
							'alias'  => $college->name,
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
