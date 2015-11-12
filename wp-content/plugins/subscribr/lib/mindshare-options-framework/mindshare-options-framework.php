<?php
/**
 * The Mindshare Options Framework is a flexible, lightweight framework for creating WordPress theme and plugin options screens.
 *
 * @version        0.3.5
 * @author         Mindshare Studios, Inc.
 * @copyright      Copyright (c) 2014
 * @link           http://www.mindsharelabs.com/documentation/
 *
 * @credits        Forked from: Admin Page Class 0.9.9 by Ohad Raz http://bainternet.info
 *                 Icons: http://www.famfamfam.com/lab/icons/silk/
 *
 * @license        GNU General Public License v3.0 - license.txt
 *                 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *                 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *                 FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 *                 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *                 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *                 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *                 THE SOFTWARE.
 *
 * Changelog:
 *
 * 0.3.5 -  merging changes, disabled auto validation
 * 0.3.4 - some refactoring, styling for checkbox lists
 * 0.3.3 - updated codemirror
 * 0.3.2 - fixed issue with code fields. css updates
 * 0.3.1 - fixed htmlspecialchars/stripslashes issue with text fields
 * 0.3 - bugfixes
 * 0.2.1 - fix for attribute escape problem
 * 0.2 - major update, fixed import/export, added subtitle field, sanitization
 * 0.1 - first release
 *
 *
 * @todo           split fields into separate classes, reduce code repetition
 * @todo           split JS blocks into separate files
 * @todo           add more filters and actions
 * @todo           make cases and naming consistent
 * @todo           Better Typography field. Figure tou a way to dynamically get font weights from Google, etc. Add option to turn off color picker.
 * @todo           ADD custom PHP/HTML field
 * @todo           change case of vars to match mindshare standard
 *
 */
if(!class_exists('subscribr_options_framework')) :
	class subscribr_options_framework {

		/**
		 * The MOF version number.
		 *
		 * @var string
		 */
		private $version = '0.3.5';

		/**
		 * Contains all saved data for a page
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $_saved;

		/**
		 * Contains all arguments needed to build the page itself
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $args;

		/**
		 * Field types to skip during data operations (e.g. save)
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $skip_array = array(
			'title',
			'paragraph',
			'subtitle',
			'TABS',
			'CloseDiv',
			'TABS_Listing',
			'OpenTab',
			'import_export'
		);

		/**
		 * Boolean to show or hide the Reset button
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $show_reset_button = TRUE;
		/**
		 * Boolean to show or hide the Uninstall button
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $show_uninstall_button = TRUE;

		/**
		 * Contains Options group name
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $option_group;

		/**
		 * User defined plugin or theme name
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $project_name;

		/**
		 * User defined plugin or theme slug
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $project_slug;

		/**
		 * User defined plugin or theme path
		 *
		 * @access protected
		 * @var array
		 * @since  0.1
		 */
		protected $project_path;

		/**
		 * Contains all the information needed to build the form structure of the page
		 *
		 * @access public
		 * @var array
		 * @since  0.1
		 */
		public $_fields;

		/**
		 * True if the table is opened, false if it is not opened
		 *
		 * @access protected
		 * @var boolean
		 * @since  0.1
		 */
		protected $table = FALSE;

		/**
		 * True if the tab div is opened, false if it is not opened
		 *
		 * @access protected
		 * @var boolean
		 * @since  0.1
		 */
		protected $tab_div = FALSE;

		/**
		 * Contains the menu_slug for the current TopLeve-Menu
		 *
		 * @access public
		 * @var string
		 * @since  0.1
		 */
		public $Top_Slug;

		/**
		 * Contains the menu_slug for the current page
		 *
		 * @access public
		 * @var string
		 * @since  0.1
		 */
		public $_Slug;

		/**
		 * Contains all the information needed to build the Help tabs
		 *
		 * @access public
		 * @var array
		 * @since  0.1
		 */
		public $_help_tabs;

		/**
		 * saved flag
		 *
		 * @var boolean
		 * @since 0.6
		 */
		public $saved_flag = FALSE;

		/**
		 * use google fonts for typo filed?
		 *
		 * @var boolean
		 * @since  0.9.9
		 * @access public
		 */
		public $google_fonts = TRUE;

		/**
		 * Google API Key
		 *
		 * Used to retrieve all Google Web fonts
		 *
		 * @access protected
		 * @var boolean
		 * @since  0.1
		 */
		protected $_google_dev_api_key = 'AIzaSyD5SEdGWditj4nAEeawa_ZlB89BfafoyWk';

		/**
		 * Builds a new Page
		 *
		 * @param $args        (string|mixed array) - Possible keys within $args:
		 * @param menu         (string) - this the name of the parent Top-Level-Menu or a TopPage object to create this page as a sub menu to.
		 * @param top          (string) - Slug for the New Top level Menu page to create.
		 * @param page_title   (string) - The name of this page (good for Top level and sub menu pages)
		 * @param menu_title   (string) - The test link for this page on the WordPress menu
		 * @param capability   (string) (optional) - The capability needed to view the page (good for Top level and sub menu pages)
		 * @param menu_slug    (string) - A unique string identifying your new menu (Top level Only)
		 * @param icon_url     (string) (optional) - URL to the icon, decorating the Top-Level-Menu (Top level Only)
		 * @param position     (string) (optional) - The position of the Menu in the ACP (Top level Only)
		 * @param option_group (string) (required) - the name of the option to create in the database
		 */
		public function __construct($args) {

			if(is_array($args) && isset($args['option_group'])) {
				$this->option_group = $args['option_group'];
			} else {
				$array['page_title'] = $args;
				$this->args = $array;
			}

			if(!isset($args['menu_title'])) {
				$args['menu_title'] = $args['page_title'];
			}

			//set defaults
			$this->saved = FALSE;
			$this->args = $args;

			// add hooks for export download
			add_action('template_redirect', array($this, 'admin_redirect_download_files'));
			add_filter('init', array($this, 'add_query_var_vars'));

			// if we are not in admin area exit
			if(!is_admin()) {
				return;
			}

			$this->google_fonts = isset($this->args['google_fonts']) ? $this->args['google_fonts'] : TRUE;
			$this->show_reset_button = isset($this->args['reset_button']) ? $this->args['reset_button'] : TRUE;
			$this->show_uninstall_button = isset($this->args['uninstall_button']) ? $this->args['uninstall_button'] : TRUE;

			//sub $menu
			if(!is_array($this->args['menu'])) {
				if(is_object($this->args['menu'])) {
					$this->Top_Slug = $this->args['menu']->Top_Slug;
				} else {
					switch($this->args['menu']) {
						case 'posts':
							$this->Top_Slug = 'edit.php';
							break;
						case 'dashboard':
							$this->Top_Slug = 'index.php';
							break;
						case 'media':
							$this->Top_Slug = 'upload.php';
							break;
						case 'links':
							$this->Top_Slug = 'link-manager.php';
							break;
						case 'pages':
							$this->Top_Slug = 'edit.php?post_type=page';
							break;
						case 'comments':
							$this->Top_Slug = 'edit-comments.php';
							break;
						case 'theme':
							$this->Top_Slug = 'themes.php';
							break;
						case 'plugins':
							$this->Top_Slug = 'plugins.php';
							break;
						case 'users':
							$this->Top_Slug = 'users.php';
							break;
						case 'tools':
							$this->Top_Slug = 'tools.php';
							break;
						case 'settings':
							$this->Top_Slug = 'options-general.php';
							break;
						default:
							if(post_type_exists($this->args['menu'])) {
								$this->Top_Slug = 'edit.php?post_type='.$this->args['menu'];
							} else {
								$this->Top_Slug = $this->args['menu'];
							}
					}
				}
				add_action('admin_menu', array($this, 'AddMenuSubPage'));
			} else {
				//top page
				$this->Top_Slug = $this->args['menu']['top'];
				add_action('admin_menu', array($this, 'AddMenuTopPage'));
			}

			// Assign page values to local variables and add it's missed values.
			$this->_Page_Config = $this->args;
			$this->_fields = & $this->_Page_Config['fields'];

			if(isset($this->args['project_name'])) {
				$this->project_name = $this->args['project_name'];
			} else {
				$this->project_name = 'this';
			}

			if(isset($this->args['project_slug'])) {
				$this->project_slug = $this->args['project_slug'];
			}

			$this->add_missed_values();
			if(isset($this->args['project_path'])) {
				$this->project_path = $this->args['project_path'];
				if($this->args['project_path'] === 'THEME') {
					$this->SelfPath = get_template_directory_uri().'/mindshare-options-framework';
				} elseif($this->args['project_path'] === 'PLUGIN') {
					$this->SelfPath = plugins_url('mindshare-options-framework', plugin_basename(dirname(__FILE__)));
				} else {
					$this->SelfPath = $this->args['project_path'];
				}
			} else {
				$this->project_path = 'PLUGIN';
				$this->SelfPath = plugins_url('mindshare-options-framework', plugin_basename(dirname(__FILE__)));
			}
			// Must enqueue for all pages as we need js for the media upload, too.

			//add_filter('attribute_escape', array($this, 'edit_insert_to_post_text'), 10, 2);  // @todo fix this & test file and image fields
			// Delete file via Ajax
			add_action('wp_ajax_apc_delete_mupload', array($this, 'wp_ajax_delete_image'));
			//import export
			add_action('wp_ajax_apc_import_'.$this->option_group, array($this, 'import'));
			add_action('wp_ajax_apc_export_'.$this->option_group, array($this, 'export'));
			//plupload ajax
			add_action('wp_ajax_plupload_action', array($this, "Handle_plupload_action"));
		}

		/**
		 * Does all the complicated stuff to build the menu and its first page
		 *
		 * @since  0.1
		 * @access public
		 */
		public function AddMenuTopPage() {
			$default = array(
				'capability' => 'edit_theme_options',
				'menu_title' => '',
				'id'         => 'id',
				'icon_url'   => '',
				'position'   => NULL
			);
			$this->args = array_merge($default, $this->args);
			$top_id = add_menu_page($this->args['page_title'], $this->args['menu_title'], $this->args['capability'], $this->args['id'], array(
				$this,
				'DisplayPage'
			), $this->args['icon_url'], $this->args['position']);
			$page = add_submenu_page($top_id, $this->args['page_title'], $this->args['menu_title'], $this->args['capability'], $this->args['id'], array($this, 'DisplayPage'));
			if($page) {
				$this->_Slug = $page;
				// Adds my_help_tab when my_admin_page loads
				add_action('load-'.$page, array($this, 'Load_page_hooker'));
			}
		}

		/**
		 * Does all the complicated stuff to build the page
		 *
		 * @since  0.1
		 * @access public
		 */
		public function AddMenuSubPage() {
			$default = array(
				'capability' => 'edit_theme_options',
			);
			$this->args = array_merge($default, $this->args);
			$page = add_submenu_page($this->Top_Slug, $this->args['page_title'], $this->args['menu_title'], $this->args['capability'], $this->args['id'], array($this, 'DisplayPage'));
			if($page) {
				$this->_Slug = $page;
				add_action('load-'.$page, array($this, 'Load_page_hooker'));
			}
		}

		/**
		 * loads scripts and styles for the page
		 *
		 *
		 * @since  0.1
		 * @access public
		 */
		public function Load_page_hooker() {
			$page = $this->_Slug;
			//help tabs
			add_action('admin_head-'.$page, array($this, 'admin_add_help_tab'));
			//pluploader code
			add_action('admin_head-'.$page, array($this, 'plupload_head_js'));
			//scripts and styles
			add_action('admin_print_styles', array(&$this, 'load_scripts_styles'));
			//panel script
			add_action('admin_footer-'.$page, array($this, 'panel_script'));
			//add mising scripts
			add_action('admin_enqueue_scripts', array($this, 'Finish'));
			if(isset($_POST['action']) && $_POST['action'] == 'save') {
				do_action('mindshare_options_framework_before_save');
				$this->save();
				$this->saved_flag = TRUE;
				do_action('mindshare_options_framework_after_save');
			}
		}

		public function plupload_head_js() {
			if($this->has_field('plupload')) {
				$plupload_init = array(
					'runtimes'            => 'html5,silverlight,flash,html4',
					'browse_button'       => 'plupload-browse-button', // will be adjusted per uploader
					'container'           => 'plupload-upload-ui', // will be adjusted per uploader
					'drop_element'        => 'drag-drop-area', // will be adjusted per uploader
					'file_data_name'      => 'async-upload', // will be adjusted per uploader
					'multiple_queues'     => TRUE,
					'max_file_size'       => wp_max_upload_size().'b',
					'url'                 => admin_url('admin-ajax.php'),
					'flash_swf_url'       => includes_url('lib/plupload/plupload.flash.swf'),
					'silverlight_xap_url' => includes_url('lib/plupload/plupload.silverlight.xap'),
					'filters'             => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
					'multipart'           => TRUE,
					'urlstream_upload'    => TRUE,
					'multi_selection'     => FALSE, // will be added per uploader
					// additional post data to send to our ajax hook
					'multipart_params'    => array(
						'_ajax_nonce' => "", // will be added per uploader
						'action'      => 'plupload_action', // the ajax action name
						'imgid'       => 0 // will be added per uploader
					)
				);
				echo '<script type="text/javascript">'."\n".'var base_plupload_config=';
				echo json_encode($plupload_init)."\n".'</script>';
			}
		}

		/** add Help Tab
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $args     (mixed|array) contains everything needed to build the field
		 *                  Possible keys within $args:
		 *                  id (string) (required)- Tab ID. Must be HTML-safe and should be unique for this menu
		 *                  title (string) (required)- Title for the tab.
		 *                  content (string) (required)- Help tab content in plain text or HTML.
		 *                  Will only work on WordPress version 3.3 and up
		 */
		public function HelpTab($args) {
			$this->_help_tabs[] = $args;
		}

		/*
		 * print Help Tabs for current screen
		 *
		 * @access public
		 * @since 0.1
		 * 
		 *
		 * Will only work on wordpres version 3.3 and up
		 */
		public function admin_add_help_tab() {
			$screen = get_current_screen();

			if($screen->id != $this->_Slug) {
				return;
			}
			// Add help_tabs for current screen 
			foreach((array) $this->_help_tabs as $tab) {
				$screen->add_help_tab($tab);
			}
		}

		/*
		 * print out panel Script
		 * 
		 * @access public
		 * @since 0.1
		 *
		 */
		public function panel_script() {
			echo '<script type="text/javascript">
	        jQuery(document).ready(function() {
	            //hide all
				jQuery(".setingstab").hide();

				//set first_tab as active
				jQuery(".panel_menu li:first").addClass("active_tab");
				var tab = jQuery(".panel_menu li:first a").attr("href");
				jQuery(tab).show();

				//bind click on menu action to show the right tab.
				jQuery(".panel_menu li").bind("click", function(event) {
					event.preventDefault();
					if(!jQuery(this).hasClass("active_tab")) {
						//hide all
						jQuery(".setingstab").fadeOut("fast");
						jQuery(".panel_menu li").removeClass("active_tab");
						tab = jQuery(this).find("a").attr("href");
						jQuery(this).addClass("active_tab");
						jQuery(tab).fadeIn("fast");
					}
				});
				';
			if($this->has_Field('upload')) {
				echo '
				function load_images_muploader() {
					jQuery(".mupload_img_holder").each(function(i, v) {
						if(jQuery(this).next().next().val() != "") {
							jQuery(this).append("<img alt=\".\" src=\"" + jQuery(this).next().next().val() + "\" style=\"height: 150px;width: 150px;\" />");
							jQuery(this).next().next().next().val("Delete");
							jQuery(this).next().next().next().removeClass("apc_upload_image_button").addClass("apc_delete_image_button");
						}
					});
				}
				//upload button
				var formfield1;
				var formfield2;
				jQuery("#image_button").click(function(e) {
					if(jQuery(this).hasClass("apc_upload_image_button")) {
						formfield1 = jQuery(this).prev();
						formfield2 = jQuery(this).prev().prev();
						tb_show("", "media-upload.php?type=image&amp;apc=insert_file&amp;TB_iframe=true");
						return false;
					} else {
						var field_id = jQuery(this).attr("rel");
						var at_id = jQuery(this).prev().prev();
						var at_src = jQuery(this).prev();
						var t_button = jQuery(this);
						data = {
							action:        "apc_delete_mupload",
							_wpnonce:      $("#nonce-delete-mupload_" + field_id).val(),
							field_id:      field_id,
							attachment_id: jQuery(at_id).val()
						};

						$.post(ajaxurl, data, function(response) {
							if("success" == response.status) {
								jQuery(t_button).val("Upload Image");
								jQuery(t_button).removeClass("apc_delete_image_button").addClass("apc_upload_image_button");
								//clear html values
								jQuery(at_id).val("");
								jQuery(at_src).val("");
								jQuery(at_id).prev().html("");
								load_images_muploader();
							} else {
								alert(response.message);
							}
						}, "json");

						return false;
					}
				});

				//delete Image button
				//jQuery(".apc_delete_image_button").click(function(e){
				//});

				//store old send to editor function
				window.restore_send_to_editor = window.send_to_editor;
				//overwrite send to editor function
				window.send_to_editor = function(html) {
					imgurl = jQuery("img", html).attr("src");
					img_calsses = jQuery("img", html).attr("class").split(" ");
					att_id = "";
					jQuery.each(img_calsses, function(i, val) {
						if(val.indexOf("wp-image") != -1) {
							att_id = val.replace("wp-image-", "");
						}
					});

					jQuery(formfield2).val(att_id);
					jQuery(formfield1).val(imgurl);
					load_images_muploader();
					tb_remove();
					//restore old send to editor function
					window.send_to_editor = window.restore_send_to_editor;
				}
				';
			}
			echo '});</script>';
		}

		/**
		 * edit_insert_to_post_text
		 * rename insert to post button
		 *
		 *
		 * @todo     is this still needed?
		 * @since    0.1
		 *
		 * @param $safe_text
		 * @param $text
		 *
		 * @internal param string $input insert to post text
		 * @return string
		 */
		public function edit_insert_to_post_text($safe_text, $text) {
			if(is_admin() && 'Insert into Post' == $safe_text) {
				if(isset($_REQUEST['apc']) && 'insert_file' == $_REQUEST['apc']) {
					return str_replace(__('Insert into Post'), __('Use this File'), $safe_text);
				} else {
					return str_replace(__('Insert into Post'), __('Use this Image'), $safe_text);
				}
			}
			return $text;
		}

		/**
		 * Outputs all the HTML needed for the new page
		 *
		 * @access   public
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 * @internal param $repeater (boolean)
		 * @since    0.1
		 */
		public function DisplayPage() {
			do_action('mindshare_options_framework_before_page');

			if(isset($_POST['mindshare_framework_uninstall'])) {
				check_admin_referer('mindshare-framework-uninstall');
				delete_option($this->option_group);
				?>
				<div class="updated">
					<p><?php _e('All options have been removed from the database.'); ?>
						<?php if($this->project_path == 'PLUGIN') : ?>
							<?php
							if(!empty($this->project_slug)) {
								$deactivate_url = 'plugins.php?action=deactivate&amp;plugin='.$this->project_slug.'/'.$this->project_slug.'.php';
								$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_'.$this->project_slug.'/'.$this->project_slug.'.php');
							} else {
								$deactivate_url = admin_url('plugins.php');
							}
							?>
							To complete the uninstall <a href="<?php echo $deactivate_url; ?>">deactivate <?php echo $this->project_name ?>.</a>
						<?php elseif($this->project_path == 'THEME') : ?>
							To complete the uninstall <a href="themes.php">deactivate <?php echo $this->project_name ?>.</a>
						<?php endif; ?>
					</p>
				</div>
				<?php
				return;
			}

			if(isset($_POST['mindshare_framework_reset'])) {
				check_admin_referer('mindshare-framework-reset');
				delete_option($this->option_group);
				?>
				<div class="updated">
					<p><?php _e('All options have been restored to their default values.'); ?></p>
				</div>
			<?php
			}

			echo '<div class="wrap"><form method="post" action="" enctype="multipart/form-data"><div class="header_wrap">';
			echo apply_filters('mindshare_options_framework_before_title', '');
			echo '<h2>'.apply_filters('mindshare_options_framework_h2', $this->args['page_title']).'</h2>'.((isset($this->args['page_header_text'])) ? $this->args['page_header_text'] : '').'</div>';
			wp_nonce_field(basename(__FILE__), 'Mindshare_Options_Framework_nonce');
			if($this->saved_flag) {
				echo '<div class="updated"><p><strong>'.__('Settings saved.').'</strong></p></div>';
			}
			$saved = get_option($this->option_group);
			$this->_saved = $saved;

			foreach($this->_fields as $field) {
				if(!in_array($field['type'], $this->skip_array)) {
					if(!$this->table) {
						echo '<div class="form-table">';
						$this->table = TRUE;
					}
				} else {
					if($this->table) {
						echo '</div>';
						$this->table = FALSE;
					}
				}
				$data = '';
				if(isset($saved[$field['id']])) {
					$data = $saved[$field['id']];
				}
				if(isset($field['std']) && $data === '') {
					$data = $field['std'];
				}
				if(method_exists($this, 'show_field_'.$field['type'])) {
					echo apply_filters('mindshare_options_framework_field_container_open', '<div class="field">', $field);
					call_user_func(array($this, 'show_field_'.$field['type']), $field, $data);
					echo apply_filters('mindshare_options_framework_field_container_close', '</div>', $field);
				} else {
					switch($field['type']) {
						case 'TABS':
							echo '<div id="tabs">';
							break;
						case 'CloseDiv':
							$this->tab_div = FALSE;
							echo '</div>';
							break;
						case 'TABS_Listing':
							echo '<div class="panel_menu"><ul>';
							foreach($field['links'] as $id => $name) {
								$extra_classes = strtolower(str_replace(' ', '-', $name)).' '.strtolower(str_replace(' ', '-', $id));
								echo '<li class="'.apply_filters('APC_tab_li_extra_class', $extra_classes).'"><a class="nav_tab_link" href="#'.$id.'">'.$name.'</a></li>';
							}
							echo '</ul></div><div class="sections">';
							break;
						case 'OpenTab':
							$this->tab_div = TRUE;
							echo '<div class="setingstab" id="'.$field['id'].'">';
							do_action('mindshare_options_framework_after_tab_open');
							break;
						case 'title':
							echo '<h2>'.$field['label'].'</h2>';
							break;
						case 'subtitle':
							echo '<h3>'.$field['label'].'</h3>';
							break;
						case 'paragraph':
							echo '<p>'.$field['text'].'</p>';
							break;
						case 'repeater':
							do_action('mindshare_options_framework_before_repeater');
							$this->output_repeater_fields($field, $data);
							do_action('mindshare_options_framework_after_repeater');
							break;
						case 'import_export':
							$this->show_import_export();
							do_action('mindshare_options_framework_import_export_tab');
							break;
					}
				}
				if(!in_array($field['type'], $this->skip_array)) {
					//echo '</tr>';
				}
			}
			if($this->table) {
				//echo '</table>';
				echo '</div>';
			}
			if($this->tab_div) {
				echo '</div>';
			}
			?>
			</div>
			<div class="footer_wrap">
				<p class="submit">
					<input type="submit" name="Submit" class="<?php echo apply_filters('mindshare_options_framework_submit_class', 'button-primary'); ?>" value="<?php echo esc_attr(__('Save Changes')); ?>" />
					<input type="hidden" name="action" value="save" />
					<?php if($this->show_reset_button == TRUE) : ?>
						<input class="button-secondary" type="button" value="Restore Defautls" onclick="document.getElementById('mindshare-framework-reset').style.display = 'block';document.getElementById('mindshare-framework-uninst').style.display = 'none';" />
					<?php endif; ?>
					<?php if($this->show_uninstall_button == TRUE) : ?>
						<input class="button-secondary" type="button" value="Uninstall" onclick="document.getElementById('mindshare-framework-uninst').style.display = 'block';document.getElementById('mindshare-framework-reset').style.display = 'none';" />
					<?php endif; ?>
				</p>
			</div>
			</div>
			</div>
			</form>
			<div id="mindshare-framework-uninst" style="display:none; clear: both;">
				<form method="post" action="">
					<?php wp_nonce_field('mindshare-framework-uninstall'); ?>
					<label style="font-weight:normal;">Do you wish to <strong>completely unistall</strong> the <?php echo $this->project_name ?> plugin?</label>
					<input class="button-secondary" type="button" name="cancel" value="Cancel" onclick="document.getElementById('mindshare-framework-uninst').style.display = 'none';" style="margin-left:20px" />
					<input class="button-primary" type="submit" name="mindshare_framework_uninstall" value="Uninstall" />
				</form>
			</div>


			<div id="mindshare-framework-reset" style="display:none; clear: both;">
				<form method="post" action="">
					<?php wp_nonce_field('mindshare-framework-reset'); ?>
					<label style="font-weight:normal;">Do you wish to <strong>completely reset</strong> the default options for <?php echo $this->project_name ?>?</label>
					<input class="button-secondary" type="button" name="cancel" value="Cancel" onclick="document.getElementById('mindshare-framework-reset').style.display='none';" style="margin-left:20px" />
					<input class="button-primary" type="submit" name="mindshare_framework_reset" value="Restore Defaults" />
				</form>
			</div>
			</div>

			<?php
			do_action('mindshare_options_framework_after_page');
		}

		/**
		 * Adds tabs to the current page
		 *
		 * @access   public
		 *
		 * @param null $text
		 *
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 *
		 * @since    0.1
		 */
		public function OpenTabs_container($text = NULL) {
			$args['type'] = 'TABS';
			$text = (NULL == $text) ? '' : $text;
			$args['text'] = $text;
			$args['id'] = 'TABS';
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * Close open Div
		 *
		 * @access   public
		 *
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 * @internal param $repeater (boolean)
		 *
		 * @since    0.1
		 */
		public function CloseDiv_Container() {
			$args['type'] = 'CloseDiv';
			$args['id'] = 'CloseDiv';
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * Adds tabs listing in ul li
		 *
		 * @access   public
		 *
		 * @param $args (mixed|array) contains everything needed to build the field
		 *
		 * @internal param $repeater (boolean)
		 *
		 * @since    0.1
		 */
		public function TabsListing($args) {
			$args['type'] = 'TABS_Listing';
			$args['id'] = 'TABS_Listing';
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * Opens a Div
		 *
		 * @access   public
		 *
		 * @param $name
		 *
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 * @internal param $repeater (boolean)
		 *
		 * @since    0.1
		 */
		public function OpenTab($name) {
			$args['type'] = 'OpenTab';
			$args['id'] = $name;
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * close a Div
		 *
		 * @access public
		 * @since  0.1
		 */
		public function CloseTab() {
			$args['type'] = 'CloseDiv';
			$args['id'] = 'CloseDiv';
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * Does the repetitive tasks of adding a field
		 *
		 * @param $args (mixed|array) contains everything needed to build the field
		 *
		 * @internal param $repeater (boolean)
		 *
		 * @since    0.1
		 * @access   private
		 */
		private function SetField($args) {
			$default = array(
				'std' => '',
				'id'  => ''
			);
			$args = array_merge($default, $args);
			$this->buildOptions($args);
			$this->_fields[] = $args;
		}

		/**
		 * Builds all the options with their std values
		 *
		 * @access public
		 *
		 * @param $args (mixed|array) contains everything needed to build the field
		 *
		 * @since  0.1
		 * @access private
		 */
		private function buildOptions($args) {
			$default = array(
				'std' => '',
				'id'  => ''
			);
			$args = array_merge($default, $args);
			$saved = get_option($this->option_group);
			if(isset($saved[$args['id']])) {
				if($saved[$args['id']] === FALSE) {
					$saved[$args['id']] = $args['std'];
					update_option($this->args['option_group'], $saved);
				}
			}
		}

		/**
		 * Adds a heading to the current page
		 *
		 * @access   public
		 *
		 * @param string $label    simply the text for your heading
		 * @param bool   $repeater (boolean)
		 *
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 * @since    0.1
		 *
		 */
		public function Title($label, $repeater = FALSE) {
			$args['type'] = 'title';
			$args['std'] = '';
			$args['label'] = $label;
			$args['id'] = 'title'.$label;
			$this->SetField($args);
		}

		/**
		 * Adds a sub-heading to the current page
		 *
		 * @access   public
		 *
		 * @param string $label    simply the text for your heading
		 * @param bool   $repeater (boolean)
		 *
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 * @since    0.1
		 *
		 */
		public function Subtitle($label, $repeater = FALSE) {
			$args['type'] = 'subtitle';
			$args['label'] = $label;
			$args['id'] = 'title'.$label;
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * Adds a paragraph to the current page
		 *
		 * @access   public
		 *
		 * @param string $text     the text you want to display
		 * @param bool   $repeater (boolean)
		 *
		 * @internal param $args (mixed|array) contains everything needed to build the field
		 * @since    0.1
		 *
		 */
		public function Paragraph($text, $repeater = FALSE) {
			$args['type'] = 'paragraph';
			$args['text'] = $text;
			$args['id'] = 'paragraph';
			$args['std'] = '';
			$this->SetField($args);
		}

		/**
		 * Load all Javascript and CSS
		 *
		 * @since  0.1
		 * @access public
		 */
		public function load_scripts_styles() {
			$this->check_field_upload();
			$this->check_field_color();
			$this->check_field_date();
			$this->check_field_time();
			$this->check_field_code();

			wp_enqueue_script('common');

			if($this->has_Field('TABS')) {
				wp_print_scripts('jquery-ui-tabs');
			}
			if($this->has_Field('editor')) {
				global $wp_version;
				if(version_compare($wp_version, '3.2.1') < 1) {
					wp_print_scripts('tiny_mce');
					wp_print_scripts('editor');
					wp_print_scripts('editor-functions');
				}
			}

			wp_enqueue_style('Admin_Page_Class', $this->SelfPath.'/css/mindshare-options.css');
			wp_enqueue_style('iphone_checkbox', $this->SelfPath.'/lib/iphone-style-checkboxes/style.css');
			wp_enqueue_script('utils');
			wp_enqueue_script('json2');
			wp_enqueue_script('Admin_Page_Class', $this->SelfPath.'/js/mindshare-options-framework.js', array('jquery'), NULL, TRUE);
			wp_enqueue_script('iphone_checkbox', $this->SelfPath.'/lib/iphone-style-checkboxes/iphone-style-checkboxes.js', array('jquery'), NULL, TRUE);
			wp_enqueue_script('jquery-ui-sortable');
		}

		/**
		 * Check field code editor
		 *
		 * @since  0.1
		 * @access public
		 */
		public function check_field_code() {
			//var_dump($this->has_field('code')); die('call');
			if($this->has_field('code')) {
				// Enqueue codemirror js and css
				wp_enqueue_style('at-code-css', $this->SelfPath.'/lib/codemirror/codemirror.css', array(), NULL);
				//wp_enqueue_style('at-code-css-dark', $this->SelfPath.'/lib/codemirror/solarizedDark.css', array(), NULL);
				//wp_enqueue_style('at-code-css-light', $this->SelfPath.'/lib/codemirror/solarizedLight.css', array(), NULL);
				wp_enqueue_script('at-code-lib', $this->SelfPath.'/lib/codemirror/codemirror.js', array('jquery'), FALSE, TRUE);
				wp_enqueue_script('at-code-lib-xml', $this->SelfPath.'/lib/codemirror/xml.js', array('jquery'), FALSE, TRUE);
				wp_enqueue_script('at-code-lib-javascript', $this->SelfPath.'/lib/codemirror/javascript.js', array('jquery'), FALSE, TRUE);
				wp_enqueue_script('at-code-lib-css', $this->SelfPath.'/lib/codemirror/css.js', array('jquery'), FALSE, TRUE);
				wp_enqueue_script('at-code-lib-clike', $this->SelfPath.'/lib/codemirror/clike.js', array('jquery'), FALSE, TRUE);
				wp_enqueue_script('at-code-lib-php', $this->SelfPath.'/lib/codemirror/php.js', array('jquery'), FALSE, TRUE);
			}
		}

		/**
		 * Check field Plupload
		 *
		 * @since  0.9.7
		 * @access public
		 */
		public function check_filed_plupload() {
			if($this->has_field('plupload')) {

				wp_enqueue_script('plupload-all');
				wp_register_script('myplupload', $this->SelfPath.'/lib/plupload/myplupload.js', array('jquery'));
				wp_enqueue_script('myplupload');
				wp_register_style('myplupload', $this->SelfPath.'/lib/plupload/myplupload.css');
				wp_enqueue_style('myplupload');
			}
		}

		/**
		 * Check the field Upload, Add needed Actions
		 *
		 * @since  0.1
		 * @access public
		 */
		public function check_field_upload() {
			// Check if the field is an image or file. If not, return.
			if(!$this->has_field('image') && !$this->has_field('file')) {
				return;
			}
			// Add data encoding type for file uploading.
			add_action('post_edit_form_tag', array(&$this, 'add_enctype'));
			// Make upload feature work event when custom post type doesn't support 'editor'
			wp_enqueue_script('media-upload');
			add_thickbox();
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-sortable');
			// Add filters for media upload.
			add_filter('media_upload_gallery', array(&$this, 'insert_images'));
			add_filter('media_upload_library', array(&$this, 'insert_images'));
			add_filter('media_upload_image', array(&$this, 'insert_images'));
			// Delete all attachments when delete custom post type.
			add_action('wp_ajax_at_delete_file', array(&$this, 'delete_file'));
			add_action('wp_ajax_at_reorder_images', array(&$this, 'reorder_images'));
			// Delete file via Ajax
			add_action('wp_ajax_at_delete_mupload', array($this, 'wp_ajax_delete_image'));
		}

		/**
		 * Add data encoding type for file uploading
		 *
		 * @since  0.1
		 * @access public
		 */
		public function add_enctype() {
			echo ' enctype="multipart/form-data"';
		}

		/**
		 * Process img added to meta field.
		 * Modified from Faster Image Insert plugin.
		 *
		 * @author Cory Crowley
		 */
		public function insert_images() {
			// If post variables are empty, return.
			if(!isset($_POST['at-insert']) || empty($_POST['attachments'])) {
				return FALSE;
			}
			// Security Check
			check_admin_referer('media-form');
			// Create Security Nonce
			$nonce = wp_create_nonce('at_ajax_delete');
			// Get Post Id and field Id
			$id = $_POST['field_id'];
			// Modify the insertion string
			$html = '';
			foreach($_POST['attachments'] as $attachment_id => $attachment) {
				// Strip Slashes
				$attachment = stripslashes_deep($attachment);
				// If not selected or url is empty, continue in loop.
				if(empty($attachment['selected']) || empty($attachment['url'])) {
					continue;
				}
				$li = "<li id='item_{$attachment_id}'>";
				$li .= "<img src='{$attachment['url']}' alt='image_{$attachment_id}' />";

				$li .= "<a title='".__('Delete this image')."' class='at-delete-file' href='#' rel='{$nonce}|{$post_id}|{$id}|{$attachment_id}'><img src='".$this->SelfPath."/img/delete-16.png' alt='".__('Delete')."' /></a>";
				$li .= "<input type='hidden' name='{$id}[]' value='{$attachment_id}' />";
				$li .= "</li>";
				$html .= $li;
			} // End For Each
			return media_send_to_editor($html);
		}

		/**
		 * Delete attachments associated with the post.
		 *
		 * @since  0.1
		 * @access public
		 */
		public function delete_attachments($post_id) {
			// Get Attachments
			$attachments = get_posts(
				array(
					'numberposts' => -1,
					'post_type'   => 'attachment',
					'post_parent' => $post_id
				)
			);
			// Loop through attachments, if not empty, delete it.
			if(!empty($attachments)) {
				foreach($attachments as $att) {
					wp_delete_attachment($att->ID);
				}
			}
		}

		/**
		 * Ajax callback for deleting files.
		 * Modified from a function used by "Verve Meta Boxes" plugin (http://goo.gl/LzYSq)
		 *
		 * @since  0.1
		 * @access public
		 */
		public function wp_ajax_delete_image() {
			$field_id = isset($_GET['field_id']) ? $_GET['field_id'] : 0;
			$attachment_id = isset($_GET['attachment_id']) ? intval($_GET['attachment_id']) : 0;
			$ok = FALSE;
			if(strpos($field_id, '[') === FALSE) {
				check_admin_referer("at-delete-mupload_".urldecode($field_id));
				$temp = get_option($this->args['option_group']);
				unset($temp[$field_id]);
				update_option($this->args['option_group'], $temp);
				$ok = wp_delete_attachment($attachment_id);
			} else {
				$f = explode('[', urldecode($field_id));
				$f_fiexed = array();
				foreach($f as $k => $v) {
					$f[$k] = str_replace(']', '', $v);
				}
				$temp = get_option($this->args['option_group']);
				$saved = $temp[$f[0]];
				//var_dump($saved[$f[1]]); die;
				if(isset($saved[$f[1]])) {
					unset($saved[$f[1]]);
					$temp[$f[0]] = $saved;
					update_option($this->args['option_group'], $temp);
					$ok = wp_delete_attachment($attachment_id);
				}
			}
			if($ok) {
				echo json_encode(array('status' => 'success'));
				die();
			} else {
				echo json_encode(array('message' => __('Cannot delete file. Something\'s wrong.')));
				die();
			}
		}

		/**
		 * Ajax callback for reordering Images.
		 *
		 * @since  0.1
		 * @access public
		 */
		public function reorder_images() {
			if(!isset($_POST['data'])) {
				die();
			}
			list($order, $post_id, $key, $nonce) = explode('|', $_POST['data']);
			if(!wp_verify_nonce($nonce, 'at_ajax_reorder')) {
				die('1');
			}
			parse_str($order, $items);
			$items = $items['item'];
			$order = 1;
			foreach($items as $item) {
				wp_update_post(array('ID' => $item, 'post_parent' => $post_id, 'menu_order' => $order));
				$order++;
			}
			die('0');
		}

		/**
		 * Check field Color
		 *
		 * @since  0.1
		 * @access public
		 */
		public function check_field_color() {
			if(($this->has_field('color') || $this->has_field('typo'))) {
				// Enqueu built-in script and style for color picker.
				wp_enqueue_style('farbtastic');
				wp_enqueue_script('farbtastic');
			}
		}

		/**
		 * Check field Date
		 *
		 * @since  0.1
		 * @access public
		 */
		public function check_field_date() {
			if($this->has_field('date')) {
				// Enqueue JQuery UI, use proper version.
				wp_enqueue_style('at-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/'.$this->get_jqueryui_ver().'/themes/base/jquery-ui.css');
				wp_enqueue_script('at-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/'.$this->get_jqueryui_ver().'/jquery-ui.min.js', array('jquery'));
			}
		}

		/**
		 * Check field Time
		 *
		 * @since  0.1
		 * @access public
		 */
		public function check_field_time() {
			if($this->has_field('time')) {

				// Enqueue JQuery UI, use proper version.
				wp_enqueue_style('at-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/'.$this->get_jqueryui_ver().'/themes/base/jquery-ui.css');
				wp_enqueue_script('at-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/'.$this->get_jqueryui_ver().'/jquery-ui.min.js', array('jquery'));
				wp_enqueue_script('at-timepicker', $this->SelfPath.'/lib/time-and-date/jquery-ui-timepicker-addon.js', array('jquery'), NULL, TRUE);
			}
		}

		/**
		 * Add Meta Box for multiple post types.
		 *
		 * @since  0.1
		 * @access public
		 */
		/*public function add() { // @todo is this used anywhere???
			// Loop through array
			foreach($this->_meta_box['pages'] as $page) {
				add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(
																					 &$this,
																					 'show'
																				), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
			}
		}*/

		/**
		 * Callback function to show fields in Page.
		 *
		 * @since  0.1
		 * @access public
		 */
		// @todo make this a better API
		public function show() {
			global $post;
			wp_nonce_field(basename(__FILE__), 'Mindshare_Options_Framework_nonce');
			echo '<table class="form-table">';
			foreach($this->_fields as $field) {
				$meta = get_post_meta($post->ID, $field['id'], !$field['multiple']);
				$meta = ($meta !== '') ? $meta : $field['std'];
				if('image' != $field['type'] && $field['type'] != 'repeater') {
					$meta = is_array($meta) ? array_map('esc_attr', $meta) : esc_attr($meta);
				}
				echo '<tr>';
				// Call methods for displaying each field type
				call_user_func(array(&$this, 'show_field_'.$field['type']), $field, $meta);
				echo '</tr>';
			}
			echo '</table>';
		}

		/**
		 * Show Repeater fields.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since    0.1
		 * @modified at 0.4 added sortable option
		 * @access   public
		 */
		public function show_field_repeater($field, $meta) {

			$this->show_field_begin($field, $meta);
			$class = '';
			if($field['sortable']) {
				$class = " repeater-sortable";
			}
			$jsid = ltrim(strtolower(str_replace(' ', '', $field['id'])), '0123456789');
			echo "<div class='at-repeat".$class."' id='{$jsid}'>";
			$c = 0;
			$meta = isset($this->_saved[$field['id']]) ? $this->_saved[$field['id']] : '';
			if(count($meta) > 0 && is_array($meta)) {
				foreach($meta as $me) {
					//for labeling toggles
					$mmm = isset($me[$field['fields'][0]['id']]) ? $me[$field['fields'][0]['id']] : '';
					echo '<div class="at-repeater-block">'.$mmm.'<br /><table class="repeater-table" style="display: none;">';
					if($field['inline']) {
						echo '<tr class="at-inline" VALIGN="top">';
					}
					foreach($field['fields'] as $f) {
						//reset var $id for repeater
						$id = '';
						$id = $field['id'].'['.$c.']['.$f['id'].']';
						$m = isset($me[$f['id']]) ? $me[$f['id']] : '';
						if($m !== '') {
							//$m = $m;
						} else {
							$m = isset($f['std']) ? $f['std'] : '';
						}
						if('image' != $f['type'] && $f['type'] != 'repeater') {
							$m = is_array($m) ? array_map('esc_attr', $m) : esc_attr($m);
						}
						if(in_array($f['type'], array('text', 'textarea'))) {
							$m = stripslashes($m);
						}
						//set new id for field in array format
						$f['id'] = $id;
						if(!$field['inline']) {
							echo '<tr>';
						}
						call_user_func(array(&$this, 'show_field_'.$f['type']), $f, $m);
						if(!$field['inline']) {
							echo '</tr>';
						}
					}
					if($field['inline']) {
						echo '</tr>';
					}
					echo '</table>
					<span class="at-re-toggle"><img src="'.$this->SelfPath.'/img/edit.png" alt="Edit" title="Edit"/></span>
					<img src="'.$this->SelfPath.'/img/remove.png" alt="'.__('Remove').'" title="'.__('Remove').'" id="remove-'.$field['id'].'"></div>';
					$c = $c + 1;
				}
			}
			echo '<img class="add-repeater" src="'.$this->SelfPath.'/img/add.png" alt="'.__('Add').'" title="'.__('Add').'" id="add-'.$jsid.'"><br /></div>';
			//create all fields once more for js function and catch with object buffer
			ob_start();
			echo '<div class="at-repeater-block"><table class="repeater-table">';
			if($field['inline']) {
				echo '<tr class="at-inline" VALIGN="top">';
			}
			foreach($field['fields'] as $f) {
				//reset var $id for repeater
				$id = '';
				$id = $field['id'].'[CurrentCounter]['.$f['id'].']';
				$f['id'] = $id;
				if(!$field['inline']) {
					echo '<tr>';
				}
				$m = isset($f['std']) ? $f['std'] : '';
				call_user_func(array(&$this, 'show_field_'.$f['type']), $f, $m);
				if(!$field['inline']) {
					echo '</tr>';
				}
			}
			if($field['inline']) {
				echo '</tr>';
			}
			echo '</table><img src="'.$this->SelfPath.'/img/remove.png" alt="'.__('Remove').'" title="'.__('Remove').'" id="remove-'.$jsid.'"></div>';
			$counter = 'countadd_'.$jsid;
			$js_code = ob_get_clean();
			$js_code = str_replace("'", "\"", $js_code);
			$js_code = str_replace("CurrentCounter", "' + ".$counter." + '", $js_code);
			echo '<script type="text/javascript">
        jQuery(document).ready(function() {
          var '.$counter.' = '.$c.';
          jQuery("#add-'.$jsid.'").live(\'click\', function() {
            '.$counter.' = '.$counter.' + 1;
            jQuery(this).before(\''.$js_code.'\');            
            update_repeater_fields();
          });
              jQuery("#remove-'.$jsid.'").live(\'click\', function() {
                  jQuery(this).parent().remove();
              });
          });
        </script><br />';
			$this->show_field_end($field, $meta);
		}

		/**
		 * Begin field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_begin($field, $meta) {
			if(isset($field['group'])) {
				if($field['group'] == "start") {
					//echo "<td class='at-field'>";
				}
			} else {
				//echo "<td class='at-field'>";
			}
			if($field['name'] != '' || $field['name'] != FALSE) {
				echo "<div class='at-label'>";
				echo "<label for='{$field['id']}'>{$field['name']}</label>";
				echo "</div>";
			}
		}

		/**
		 * End field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @param bool   $group
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_end($field, $meta = NULL, $group = FALSE) {
			if(isset($field['group'])) {
				if($group == 'end') {
					if($field['desc'] != '') {
						echo "<div class='desc-field'>{$field['desc']}</div>";
						//echo "<div class='desc-field'>{$field['desc']}</div></td>";
					} else {
						//echo "</td>";
					}
				} else {
					if($field['desc'] != '') {
						echo "<div class='desc-field'>{$field['desc']}</div><br />";
					} else {
						echo '<br />';
					}
				}
			} else {
				if($field['desc'] != '') {
					echo "<div class='desc-field'>{$field['desc']}</div>";
					//echo "<div class='desc-field'>{$field['desc']}</div></td>";
				} else {
					//echo "</td>";
				}
			}
		}

		/**
		 * Show Sortable field
		 *
		 *
		 * @since  0.4
		 * @access public
		 *
		 * @param  (array) $field
		 * @param  (array) $meta
		 *
		 * @return void
		 */
		public function show_field_sortable($field, $meta) {
			$this->show_field_begin($field, $meta);
			$re = '<div class="at-sortable-con"><ul class="at-sortable">';
			$i = 0;
			if(!is_array($meta) || empty($meta)) {
				foreach($field['options'] as $value => $label) {
					$re .= '<li class="widget-sort at-sort-item_'.$i.'">'.$label.'<input type="hidden" value="'.$label.'" name="'.$field['id'].'['.$value.']">';
				}
			} else {
				foreach($meta as $value => $label) {
					$re .= '<li class="widget-sort at-sort-item_'.$i.'">'.$label.'<input type="hidden" value="'.$label.'" name="'.$field['id'].'['.$value.']">';
				}
			}
			$re .= '</ul></div>';
			echo $re;
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show field Text.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_text($field, $meta) {
			$this->show_field_begin($field, $meta);
			$meta_no_slashes = htmlspecialchars(stripslashes($meta), ENT_QUOTES);
			echo "<input type='text' class='at-text' name='{$field['id']}' id='{$field['id']}' value='".$meta_no_slashes."' size='30' />";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show field Plupload.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.9.7
		 * @access public
		 */
		public function show_field_plupload($field, $meta) {
			$this->show_field_begin($field, $meta);
			$id = $field['id']; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == “img1” then $_POST[“img1”] will have all the image urls
			$multiple = $field['multiple']; // allow multiple files upload
			$m1 = ($multiple) ? 'plupload-upload-uic-multiple' : '';
			$m2 = ($multiple) ? 'plupload-thumbs-multiple' : '';
			$width = $field['width']; // If you want to automatically resize all uploaded img then provide width here (in pixels)
			$height = $field['height']; // If you want to automatically resize all uploaded img then provide height here (in pixels)
			$html = '
        <input type="hidden" name="'.$id.'" id="'.$id.'" value="'.$meta.'" />
        <div class="plupload-upload-uic hide-if-no-js '.$m1.'" id="'.$id.'plupload-upload-ui">
          <input id="'.$id.'plupload-browse-button" type="button" value="'.__('Select Files').'" class="button" />
          <span class="ajaxnonceplu" id="ajaxnonceplu'.wp_create_nonce($id.'pluploadan').'"></span>';
			if($width && $height) {
				$html .= '<span class="plupload-resize"></span><span class="plupload-width" id="plupload-width'.$width.'"></span>
              <span class="plupload-height" id="plupload-height'.$height.'"></span>';
			}
			$html .= '<div class="filelist"></div>
        </div>
        <div class="plupload-thumbs '.$m2.'" id="'.$id.'plupload-thumbs">
        </div>
        <div class="clear"></div>';
			echo $html;
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show field code editor.
		 *
		 * @param string $field
		 *
		 *
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_code($field, $meta) {
			$this->show_field_begin($field, $meta);
			echo "<textarea class='code_text' name='{$field['id']}' id='{$field['id']}' data-lang='{$field['syntax']}' data-theme='{$field['theme']}'>".stripslashes($meta)."</textarea>";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show field hidden.
		 *
		 * @param string       $field
		 * @param string|mixed $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_hidden($field, $meta) {
			echo "<input type='hidden' class='at-text' name='{$field['id']}' id='{$field['id']}' value='{$meta}'/>";
		}

		/**
		 * Show field Paragraph.
		 *
		 * @param string $field
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_paragraph($field) {
			echo '<p>'.$field['value'].'</p>';
		}

		/**
		 * Show field Subtitle.
		 *
		 * @param string $field
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_subtitle($field) {
			echo '<h3>'.$field['value'].'</h3>';
		}

		/**
		 * Show field Textarea.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_textarea($field, $meta) {
			$this->show_field_begin($field, $meta);
			echo "<textarea class='at-textarea large-text' name='{$field['id']}' id='{$field['id']}' cols='60' rows='4'>{$meta}</textarea>";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show field Select.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_select($field, $meta) {
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			echo "<select class='at-select' name='{$field['id']}".((isset($field['multiple']) && $field['multiple']) ? "[]' id='{$field['id']}' multiple='multiple'" : "'").">";
			foreach($field['options'] as $key => $value) {
				echo "<option value='{$key}'".selected(in_array($key, $meta), TRUE, FALSE).">{$value}</option>";
			}
			echo "</select>";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Radio field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_radio($field, $meta) {
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			foreach($field['options'] as $key => $value) {
				echo "<input type='radio' class='at-radio' name='{$field['id']}' value='{$key}'".checked(in_array($key, $meta), TRUE, FALSE)." /> <span class='at-radio-label'>{$value}</span>";
			}
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Checkbox field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_checkbox($field, $meta) {
			$this->show_field_begin($field, $meta);
			echo "<input type='checkbox' ";
			if($field['style'] != 'simple') {
				echo "class='rw-checkbox' ";
			}
			echo "name='{$field['id']}' id='{$field['id']}'".checked($meta, TRUE, FALSE)." />";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show conditional Checkbox field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.5
		 * @access public
		 */
		public function show_field_cond($field, $meta) {
			$this->show_field_begin($field, $meta);
			$checked = FALSE;
			//var_dump($meta);
			if(is_array($meta) && isset($meta['enabled']) && $meta['enabled'] == 'on' || $meta == 'on') {
				$checked = TRUE;
			}
			if(is_bool($meta)) {
				$checked = $meta;
			} elseif(is_bool($field['std']) && empty($meta)) {
				$checked = $field['std'];
			}
			echo "<input type='checkbox' ";
			if($field['style'] != 'simple') {
				echo "class='rw-checkbox conditional_control' ";
			} else {
				echo "class='conditional_control' ";
			}
			echo "name='{$field['id']}[enabled]' id='{$field['id']}'".checked($checked, TRUE, FALSE)." />";
			//start showing the fields
			$display = ' style="display: none;"';
			if($checked) {
				$display = '';
			}
			echo '<div class="conditional_container"'.$display.'>';
			foreach((array) $field['fields'] as $f) {
				//reset var $id for conditional
				$id = $field['id'].'['.$f['id'].']';
				$m = '';
				if($checked) {
					$m = (isset($meta[$f['id']])) ? $meta[$f['id']] : '';
				}

				$m = ($m !== '') ? $m : @$f['std'];
				if('image' != $f['type'] && $f['type'] != 'repeater') {
					$m = is_array($m) ? array_map('esc_attr', $m) : esc_attr($m);
				}
				if(is_array($m) && isset($m['enabled']) && $m['enabled'] == 'on' || $m == 'on') {
					$m = TRUE;
				}
				if(!is_bool($m) && @is_bool($f['std'])) {
					$m = $f['std'];
				}
				//set new id for field in array format
				$f['id'] = $id;
				call_user_func(array(&$this, 'show_field_'.$f['type']), $f, $m);
			}
			echo '</div>';
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Wysiwig field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_wysiwyg($field, $meta) {
			$this->show_field_begin($field, $meta);
			// Add TinyMCE script for WP version < 3.3
			global $wp_version;
			if(version_compare($wp_version, '3.2.1') < 1) {
				echo "<textarea class='at-wysiwyg theEditor large-text' name='{$field['id']}' id='{$field['id']}' cols='60' rows='10'>{$meta}</textarea>";
			} else {
				// Use new wp_editor() since WP 3.3
				@wp_editor(stripslashes(stripslashes(html_entity_decode($meta))), $field['id'], array('editor_class' => 'at-wysiwyg'));
			}
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show File field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_file($field, $meta) {
			global $post;
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			echo "{$field['desc']}<br />";
			if(!empty($meta)) {
				$nonce = wp_create_nonce('at_ajax_delete');
				echo '<div style="margin-bottom: 10px"><strong>'.__('Uploaded files').'</strong></div>';
				echo '<ol class="at-upload">';
				foreach($meta as $att) {
					// if (wp_attachment_is_image($att)) continue; // what's image uploader for?
					echo "<li>".wp_get_attachment_link($att, '', FALSE, FALSE, ' ')." (<a class='at-delete-file' href='#' rel='{$nonce}|{$post->ID}|{$field['id']}|{$att}'>".__('Delete')."</a>)</li>";
				}
				echo '</ol>';
			}
			// show form upload
			echo "<div class='at-file-upload-label'>";
			echo "<strong>".__('Upload new files')."</strong>";
			echo "</div>";
			echo "<div class='new-files'>";
			echo "<div class='file-input'>";
			echo "<input type='file' name='{$field['id']}[]' />";
			echo "</div><!-- End .file-input -->";
			echo "<a class='at-add-file button' href='#'>".__('Add more files')."</a>";
			echo "</div><!-- End .new-files -->";
			echo "</td>";
		}

		/**
		 * Show Image field.
		 *
		 * @param array $field
		 * @param array $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_image($field, $meta) {
			$this->show_field_begin($field, $meta);
			$html = wp_nonce_field("at-delete-mupload_{$field['id']}", "nonce-delete-mupload_".$field['id'], FALSE, FALSE);
			$height = (isset($field['preview_height'])) ? $field['preview_height'] : 'auto';
			$width = (isset($field['preview_width'])) ? $field['preview_width'] : '150px';
			if(is_array($meta)) {
				if(isset($meta[0]) && is_array($meta[0])) {
					$meta = $meta[0];
				}
			}
			if(is_array($meta) && isset($meta['src']) && $meta['src'] != '') {
				$html .= "<span class='mupload_img_holder' data-wi='".$width."' data-he='".$height."'><img src='".$meta['src']."' style='height: ".$height.";width: ".$width.";' /></span>";
				$html .= "<input type='hidden' name='".$field['id']."[id]' id='".$field['id']."[id]' value='".$meta['id']."' />";
				$html .= "<input type='hidden' name='".$field['id']."[src]' id='".$field['id']."[src]' value='".$meta['src']."' />";
				$html .= "<input class='at-delete_image_button' type='button' rel='".$field['id']."' value='Delete Image' />";
			} else {
				$html .= "<span class='mupload_img_holder'  data-wi='".$width."' data-he='".$height."'></span>";
				$html .= "<input type='hidden' name='".$field['id']."[id]' id='".$field['id']."[id]' value='' />";
				$html .= "<input type='hidden' name='".$field['id']."[src]' id='".$field['id']."[src]' value='' />";
				$html .= "<input class='at-upload_image_button' type='button' rel='".$field['id']."' value='Upload Image' />";
			}
			echo $html;
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Typography field.
		 *
		 *
		 * @param array $field
		 * @param array $meta
		 *
		 * @since  0.3
		 * @access public
		 */
		public function show_field_typo($field, $meta) {
			$this->show_field_begin($field, $meta);
			if(!is_array($meta)) {
				$meta = array(
					'size'   => '',
					'face'   => '',
					'style'  => '',
					'color'  => '#',
					'weight' => '',
				);
			}
			$html = '<select class="at-typography at-typography-size" name="'.esc_attr($field['id'].'[size]').'" id="'.esc_attr($field['id'].'_size').'">';
			$op = '';
			for($i = 16; $i < 200; $i = $i + 8) {
				$size = $i.'px';
				$op .= '<option value="'.esc_attr($size).'">'.esc_html($size).'</option>';
			}
			if(isset($meta['size'])) {
				$op = str_replace('value="'.$meta['size'].'"', 'value="'.$meta['size'].'" selected="selected"', $op);
			}
			$html .= $op.'</select>';
			// Font Face
			$html .= '<select class="at-typography at-typography-face" name="'.esc_attr($field['id'].'[face]').'" id="'.esc_attr($field['id'].'_face').'">';
			$faces = $this->get_fonts_family();
			$op = '';
			foreach($faces as $key => $face) {
				$op .= '<option value="'.esc_attr($key).'">'.esc_html($face['name']).'</option>';
			}
			if(isset($meta['face'])) {
				$op = str_replace('value="'.$meta['face'].'"', 'value="'.$meta['face'].'" selected="selected"', $op);
			}
			$html .= $op.'</select>';
			// Font Weight
			$html .= '<select class="at-typography at-typography-weight" name="'.esc_attr($field['id'].'[weight]').'" id="'.esc_attr($field['id'].'_weight').'">';
			$weights = $this->get_font_weight();
			$op = '';
			foreach($weights as $key => $label) {
				$op .= '<option value="'.esc_attr($key).'">'.esc_html($label).'</option>';
			}
			if(isset($meta['weight'])) {
				$op = str_replace('value="'.$meta['weight'].'"', 'value="'.$meta['weight'].'" selected="selected"', $op);
			}
			$html .= $op.'</select>';
			/* Font Style */
			$html .= '<select class="at-typography at-typography-style" name="'.$field['id'].'[style]" id="'.$field['id'].'_style">';
			$styles = $this->get_font_style();
			$op = '';
			foreach($styles as $key => $style) {
				$op .= '<option value="'.esc_attr($key).'">'.$style.'</option>';
			}
			if(isset($meta['style'])) {
				$op = str_replace('value="'.$meta['style'].'"', 'value="'.$meta['style'].'" selected="selected"', $op);
			}
			$html .= $op.'</select>';
			// Font Color
			$html .= "<input class='at-color' type='text' name='".$field['id']."[color]' id='".$field['id']."' value='".$meta['color']."' size='6' />";
			$html .= "<input type='button' class='at-color-select button' rel='".$field['id']."' value='".__('Select a color')."'/>";
			$html .= "<div style='display:none' class='at-color-picker' rel='".$field['id']."'></div>";
			echo $html;
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Color Picker field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_color($field, $meta) {
			if(empty($meta)) {
				$meta = '#';
			}
			$this->show_field_begin($field, $meta);
			echo "<input class='at-color' type='text' name='{$field['id']}' id='{$field['id']}' value='{$meta}' size='8' />";
			echo "<input type='button' class='at-color-select button' rel='{$field['id']}' value='".__('Select a color')."'/>";
			echo "<div style='display:none' class='at-color-picker' rel='{$field['id']}'></div>";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Checkbox List field
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_checkbox_list($field, $meta) {
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			$html = array();
			foreach($field['options'] as $key => $value) {
				$html[] = "<input type='checkbox' class='at-checkbox_list' name='{$field['id']}[]' value='{$key}'".checked(in_array($key, $meta), TRUE, FALSE)." /> {$value}";
			}
			echo implode('<br />', $html);
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Date field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_date($field, $meta) {
			$this->show_field_begin($field, $meta);
			echo "<input type='text' class='at-date' name='{$field['id']}' id='{$field['id']}' rel='{$field['format']}' value='{$meta}' size='30' />";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show time field.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_time($field, $meta) {
			$this->show_field_begin($field, $meta);
			echo "<input type='text' class='at-time' name='{$field['id']}' id='{$field['id']}' rel='{$field['format']}' value='{$meta}' size='30' />";
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Posts field.
		 * used creating a posts/pages/custom types checkboxlist or a select dropdown
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 */
		public function show_field_posts($field, $meta) {
			global $post;
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			$options = $field['options'];
			$posts = get_posts($options['args']);
			// checkbox_list
			if('checkbox_list' == $options['type']) {
				foreach($posts as $p) {
					echo "<input type='checkbox' name='{$field['id']}[]' value='$p->ID'".checked(in_array($p->ID, $meta), TRUE, FALSE)." /> $p->post_title<br />";
				}
			} // select
			else {
				echo "<select name='{$field['id']}".($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'").">";
				foreach($posts as $p) {
					echo "<option value='$p->ID'".selected(in_array($p->ID, $meta), TRUE, FALSE).">$p->post_title</option>";
				}
				echo "</select>";
			}
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Taxonomy field.
		 * used creating a category/tags/custom taxonomy checkboxlist or a select dropdown
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 * @uses   get_terms()
		 */
		public function show_field_taxonomy($field, $meta) {
			global $post;
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			$options = $field['options'];
			$terms = get_terms($options['taxonomy'], $options['args']);
			// checkbox_list
			if('checkbox_list' == $options['type']) {
				foreach($terms as $term) {
					echo "<input type='checkbox' class='{$field['id']}' name='{$field['id']}[]' value='$term->slug'".checked(in_array($term->slug, $meta), TRUE, FALSE)." /> $term->name  <br />";
				}
				echo "<br /><input type='checkbox' name='all' id='selectall' class='{$field['id']}' /> <strong>Select/deselect all</strong><br />";
				echo "<script type='text/javascript'>
						jQuery('document').ready(function() {
							jQuery('#selectall.{$field['id']}').click(function() {
								jQuery('input.{$field['id']}').attr('checked', this.checked);
							});
						});
						</script>";
			} // select
			else {
				echo "<select id='{$field['id']}' name='{$field['id']}".($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'").">";
				foreach($terms as $term) {
					echo "<option value='$term->slug'".selected(in_array($term->slug, $meta), TRUE, FALSE).">$term->name</option>";
				}
				echo "</select>";
			}
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Post Types field.
		 * used  to create a post types checkbox list or a select drop down.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.3.5
		 * @access public
		 * @uses   get_post_types()
		 */
		public function show_field_posttypes($field, $meta) {

			global $post;

			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			$options = $field['options'];
			$types = $options['types'];

			// checkbox_list
			if('checkbox_list' == $options['type']) {
				foreach($types as $type) {

					echo "<input type='checkbox' class='{$field['id']}' name='{$field['id']}[]' value='$type'".checked(in_array($type, $meta), TRUE, FALSE)." /> $type  <br />";
				}
				echo "<br /><input type='checkbox' name='all' id='selectall' class='{$field['id']}' /> <strong>Select/deselect all</strong><br />";
				echo "<script type='text/javascript'>
						jQuery('document').ready(function() {
							jQuery('#selectall.{$field['id']}').click(function() {
								jQuery('input.{$field['id']}').attr('checked', this.checked);
							});
						});
						</script>";
			} // select
			else {
				echo "<select name='{$field['id']}".($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'").">";
				foreach($types as $type) {
					echo "<option value='$type->slug'".selected(in_array($type->slug, $meta), TRUE, FALSE).">$type->name</option>";
				}
				echo "";
				echo "</select>";
			}
			$this->show_field_end($field, $meta);
		}

		/**
		 * Show Role field.
		 * used creating a WordPress roles list checkbox list or a select drop down.
		 *
		 * @param string $field
		 * @param string $meta
		 *
		 * @since  0.1
		 * @access public
		 * @uses   global $wp_roles;
		 * @uses   checked();
		 */
		public function show_field_WProle($field, $meta) {
			if(!is_array($meta)) {
				$meta = (array) $meta;
			}
			$this->show_field_begin($field, $meta);
			$options = $field['options'];
			global $wp_roles;
			if(!isset($wp_roles)) {
				$wp_roles = new WP_Roles();
			}
			$names = $wp_roles->get_names();
			if($names) {
				// checkbox_list
				if('checkbox_list' == $options['type']) {
					foreach($names as $n) {
						echo "<input type='checkbox' name='{$field['id']}[]' value='$n'".checked(in_array($n, $meta), TRUE, FALSE)." /> $n<br />";
					}
				} // select
				else {
					echo "<select name='{$field['id']}".(@$field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'").">";
					foreach($names as $n) {
						echo "<option value='$n'".selected(in_array($n, $meta), TRUE, FALSE).">$n</option>";
					}
					echo "</select>";
				}
			}
			$this->show_field_end($field, $meta);
		}

		/**
		 * Save Data from page
		 *
		 * @param bool|string $repeater (false )
		 *
		 * @since  0.1
		 * @access public
		 */
		public function save($repeater = FALSE) {
			$saved = get_option($this->option_group);
			$this->_saved = $saved;
			$post_data = isset($_POST) ? $_POST : NULL;
			if($post_data == NULL) {
				return;
			}

			//check nonce
			if(!check_admin_referer(basename(__FILE__), 'Mindshare_Options_Framework_nonce')) {
				return;
			}
			foreach($this->_fields as $field) {
				if(!in_array($field['type'], $this->skip_array)) {
					$name = $field['id'];
					$type = $field['type'];
					$old = isset($saved[$name]) ? $saved[$name] : NULL;
					//$new = (isset($_POST[$name])) ? $_POST[$name] : ((isset($field['multiple']) && $field['multiple']) ? array() : ''); // double ternary yuck, replaced:
					if((isset($_POST[$name]))) {
						$new = $_POST[$name];
					} else {
						if(isset($field['multiple']) && $field['multiple']) {
							$new = array();
						} else {
							$new = '';
						}
					}

					// Validate meta value
					if(class_exists('Subscribr_Mindshare_Validator')) {
						$new = Subscribr_Mindshare_Validator::validate($field, $new);
					}

					// Call defined method to save meta value, if there's no methods, call common one.
					$save_func = 'save_field_'.$type;
					if(method_exists($this, $save_func)) {
						call_user_func(array(&$this, 'save_field_'.$type), $field, $old, $new);
					} else {
						$this->save_field($field, $old, $new);
					}
				} //END Skip
			} // End foreach
			update_option($this->args['option_group'], $this->_saved);
		}

		/**
		 * Common function for saving fields.
		 *
		 * @param string       $field
		 * @param string       $old
		 * @param string|mixed $new
		 *
		 * @since  0.1
		 * @access public
		 */
		public function save_field($field, $old, $new) {
			$name = $field['id'];
			unset($this->_saved[$name]);
			if($new === '' || $new === array()) {
				return;
			}
			if(isset($field['multiple']) && $field['multiple'] && $field['type'] != 'plupload') {
				foreach($new as $add_new) {
					$temp[] = $add_new;
				}
				$this->_saved[$name] = $temp;
			} else {
				$this->_saved[$name] = $new;
			}
		}

		/**
		 * function for saving image field.
		 *
		 * @param string       $field
		 * @param string       $old
		 * @param string|mixed $new
		 *
		 * @since  0.1
		 * @access public
		 */
		public function save_field_image($field, $old, $new) {
			$name = $field['id'];
			unset($this->_saved[$name]);
			if($new === '' || $new === array() || $new['id'] == '' || $new['src'] == '') {
				return;
			}
			$this->_saved[$name] = $new;
		}

		/**
		 * Save Wysiwyg field.
		 *
		 * @param string $field
		 * @param string $old
		 * @param string $new
		 *
		 * @since  0.1
		 * @access public
		 */
		public function save_field_wysiwyg($field, $old, $new) {
			$this->save_field($field, $old, htmlentities($new));
		}

		/**
		 * Save checkbox field.
		 *
		 * @param string $field
		 * @param string $old
		 * @param string $new
		 *
		 * @since  0.9
		 * @access public
		 */
		public function save_field_checkbox($field, $old, $new) {
			if($new === '') {
				$this->save_field($field, $old, FALSE);
			} else {
				$this->save_field($field, $old, TRUE);
			}
		}

		/**
		 * Save repeater fields.
		 *
		 * @param string       $field
		 * @param string|mixed $old
		 * @param string|mixed $new
		 *
		 * @since  0.1
		 * @access public
		 */
		public function save_field_repeater($field, $old, $new) {
			if(is_array($new) && count($new) > 0) {
				foreach($new as $n) {
					foreach($field['fields'] as $f) {
						$type = $f['type'];
						switch($type) {
							case 'wysiwyg':
								$n[$f['id']] = wpautop($n[$f['id']]);
								break;
							case 'file':
								$n[$f['id']] = $this->save_field_file_repeater($f, '', $n[$f['id']]);
								break;
							default:
								break;
						}
					}
					if(!$this->is_array_empty($n)) {
						$temp[] = $n;
					}
				}
				if(isset($temp) && count($temp) > 0 && !$this->is_array_empty($temp)) {
					$this->_saved[$field['id']] = $temp;
				} else {
					if(isset($this->_saved[$field['id']])) {
						unset($this->_saved[$field['id']]);
					}
				}
			} else {
				//  remove old meta if exists
				if(isset($this->_saved[$field['id']])) {
					unset($this->_saved[$field['id']]);
				}
			}
		}

		/**
		 * Add missed values for Page.
		 *
		 * @since  0.1
		 * @access public
		 */
		public function add_missed_values() {
			// Default values for admin
			//$this->_meta_box = array_merge( array( 'context' => 'normal', 'priority' => 'high', 'pages' => array( 'post' ) ), $this->_meta_box );
			// Default values for fields
			foreach($this->_fields as &$field) {
				$multiple = in_array($field['type'], array('checkbox_list', 'file', 'image'));
				$std = $multiple ? array() : '';
				$format = 'date' == $field['type'] ? 'yy-mm-dd' : ('time' == $field['type'] ? 'hh:mm' : '');
				$field = array_merge(
					array(
						'multiple'            => $multiple,
						'std'                 => $std,
						'desc'                => '',
						'format'              => $format,
						'validation_function' => ''
					), $field);
			}
		}

		/**
		 * Check if field with $type exists.
		 *
		 * @param string $type
		 *
		 * @return bool
		 * @since  0.1
		 * @access public
		 */
		public function has_field($type) {
			foreach($this->_fields as $field) {
				if($field['type'] == $type) {
					return TRUE;
				}
				if($field['type'] == 'cond' || $field['type'] == 'repeater') {
					foreach($field['fields'] as $sub_field) {
						if($sub_field['type'] == $type) {
							return TRUE;
						}
					}
				}
			}
			return FALSE;
		}

		/**
		 * Fixes the odd indexing of multiple file uploads.
		 * Goes from the format:
		 * $_FILES['field']['key']['index']
		 * to
		 * The More standard and appropriate:
		 * $_FILES['field']['index']['key']
		 *
		 * @param string $files
		 *
		 * @return array
		 * @since  0.1
		 * @access public
		 */
		/*public function fix_file_array(&$files) {
			$output = array();
			foreach($files as $key => $list) {
				foreach($list as $index => $value) {
					$output[$index][$key] = $value;
				}
			}
			return $files = $output;
		}*/

		/**
		 * Get proper JQuery UI version.
		 * Used in order to not conflict with WP Admin Scripts.
		 *
		 * @since  0.1
		 * @access public
		 */
		public function get_jqueryui_ver() {
			global $wp_version;
			if(version_compare($wp_version, '3.1', '>=')) {
				return '1.8.10';
			}
			return '1.7.3';
		}

		/**
		 *  Add field to page (generic function)
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id   string  field id, i.e. the meta key
		 * @param $args mixed|array
		 */
		public function addField($id, $args) {
			$new_field = array('id' => $id, 'std' => '', 'desc' => '', 'style' => '');
			$new_field = array_merge($new_field, $args);
			$this->_fields[] = $new_field;
		}

		/**
		 * Add typography field
		 *
		 * @since  0.3
		 * @access public
		 *
		 * @param          $id       string  id of the field
		 * @param          $args     mixed|array
		 * @param  boolean $repeater =false
		 */
		public function addTypo($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'  => 'typo',
				'id'    => $id,
				'std'   => array(
					'size'   => '12px',
					'color'  => '#000000',
					'face'   => 'arial',
					'style'  => 'normal',
					'weight' => 'normal'
				),
				'desc'  => '',
				'style' => '',
				'name'  => 'Typography field'
			);
			$new_field = array_merge($new_field, $args);
			$this->_fields[] = $new_field;
		}

		/**
		 *  Add Text field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'style' =>   // custom style for field, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater/conditional? true|false(default)
		 *
		 * @return array
		 */
		public function addText($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'  => 'text',
				'id'    => $id,
				'std'   => '',
				'desc'  => '',
				'style' => '',
				'name'  => 'Text field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Pluploader field to Page
		 *
		 *
		 * @since  0.9.7
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'style' =>   // custom style for field, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addPlupload($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'     => 'plupload',
				'id'       => $id,
				'std'      => '',
				'desc'     => '',
				'style'    => '',
				'name'     => 'PlUpload field',
				'width'    => NULL,
				'height'   => NULL,
				'multiple' => FALSE
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Hidden field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'style' =>   // custom style for field, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addHidden($id, $args, $repeater = FALSE) {
			$new_field = array('type' => 'hidden', 'id' => $id, 'std' => '', 'desc' => '', 'style' => '', 'name' => '');
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add code Editor to page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'style' =>   // custom style for field, string optional
		 *                  'syntax' =>   // syntax language to use in editor (php,javascript,css,html)
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addCode($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'   => 'code',
				'id'     => $id,
				'std'    => '',
				'desc'   => '',
				'style'  => '',
				'name'   => 'Code Editor field',
				'syntax' => 'php',
				'theme'  => 'default'
			);
			$new_field = array_merge($new_field, (array) $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Paragraph to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $p        (string) paragraph html
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addParagraph($p, $repeater = FALSE) {
			$new_field = array('type' => 'paragraph', 'id' => '', 'value' => $p);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Subtitle to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $s        (string) subtitle html
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addSubtitle($s, $repeater = FALSE) {
			$new_field = array('type' => 'subtitle', 'id' => '', 'value' => $s);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Checkbox field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addCheckbox($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'  => 'checkbox',
				'id'    => $id,
				'std'   => '',
				'desc'  => '',
				'style' => '',
				'name'  => 'Checkbox field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Checkbox conditional field to Page
		 *
		 *
		 * @since  0.5
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 *                  'fields' => list of fields to show conditionally.
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addCondition($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'   => 'cond',
				'id'     => $id,
				'std'    => FALSE,
				'desc'   => '',
				'style'  => 'simple',
				'name'   => 'Conditional field',
				'fields' => array()
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add CheckboxList field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $options  (array)  array of key => value pairs for select options
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array : remember to call: $checkbox_list = get_post_meta(get_the_ID(), 'meta_name', false);
		 */
		public function addCheckboxList($id, $options, $args, $repeater = FALSE) {
			$new_field = array(
				'type'  => 'checkbox_list',
				'id'    => $id,
				'std'   => '',
				'desc'  => '',
				'style' => '',
				'name'  => 'Checkbox List field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Textarea field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'style' =>   // custom style for field, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addTextarea($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'  => 'textarea',
				'id'    => $id,
				'std'   => '',
				'desc'  => '',
				'style' => '',
				'name'  => 'Textarea field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Select field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string field id, i.e. the meta key
		 * @param $options  (array)  array of key => value pairs for select options
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, (array) optional
		 *                  'multiple' => // select multiple values, optional. Default is false.
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addSelect($id, $options, $args, $repeater = FALSE) {
			$new_field = array(
				'type'     => 'select',
				'id'       => $id,
				'std'      => array(),
				'desc'     => '',
				'style'    => '',
				'name'     => 'Select field',
				'multiple' => FALSE,
				'options'  => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add US States field to Page
		 *
		 * @author Damian Taggart
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, (array) optional
		 *                  'multiple' => // select multiple values, optional. Default is false.
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addUSStates($id, $args, $repeater = FALSE) {
			$options = array(
				''   => 'Non US',
				'AL' => 'Alabama',
				'AK' => 'Alaska',
				'AZ' => 'Arizona',
				'AR' => 'Arkansas',
				'CA' => 'California',
				'CO' => 'Colorado',
				'CT' => 'Connecticut',
				'DE' => 'Delaware',
				'DC' => 'District Of Columbia',
				'FL' => 'Florida',
				'GA' => 'Georgia',
				'HI' => 'Hawaii',
				'ID' => 'Idaho',
				'IL' => 'Illinois',
				'IN' => 'Indiana',
				'IA' => 'Iowa',
				'KS' => 'Kansas',
				'KY' => 'Kentucky',
				'LA' => 'Louisiana',
				'ME' => 'Maine',
				'MD' => 'Maryland',
				'MA' => 'Massachusetts',
				'MI' => 'Michigan',
				'MN' => 'Minnesota',
				'MS' => 'Mississippi',
				'MO' => 'Missouri',
				'MT' => 'Montana',
				'NE' => 'Nebraska',
				'NV' => 'Nevada',
				'NH' => 'New Hampshire',
				'NJ' => 'New Jersey',
				'NM' => 'New Mexico',
				'NY' => 'New York',
				'NC' => 'North Carolina',
				'ND' => 'North Dakota',
				'OH' => 'Ohio',
				'OK' => 'Oklahoma',
				'OR' => 'Oregon',
				'PA' => 'Pennsylvania',
				'RI' => 'Rhode Island',
				'SC' => 'South Carolina',
				'SD' => 'South Dakota',
				'TN' => 'Tennessee',
				'TX' => 'Texas',
				'UT' => 'Utah',
				'VT' => 'Vermont',
				'VA' => 'Virginia',
				'WA' => 'Washington',
				'WV' => 'West Virginia',
				'WI' => 'Wisconsin',
				'WY' => 'Wyoming',
				'AS' => 'American Samoa',
				'GU' => 'Guam',
				'MP' => 'Northern Mariana Islands',
				'PR' => 'Puerto Rico',
				'UM' => 'United States Minor Outlying Islands',
				'VI' => 'Virgin Islands',
				'AA' => 'Armed Forces Americas',
				'AP' => 'Armed Forces Pacific',
				'AE' => 'Armed Forces Others'
			);
			$new_field = array(
				'type'     => 'select',
				'id'       => $id,
				'std'      => array(),
				'desc'     => '',
				'style'    => '',
				'name'     => 'Select field',
				'multiple' => FALSE,
				'options'  => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Country field to Page
		 *
		 * @author Damian Taggart
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, (array) optional
		 *                  'multiple' => // select multiple values, optional. Default is false.
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addCountry($id, $args, $repeater = FALSE) {
			$options = array(
				'AF' => 'Afghanistan',
				'AX' => '&Aring;land Islands',
				'AL' => 'Albania',
				'DZ' => 'Algeria',
				'AS' => 'American Samoa',
				'AD' => 'Andorra',
				'AO' => 'Angola',
				'AI' => 'Anguilla',
				'AQ' => 'Antarctica',
				'AG' => 'Antigua and Barbuda',
				'AR' => 'Argentina',
				'AM' => 'Armenia',
				'AW' => 'Aruba',
				'AU' => 'Australia',
				'AT' => 'Austria',
				'AZ' => 'Azerbaijan',
				'BS' => 'Bahamas',
				'BH' => 'Bahrain',
				'BD' => 'Bangladesh',
				'BB' => 'Barbados',
				'BY' => 'Belarus',
				'BE' => 'Belgium',
				'BZ' => 'Belize',
				'BJ' => 'Benin',
				'BM' => 'Bermuda',
				'BT' => 'Bhutan',
				'BO' => 'Bolivia, Plurinational State of',
				'BQ' => 'Bonaire, Sint Eustatius and Saba',
				'BA' => 'Bosnia and Herzegovina',
				'BW' => 'Botswana',
				'BV' => 'Bouvet Island',
				'BR' => 'Brazil',
				'IO' => 'British Indian Ocean Territory',
				'BN' => 'Brunei Darussalam',
				'BG' => 'Bulgaria',
				'BF' => 'Burkina Faso',
				'BI' => 'Burundi',
				'KH' => 'Cambodia',
				'CM' => 'Cameroon',
				'CA' => 'Canada',
				'CV' => 'Cape Verde',
				'KY' => 'Cayman Islands',
				'CF' => 'Central African Republic',
				'TD' => 'Chad',
				'CL' => 'Chile',
				'CN' => 'China',
				'CX' => 'Christmas Island',
				'CC' => 'Cocos (Keeling) Islands',
				'CO' => 'Colombia',
				'KM' => 'Comoros',
				'CG' => 'Congo',
				'CD' => 'Congo, the Democratic Republic of the',
				'CK' => 'Cook Islands',
				'CR' => 'Costa Rica',
				'CI' => 'C&ocirc;te d\'Ivoire',
				'HR' => 'Croatia',
				'CU' => 'Cuba',
				'CW' => 'Curaçao',
				'CY' => 'Cyprus',
				'CZ' => 'Czech Republic',
				'DK' => 'Denmark',
				'DJ' => 'Djibouti',
				'DM' => 'Dominica',
				'DO' => 'Dominican Republic',
				'EC' => 'Ecuador',
				'EG' => 'Egypt',
				'SV' => 'El Salvador',
				'GQ' => 'Equatorial Guinea',
				'ER' => 'Eritrea',
				'EE' => 'Estonia',
				'ET' => 'Ethiopia',
				'FK' => 'Falkland Islands (Malvinas)',
				'FO' => 'Faroe Islands',
				'FJ' => 'Fiji',
				'FI' => 'Finland',
				'FR' => 'France',
				'GF' => 'French Guiana',
				'PF' => 'French Polynesia',
				'TF' => 'French Southern Territories',
				'GA' => 'Gabon',
				'GM' => 'Gambia',
				'GE' => 'Georgia',
				'DE' => 'Germany',
				'GH' => 'Ghana',
				'GI' => 'Gibraltar',
				'GR' => 'Greece',
				'GL' => 'Greenland',
				'GD' => 'Grenada',
				'GP' => 'Guadeloupe',
				'GU' => 'Guam',
				'GT' => 'Guatemala',
				'GG' => 'Guernsey',
				'GN' => 'Guinea',
				'GW' => 'Guinea-Bissau',
				'GY' => 'Guyana',
				'HT' => 'Haiti',
				'HM' => 'Heard Island and McDonald Islands',
				'VA' => 'Holy See (Vatican City State)',
				'HN' => 'Honduras',
				'HK' => 'Hong Kong',
				'HU' => 'Hungary',
				'IS' => 'Iceland',
				'IN' => 'India',
				'ID' => 'Indonesia',
				'IR' => 'Iran, Islamic Republic of',
				'IQ' => 'Iraq',
				'IE' => 'Ireland',
				'IM' => 'Isle of Man',
				'IL' => 'Israel',
				'IT' => 'Italy',
				'JM' => 'Jamaica',
				'JP' => 'Japan',
				'JE' => 'Jersey',
				'JO' => 'Jordan',
				'KZ' => 'Kazakhstan',
				'KE' => 'Kenya',
				'KI' => 'Kiribati',
				'KP' => 'Korea, Democratic People\'s Republic of',
				'KR' => 'Korea, Republic of',
				'KW' => 'Kuwait',
				'KG' => 'Kyrgyzstan',
				'LA' => 'Lao People\'s Democratic Republic',
				'LV' => 'Latvia',
				'LB' => 'Lebanon',
				'LS' => 'Lesotho',
				'LR' => 'Liberia',
				'LY' => 'Libya',
				'LI' => 'Liechtenstein',
				'LT' => 'Lithuania',
				'LU' => 'Luxembourg',
				'MO' => 'Macao',
				'MK' => 'Macedonia, the former Yugoslav Republic of',
				'MG' => 'Madagascar',
				'MW' => 'Malawi',
				'MY' => 'Malaysia',
				'MV' => 'Maldives',
				'ML' => 'Mali',
				'MT' => 'Malta',
				'MH' => 'Marshall Islands',
				'MQ' => 'Martinique',
				'MR' => 'Mauritania',
				'MU' => 'Mauritius',
				'YT' => 'Mayotte',
				'MX' => 'Mexico',
				'FM' => 'Micronesia, Federated States of',
				'MD' => 'Moldova, Republic of',
				'MC' => 'Monaco',
				'MN' => 'Mongolia',
				'ME' => 'Montenegro',
				'MS' => 'Montserrat',
				'MA' => 'Morocco',
				'MZ' => 'Mozambique',
				'MM' => 'Myanmar',
				'NA' => 'Namibia',
				'NR' => 'Nauru',
				'NP' => 'Nepal',
				'NL' => 'Netherlands',
				'NC' => 'New Caledonia',
				'NZ' => 'New Zealand',
				'NI' => 'Nicaragua',
				'NE' => 'Niger',
				'NG' => 'Nigeria',
				'NU' => 'Niue',
				'NF' => 'Norfolk Island',
				'MP' => 'Northern Mariana Islands',
				'NO' => 'Norway',
				'OM' => 'Oman',
				'PK' => 'Pakistan',
				'PW' => 'Palau',
				'PS' => 'Palestinian Territory, Occupied',
				'PA' => 'Panama',
				'PG' => 'Papua New Guinea',
				'PY' => 'Paraguay',
				'PE' => 'Peru',
				'PH' => 'Philippines',
				'PN' => 'Pitcairn',
				'PL' => 'Poland',
				'PT' => 'Portugal',
				'PR' => 'Puerto Rico',
				'QA' => 'Qatar',
				'RE' => 'Réunion',
				'RO' => 'Romania',
				'RU' => 'Russian Federation',
				'RW' => 'Rwanda',
				'BL' => 'Saint Barthélemy',
				'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
				'KN' => 'Saint Kitts and Nevis',
				'LC' => 'Saint Lucia',
				'MF' => 'Saint Martin (French part)',
				'PM' => 'Saint Pierre and Miquelon',
				'VC' => 'Saint Vincent and the Grenadines',
				'WS' => 'Samoa',
				'SM' => 'San Marino',
				'ST' => 'Sao Tome and Principe',
				'SA' => 'Saudi Arabia',
				'SN' => 'Senegal',
				'RS' => 'Serbia',
				'SC' => 'Seychelles',
				'SL' => 'Sierra Leone',
				'SG' => 'Singapore',
				'SX' => 'Sint Maarten (Dutch part)',
				'SK' => 'Slovakia',
				'SI' => 'Slovenia',
				'SB' => 'Solomon Islands',
				'SO' => 'Somalia',
				'ZA' => 'South Africa',
				'GS' => 'South Georgia and the South Sandwich Islands',
				'SS' => 'South Sudan',
				'ES' => 'Spain',
				'LK' => 'Sri Lanka',
				'SD' => 'Sudan',
				'SR' => 'Suriname',
				'SJ' => 'Svalbard and Jan Mayen',
				'SZ' => 'Swaziland',
				'SE' => 'Sweden',
				'CH' => 'Switzerland',
				'SY' => 'Syrian Arab Republic',
				'TW' => 'Taiwan, Province of China',
				'TJ' => 'Tajikistan',
				'TZ' => 'Tanzania, United Republic of',
				'TH' => 'Thailand',
				'TL' => 'Timor-Leste',
				'TG' => 'Togo',
				'TK' => 'Tokelau',
				'TO' => 'Tonga',
				'TT' => 'Trinidad and Tobago',
				'TN' => 'Tunisia',
				'TR' => 'Turkey',
				'TM' => 'Turkmenistan',
				'TC' => 'Turks and Caicos Islands',
				'TV' => 'Tuvalu',
				'UG' => 'Uganda',
				'UA' => 'Ukraine',
				'AE' => 'United Arab Emirates',
				'GB' => 'United Kingdom',
				'US' => 'United States',
				'UM' => 'United States Minor Outlying Islands',
				'UY' => 'Uruguay',
				'UZ' => 'Uzbekistan',
				'VU' => 'Vanuatu',
				'VE' => 'Venezuela, Bolivarian Republic of',
				'VN' => 'Viet Nam',
				'VG' => 'Virgin Islands, British',
				'VI' => 'Virgin Islands, U.S.',
				'WF' => 'Wallis and Futuna',
				'EH' => 'Western Sahara',
				'YE' => 'Yemen',
				'ZM' => 'Zambia',
				'ZW' => 'Zimbabwe',

			);
			$new_field = array(
				'type'     => 'select',
				'id'       => $id,
				'std'      => array(),
				'desc'     => '',
				'style'    => '',
				'name'     => 'Select field',
				'multiple' => FALSE,
				'options'  => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Sortable field to Page
		 *
		 *
		 * @since  0.4
		 * @access public
		 *
		 * @param $id       string field id, i.e. the meta key
		 * @param $options  (array)  array of key => value pairs for sortable options  as value => label
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, (array) optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addSortable($id, $options, $args, $repeater = FALSE) {
			$new_field = array(
				'type'     => 'sortable',
				'id'       => $id,
				'std'      => array(),
				'desc'     => '',
				'style'    => '',
				'name'     => 'Select field',
				'multiple' => FALSE,
				'options'  => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Radio field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string field id, i.e. the meta key
		 * @param $options  (array)  array of key => value pairs for radio options
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addRadio($id, $options, $args, $repeater = FALSE) {
			$new_field = array(
				'type'    => 'radio',
				'id'      => $id,
				'std'     => array(),
				'desc'    => '',
				'style'   => '',
				'name'    => 'Radio field',
				'options' => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Date field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 *                  'format' => // date format, default yy-mm-dd. Optional. Default "'d MM, yy'"  See more formats here: http://goo.gl/Wcwxn
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addDate($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'   => 'date',
				'id'     => $id,
				'std'    => '',
				'desc'   => '',
				'format' => 'd MM, yy',
				'name'   => 'Date field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Time field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string- field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 *                  'format' => // time format, default hh:mm. Optional. See more formats here: http://goo.gl/83woX
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addTime($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'   => 'time',
				'id'     => $id,
				'std'    => '',
				'desc'   => '',
				'format' => 'hh:mm',
				'name'   => 'Time field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Color field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addColor($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type' => 'color',
				'id'   => $id,
				'std'  => '',
				'desc' => '',
				'name' => 'ColorPicker field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Image field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addImage($id, $args, $repeater = FALSE) {
			$new_field = array('type' => 'image', 'id' => $id, 'desc' => '', 'name' => 'Image field');
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add WYSIWYG field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'style' =>   // custom style for field, string optional Default 'width: 300px; height: 400px'
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addWysiwyg($id, $args, $repeater = FALSE) {
			$new_field = array(
				'type'  => 'wysiwyg',
				'id'    => $id,
				'std'   => '',
				'desc'  => '',
				'style' => 'width: 300px; height: 400px',
				'name'  => 'WYSIWYG Editor field'
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Taxonomy field to Page
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $options  mixed|array options of taxonomy field
		 *                  'taxonomy' =>    // taxonomy name can be category,post_tag or any custom taxonomy default is category
		 *                  'type' =>  // how to show taxonomy? 'select' (default) or 'checkbox_list'
		 *                  'args' =>  // arguments to query taxonomy, see http://goo.gl/uAANN default ('hide_empty' => false)
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addTaxonomy($id, $options, $args, $repeater = FALSE) {
			$q = array('hide_empty' => 0);
			$tax = 'category';
			$type = 'select';
			$temp = array('taxonomy' => $tax, 'type' => $type, 'args' => $q);
			$options = array_merge($temp, $options);
			$new_field = array(
				'type'    => 'taxonomy',
				'id'      => $id,
				'desc'    => '',
				'name'    => 'Taxonomies',
				'options' => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add Post Type field to Page
		 *
		 * @since  0.3.5
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $options  mixed|array options of taxonomy field
		 *                  'taxonomy' => // taxonomy name can be category,post_tag or any custom taxonomy default is category
		 *                  'type' => // how to show taxonomy? 'select' (default) or 'checkbox_list'
		 *                  'args' => // arguments to query taxonomy, see http://goo.gl/uAANN default ('hide_empty' => false)
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addPostTypes($id, $options, $args, $repeater = FALSE) {

			$temp = array(
				'type'  => 'select', // type of field to display
				'types' => get_post_types(), // post types to include
				'args'  => array()
			);
			$options = array_merge($temp, $options);

			$new_field = array(
				'id'      => $id,
				'type'    => 'posttypes', // tells the framework which show_field_ function to use
				'desc'    => '',
				'name'    => 'Post Types',
				'options' => $options
			);
			$new_field = array_merge($new_field, $args);

			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add WP_Roles field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $options  mixed|array options of taxonomy field
		 *                  'type' =>  // how to show taxonomy? 'select' (default) or 'checkbox_list'
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addRoles($id, $options, $args, $repeater = FALSE) {
			$type = 'select';
			$temp = array('type' => $type);
			$options = array_merge($temp, $options);
			$new_field = array(
				'type'    => 'WProle',
				'id'      => $id,
				'desc'    => '',
				'name'    => 'Select WordPress Role',
				'options' => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add posts field to Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 *
		 * @param $id       string  field id, i.e. the meta key
		 * @param $options  mixed|array options of taxonomy field
		 *                  'post_type' =>    // post type name, 'post' (default) 'page' or any custom post type
		 *                  type' =>  // how to show posts? 'select' (default) or 'checkbox_list'
		 *                  args' =>  // arguments to query posts, see http://goo.gl/is0yK default ('posts_per_page' => -1)
		 * @param $args     mixed|array
		 *                  'name' => // field name/label string optional
		 *                  'desc' => // field description, string optional
		 *                  'std' => // default value, string optional
		 *                  'validation_function' => // validate function, string optional
		 * @param $repeater bool  is this a field inside a repeater? true|false(default)
		 *
		 * @return array
		 */
		public function addPosts($id, $options, $args, $repeater = FALSE) {
			$q = array('posts_per_page' => -1);
			$temp = array('post_type' => 'post', 'type' => 'select', 'args' => $q);
			$options = array_merge($temp, $options);
			$new_field = array(
				'type'    => 'posts',
				'id'      => $id,
				'desc'    => '',
				'name'    => 'Posts field',
				'options' => $options
			);
			$new_field = array_merge($new_field, $args);
			if(FALSE === $repeater) {
				$this->_fields[] = $new_field;
			} else {
				return $new_field;
			}
		}

		/**
		 *  Add repeater field Block to Page
		 *
		 * @author   Ohad Raz
		 * @since    0.1
		 * @access   public
		 *
		 * @param $id   string  field id, i.e. the meta key
		 * @param $args mixed|array
		 *              'name' => // field name/label string optional
		 *              'desc' => // field description, string optional
		 *              'std' => // default value, string optional
		 *              'style' =>   // custom style for field, string optional
		 *              'validation_function' => // validate function, string optional
		 *              'fields' => //fields to repeater
		 *
		 * @modified 0.4 added sortable option
		 */
		public function addRepeaterBlock($id, $args) {
			$new_field = array(
				'type'     => 'repeater',
				'id'       => $id,
				'name'     => 'Reapeater field',
				'fields'   => array(),
				'inline'   => FALSE,
				'sortable' => FALSE
			);
			$new_field = array_merge($new_field, $args);
			$this->_fields[] = $new_field;
		}

		/**
		 * Finish Declaration of Page
		 *
		 *
		 * @since  0.1
		 * @access public
		 */
		public function Finish() {
			$this->add_missed_values();
			$this->check_field_upload();
			$this->check_filed_plupload();
			$this->check_field_color();
			$this->check_field_date();
			$this->check_field_time();
			$this->check_field_code();
		}

		/**
		 * Helper function to check for empty arrays
		 *
		 *
		 * @since    0.1
		 * @access   public
		 *
		 * @param $array
		 *
		 * @internal param array|mixed $args
		 *
		 * @return bool
		 */
		public function is_array_empty($array) {
			if(!is_array($array)) {
				return TRUE;
			}
			foreach($array as $a) {
				if(is_array($a)) {
					foreach($a as $sub_a) {
						if(!empty($sub_a) && $sub_a != '') {
							return FALSE;
						}
					}
				} else {
					if(!empty($a) && $a != '') {
						return FALSE;
					}
				}
			}
			return TRUE;
		}

		/**
		 * Response JSON
		 * Get json date from url and decode.
		 */

		public function json_response($url) {

			// Parse the given url
			$raw = file_get_contents($url, 0, NULL, NULL);
			$decoded = json_decode($raw);

			return $decoded;
		}

		public function get_google_fonts($sort) {

			// Store current font list from set transient
			$font_list = get_transient('rwp_google_fonts_'.$sort);

			// Set the transient from the Developer API if it's empty
			if(FALSE === $font_list)    :

				$gwf_uri = "https://www.googleapis.com/webfonts/v1/webfonts?key=".$this->_google_dev_api_key."&sort=".$sort;
				$gwf_raw = $this->json_response($gwf_uri);

				foreach($gwf_raw->items as $i => $font) {

					$font_list[$i]['font-family'] .= 'font-family: \''.$font->family.'\';';
					$font_list[$i]['font-name'] .= $font->family;
					$font_list[$i]['css-name'] .= urlencode($font->family);
					$font_list[$i]['variants'] .= $font->variants;
					$font_list[$i]['subsets'] .= $font->subsets;
				}

				set_transient('gwf_raw_'.$sort, $font_list, 60 * 60 * 24);

			endif;

			// Return the saved lit of Google Web Fonts
			return $font_list;
		}

		/**
		 * Get the list of available Fonts
		 *
		 *
		 * @since  0.3
		 * @access public
		 *
		 * @param null $font
		 *
		 * @return mixed|array
		 */
		public function get_fonts_family($font = NULL) {
			$fonts = get_option('WP_EX_FONTS_LIST', $default = FALSE);
			//$fonts = FALSE; // debugging
			if($fonts === FALSE) {
				$fonts = array(
					'arial'     => array(
						'name' => 'Arial',
						'css'  => "font-family: Arial, Helvetica, sans-serif;",
					),
					'georgia'   => array(
						'name' => "Georgia",
						'css'  => "font-family: Georgia;",
					),
					'helvetica' => array(
						'name' => "Helvetica",
						'css'  => "font-family: Helvetica, Arial, sans-serif;",
					),
					'tahoma'    => array(
						'name' => "Tahoma, Geneva",
						'css'  => "font-family: Tahoma, Geneva;",
					),
					'times'     => array(
						'name' => "Times New Roman",
						'css'  => "font-family: Times New Roman;",
					),
					'trebuchet' => array(
						'name' => "Trebuchet",
						'css'  => "font-family: Trebuchet, sans-serif;",
					),
					'verdana'   => array(
						'name' => "Verdana, Geneva",
						'css'  => "font-family: Verdana, Geneva;",
					),
				);

				if($this->google_fonts) {
					$fontArray = $this->get_google_fonts('alpha');
					foreach($fontArray as $f) {
						$key = strtolower(str_replace(" ", "_", $f['font-name']));
						$fonts[$key] = array(
							'name'   => $f['font-name'],
							'import' => '@import url(http://fonts.googleapis.com/css?family='.$f['css-name'].');',
							'google' => $f['css-name'],
							'css'    => $f['font-family']
						);
					}
				}
				update_option('WP_EX_FONTS_LIST', $fonts);
			}
			$fonts = apply_filters('WP_EX_available_fonts_family', $fonts);
			if($font === NULL) { // @todo this also seems confusing and poorly handled
				return $fonts;
			} else {
				foreach($fonts as $f => $value) {
					if($f == $font) {
						return $value;
					}
				}
			}
		}

		/**
		 * Get list of font faces
		 *
		 *
		 * @since  0.3
		 * @access public
		 * @return array
		 */
		public function get_font_style() {
			$default = array(
				'normal'   => 'Normal',
				'italic'   => 'Italic',
				'oblique ' => 'Oblique'
			);
			return apply_filters('BF_available_fonts_style', $default);
		}

		/**
		 * Get list of font wieght
		 *
		 *
		 * @since  0.9.9
		 * @access public
		 * @return array
		 */
		public function get_font_weight() {
			$default = array(
				'normal'  => 'Normal',
				'bold'    => 'Bold',
				'bolder'  => 'Bolder',
				'lighter' => 'Lighter',
				'100'     => '100',
				'200'     => '200',
				'300'     => '300',
				'400'     => '400',
				'500'     => '500',
				'600'     => '600',
				'700'     => '700',
				'800'     => '800',
				'900'     => '900',
				'inherit' => 'Inherit'
			);
			return apply_filters('BF_available_fonts_weights', $default);
		}

		/**
		 *  Export Import Functions
		 */
		/**
		 *  Add import export to Page
		 *
		 *
		 * @since  0.8
		 * @access public
		 * @return void
		 */
		public function addImportExport() {
			$new_field = array('type' => 'import_export', 'id' => '', 'value' => '');
			$this->_fields[] = $new_field;
		}

		public function show_import_export() {
			$this->show_field_begin(array('name' => ''), NULL);
			$ret = '
    <div class="apc_ie_panel field">
      <div class="apc_export"><h3>'.__('Export').'</h3>
        <p>'.__('To export your settings click Export below.').'</p>
        <div class="export_code">
          <label for="export_code">'.__('Export Code').'</label><br />
          <textarea id="export_code"></textarea>        
          <input class="button-primary" type="button" value="'.__('Get Export').'" id="apc_export_b" />'.$this->create_export_download_link().'
          <span class="export_status" style="display: none;"><img style="vertical-align: middle;" src="'.$this->SelfPath.'/img/load16x16.gif" alt="loading..."/></span>
          <div class="export_results alert" style="display: none;"></div>
        </div>
      </div>
      <div class="apc_import"><h3>'.__('Import').'</h3>
        <p>'.__('To Import saved settings paste the Export output in to the Import Code box bellow and click Import.').'</p>
        <div class="import_code">
          <label for="import_code">'.__('Import Code').'</label><br />
          <textarea id="import_code"></textarea>
                  <input class="button-primary" type="button"  value="'.__('Import').'" id="apc_import_b" />
          <span class="import_status" style="display: none;"><img style="vertical-align: middle;" src="'.$this->SelfPath.'/img/load16x16.gif" alt="loading..."/></span>
          <div class="import_results alert" style="display: none;"></div>
        </div>
      </div>
      <input type="hidden" id="option_group_name" value="'.$this->option_group.'" />
      <input type="hidden" id="apc_import_nonce" name="apc_Import" value="'.wp_create_nonce("apc_import").'" />
      <input type="hidden" id="apc_export_nonce" name="apc_export" value="'.wp_create_nonce("apc_export").'" />
    ';
			echo apply_filters('apc_import_export_panel', $ret);
			$this->show_field_end(array('name' => '', 'desc' => ''), NULL);
		}

		/**
		 * Ajax export
		 *
		 *
		 * @since  0.8
		 * @access public
		 * @return json object
		 */
		public function export() {
			check_ajax_referer('apc_export', 'seq');
			if(!isset($_GET['group'])) {
				$re['err'] = __('error in ajax request! (1)');
				$re['nonce'] = wp_create_nonce("apc_export");
				echo json_encode($re);
				die();
			}
			$options = get_option($this->option_group, FALSE);
			if($options !== FALSE) {
				$re['code'] = "<!*!* START export Code !*!*>\n".base64_encode(serialize($options))."\n<!*!* END export Code !*!*>";
			} else {
				$re['err'] = __('error in ajax request! Maybe you haven\'t saved any options yet? (2)');
			}
			//update_nonce
			$re['nonce'] = wp_create_nonce("apc_export");
			echo json_encode($re);
			die();
		}

		/**
		 * Ajax import
		 *
		 *
		 * @since  0.8
		 * @access public
		 * @return json object
		 */
		public function import() {
			check_ajax_referer('apc_import', 'seq');
			if(!isset($_POST['imp'])) {
				$re['err'] = __('error in ajax request! (3)');
				$re['nonce'] = wp_create_nonce("apc_import");
				echo json_encode($re);
				die();
			}
			$import_code = $_POST['imp'];
			$import_code = str_replace("<!*!* START export Code !*!*>\n", "", $import_code);
			$import_code = str_replace("\n<!*!* END export Code !*!*>", "", $import_code);
			$import_code = base64_decode($import_code);
			$import_code = unserialize($import_code);
			if(is_array($import_code)) {
				update_option($this->option_group, $import_code);
				$re['success'] = __('Setting imported, make sure you ').'<input class="button-secondary" type="button"  value="'.__('Refresh this page').'" id="apc_refresh_page_b" />';
			} else {
				$re['err'] = __('Could not import settings! (4)');
			}
			//update_nonce
			$re['nonce'] = wp_create_nonce("apc_import");
			echo json_encode($re);
			die();
		}

		//then define the function that will take care of the actual download
		public function download_file($content = NULL, $file_name = NULL) {
			if(!wp_verify_nonce($_REQUEST['nonce'], 'theme_export_options')) {
				wp_die('Security check');
			}
			//here you get the options to export and set it as content, ex:
			$options = get_option($_REQUEST['option_group']);
			$content = "<!*!* START export Code !*!*>\n".base64_encode(serialize($options))."\n<!*!* END export Code !*!*>";
			$file_name = 'exported_settings_'.date('d-m-y').'.txt';
			header('HTTP/1.1 200 OK');
			if(!current_user_can('edit_theme_options')) {
				wp_die('<p>'.__('You do not have sufficient permissions to edit subscribr for this site.').'</p>');
			}
			if($content === NULL || $file_name === NULL) {
				wp_die('<p>'.__('Error Downloading file.').'</p>');
			}
			$fsize = strlen($content);
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header('Content-Description: File Transfer');
			header("Content-Disposition: attachment; filename=".$file_name);
			header("Content-Length: ".$fsize);
			header("Expires: 0");
			header("Pragma: public");
			echo $content;
			exit;
		}

		public function create_export_download_link($echo = FALSE) {
			$site_url = get_bloginfo('url');
			$args = array(
				'theme_export_options' => 'safe_download',
				'nonce'                => wp_create_nonce('theme_export_options'),
				'option_group'         => $this->option_group
			);
			$export_url = add_query_arg($args, $site_url);
			if($echo === TRUE) {
				echo '<a href="'.$export_url.'" target="_blank">Download Export</a>';
			} elseif($echo == 'url') {
				return $export_url;
			}
			return '<a class="button-primary" href="'.$export_url.'" target="_blank">Download Export</a>';
		}

		//first  add a new query var
		public function add_query_var_vars() {
			global $wp;
			$wp->add_query_var('theme_export_options');
		}

		//then add a template redirect which looks for that query var and if found calls the download function
		public function admin_redirect_download_files() {
			global $wp;
			global $wp_query;
			//download theme export
			if(array_key_exists('theme_export_options', $wp->query_vars) && $wp->query_vars['theme_export_options'] == 'safe_download' && $this->option_group == $_REQUEST['option_group']) {
				$this->download_file();
				die();
			}
		}

		public function Handle_plupload_action() {
			// check ajax nonce
			$imgid = $_POST["imgid"];
			check_ajax_referer($imgid.'pluploadan');
			// handle file upload
			$status = wp_handle_upload(
				$_FILES[$imgid.'async-upload'],
				array(
					'test_form' => TRUE,
					'action'    => 'plupload_action'
				)
			);
			// send the uploaded file url in response
			echo $status['url'];
			exit;
		}

		/**
		 *
		 * Retrieve an option from the options array.
		 *
		 * @param null $name
		 *
		 * @return string
		 */
		public function get_option($name = NULL) {
			if(empty($name)) {
				return $this->error(array('msg' => 'Option name is NULL', 'echo' => FALSE, 'die' => FALSE));
			}

			$options = get_option($this->option_group);
			if($options) {
				return $options[$name];
			} else {
				return $this->error(array('msg' => 'get_option returned FALSE', 'echo' => FALSE, 'die' => FALSE));
			}
		}

		/**
		 * option
		 *
		 * Echoes a given option from the options array.
		 *
		 * @param string $name
		 */
		public function option($name = NULL) {
			echo $this->get_option($name);
		}

		/**
		 * error
		 *
		 * Provides an additional mechanism for outputting errors to the browser or the JavaScript console.
		 *
		 * @param $args
		 *
		 * @return string
		 *
		 * @todo build off of WP_Error or PHP Exception?
		 */
		public function error($args) {
			if(!is_array($args)) {
				echo('Fatal error: '.$this.'::error must be passed an array.');
				return NULL;
			}
			$defaults = array(
				'msg'  => 'An unspecified error occurred',
				'echo' => TRUE,
				'die'  => TRUE
			);
			$args = wp_parse_args($args, $defaults);
			extract($args, EXTR_SKIP);

			$debug = debug_backtrace();

			$str = '';

			if($echo) {
				$str .= '<div id="message" class="error"><p><strong>';
			}
			$str .= $this."->error['".$debug[1]["function"]."']";
			if($echo) {
				$str .= '</strong>';
			}

			$str .= ": ".$msg." in ".$debug[1]["file"]." on line ".$debug[1]["line"];
			if($echo) {
				$str .= '</p></div>';
			}

			if($echo) {

				if($die) {
					die($str);
				} else {
					echo($str);
				}
			} else {
				?>
				<script type="text/javascript">console.log("<?php echo $str; ?>");</script><?php
				return $str;
			}
		}

		/**
		 * Returns the class name and version.
		 *
		 * @return string
		 */
		public function __toString() {
			return get_class($this).' '.$this->version;
		}
	}
endif;

if(!class_exists('Subscribr_Mindshare_Validator')) :

	class Subscribr_Mindshare_Validator {

		/**
		 * @param $field
		 *
		 * @param $new_value
		 *
		 * @return array|mixed|null
		 */
		public static function validate($field, $new_value) {
			if(!is_array($field)) {
				// no field was passed, return
				return NULL;
			} else {
				// check if the user passes in a custom validation method, also check if it exists
				if(isset($field['validation_function'])) {
					if(method_exists(__CLASS__, $field['validation_function'])) {
						return call_user_func(array(__CLASS__, $field['validation_function']), $field, $new_value);
					} elseif(function_exists($field['validation_function'])) {
						return call_user_func($field['validation_function'], $field, $new_value);
					}
				} else {
					// otherwise use the default method validate::text_field
					return self::sanitize_by_id($field, $new_value);
				}
			}
		}

		/**
		 * Sanitize and validate user input based on field id
		 *
		 * sanitize_by_id() checks the field id for the suffixes:
		 * '__url' or '__uri' : validates URLs
		 * '__txt' : validates text fields and text areas
		 * '__email' : validates email addresses
		 * '__slug' : validates input as a "slug", removing special characters, spaces, etc.
		 *
		 * @param $field array field array
		 *
		 * @param $new_value
		 *
		 * @return string sanitized field value
		 */
		private static function sanitize_by_id($field, $new_value) {

			if(stristr($field['id'], '__txt')) {
				$new_value = self::txt($new_value);
			} elseif(stristr($field['id'], '__uri') || stristr($field['id'], '__url')) {
				$new_value = self::uri($new_value);
			} elseif(stristr($field['id'], '__email')) {
				$new_value = self::email($new_value);
			} elseif(stristr($field['id'], '__slug')) {
				$new_value = self::slug($new_value);
			}

			return $new_value;
		}

		public static function txt($new_value) {
			return sanitize_text_field($new_value);
		}

		public static function uri($new_value) {
			return esc_url($new_value);
		}

		public static function email($new_value) {
			return sanitize_email($new_value);
		}

		public static function slug($new_value) {
			return sanitize_title($new_value);
		}
	}

endif;
