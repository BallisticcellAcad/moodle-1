<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Partial - Pre Sidebar
 *
 * @package   theme_remui
 * @copyright Copyright (c) 2016 WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// get and set sidebar state
user_preference_allow_ajax_update("presidebar_state", PARAM_TEXT);
$presidebar_open = json_decode(get_user_preferences("presidebar_state", 1));

// get main color scheme
$sidebar = "dark-sidebar";
if (empty($search)) {
    $search = '';
}
$colorscheme = \theme_remui\toolbox::get_setting('colorscheme');
// Get course renederer
$courserenderer = $PAGE->get_renderer('core', 'course');
if (!empty($colorscheme) && strpos($colorscheme, 'light') !== false) {
    $sidebar = "light-sidebar";
}
?>
<aside class="main-sidebar moodle-has-zindex <?php echo $sidebar; ?>">
  
      <!-- sidebar: style can be found in sidebar.less -->
       <section class="sidebar">
        
        <!-- search form -->
<?php
       echo $courserenderer->course_search_form($search);
?>
        <!-- moodle side-pre block -->
        <?php
          echo $OUTPUT->blocks('side-pre', array(), 'div');
        ?>
      </section> 
      <!-- /.sidebar -->
      <!-- </section> -->
</aside>