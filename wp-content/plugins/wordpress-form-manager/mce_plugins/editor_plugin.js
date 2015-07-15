(function() {
	tinymce.create('tinymce.plugins.FormManager', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('wp_cmd_form_manager', function() {
				ed.windowManager.open({
					file : url + '/form.php',
					width : 350 ,
					height : 130,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			
			
			ed.addButton('WPformManager', {
				title : 'WordPress Form Manager',
				image : url+'/formmanager.png',
				cmd: 'wp_cmd_form_manager'		
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "WordPress Form Manager Shortcode",
				author : 'Campbell Hoffman',
				authorurl : 'http://www.campbellhoffman.com/',
				infourl : 'http://www.campbellhoffman.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('WPformManager', tinymce.plugins.FormManager);
})();