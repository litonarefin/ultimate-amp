<?php

	/*
	 * Ultimate AMP
	 * @author Liton Arefin
	 * @package Custom Nav Walker
	 */

	/**
	 *  	CUSTOM NAV WALKER
	 *		<li class="ampstart-nav-item ampstart-nav-dropdown relative ">
	 * 			<amp-accordion layout="container" disable-session-states="" class="ampstart-dropdown">
	 *				<section>
	 *					<header>Fashion</header>
	 *					<ul class="ampstart-dropdown-items list-reset m0 p0">
	 *						<li class="ampstart-dropdown-item">
	 *							<a href="#" class="text-decoration-none">Styling Tips</a>
	 *						</li>
	 *						<li class="ampstart-dropdown-item">
	 *							<a href="#" class="text-decoration-none">Designers</a>
	 *						</li>
	 *					</ul>
	 *				</section>
	 *			</amp-accordion>
	 *		</li>
	 *
	 *
	 */

	class UAMP_Nav_Menu_Walker extends Walker_Nav_Menu {

		private $show_parent_title 		 = false;

		public function start_lvl( &$output, $depth = 0, $args = array() ) {

			$indent = str_repeat( "\t", $depth );

			$output .= "\n$indent<ul class=\"ampstart-dropdown-items list-reset m0 p0\">\n";

		}


		public function end_lvl( &$output, $depth = 1, $args = array() ) {
			$output .= "</ul>";
			$output .= "</section></amp-accordion>";
		}


		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			if ( $args->has_children ){
				$output.= '<li class="ampstart-nav-item ampstart-nav-dropdown relative">';
				$output .= "<amp-accordion layout=\"container\" disable-session-states=\"\" class=\"ampstart-dropdown\">
					<section>";
				$output .= "<header>" . apply_filters( 'the_title', $item->title, $item->ID ) . "</header>";

			}


			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
				$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
			} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
				$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
			} else {

				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;

				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

				if ( $args->has_children )
					$class_names .= ' dropdown';

				if ( in_array( 'current-menu-item', $classes ) )
					$class_names .= ' active';

				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				if ( ! $args->has_children && $depth === 0 ) {
					$output .= $indent . '<li class="ampstart-nav-item">';
				}
				if($depth === 1){
					$output .= $indent . '<li class="ampstart-dropdown-item">';
				}

				$atts = array();
				$atts['title']  = ! empty( $item->title )	? $item->title	: '';
				$atts['target'] = ! empty( $item->target )	? $item->target	: '';
				$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

				// If item has_children add atts to a.
				if ( $args->has_children && $depth === 0 ) {
//					$atts['class']			= '';
				} else {
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				}

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$item_output = $args->before;


				if ( ! empty( $item->attr_title ) )
					$item_output .= '<a class="ampstart-nav-link"'. $attributes .'>&nbsp;';
				elseif( $args->has_children && $depth === 0 )
					$item_output .= '';
				elseif( $depth === 1 )
					$item_output .= '<a class="text-decoration-none"'. $attributes .'>';

				else
					$item_output .= '<a class="ampstart-nav-link"'. $attributes .'>';

				if( !$args->has_children){
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				}

				$item_output .= ! empty($item->description) ? '<span class="sub">' . $item->description . '</span>' : '';
				$item_output .= ( $args->has_children && 0 === $depth ) ? '' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}


		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element )
				return;

			$id_field = $this->db_fields['id'];

			// Display this element.
			if ( is_object( $args[0] ) )
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {

				extract( $args );

				$fb_output = null;

				if ( $container ) {
					$fb_output = '<' . $container;

					if ( $container_id )
						$fb_output .= ' id="' . $container_id . '"';

					if ( $container_class )
						$fb_output .= ' class="' . $container_class . '"';

					$fb_output .= '>';
				}

				$fb_output .= '<ul';

				if ( $menu_id )
					$fb_output .= ' id="' . $menu_id . '"';

				if ( $menu_class )
					$fb_output .= ' class="' . $menu_class . '"';

				$fb_output .= '>';
				$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
				$fb_output .= '</ul>';

				if ( $container )
					$fb_output .= '</' . $container . '>';

				echo $fb_output;
			}
		}




	}