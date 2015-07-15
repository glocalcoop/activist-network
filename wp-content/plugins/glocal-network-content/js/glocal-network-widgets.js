//Create button to display shortcode [anp_network_posts]
//Add parameter fields: number_sites, exclude_sites, sort_by, default_image, show_meta, show_image, id

(function() {
    tinymce.PluginManager.add('glocal_network_content_button', function( editor, url ) {
		
        editor.addButton( 'glocal_network_content_button', {
            //title: 'My test button II',
            type: "buttongroup",
            items: [
				{
					title: 'Network Posts',
					icon: 'icon dashicons-admin-page',
					tooltip: 'Network Posts Shortcode',
					
					onclick: function() {
						
						editor.windowManager.open( {
							title: 'Network Posts Options',
							body: [
							{
								type: 'textbox',
								name: 'title',
								label: 'Title'
							},
							{
								type: 'textbox',
								name: 'title_image',
								label: 'Title Image (URL)'
							},
							{
								type: 'textbox',
								name: 'number_posts',
								label: 'Number of Posts'
							},
							{
								type: 'textbox',
								name: 'exclude_sites',
								label: 'Sites to Exclude (Comma-separated)'
							},
							{
								type: 'textbox',
								name: 'posts_per_site',
								label: 'Posts per Site'
							},
							{
								type: 'listbox',
								name: 'style',
								label: 'Style of Display',
								'values': [
									{text: 'List (default)', value: ''},
									{text: 'Block List', value: 'block'},
									{text: 'Highlights Module', value: 'highlights'},
								]
							},
							{
								type: 'checkbox',
								name: 'show_meta',
								label: 'Show Meta Info (Date updated and latest post)',
								checked: true,
							},
							{
								type: 'checkbox',
								name: 'show_excerpt',
								label: 'Show Excerpt',
								checked: true,
							},
							{
								type: 'textbox',
								name: 'excerpt_length',
								label: 'Excerpt Length'
							},
							{
								type: 'checkbox',
								name: 'show_site_name',
								label: 'Show Site Name',
								checked: true,
							},
							{
								type: 'textbox',
								name: 'id',
								label: 'HTML ID',
							},
							{
								type: 'textbox',
								name: 'class',
								label: 'HTML Class Name',
							},
							],
							//number_posts
							//exclude_sites
							//posts_per_site
							//style
							//show_meta
							//show_excerpt
							//show_site_name
							//id
							//class
							onsubmit: function( e ) {
								var shortcode = '[anp_network_posts';
								if(e.data.title) {
									shortcode += ' title="' + e.data.title + '"';
								}
								if(e.data.title_image) {
									shortcode += ' title_image="' + e.data.title_image + '"';
								}
								if(e.data.number_posts) {
									shortcode += ' number_posts="' + e.data.number_posts + '"';
								}
								if(e.data.exclude_sites) {
									shortcode += ' exclude_sites="' + e.data.exclude_sites + '"';
								}
								if(e.data.posts_per_site) {
									shortcode += ' posts_per_site="' + e.data.posts_per_site + '"';
								}
								if(e.data.style) {
									shortcode += ' style="' + e.data.style + '"';
								}
								if(e.data.show_meta) {
									shortcode += ' show_meta="' + e.data.show_meta + '"';
								} 
								if(e.data.show_excerpt) {
									shortcode += ' show_excerpt="' + e.data.show_excerpt + '"';
								} 
								if(e.data.show_site_name) {
									shortcode += ' show_site_name="' + e.data.show_site_name + '"';
								} 
								if(e.data.id) {
									shortcode += ' id="' + e.data.id + '"';
								}
								if(e.data.class) {
									shortcode += ' class="' + e.data.class + '"';
								}
								shortcode += ']';

								editor.insertContent( shortcode );
							}
						});
					}

					
				},
				{
					title: 'Network Sites',
					icon: 'icon dashicons-networking',
					tooltip: 'Network Sites Shortcode',
					
					onclick: function() {
						
						editor.windowManager.open( {
							title: 'Network Sites Options',
							body: [
							{
								type: 'textbox',
								name: 'number_sites',
								label: 'Number of Sites'
							},
							{
								type: 'textbox',
								name: 'exclude_sites',
								label: 'Sites to Exclude (Comma-separated)'
							},
							//@sortby - newest, updated, active, alpha (registered, last_updated, post_count, blogname) (default: alpha)
							{
								type: 'listbox',
								name: 'sort_by',
								label: 'Sort By',
								'values': [
									{text: 'Alphabetic (default)', value: ''},
									{text: 'Recently Added', value: 'registered'},
									{text: 'Recently Updated', value: 'last_updated'},
									{text: 'Most Active', value: 'post_count'}
								]
							},
							{
								type: 'textbox',
								name: 'default_image',
								label: 'Default Site Image (URL)'
							},
							{
								type: 'checkbox',
								name: 'show_image',
								label: 'Show Site Image',
								checked: false,
							},
							{
								type: 'checkbox',
								name: 'show_meta',
								label: 'Show Meta Info (Date updated and latest post)',
								checked: true,
							},
							{
								type: 'textbox',
								name: 'id',
								label: 'ID',
							},
							{
								type: 'textbox',
								name: 'class',
								label: 'Class'
							},
							],

							onsubmit: function( e ) {
								var shortcode = '[anp_network_sites';
								if(e.data.number_sites) {
									shortcode += ' number_sites="' + e.data.number_sites + '"';
								}
								if(e.data.exclude_sites) {
									shortcode += ' exclude_sites="' + e.data.exclude_sites + '"';
								}
								if(e.data.sort_by) {
									shortcode += ' sort_by="' + e.data.sort_by + '"';
								}
								if(e.data.default_image) {
									shortcode += ' default_image="' + e.data.default_image + '"';
								}
								if(e.data.show_meta) {
									shortcode += ' show_meta=1';
								}
								if(e.data.show_image) {
									shortcode += ' show_image=1';
								}
								if(e.data.id) {
									shortcode += ' id="' + e.data.id + '"';
								}
								if(e.data.class) {
									shortcode += ' class="' + e.data.class + '"';
								}
								shortcode += ']';

								editor.insertContent( shortcode );
							}
						});
					}
					
				}
			]
        });
		
    });
})();

