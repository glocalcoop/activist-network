<?php

function process_site_categories_accorion_display($content, $data, $args) {
	global $site_categories;

//	echo "args<pre>"; print_r($args); echo "</pre>";
//	echo "data<pre>"; print_r($data); echo "</pre>";

	if ((isset($data['categories'])) && (count($data['categories']))) {

		$content .= '<div id="site-categories-wrapper">';

		$content .= '<div class="site-categories-accordion" style="width: 100%; float: left;">';

		foreach ($data['categories'] as $category) {

			$content .= '<div class="site-categories-accordion-header">';

			//if ($category->count > 0)
			//	$content .= '<a href="#">';

			if ( ($args['icon_show'] == true) && (isset($category->icon_image_src)) && (strlen($category->icon_image_src)) ) {
				if (is_ssl()) {
					$image_src = str_replace('http://', 'https://', $category->icon_image_src);
				} else {
					$image_src = $category->icon_image_src;
				}

				$content .= '<div style="float: left; width: '.$args['icon_size'] .'px; margin-right: 10px;"><img class="site-category-icon" width="'. $args['icon_size'] .'" height="'. $args['icon_size'] .'" alt="'. $category->name .'" src="'. $image_src .'" /></div>';
			}

			$content .= '<div style="float: left;"><span class="site-category-title">'. $category->name .'</span>';

			if ($args['show_counts']) {
				//$count = $category->count;
				//if ($count == 0) {
				//	if ((isset($category->children_count)) && ($category->children_count > 0)) {
						$count = $category->children_count;
				//	}
				//}
				$content .= '<span class="site-category-count">('. $count .')</span>';
			}

			$content .= '</div>';
			$content .='</div>';

			$content .= '<div class="site-categories-accordion-details">';

			if ($category->count > 0) {
				$content .= '<span class="site-category-parent-link" style="float:right;">'. __('view', SITE_CATEGORIES_I18N_DOMAIN) .' ';
				$content .= '<a href="'. $category->bcat_url.'">'. $category->name .'</a>';
				if ($args['show_counts']) {
					$content .= '<span class="site-category-count">('. $category->count .')</span>';
				}
				$content .= '</span>';
			}

			if (($args['show_description']) && (strlen($category->description))) {

				$bact_category_description = wpautop(stripslashes($category->description));
				$bact_category_description = str_replace(']]>', ']]&gt;', $bact_category_description);

				if (strlen($bact_category_description)) {
					$content .= '<div class="site-category-description-parent">'. $bact_category_description .'</div>';
				}
			}

			if ((isset($category->children)) && (count($category->children))) {

				$walker = new BCat_Walker_WidgetCategoryDropdown;
				$args['walker'] = $walker;
				$args['show_style'] = $args['show_style_children'];
				$args['hierarchical'] = 0;

				if (($args['show_style_children'] == "ul-nested") || ($args['show_style_children'] == "ul")) {
	 				$content .= '<ul class="site-categories-children site-categories-children-list">';
				} else if (($args['show_style_children'] == "ol-nested") || ($args['show_style_children'] == "ol")) {
	 				$content .= '<ol class="site-categories-children site-categories-children-list">';

				} else if (($args['show_style_children'] == "select-nested") || ($args['show_style_children'] == "select")) {
					$content .= '<select id="site-categories-list-'. $category->slug .'" class="site-categories-children site-categories-children-list">';
					$content .= '<option value="">'. __('Select Category', SITE_CATEGORIES_I18N_DOMAIN) .'</option>';

				}
				$content .= $site_categories->walk_category_dropdown_tree( $category->children, 10, $args );

				if (($args['show_style_children'] == "ul-nested") || ($args['show_style_children'] == "ul")) {
	 				$content .= '</ul>';
				} else if (($args['show_style_children'] == "ol-nested") || ($args['show_style_children'] == "ol")) {
	 				$content .= '</ol>';
 				} else if (($args['show_style_children'] == "select-nested") || ($args['show_style_children'] == "select")) {
					$content .= '</select>';
					$content .= '<script type="text/javascript">
					/* <![CDATA[ */
						var dropdown_'. $category->slug .' = document.getElementById("site-categories-list-'. $category->slug .'");
						function onCatChange_'. $category->slug .'() {
							var selected_index = dropdown_'. $category->slug .'.selectedIndex;
							var href = dropdown_'. $category->slug .'.options[selected_index].value;
							if (href != "") {
								window.location.href = href;
							}
						}
						dropdown_'. $category->slug .'.onchange = onCatChange_'.$category->slug.';
					/* ]]> */
					</script>';

				}
			}
			$content .= '</div>';
		}

		$content .= "</div>";

		if ((isset($data['prev'])) || (isset($data['next']))) {

			$content .= '<div id="site-categories-navigation">';

			if (isset($data['prev'])) {
				$content .= '<a href="'. $data['prev']['link_url'] .'">'. $data['prev']['link_label'] .'</a>';
			}

			if (isset($data['next'])) {
				$content .= '<a href="'. $data['next']['link_url'] .'">'. $data['next']['link_label'] .'</a>';
			}
			$content .= '</div>';
		}
		$content .= "</div>";
	}

	return $content;
}
add_filter('site_categories_landing_accordion_display', 'process_site_categories_accorion_display', 99, 3);