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
 * Course overview block
 *
 * @package    block_course_overview_gl
 */
require_once($CFG->dirroot.'/blocks/course_overview_gl/locallib.php');

/**
 * Course overview block
 *
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_course_overview_gl extends block_base {
    /**
     * If this is passed as mynumber then showallcourses, irrespective of limit by user.
     */
    const SHOW_ALL_COURSES = -2;

    /**
     * Block initialization
     */
    public function init() {
        $this->title   = get_string('pluginnamecustom', 'block_course_overview_gl');
    }

    /**
     * Return contents of course_overview_gl block
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        global $USER, $CFG, $DB;
        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/blocks/course_overview_gl/renderer.php'); 
        if($this->content !== NULL) {
            return $this->content;
        }

        $config = get_config('block_course_overview_gl');

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $content = array();

        $updatemynumber = optional_param('mynumber', -1, PARAM_INT);
        if ($updatemynumber >= 0 && optional_param('sesskey', '', PARAM_RAW) && confirm_sesskey()) {
            block_course_overview_gl_update_mynumber($updatemynumber);
        }

        profile_load_custom_fields($USER);

        $showallcourses = ($updatemynumber === self::SHOW_ALL_COURSES);
        list($sortedcourses, $sitecourses, $totalcourses) = block_course_overview_gl_get_sorted_courses($showallcourses);
        $overviews = block_course_overview_gl_get_overviews($sitecourses);
        //$renderer = $this->page->get_renderer('block_course_overview_gl');
        $renderer = new block_course_overview_gl_renderer($this->page);
        if (!empty($config->showwelcomearea)) {
            require_once($CFG->dirroot.'/message/lib.php');
            $msgcount = message_count_unread_messages();
            $this->content->text = $renderer->welcome_area($msgcount);
        }

        // Number of sites to display.
        if ($this->page->user_is_editing() && empty($config->forcedefaultmaxcourses)) {
            $this->content->text .= $renderer->editing_bar_head($totalcourses);
        }

        if (empty($sortedcourses) && $totalcourses == 0) {
            $this->content = NULL;
	    return NULL;
        } else {
            // For each course, build category cache.
            $this->content->text .= $renderer->course_overview_gl($sortedcourses, $overviews);
            $this->content->text .= $renderer->hidden_courses($totalcourses - count($sortedcourses));
        }
        
        //assign needed class to the main block, handled by our theme
        $jquerySrcipt = "<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>";
        $asignClass = $jquerySrcipt . "<script>$(document).ready(function() { $( \".block_course_overview_gl\").addClass(\"block_course_overview block_course_overview_gl\") });</script>";
        $this->content->text .= $asignClass;

        return $this->content;
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('my' => true);
    }

    /**
     * Sets block header to be hidden or visible
     *
     * @return bool if true then header will be visible.
     */
    public function hide_header() {
        // Hide header if welcome area is show.
        $config = get_config('block_course_overview_gl');
        return !empty($config->showwelcomearea);
    }
}
