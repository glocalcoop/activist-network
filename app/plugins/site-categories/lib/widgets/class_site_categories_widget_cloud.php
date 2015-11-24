<?php
/**
 * Shows all Blog categories.
 */
class Bcat_WidgetCloud extends WP_Widget {

	function Bcat_WidgetCloud () {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('Shows a tag cloud of Site Categories.', SITE_CATEGORIES_I18N_DOMAIN));	 	 	 	  	    					 
		parent::WP_Widget(__CLASS__, __('Site Categories Cloud', SITE_CATEGORIES_I18N_DOMAIN), $widget_ops);
	}

	function form($instance) {
		global $current_site;

		// Set defaults
		// ...
		$defaults = array(
			'title' 				=> 	'',
			'number'				=>	'',
			'orderby'				=>	'name',
			'order'					=>	'ASC',
			'smallest'				=>	'8',
			'largest'				=>	'22',
			'unit'					=>	'pt',
			'category'				=>	'',
			'category_filter'		=>	'',
			'category_ids'			=>	'',
			'include_parent'		=>	'on',
			'open_blank'			=>	0,
			'show_more_link'		=>	1,
			'landing_link_label'	=>	__('more categories', SITE_CATEGORIES_I18N_DOMAIN)
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		//echo "instance<pre>"; print_r($instance); echo "</pre>";

		//if (!empty($instance['per_page'])) {
		//	$instance['per_page'] = intval($instance['per_page']);
		//}

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>"
				class="widefat" value="<?php echo $instance['title'] ?> "/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php
				_e('Number of item to show: (blank for all)', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="text" id="<?php echo $this->get_field_id( 'number' ); ?>" value="<?php echo $instance['number']; ?>"
				name="<?php echo $this->get_field_name( 'number'); ?>" class="widefat" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php
				_e('Show Site Categories:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<?php
				switch_to_blog( $current_site->blog_id );

				$bcat_args = array(
					'taxonomy'			=> 	SITE_CATEGORIES_TAXONOMY,
					'orderby'			=>	'name',
					'order'				=>	'ASC',
					'hierarchical'		=>	true,
					'hide_empty'		=>	false,
					'show_count'		=>	true,
					'show_option_none'	=>	__('All Categories', SITE_CATEGORIES_I18N_DOMAIN),
					'name'				=>	$this->get_field_name('category'),
					'class'				=>	'widefat',
					'selected'			=>	$instance['category']
				);

				wp_dropdown_categories( $bcat_args );
				restore_current_blog();

			?><input type="checkbox" id="<?php echo $this->get_field_id( 'include_parent' ); ?>" <?php if ($instance['include_parent'] == "on") {
				echo ' checked="checked" '; } ?>
				name="<?php echo $this->get_field_name( 'include_parent'); ?>"  /> <label for="<?php echo $this->get_field_id( 'include_parent' ); ?>"><?php
				_e('Include Parent:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category_filter') ?>"><?php _e('Site Categories Include/Exclude:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'category_filter' ); ?>"
				name="<?php echo $this->get_field_name( 'category_filter'); ?>" class="widefat">
				<option value="" <?php if ($instance['category_filter'] == "") { echo ' selected="selected" '; }?>><?php
					_e('Show All', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="include" <?php if ($instance['category_filter'] == "include") { echo ' selected="selected" '; }?>><?php
					_e('Include', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="exclude" <?php if ($instance['category_filter'] == "exclude") { echo ' selected="selected" '; }?>><?php
					_e('Exclude', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="exclude_tree" <?php if ($instance['category_filter'] == "exclude_tree") { echo ' selected="selected" '; }?>><?php
					_e('Exclude Tree', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select><br />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category_ids') ?>"><?php _e('Site Categories IDs (comma seperated):', SITE_CATEGORIES_I18N_DOMAIN); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('category_ids'); ?>" id="<?php echo $this->get_field_id('category_ids'); ?>"
				class="widefat" value="<?php echo $instance['category_ids'] ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby') ?>"><?php _e('Ordering:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>"
				name="<?php echo $this->get_field_name( 'orderby'); ?>" class="widefat" style="width:60%;">
				<option value="name" <?php if ($instance['orderby'] == "name") { echo ' selected="selected" '; }?>><?php
					_e('Name', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="count" <?php if ($instance['orderby'] == "count") { echo ' selected="selected" '; }?>><?php
					_e('Count', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select>
			<select id="<?php echo $this->get_field_id( 'order' ); ?>"
				name="<?php echo $this->get_field_name( 'order'); ?>" class="widefat" style="width:25%;">
				<option value="ASC" <?php if ($instance['order'] == "ASC") { echo ' selected="selected" '; }?>><?php
					_e('ASC', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="DESC" <?php if ($instance['order'] == "DESC") { echo ' selected="selected" '; }?>><?php
					_e('DESC', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="RAND" <?php if ($instance['order'] == "RAND") { echo ' selected="selected" '; }?>><?php
					_e('Random', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'smallest' ); ?>"><?php
				_e('Small Font size:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="text" id="<?php echo $this->get_field_id( 'smallest' ); ?>" value="<?php echo $instance['smallest']; ?>"
				name="<?php echo $this->get_field_name( 'smallest'); ?>" class="widefat" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'largest' ); ?>"><?php
				_e('Large Font size:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="text" id="<?php echo $this->get_field_id( 'largest' ); ?>" value="<?php echo $instance['largest']; ?>"
				name="<?php echo $this->get_field_name( 'largest'); ?>" class="widefat" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'unit' ); ?>"><?php
				_e('Font size unit:', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<select id="<?php echo $this->get_field_id( 'unit' ); ?>"
				name="<?php echo $this->get_field_name( 'unit'); ?>" class="widefat" style="width:100%;">
				<option value="%" <?php if ($instance['unit'] == "%") { echo ' selected="selected" '; }?>><?php
					_e('% Percentage', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="in" <?php if ($instance['unit'] == "in") { echo ' selected="selected" '; }?>><?php
					_e('in Inch', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="cm" <?php if ($instance['unit'] == "cm") { echo ' selected="selected" '; }?>><?php
					_e('cm Centimeter', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="mm" <?php if ($instance['unit'] == "mm") { echo ' selected="selected" '; }?>><?php
					_e('mm Millimeter', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="em" <?php if ($instance['unit'] == "em") { echo ' selected="selected" '; }?>><?php
					_e('em', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="ex" <?php if ($instance['unit'] == "ex") { echo ' selected="selected" '; }?>><?php
					_e('ex', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="pt" <?php if ($instance['unit'] == "pt") { echo ' selected="selected" '; }?>><?php
					_e('pt Point', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="pc" <?php if ($instance['unit'] == "pc") { echo ' selected="selected" '; }?>><?php
					_e('pc Pica', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
				<option value="px" <?php if ($instance['unit'] == "px") { echo ' selected="selected" '; }?>><?php
					_e('px Pixels', SITE_CATEGORIES_I18N_DOMAIN); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('open_blank') ?>"><?php _e('Open links in new window:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
			<input type="radio" name="<?php echo $this->get_field_name( 'open_blank'); ?>" id="<?php echo $this->get_field_id('open_blank') ?>_yes"
				value="1" <?php if ($instance['open_blank'] == "1") { echo ' checked="checked" '; } ?> /> <label for="<?php echo $this->get_field_id('open_blank') ?>_yes"><?php _e('Yes', SITE_CATEGORIES_I18N_DOMAIN); ?></label>

			<input type="radio" name="<?php echo $this->get_field_name( 'open_blank'); ?>" id="<?php echo $this->get_field_id('open_blank') ?>_no"
				value="0" <?php if ($instance['open_blank'] == "0") { echo ' checked="checked" '; } ?> /> <label for="<?php echo $this->get_field_id('open_blank') ?>_no"><?php _e('No', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />

		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_more_link') ?>"><?php _e('Landing Page link below cloud list:', SITE_CATEGORIES_I18N_DOMAIN); ?></label><br />
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

		$instance['title'] 					= strip_tags($new_instance['title']);

		if (!empty($new_instance['category']))
			$instance['category'] 			= intval($new_instance['category']);
		else
			$instance['category']			= '';

		if (!empty($new_instance['category_filter']))
			$instance['category_filter'] 	= strip_tags($new_instance['category_filter']);
		else
			$instance['category_filter'] 	= '';

		if (!empty($new_instance['category_ids']))
			$instance['category_ids'] 		= strip_tags($new_instance['category_ids']);
		else
			$instance['category_ids'] 		= '';

		if (isset($new_instance['include_parent']))
			$instance['include_parent'] 	= esc_attr($new_instance['include_parent']);
		else
			$instance['include_parent']		= '';


		if (!empty($new_instance['number']))
			$instance['number'] 			= intval($new_instance['number']);
		else
			$instance['number']				= '';


		if (isset($new_instance['orderby']))
			$instance['orderby'] 			= esc_attr($new_instance['orderby']);
		else
			$instance['orderby']			= 'name';


		if (isset($new_instance['order']))
			$instance['order'] 			= esc_attr($new_instance['order']);
		else
			$instance['order']			= 'ASC';


		if (!empty($new_instance['smallest']))
			$instance['smallest'] 		= intval($new_instance['smallest']);
		else
			$instance['smallest']		= 8;


		if (!empty($new_instance['largest']))
			$instance['largest'] 		= intval($new_instance['largest']);
		else
			$instance['largest']		= 22;


		if (!empty($new_instance['unit']))
			$instance['unit'] 			= esc_attr($new_instance['unit']);
		else
			$instance['unit']		= "pt";

		$instance['show_more_link'] 	= intval($new_instance['show_more_link']);
		$instance['open_blank'] 		= intval($new_instance['open_blank']);
		$instance['landing_link_label'] = strip_tags($new_instance['landing_link_label']);

		delete_site_transient( 'site-categories-cloud-data-'. $this->number );
		return $instance;
	}

	function widget($args, $instance) {

		global $site_categories, $current_site;

		$site_categories->load_config();
		extract($args);

		$data = get_site_transient( 'site-categories-cloud-data-'. $this->number );
		//echo "data<pre>"; print_r($data); echo "</pre>";
		if (!$data) {

			switch_to_blog( $current_site->blog_id );

			$defaults = array(
				'smallest' 		=> 	8,
				'largest' 		=> 	22,
				'unit' 			=> 	'pt',
				'number' 		=> 	45,
				'format' 		=> 	'flat',
				'separator' 	=> 	"\n",
				'orderby' 		=> 	'name',
				'order' 		=> 	'ASC',
				'exclude' 		=> 	'',
				'include' 		=> 	'',
				'link' 			=> 	'view',
				'taxonomy' 		=> 	SITE_CATEGORIES_TAXONOMY,
				'echo' 			=> 	false
			);
			$instance = wp_parse_args( $instance, $defaults );
			if ((isset($instance['category'])) && (intval($instance['category']) > 0)) {
				$instance['child_of'] 	= intval($instance['category']);
			} else {
				$instance['child_of'] 	= 0;
			}


			if (( isset($instance['category_filter'])) && (!empty($instance['category_filter']))) {
				$instance['category_ids'] = str_replace(' ', '', $instance['category_ids']);
				if ((isset($instance['category_ids'])) && (!empty($instance['category_ids']))) {
					$category_ids = explode( ',', $instance['category_ids'] );
					if (!empty($category_ids)) {
						if ($instance['category_filter'] == 'include') {
							$instance['include'] = $category_ids;
						} else if ($instance['category_filter'] == "exclude") {
							$instance['exclude'] = $category_ids;
					 	} else if ($instance['category_filter'] == 'exclude_tree') {
							$instance['exclude_tree'] = $category_ids;
					 	}
					}
				}
			}


			//echo "instance<pre>"; print_r($instance); echo "</pre>";

			$tags = get_terms( SITE_CATEGORIES_TAXONOMY, $instance); // Always query top tags
			//echo "tags<pre>"; print_r($tags); echo "</pre>";
			if ((isset($instance['include_parent']))	&& ($instance['include_parent'] == "on")) {
				$parent_tag = get_term_by('id', $instance['category'], SITE_CATEGORIES_TAXONOMY);
				if ( !empty( $parent_tag ) && !is_wp_error( $parent_tag ) ) {
					//echo "parent_tag<pre>"; print_r($parent_tag); echo "</pre>";
					$tags[] = $parent_tag;
				}
			}

			if ( empty( $tags ) || is_wp_error( $tags ) )
				return;

			foreach ( $tags as $key => $tag ) {

				$tags[ $key ]->id = $tag->term_id;
				if ((isset($site_categories->opts['landing_page_rewrite'])) && ($site_categories->opts['landing_page_rewrite'] == true) && ($site_categories->opts['landing_page_use_rewrite'] == "yes")) {
					$tags[ $key ]->link = trailingslashit($site_categories->opts['landing_page_slug']) . $tag->slug;
				} else {
					//$tags[ $key ]->link = $site_categories->opts['landing_page_slug'] .'&amp;category_name=' . $tag->slug;
					$tags[ $key ]->link = add_query_arg(array('category' => $tag->slug), $site_categories->opts['landing_page_slug']);
				}
			}
			$data = wp_generate_tag_cloud( $tags, $instance ); // Here's where those top tags get sorted according to $args

			if ($instance['open_blank'] == '1'){
				$data = str_replace('<a ', '<a target="_blank" ', $data);
			}

			if (!empty($data)) {
				if ((isset($instance['show_more_link'])) && ($instance['show_more_link'])) {

					if ($instance['open_blank'] == '1') { $link_target = ' target="_blank" '; }
					else { $link_target = ''; }
					$data .= '<div id="site-categories-navigation">';
					$data .= '<a href="'. $site_categories->opts['landing_page_slug'] .'" '. $link_target .'>'. $instance['landing_link_label'] .'</a>';
					echo '</div>';
				}
			}

			restore_current_blog();

			set_site_transient( 'site-categories-cloud-data-'. $this->number, $data, 30);
		}

		$user_access_content = apply_filters('site_categories_user_can_view', '', $this->id_base);
		//echo "user_access_content[". $user_access_content ."]<br />";

		// If the filters returned simply false we return the default content'
		if ($user_access_content === false)
			return false;

		if ($data) {
			echo $before_widget;

			$title = apply_filters('widget_title', $instance['title']);
			if ($title) echo $before_title . $title . $after_title;

			// If the filters returned a string/text we want to use that as the user viewed content
			if ((is_string($user_access_content)) && (!empty($user_access_content)))
				echo $user_access_content;
			else
				echo $data;

			echo $after_widget;

		}
	}
}
