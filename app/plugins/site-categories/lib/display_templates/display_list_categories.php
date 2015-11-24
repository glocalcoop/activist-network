<?php

function process_site_categories_list_display($content, $data, $args) {
	global $site_categories;

	//echo "args<pre>"; print_r($args); echo "</pre>";
	//echo "data<pre>"; print_r($data); echo "</pre>";

	if ((isset($data['categories'])) && (count($data['categories']))) {

		$content .= '<div id="site-categories-wrapper">';

		if (($args['show_style'] == "ul-nested") || ($args['show_style'] == "ol-nested")
		 || ($args['show_style'] == "select-flat") || ($args['show_style'] == "select-nested")) {
			//echo "args<pre>"; print_r($args); echo "</pre>";

			if (($args['show_style'] == "ul-nested") || ($args['show_style'] == "ol-nested")) {
				if (count($data['categories']) > $args['per_page']) {
					echo "categories[". count($data['categories']) ."] per_page[". $args['per_page'] ."]<br />";
				}
			}

			if ($args['show_style'] == "ol-nested") {
				$content .= '<ol class="site-categories site-categories-list">';
			} else if ($args['show_style'] == "ul-nested") {
				$content .= '<ul class="site-categories site-categories-list">';
			} else if (($args['show_style'] == "select-nested") || ($args['show_style'] == "select-flat")) {
				$content .= '<select id="site-categories-list-'. $category->slug .'" class="site-categories site-categories-list">';
				$content .= '<option value="">'. __('Select Category', SITE_CATEGORIES_I18N_DOMAIN) .'</option>';
			}

			$walker = new BCat_Walker_WidgetCategoryDropdown;
			$args['walker'] = $walker;
			$args['category'] = -1;
			$args['number'] = $args['per_page'];
			//$args['per_page'] = -1;
			//$args['show_style'] = $args['show_children'];
			//$args['hierarchical'] = 0;

			$content .= $site_categories->walk_category_dropdown_tree( $data['categories'], 10, $args );
			//echo "content[". $content ."]<br />";

			if ($args['show_style'] == "ol-nested") {
				$content .= '</ol>';
			} else if ($args['show_style'] == "ul-nested") {
				$content .= '</ul>';
			} else if (($args['show_style'] == "select-nested") || ($args['show_style'] == "select-flar")) {
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

		} else {

			if ($args['show_style'] == "ol") { $content .= '<ol class="site-categories site-categories-list">'; }
			else if ($args['show_style'] == "select") { $content .= '<select class="site-categories site-categories-select">'; }
			else { $content .= '<ul class="site-categories site-categories-list">'; }

			foreach ($data['categories'] as $category) {
				//echo "category<pre>"; print_r($category); echo "</pre>";
				if ($args['show_style'] != "select") {

					$content .=	'<li>';
						if ($category->count > 0)
							$content .= '<a href="'. $category->bcat_url .'">';

						if ( ($args['icon_show'] == true) && (isset($category->icon_image_src)) && (strlen($category->icon_image_src)) ) {
							if (is_ssl()) {
								$image_src = str_replace('http://', 'https://', $category->icon_image_src);
							} else {
								$image_src = $category->icon_image_src;
							}

							$content .= '<img class="site-category-icon" width="'. $args['icon_size'] .'" height="'. $args['icon_size'] .'" alt="'. $category->name .'" src="'. $image_src .'" />';
						}
						$content .= '<span class="site-category-title">'. $category->name .'</span>';
						if ($args['show_counts']) {
							$content .= '<span class="site-category-count">('. $category->count .')</span>';
						}

						if ($category->count > 0)
							$content .= '</a>';

						if (($args['show_description']) && (strlen($category->description))) {
							$bact_category_description = wpautop(stripslashes($category->description));
							$bact_category_description = str_replace(']]>', ']]&gt;', $bact_category_description);
							if (strlen($bact_category_description)) {
								$content .= '<div class="site-category-description">'. $bact_category_description .'</div>';
							}
						}

					$content .= '</li>';
				} else {
					$content .= '<option value="'. $category->bcat_url .'">'. $category->name .'</option>';
				}
			}

			if ($args['show_style'] == "ol") { $content .= "</ol>"; }
			else if ($args['show_style'] == "select") { $content .= "</select>"; }
			else { $content .= "</ul>"; }
		}

		if ((isset($data['prev'])) || (isset($data['next']))) {

			$content .= '<div id="site-categories-navigation">';

			if (isset($data['prev'])) {
				$content .= '<a class="site-categories-prev" href="'. $data['prev']['link_url'] .'">'. $data['prev']['link_label'] .'</a>';
			}

			if (isset($data['next'])) {
				$content .= '<a class="site-categories-next" href="'. $data['next']['link_url'] .'">'. $data['next']['link_label'] .'</a>';
			}
			$content .= '</div>';
		}
		$content .= '</div>';
	}

	return $content;
}
add_filter('site_categories_landing_list_display', 'process_site_categories_list_display', 99, 3);