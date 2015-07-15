# Network Content Modules

More documentation coming soon.

## Purpose

Create a pluggable modules that will display content from all the sites on the network.

## Content
	* Network Posts
	* Network Post Highlights
	* Network Sites

## Features
	* Shortcode
	* Widgets
	* Editor quicktags
	* Customizable templates
  
---
### Data Output


### Render

	* Posts and sites list can be rendered as HTML or used to return an array.
	* Rendering can be customized by putting templates in active theme in plugins/glocal-network-content/ directory


### File Structure

	glocal-network-content

		anp_network_content_modules.md
		glocal-network-content-widgets.php
		glocal-network-content.php
		glocal-network-widgets-tinymce.php
		images
			post-highlights.png
		js
			glocal-network-widgets.js
		LICENSE
		stylesheets
			css
				editor-styles.css
				style.css
				style.css.map
			sass
				_config.scss
				style.scss
		templates
			anp-post-block-template.php
			anp-post-highlights-template.php
			anp-post-list-template.php
			anp-sites-list-template.php
