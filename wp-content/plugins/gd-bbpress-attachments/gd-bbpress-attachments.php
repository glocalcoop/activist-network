<?php

/*
Plugin Name: GD bbPress Attachments
Plugin URI: http://www.dev4press.com/plugin/gd-bbpress-attachments/
Description: Implements attachments upload to the topics and replies in bbPress plugin through media library and adds additional forum based controls.
Version: 2.3.1
Author: Milan Petrovic
Author URI: http://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2015 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('GDBBPRESSATTACHMENTS_CAP')) {
    define('GDBBPRESSATTACHMENTS_CAP', 'activate_plugins');
}

require_once(dirname(__FILE__).'/code/defaults.php');
require_once(dirname(__FILE__).'/code/shared.php');
require_once(dirname(__FILE__).'/code/attachments/class.php');
require_once(dirname(__FILE__).'/code/public.php');

?>