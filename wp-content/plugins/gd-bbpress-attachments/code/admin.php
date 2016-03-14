<?php

if (!defined('ABSPATH')) exit;

require_once(GDBBPRESSATTACHMENTS_PATH.'code/attachments/admin.php');

class gdbbA_Admin {
    private $page_ids = array();
    private $admin_plugin = false;

    function __construct() {
        add_action('after_setup_theme', array($this, 'load'));
    }

    public function admin_init() {
        if (isset($_GET['page'])) {
            $this->admin_plugin = $_GET['page'] == 'gdbbpress_attachments';
        }

        if ($this->admin_plugin) {
            wp_enqueue_style('gd-bbpress-attachments', GDBBPRESSATTACHMENTS_URL."css/gd-bbpress-attachments_admin.css", array(), GDBBPRESSATTACHMENTS_VERSION);
        }

        if (isset($_GET['proupgradebba']) && $_GET['proupgradebba'] == 'hide') {
            global $gdbbpress_attachments;

            $gdbbpress_attachments->o['upgrade_to_pro_230'] = 0;

            update_option('gd-bbpress-attachments', $gdbbpress_attachments->o);

            wp_redirect(remove_query_arg('proupgradebba'));
            exit;
        }
    }

    public function load() {
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));

        add_filter('plugin_action_links', array(&$this, 'plugin_actions'), 10, 2);
        add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
    }

    function upgrade_notice() {
        global $gdbbpress_attachments;

        if ($gdbbpress_attachments->o['upgrade_to_pro_230'] == 1) {
            $no_thanks = add_query_arg('proupgradebba', 'hide');

            echo '<div class="updated d4p-updated">';
                echo __("Thank you for using this plugin. Please, take a few minutes and check out the GD bbPress Toolbox Pro plugin with many new and improved features.", "gd-bbpress-attachments");
                echo '<br/>'.__("Buy GD bbPress Toolbox Pro version or Dev4Press Plugins Pack and get 15% discount using this coupon", "gd-bbpress-attachments");
                echo ': <strong style="color: #c00;">GDBBPTOPRO</strong><br/>';
                echo '<strong><a href="http://www.gdbbpbox.com/" target="_blank">'.__("Official Website", "gd-bbpress-attachments")."</a></strong> | ";
                echo '<strong><a href="http://d4p.me/247" target="_blank">'.__("Dev4Press Plugins Pack", "gd-bbpress-attachments")."</a></strong> | ";
                echo '<a href="'.$no_thanks.'">'.__("Don't display this message anymore", "gd-bbpress-attachments")."</a>.";
            echo '</div>';
        }
    }

    public function admin_menu() {
        $this->page_ids[] = add_submenu_page('edit.php?post_type=forum', 'GD bbPress Attachments', __("Attachments", "gd-bbpress-attachments"), GDBBPRESSATTACHMENTS_CAP, 'gdbbpress_attachments', array(&$this, 'menu_attachments'));

        $this->admin_load_hooks();
    }

    public function admin_load_hooks() {
        if (GDBBPRESSATTACHMENTS_WPV < 33) return;

        foreach ($this->page_ids as $id) {
            add_action('load-'.$id, array(&$this, 'load_admin_page'));
        }
    }

    public function plugin_actions($links, $file) {
        if ($file == 'gd-bbpress-attachments/gd-bbpress-attachments.php' ){
            $settings_link = '<a href="edit.php?post_type=forum&page=gdbbpress_attachments">'.__("Settings", "gd-bbpress-attachments").'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    function plugin_links($links, $file) {
        if ($file == 'gd-bbpress-attachments/gd-bbpress-attachments.php' ){
            $links[] = '<a href="edit.php?post_type=forum&page=gdbbpress_attachments&tab=faq">'.__("FAQ", "gd-bbpress-attachments").'</a>';
            $links[] = '<a target="_blank" style="color: #cc0000; font-weight: bold;" href="http://www.gdbbpbox.com/">'.__("Upgrade to GD bbPress Toolbox Pro", "gd-bbpress-attachments").'</a>';
        }

        return $links;
    }

    public function load_admin_page() {
        $screen = get_current_screen();

        $screen->set_help_sidebar('
            <p><strong>Dev4Press:</strong></p>
            <p><a target="_blank" href="http://www.dev4press.com/">'.__("Website", "gd-bbpress-attachments").'</a></p>
            <p><a target="_blank" href="http://twitter.com/milangd">'.__("On Twitter", "gd-bbpress-attachments").'</a></p>
            <p><a target="_blank" href="http://facebook.com/dev4press">'.__("On Facebook", "gd-bbpress-attachments").'</a></p>');

        $screen->add_help_tab(array(
            "id" => "gdpt-screenhelp-help",
            "title" => __("Get Help", "gd-bbpress-attachments"),
            "content" => '<h5>'.__("General Plugin Information", "gd-bbpress-attachments").'</h5>
                <p><a href="http://www.dev4press.com/plugins/gd-bbpress-attachments/" target="_blank">'.__("Home Page on Dev4Press.com", "gd-bbpress-attachments").'</a> | 
                <a href="http://wordpress.org/extend/plugins/gd-bbpress-attachments/" target="_blank">'.__("Home Page on WordPress.org", "gd-bbpress-attachments").'</a></p> 
                <h5>'.__("Getting Plugin Support", "gd-bbpress-attachments").'</h5>
                <p><a href="http://support.dev4press.com/forums/forum/plugins-free/gd-bbpress-attachments/" target="_blank">'.__("Support Forum on Dev4Press.com", "gd-bbpress-attachments").'</a> | 
                <a href="http://wordpress.org/support/plugin/gd-bbpress-attachments" target="_blank">'.__("Support Forum on WordPress.org", "gd-bbpress-attachments").'</a> | 
                <a href="http://www.dev4press.com/plugins/gd-bbpress-attachments/support/" target="_blank">'.__("Plugin Support Sources", "gd-bbpress-attachments").'</a></p>'));

        $screen->add_help_tab(array(
            "id" => "gdpt-screenhelp-website",
            "title" => "Dev4Press", "sfc",
            "content" => '<p>'.__("On Dev4Press website you can find many useful plugins, themes and tutorials, all for WordPress. Please, take a few minutes to browse some of these resources, you might find some of them very useful.", "gd-bbpress-attachments").'</p>
                <p><a href="http://www.dev4press.com/plugins/" target="_blank"><strong>'.__("Plugins", "gd-bbpress-attachments").'</strong></a> - '.__("We have more than 10 plugins available, some of them are commercial and some are available for free.", "gd-bbpress-attachments").'</p>
                <p><a href="http://www.dev4press.com/themes/" target="_blank"><strong>'.__("Themes", "gd-bbpress-attachments").'</strong></a> - '.__("All our themes are based on our own xScape Theme Framework, and only available as premium.", "gd-bbpress-attachments").'</p>
                <p><a href="http://www.dev4press.com/category/tutorials/" target="_blank"><strong>'.__("Tutorials", "gd-bbpress-attachments").'</strong></a> - '.__("Premium and free tutorials for our plugins themes, and many general and practical WordPress tutorials.", "gd-bbpress-attachments").'</p>
                <p><a href="http://www.dev4press.com/documentation/" target="_blank"><strong>'.__("Central Documentation", "gd-bbpress-attachments").'</strong></a> - '.__("Growing collection of functions, classes, hooks, constants with examples for our plugins and themes.", "gd-bbpress-attachments").'</p>
                <p><a href="http://www.dev4press.com/forums/" target="_blank"><strong>'.__("Support Forums", "gd-bbpress-attachments").'</strong></a> - '.__("Premium support forum for all with valid licenses to get help. Also, report bugs and leave suggestions.", "gd-bbpress-attachments").'</p>'));
    }

    public function menu_attachments() {
        global $gdbbpress_attachments;

        $options = $gdbbpress_attachments->o;
        $_user_roles = d4p_bbpress_get_user_roles();

        include(GDBBPRESSATTACHMENTS_PATH.'forms/panels.php');
    }
}

global $gdbbpress_a_admin;
$gdbbpress_a_admin = new gdbbA_Admin();

?>