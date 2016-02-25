<?php

    /*
    Plugin Name: Polylang Theme Strings
    Plugin URI: http://modeewine.com/en-polylang-theme-strings
    Description: Automatic scanning of strings translation in the theme and registration of them in Polylang plugin.
    Version: 2.2
    Author: Modeewine
    Author URI: http://modeewine.com
    License: GPL2
    */

    new MW_Polylang_Theme_Strings();

    class MW_Polylang_Theme_Strings
    {
        static $prefix = 'mw_polylang_strings_';
        static $pll_f = 'pll_register_string';
        private $paths;

        function __construct()
        {
            $this->Init();
        }

        public function Install()
        {
            if (!version_compare(phpversion(), '5', '>='))
            {
                echo 'Your PHP version (' . phpversion() . ') is incompatible with the plug-in code.';
                echo '<br />';
                echo 'The minimum supported PHP version is 5.0.';
                exit;
            }
            else
            {
                self::Themes_PLL_Strings_Scan();
            }
        }

        public function Uninstall()
        {
            global $wpdb;

            $wpdb->query("DELETE FROM `" . $wpdb->prefix . "options` WHERE `option_name` LIKE '" . self::$prefix . "%'");
        }

        public function Init()
        {
            $this->Paths_Init();
            $this->Plugin_Install_Hooks_Init();

            add_action('init', array($this, 'Plugin_Hooks_Init'));
        }

        private function Paths_Init()
        {
            $theme = realpath(get_template_directory());
            $theme_dir_name = preg_split("/[\/\\\]/uis", $theme);
            $theme_dir_name = (string)$theme_dir_name[count($theme_dir_name) - 1];

            $this->paths = Array(
                'plugin_file_index' => __FILE__,
                'themes'            => WP_CONTENT_DIR . get_theme_roots(),
                'theme'             => $theme,
                'theme_dir_name'    => $theme_dir_name,
                'theme_name'        => wp_get_theme()->Name
            );
        }

        private function Plugin_Install_Hooks_Init()
        {
            register_activation_hook($this->Path_Get('plugin_file_index'), array('MW_Polylang_Theme_Strings', 'Install'));
            register_uninstall_hook($this->Path_Get('plugin_file_index'), array('MW_Polylang_Theme_Strings', 'Uninstall'));
        }

        public function Plugin_Hooks_Init()
        {
            if (!is_admin() && function_exists(self::$pll_f))
            {
                $this->Theme_Current_PLL_Strings_Init();
            }
            else
            if (self::Is_PLL_Strings_Settings_Page())
            {
                $this->Themes_PLL_Strings_Scan();
                $this->Themes_PLL_Strings_Init();
            }
        }

        public function Path_Get($key)
        {
            if (isset($this->paths[$key]))
            {
                return $this->paths[$key];
            }
        }

        static function Files_Recursive_Get($dir)
        {
            $files = array();

            if ($h = opendir($dir))
            {
                while (($item = readdir($h)) !== false)
                {
                    $f = $dir . '/' . $item;

                    if (is_file($f))
                    {
                        $files[] = $f;
                    }
                    else
                    if (is_dir($f) && !preg_match("/^[\.]{1,2}$/uis", $item))
                    {
                        $files = array_merge($files, self::Files_Recursive_Get($f));
                    }
                }

                closedir($h);
            }

            return $files;
        }

        static function Is_PLL_Strings_Settings_Page()
        {
            if
            (
                is_admin() &&
                function_exists(self::$pll_f) &&
                (isset($_REQUEST['page']) && $_REQUEST['page'] == 'mlang') &&
                (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'strings')
            )
            {
                return true;
            }
        }

        private function Themes_PLL_Strings_Scan()
        {
            $themes = wp_get_themes();

            if (count($themes))
            {
                foreach ($themes as $theme_dir_name => $theme)
                {
                    $data = array(
                        'name'    => $theme->Name,
                        'strings' => array()
                    );

                    $theme_path = $theme->theme_root . '/' . $theme_dir_name;

                    if (file_exists($theme_path))
                    {
                        $files = self::Files_Recursive_Get($theme_path);

                        foreach($files as $v)
                        {
                            if (preg_match("/\/.*?\.(php|inc)$/uis", $v))
                            {
                                preg_match_all("/\<\?.*?\?\>/uis", file_get_contents($v), $p);

                                if (count($p[0]))
                                {
                                    foreach ($p[0] as $pv)
                                    {
                                        preg_match_all("/pll_[_e][\s]*\([\s]*[\'\"](.*?)[\'\"][\s]*\)/uis", $pv, $m);

                                        if (count($m[0]))
                                        {
                                            foreach ($m[1] as $mv)
                                            {
                                                if (!in_array($mv, $data))
                                                {
                                                    $data['strings'][] = $mv;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        update_option(self::$prefix . $theme_dir_name . '_data', $data);
                    }
                }
            }
        }

        public function Theme_Current_PLL_Strings_Init()
        {
            $data = get_option(self::$prefix . $this->Path_Get('theme_dir_name') . '_data');

            if (is_array($data) && is_array($data['strings']) && count($data['strings']))
            {
                foreach ($data['strings'] as $v)
                {
                    pll_register_string($v, $v, __('Theme') . ': ' . $data['name']);
                }
            }
        }

        public function Themes_PLL_Strings_Init()
        {
            $themes = wp_get_themes();

            if (count($themes))
            {
                foreach ($themes as $theme_dir_name => $theme)
                {
                    $data = get_option(self::$prefix . $theme_dir_name . '_data');

                    if (is_array($data) && is_array($data['strings']) && count($data['strings']))
                    {
                        foreach ($data['strings'] as $v)
                        {
                            pll_register_string($v, $v, __('Theme') . ': ' . $data['name']);
                        }
                    }
                }
            }
        }
    }
