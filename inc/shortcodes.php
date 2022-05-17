<?php

//table shortcode
add_shortcode( 'table', 'smexample_table' );

if ( ! function_exists( 'smexample_table' ) ) {
	/**
	 * Table shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 * @return void
	 */
	function smexample_table( $atts, $content ) {
		$a = shortcode_atts( array(
			'class'  => '',
			'width'  => '',
			'style'  => '',
			'head'   => 1,
			'fixed'  => 0,
			'center' => 0,
			'size'   => '',
		), $atts );

		$content = trim( strip_tags( $content ) );
		$content = do_shortcode( $content );

		$class = ( ! empty( $a['class'] ) ) ? sanitize_html_class( $a['class'] ) : '';
		$width = ( ! empty( $a['width'] ) ) ? 'width: ' . esc_attr( $a['width'] ) . ';' : '';
		$style = ( ! empty( $a['style'] ) ) ? esc_attr( $a['style'] ) : '';

		if ( ! empty( $a['fixed'] ) && ( $a['fixed'] === 1 || $a['fixed'] === 'true' ) ) {
			$class .= ' fixed';
		}

		if ( ! empty( $a['center'] ) && ( $a['center'] === 1 || $a['center'] === 'true' ) ) {
			$class .= ' center';
		}

		if ( ! empty( $a['size'] ) ) {
			$a['size']   = esc_attr( $a['size'] );
			$sizes_array = array_map( 'trim', (array) explode( ",", $a['size'] ) );
		}

		$array = array_map( 'trim', (array) explode( "\n", $content ) );

		if ( ! empty( $a['head'] ) && ( $a['head'] === 1 || $a['head'] === 'true' ) ) {
			$show_title = true;
			$th         = array_slice( $array, 0, 1 );
			$th_array   = explode( '|', $th[0] );
			$th_count   = count( $th_array );

			$td       = array_slice( $array, 1 );
			$td_array = array();
			foreach ( $td as $key ) {
				$td_array[] = explode( '|', $key );
			}
			array_multisort( $result = array_map( 'count', $td_array ), SORT_DESC );
			$td_count = ( $result ) ? $result[0] : 0;

		} else {
			$show_title = false;

			$td       = $array;
			$td_array = array();
			foreach ( $td as $key ) {
				$td_array[] = explode( '|', $key );
			}
		}

		$th_nm = 0;
		$tr_nm = 0;
		$td_nm = 0;

		$style_attr = ( $width || $style ) ? 'style="' . $width . $style . '"' : '';

		//open table
		$html = '<div class="table-responsive"><table class="table' . $class . '" ' . $style_attr . '>';

		//show title
		if ( $show_title ) {
			$html .= '<thead class="table__headline">';
			foreach ( $th as $row ) {
				$html    .= '<tr class="table__row">';
				$colspan = ( $th_count == 1 ) ? 'colspan="' . $td_count . '"' : '';

				foreach ( $th_array as $col ) {
					$th_nm ++;
					$html .= '<th class="table__head table__head--' . $th_nm . '" ' . $colspan . '>' . trim( $col ) . '</th>';
				}

				$html .= '</tr>';
			}
			$html .= '</thead>';
		}

		//table body
		$html .= '<tbody class="table__body">';
		foreach ( $td_array as $row ) {
			$tr_nm ++;
			$html .= '<tr class="table__row table__row--' . $tr_nm . '">';
			foreach ( $row as $col ) {

				$td_size = ( isset( $sizes_array[ $td_nm ] ) ) ? 'style="width: ' . $sizes_array[ $td_nm ] . ';"' : '';

				$td_nm ++;

				$html .= '<td class="table__cell table__cell--' . $td_nm . '" ' . $td_size . '>' . trim( $col ) . '</td>';

			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		//close table
		$html .= '</table></div>';

		return $html;

	}
}

add_shortcode( 'br', 'smexample_br' );
/**
 * Break the line shortcode
 *
 * @return void
 */
function smexample_br() {
	return '<br>';
}

add_shortcode( 'file', 'smexample_file' );
if ( ! function_exists( 'smexample_file' ) ) {
	/**
	 * Link to download shortcode (used along with table shortcode)
	 *
	 * @param array $atts
	 * @param string $content
	 * @return void
	 */
	function smexample_file( $atts, $content ) {
		$a = shortcode_atts(
			array(
				'class' => '',
				'icon'  => 1,
				'type'  => ''
			), $atts );

		$class = ( ! empty( $a['class'] ) ) ? ' ' . sanitize_html_class( $a['class'] ) : '';

		$html = '';

		if ( ! empty( $content ) ) {

			$name = $content;

			$path = parse_url($content)['path'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);

			if( !empty($a['type']) ) $ext = $a['type'];

			if( $a['icon'] == true || $a['icon'] === 'true' ) {
				$name = '<span class="file file--' . $ext . '"></span>';
			}

			$html .= '<a class="file-link ' . $class . '" href="' . $content . '">' . $name . '</a> ';
		}
		return $html;
	}
}


add_shortcode( 'chart', 'smexample_chart' );
if ( ! function_exists( 'smexample_chart' ) ) {
	/**
	 * Chart shortcode - return container with options as data attribute
	 *
	 * @param array $atts
	 * @param string $content
	 * @return void
	 */
	function smexample_chart( $atts, $content ) {
		$a = shortcode_atts( array(
			'class'  => '',
		), $atts );

		$content = trim( strip_tags( $content ) );
		$content = do_shortcode( $content );

		$array = array_map( 'trim', (array) explode( "\n", $content ) );

		$th         = array_slice( $array, 0, 1 );
		$th_array   = explode( '|', $th[0] );
		$th_array   = array_map('trim', $th_array);

		$td       = array_slice( $array, 1 );
		$td_array = array();

		$i = 0;

		foreach ( $td as $key ) {
			$key = str_replace(' ', '', $key);
			$td_array[] = explode( '|', $key );
		}

		$ob = [];
		for ($i = 0; $i < count($td_array); $i++) {
			$row = new stdClass;
			for ($j = 0; $j < count($td_array[$i]); $j++) {
				$name = $th_array[$j];
				$row->$name = $td_array[$i][$j];
			}
			$ob[] = $row;
		}

		//good idea is to add colors as parameter in future :)
		$colors = array('#7A231D', '#B5332D');

		return '<div class="report__chart" data-dimm=\''.json_encode($th_array).'\' data-source=\''.json_encode($ob).'\' data-color=\''.json_encode($colors).'\'></div>';
	}
}

add_shortcode('reports', 'smexample_reports');
/**
 * Reports shortcode - get latest posts with selected type
 *
 * @param array $atts
 * @param string $content
 * @return void
 */
function smexample_reports($atts, $content = null) {
	
	global $post;
	
	extract(shortcode_atts(array(
		'cat'       => '',
		'post_type' => 'report',
		'num'       => '3',
		'order'     => 'DESC',
		'orderby'   => 'post_date',
	), $atts));
	
	$args = array(
		'cat'            => $cat,
		'posts_per_page' => $num,
		'order'          => $order,
		'orderby'        => $orderby,
		'post_type'      => $post_type
	);
	
	$output = '';
	
	$posts = get_posts($args);
	
	if(! empty($posts) ) {
		foreach($posts as $post) {
			
			setup_postdata($post);

			$post_id = get_the_ID();

			$subname = smexample_get_meta($post_id, 'subname' );
			$file = smexample_get_meta($post_id, 'file' );
			
			$output .= '<li class="latest-reports__item">
				<a class="latest-reports__link" href="'. get_the_permalink() .'">';

					if( !empty($subname) ) $output .= '<span class="latest-reports__subname">' . $subname . '</span>';

					if( !empty($file) ) {
						$path = parse_url($file)['path'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
						$output .= '<span class="latest-reports__file file file--' . $ext . '"></span>';
					}

					$output .= '<span class="latest-reports__title">' . get_the_title(). '</span>';
					$output .= '<span class="latest-reports__excerpt">' . get_the_excerpt(). '</span>';
			$output .= '</a>
			</li>';
			
		}
	} else {
		return '<ul class="latest-reports">'. __('Nie ma raportów :)', 'smexample') .'</ul>';
	}

	$output .= '<li class="latest-reports__item latest-reports__item--more">
	<a class="latest-reports__btn btn" href="' . get_post_type_archive_link($post_type) . '">' . __('Więcej', 'smexample') . '</a>
	</li>';
	
	wp_reset_postdata();
	return '<ul class="latest-reports">'. $output .'</ul>';
}
?>