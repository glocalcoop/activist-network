<?php
/**
 * Shows all Blog categories.
 */
class Bcat_WidgetCategorySites extends WP_Widget {

	function Bcat_WidgetCategorySites () {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Shows a list of all sites that belong to selected site category.', SITE_CATEGORIES_I18N_DOMAIN));
		parent::WP_Widget(__CLASS__, __('Sites from a single site category', SITE_CATEGORIES_I18N_DOMAIN), $widget_ops);
	}

	function form($instance) {

		global $current_site;

		$defaults	=	array(
			'title'					=>	'',
			'category'				=>	0,
			'include_children'		=>	'',
			'category_filter'		=>	'',
			'category_relation'		=>	'OR',
			'category_ids'			=>	'',
			'blog_filter'			=>	'',
			'blog_ids'				=>	'',
			'per_page'				=>	5,
			'show_style'			=>	'ul',
			'icon_show' 			=> 	1,
			'icon_size'				=>	32,
			'ordering' 				=> 	'name',
			'order' 				=> 	'ASC',
			'open_blank'			=>	0,
			'show_more_link'		=>	1,
			'landing_link_label'	=>	__('more sites', SITE_CATEGORIES_I18N_DOMAIN)
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		if (isset($instance['include_children'])) {
			if ($instance['include_children'] == "1")
				$instance['include_children'] = 'on';
			else if ($instance['include_children'] == "0")
				$instance['include_children'] = '';

		}

		//echo "instance<pre>"; print_r($instance); echo "</pre>";

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>"
				class="widefat" value="<?php echo $instance['title'] ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Site Category:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<?php
				switch_to_blog( $current_site->blog_id );

				$bcat_args = array(
					'taxonomy'			=> 	SITE_CATEGORIES_TAXONOMY,
					'orderby'			=>	'name',
					'order'				=>	'ASC',
					'hierarchical'		=>	true,
					'hide_empty'		=>	false,
					'show_count'		=>	true,
					'show_option_none'	=>	__('Show All', SITE_CATEGORIES_I18N_DOMAIN),
					'name'				=>	$this->get_field_name('category'),
					'class'				=>	'widefat',
					'selected'			=>	$instance['category']
				);

				wp_dropdown_categories( $bcat_args );
				restore_current_blog();
			?><input type="checkbox" id="<?php echo $this->get_field_id( 'include_children' ); ?>" <?php if ($instance['include_children'] == "on") {
				echo ' checked="checked" '; } ?> name="<?php echo $this->get_field_name( 'include_children'); ?>"  /> <label for="<?php echo $this->get_field_id( 'include_children' ); ?>"><?php _e('Include child categories? If not set only top-level categories will be included', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_style') ?>"><?php _e('Display as:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			<select id="<?php echo $this->get_field_id( 'show_style' ); ?>"
				name="<?php echo $this->get_field_name( 'show_style'); ?>" class="widefat" style="width:100%;">
				<option value="ol" <?php if ($instance['show_style'] == "ol") { echo ' selected="selected" '; }?>><?php
					_e('Ordered List (ol)', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="ul" <?php if ($instance['show_style'] == "ul") { echo ' selected="selected" '; }?>><?php
					_e('Unordered List (ul)', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="select" <?php if ($instance['show_style'] == "select") { echo ' selected="selected" '; }?>><?php _e('Dropdown (select)', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('category_filter') ?>"><?php _e('Site Categories Include/Exclude:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'category_filter' ); ?>"
				name="<?php echo $this->get_field_name( 'category_filter'); ?>" class="widefat">
				<option value="" <?php if ($instance['category_filter'] == "") { echo ' selected="selected" '; }?>><?php
					_e('Show All', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="include" <?php if ($instance['category_filter'] == "include") { echo ' selected="selected" '; }?>><?php
					_e('Include (OR)', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="include-and" <?php if ($instance['category_filter'] == "include-and") { echo ' selected="selected" '; }?>><?php
					_e('Include (AND)', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="exclude" <?php if ($instance['category_filter'] == "exclude") { echo ' selected="selected" '; }?>><?php
					_e('Exclude', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="exclude_tree" <?php if ($instance['category_filter'] == "exclude_tree") { echo ' selected="selected" '; }?>><?php
					_e('Exclude Tree', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select><br />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category_ids') ?>"><?php _e('Site Category IDs (comma seperated):', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('category_ids'); ?>" id="<?php echo $this->get_field_id('category_ids'); ?>"
				class="widefat" value="<?php echo $instance['category_ids'] ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('blog_filter') ?>"><?php _e('Blogs Include/Exclude:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'blog_filter' ); ?>"
				name="<?php echo $this->get_field_name( 'blog_filter'); ?>" class="widefat">
				<option value="" <?php if ($instance['blog_filter'] == "") { echo ' selected="selected" '; }?>><?php
					_e('Show All', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="include" <?php if ($instance['blog_filter'] == "include") { echo ' selected="selected" '; }?>><?php
					_e('Include', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="exclude" <?php if ($instance['blog_filter'] == "exclude") { echo ' selected="selected" '; }?>><?php
					_e('Exclude', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select><br />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('blog_ids') ?>"><?php _e('Blog IDs (comma seperated):', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('blog_ids'); ?>" id="<?php echo $this->get_field_id('blog_ids'); ?>"
				class="widefat" value="<?php echo $instance['blog_ids'] ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'per_page' ); ?>"><?php
				_e('Number of Sites to show (0 for all):', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="text" id="<?php echo $this->get_field_id( 'per_page' ); ?>" value="<?php echo $instance['per_page']; ?>"
				name="<?php echo $this->get_field_name( 'per_page'); ?>" class="widefat" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('ordering') ?>"><?php _e('Ordering:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'ordering' ); ?>"
				name="<?php echo $this->get_field_name( 'ordering'); ?>" class="widefat" style="width:60%;">
				<option value="name" <?php if ($instance['ordering'] == "name") { echo ' selected="selected" '; }?>><?php
					_e('Name', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="id" <?php if ($instance['ordering'] == "id") { echo ' selected="selected" '; }?>><?php
					_e('Site ID', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="registered" <?php if ($instance['ordering'] == "registered") { echo ' selected="selected" '; }?>><?php
					_e('Registered Date', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="last_updated" <?php if ($instance['ordering'] == "last_updated") { echo ' selected="selected" '; }?>><?php
					_e('Last Updated Date', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select> <select id="<?php echo $this->get_field_id( 'order' ); ?>"
				name="<?php echo $this->get_field_name( 'order'); ?>" class="widefat" style="width:25%;">
				<option value="ASC" <?php if ($instance['order'] == "ASC") { echo ' selected="selected" '; }?>><?php
					_e('ASC', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="DESC" <?php if ($instance['order'] == "DESC") { echo ' selected="selected" '; }?>><?php
					_e('DESC', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select>
		</p>


		<?php
		if (function_exists('get_blog_avatar')) {
			?>
			<p>
				<label for="<?php echo $this->get_field_id('icon_show') ?>"><?php _e('Show Blog icons:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
				<input type="radio" name="<?php echo $this->get_field_name( 'icon_show'); ?>" id="<?php echo $this->get_field_id('icon_show') ?>_yes"
					value="1" <?php if ($instance['icon_show'] == "1") { echo ' checked="checked" '; } ?> /> <label for="<?php
						echo $this->get_field_id('icon_show') ?>_yes"><?php _e('Yes', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

				<input type="radio" name="<?php echo $this->get_field_name( 'icon_show'); ?>" id="<?php echo $this->get_field_id('icon_show') ?>_no"
					value="0" <?php if ($instance['icon_show'] == "0") { echo ' checked="checked" '; } ?> /> <label for="<?php
						echo $this->get_field_id('icon_show') ?>_no"><?php _e('No', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('icon_size') ?>"><?php _e('Icon size:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('icon_size'); ?>" id="<?php echo $this->get_field_id('icon_size'); ?>"
					class="" size="5" value="<?php echo $instance['icon_size'] ?>"/>px <?php _e('square', SITE_CATEGORIES_I18N_DOMAIN); ?>
			</p>
			<?php
		} else {
			_e('<p>Blog Avatar require <a target="_blank" href="http://premium.wpmudev.org/project/avatars">Avatars</a> plugin.</p>', SITE_CATEGORIES_I18N_DOMAIN);
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('open_blank') ?>"><?php _e('Open links in new window:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<input type="radio" name="<?php echo $this->get_field_name( 'open_blank'); ?>" id="<?php echo $this->get_field_id('open_blank') ?>_yes"
				value="1" <?php if ($instance['open_blank'] == "1") { echo ' checked="checked" '; } ?> /> <label for="<?php echo $this->get_field_id('open_blank') ?>_yes"><?php _e('Yes', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="radio" name="<?php echo $this->get_field_name( 'open_blank'); ?>" id="<?php echo $this->get_field_id('open_blank') ?>_no"
				value="0" <?php if ($instance['open_blank'] == "0") { echo ' checked="checked" '; } ?> /> <label for="<?php echo $this->get_field_id('open_blank') ?>_no"><?php _e('No', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />

		</p>

		<p>
			<label for="<?php echo $this->get_field_id('show_more_link') ?>"><?php _e('Landing Page link below Sites list:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<input type="radio" name="<?php echo $this->get_field_name( 'show_more_link'); ?>" id="<?php echo $this->get_field_id('show_more_link') ?>_yes"
				value="1" <?php if ($instance['show_more_link'] == "1") { echo ' checked="checked" '; } ?> /> <label for="<?php echo $this->get_field_id('show_more_link') ?>_yes"><?php _e('Yes', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="radio" name="<?php echo $this->get_field_name( 'show_more_link'); ?>" id="<?php echo $this->get_field_id('show_more_link') ?>_no"
				value="0" <?php if ($instance['show_more_link'] == "0") { echo ' checked="checked" '; } ?> /> <label for="<?php echo $this->get_field_id('show_more_link') ?>_no"><?php _e('No', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />

		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'landing_link_label' ); ?>"><?php
				_e('Label for link:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="text" id="<?php echo $this->get_field_id( 'landing_link_label' ); ?>" value="<?php echo $instance['landing_link_label']; ?>"
				name="<?php echo $this->get_field_name( 'landing_link_label'); ?>" class="widefat" style="width:100%;" />
		</p>


		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['category'] 			= strip_tags($new_instance['category']);
		$instance['include_children'] 	= strip_tags($new_instance['include_children']);
		$instance['category_filter'] 	= strip_tags($new_instance['category_filter']);
		$instance['category_ids'] 		= strip_tags($new_instance['category_ids']);
		$instance['blog_filter'] 		= strip_tags($new_instance['blog_filter']);
		$instance['blog_ids'] 			= strip_tags($new_instance['blog_ids']);
		$instance['per_page'] 			= intval($new_instance['per_page']);
		$instance['ordering'] 			= strip_tags($new_instance['ordering']);
		$instance['order'] 				= strip_tags($new_instance['order']);
		$instance['show_style'] 		= strip_tags($new_instance['show_style']);

		if (isset($new_instance['icon_show']))
			$instance['icon_show'] 			= strip_tags($new_instance['icon_show']);

		if (isset($new_instance['icon_size']))
			$instance['icon_size'] 			= intval($new_instance['icon_size']);

		$instance['show_more_link'] 	= intval($new_instance['show_more_link']);
		$instance['open_blank'] 		= intval($new_instance['open_blank']);
		$instance['landing_link_label'] = strip_tags($new_instance['landing_link_label']);

		delete_site_transient( 'site-categories-sites-data-'. $this->number);

		return $instance;
	}

	function widget($args, $instance) {
		global $site_categories, $current_site;

		$site_categories->load_config();
		//echo "args<pre>"; print_r($args); echo "</pre>";
		//echo "instance<pre>"; print_r($instance); echo "</pre>";

		extract($args);

		//$data = get_site_transient( 'site-categories-sites-data-'. $this->number );
		$data = array();
		if (!$data) {

			switch_to_blog( $current_site->blog_id );
			//echo "instance<pre>"; print_r($instance); echo "</pre>";
			$bcat_term = get_term($instance['category'], SITE_CATEGORIES_TAXONOMY);

			$get_terms_args = array(
				'orderby'			=>	$instance['ordering'],
				'order'				=>	$instance['order']
			);

			if (isset($instance['include_children'])) {
				if ($instance['include_children'] == "on")
					$get_terms_args['include_children'] = true;
				else
					$get_terms_args['include_children'] = false;
			} else {
				$get_terms_args['include_children'] = false;
			}

			if (( isset($instance['category_filter'])) && (!empty($instance['category_filter']))) {
				$instance['category_ids'] = str_replace(' ', '', $instance['category_ids']);

				if ((isset($instance['category_ids'])) && (!empty($instance['category_ids']))) {

					$category_ids = explode( ',', $instance['category_ids'] );
					if (!empty($category_ids)) {
						foreach($category_ids as $cat_idx => $cat_id) {
							$category_ids[$cat_idx] = intval($cat_id);
						}
						if ($instance['category_filter'] == 'include') {
							$get_terms_args['include'] = $category_ids;
						} else if ($instance['category_filter'] == "include-and") {
							$get_terms_args['include-and'] = $category_ids;
						} else if ($instance['category_filter'] == "exclude") {
							$get_terms_args['exclude'] = $category_ids;
					 	} else if ($instance['category_filter'] == 'exclude_tree') {
							$get_terms_args['exclude_tree'] = $category_ids;
					 	}
					}
				}
			}

			if (( isset($instance['blog_filter'])) && (!empty($instance['blog_filter']))) {
				$instance['blog_ids'] = str_replace(' ', '', $instance['blog_ids']);
				if ((isset($instance['blog_ids'])) && (!empty($instance['blog_ids']))) {
					$blog_ids = explode( ',', $instance['blog_ids'] );
					if (!empty($blog_ids)) {
						if ($instance['blog_filter'] == 'include') {
							$get_terms_args['blog_ids'] = $blog_ids;
							$get_terms_args['blog_filter'] 	= $instance['blog_filter'];
						} else if ($instance['blog_filter'] == "exclude") {
							$get_terms_args['blog_ids'] 	= $blog_ids;
							$get_terms_args['blog_filter'] 	= $instance['blog_filter'];
					 	}
					}
				}
			}

			if ($instance['category'] == "-1")
				$instance['category'] = '';
			//echo "get_terms_args<pre>"; print_r($get_terms_args); echo "</pre>";

			$get_terms_args['context'] = 'widget';
			$sites = $site_categories->get_taxonomy_sites($instance['category'], $get_terms_args);
			//echo "sites<pre>"; print_r($sites); echo "</pre>";
			if (($instance['per_page'] == 0) || (count($sites) < $instance['per_page'])) {
				$data['sites'] = $sites;

			} else {
				$data['current_page'] = 1;

				$data['offset'] 		= intval($instance['per_page']) * (intval($data['current_page'])-1);
				$data['sites'] 			= array_slice($sites, $data['offset'], $instance['per_page'], true);
				$data['total_pages'] 	= ceil(count($sites)/intval($instance['per_page']));
			}

			if (intval($instance['show_more_link'])) {
				if ((isset($site_categories->opts['landing_page_rewrite'])) && ($site_categories->opts['landing_page_rewrite'] == true) && ($site_categories->opts['landing_page_use_rewrite'] == "yes")) {
					$data['landing']['link_url'] = trailingslashit($site_categories->opts['landing_page_slug']);
					if ($bcat_term)
						 $data['landing']['link_url'] .= $bcat_term->slug;
				} else {
					//$data['landing']['link_url'] = trailingslashit($site_categories->opts['landing_page_slug']) ."&category_name=". $bcat_term->slug;
					if ($bcat_term) {
						$data['landing']['link_url'] = add_query_arg( array( 'category' => $bcat_term->slug),
							$site_categories->opts['landing_page_slug']);
					}
				}
				if (isset($instance['landing_link_label']))
					$data['landing']['link_label'] = $instance['landing_link_label'];
				else
					$data['landing']['link_label'] = __('More Sites', SITE_CATEGORIES_I18N_DOMAIN);
			}

			restore_current_blog();


			if (!function_exists('get_blog_avatar')) {
				$instance['icon_show'] = false;
			} else {
				$default_icon_src = $site_categories->get_default_category_icon_url();
			}

			if ( (isset($data['sites'])) && (count($data['sites'])) ) {

				foreach($data['sites'] as $idx => $site) {

					if ((isset($instance['icon_show'])) && ($instance['icon_show'] == true)) {
						$icon_image_src = get_blog_avatar($site->blog_id, $instance['icon_size']);

						if ((!$icon_image_src) || (!strlen($icon_image_src))) {
							$data['sites'][$idx]->icon_image_src = $default_icon_src;
						} else {
							$data['sites'][$idx]->icon_image_src = $icon_image_src;
						}
					}
				}
			}

			set_site_transient( 'site-categories-sites-data-'. $this->number, $data, 30);
		}

		$user_access_content = apply_filters('site_categories_user_can_view', '', $this->id_base);

		// If the filters returned simply false we return the default content'
		if ($user_access_content === false)
			return false;

		$instance['id'] = $this->id;
		$categories_content = apply_filters('categories_widget_list_sites_display', '', $data, $instance);
		if (strlen($categories_content)) {
			echo $before_widget;

			$title = apply_filters('widget_title', $instance['title']);
			if ($title) echo $before_title . $title . $after_title;

			// If the filters returned a string/text we want to use that as the user viewed content
			if ((is_string($user_access_content)) && (!empty($user_access_content)))
				echo  $user_access_content;
			else
				echo $categories_content;

			echo $after_widget;
		}
	}
}

function process_categories_widget_list_sites_display($content, $data, $args) {
	//echo "args<pre>"; print_r($args); echo "</pre>";
	//echo "data<pre>"; print_r($data); echo "</pre>";

	$form_id = str_replace('-', '_', $args['id']) . "_select";

	if (($data['sites']) && (count($data['sites']))) {

		if ($args['show_style'] == "ol") { $content .= '<ol class="site-categories site-categories-widget">'; }
		else if ($args['show_style'] == "select") {
			$content .= '<select id="'. $form_id .'" class="site-categories site-categories-widget">';
			$content .= '<option value="">'. __('Select Site', SITE_CATEGORIES_I18N_DOMAIN) .'</option>';
		} else { $content .= '<ul class="site-categories site-categories-widget">'; }

		foreach ($data['sites'] as $site) {

			//echo "site<pre>"; print_r($site); echo "</pre>";

			if ($args['show_style'] != "select") {

				if (($args['icon_show'] == true) && (isset($site->icon_image_src)) && (strlen($site->icon_image_src))) {
					//$image_src = '<img class="site-category-site-icon" width="'. $args['icon_size'] .'" height="'. $args['icon_size'] .'"
					//	alt="'. $site->blogname .'" src="'. $site->icon_image_src .'" />';
					if (is_ssl()) {
						$image_src = str_replace('http://', 'https://', $site->icon_image_src);
					} else {
						$image_src = $site->icon_image_src;
					}
				} else {
					$image_src = '';
				}

				$content .= '<li class="site-category-site">';
				if ($args['open_blank'] == 1)
					$link_target = ' target="_blank" ';
				else
					$link_target = '';
				//echo "link_target[". $link_target ."]<br />";
				$content .=	'<a href="'. $site->siteurl .'" '. $link_target .' class="site-category-site-url">'. $image_src
					.'<span class="site-category-site-title">'. $site->blogname .'</span></a>';

				if ( (isset($args['show_description'])) && ($args['show_description'] == true) && (isset($site->bact_site_description)) && (strlen($site->bact_site_description))) {

					//$bact_site_description = apply_filters('the_content', $site->bact_site_description);
					$bact_category_description = wpautop(stripslashes($category->description));

					$bact_site_description = str_replace(']]>', ']]&gt;', $bact_site_description);

					if (strlen($bact_site_description)) {
						$content .= '<div class="site-category-site-description">'. $bact_site_description .'</div>';
					}
				}
				$content = apply_filters('categories_widget_list_sites_display_item_after', $content, $args, $site);
				$content .= '</li>';

			} else {
				$content .= '<option value="'. $site->siteurl .'">'. $site->blogname .'</option>';
			}
		}

		if ($args['show_style'] == "ol") { $content .= '</ol>'; }
		else if ($args['show_style'] == "select") {
			$content .= '</select>';
			if ($args['open_blank'] == '1') {
				$content .= '<script type="text/javascript">
				/* <![CDATA[ */
					var dropdown_'. $form_id .' = document.getElementById("'. $form_id .'");
					function onCatChange_'. $form_id .'() {
						var selected_index = dropdown_'. $form_id .'.selectedIndex;
						var href = dropdown_'. $form_id .'.options[selected_index].value;
						if (href != "") {
							var a = document.createElement(\'a\');
							a.href=href;
							a.target = \'_blank\';
							document.body.appendChild(a);
							a.click();
						}
					}
					dropdown_'. $form_id .'.onchange = onCatChange_'.$form_id.';
				/* ]]> */
				</script>';
			} else {
				$content .= '<script type="text/javascript">
				/* <![CDATA[ */
					var dropdown_'. $form_id .' = document.getElementById("'. $form_id .'");
					function onCatChange_'. $form_id .'() {
						var selected_index = dropdown_'. $form_id .'.selectedIndex;
						var href = dropdown_'. $form_id .'.options[selected_index].value;
						if (href != "") {
							window.location.href = href;
						}
					}
					dropdown_'. $form_id .'.onchange = onCatChange_'.$form_id.';
				/* ]]> */
				</script>';
			}
		} else { $content .= '</ul>'; }

		if ((isset($args['show_more_link'])) && ($args['show_more_link']) && (isset($data['landing']))) {

			if ($args['open_blank'] == '1') { $link_target = ' target="_blank" '; }
			else { $link_target = ''; }
			$content .= '<div id="site-categories-navigation">';
			$content .= '<a href="'. $data['landing']['link_url'] .'" '. $link_target .'>'. $data['landing']['link_label'] .'</a>';
			$content .= '</div>';
		}
	}

	return $content;
}
add_filter('categories_widget_list_sites_display', 'process_categories_widget_list_sites_display', 99, 3);