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
 * course_overview_gl block rendrer
 *
 * @package    block_course_overview_gl
 */
defined('MOODLE_INTERNAL') || die;

/**
 * Course_overview block rendrer
 *
 * @copyright  2012 Adam Olley <adam.olley@netspot.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_course_overview_gl_renderer extends plugin_renderer_base {

    
    public static function course_completion_progress($course, $userid = null) {
        global $USER;
        if (!isloggedin() || isguestuser()) {
            return null; // Can't get completion progress for users who aren't logged in.
        }
        if (!$userid){
            $userid = $USER->id;
        } else {
            $userid = $userid;
        }
        // Security check - are they enrolled on course.
        $context = \context_course::instance($course->id);
        if (!is_siteadmin() && !is_enrolled($context, null, '', true)) {
            return null;
        }

        // return if not student
        $roles = get_user_roles($context, $userid, false);
        $keys = array_keys($roles);
        if ($roles && ($roles[$keys[0]]->roleid != 5)) {
          return null;  
        }

        $completioninfo = new \completion_info($course);
        $trackcount = 0;
        $compcount = 0;

            if ($completioninfo->is_enabled()) {
            $modinfo = get_fast_modinfo($course);
            foreach ($modinfo->cms as $thismod) {
                if (!is_siteadmin() && !$thismod->uservisible) {
                    // Skip when mod is not user visible.
                    continue;
                }

                $completioninfo->get_data($thismod, true, $userid);

               // echo $completioninfo->userid;
                if ($completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $trackcount++;
                    $completiondata = $completioninfo->get_data($thismod, true, $userid);
                /*    echo '<pre>';
                    print_r($completiondata);
                    echo '<pre>';*/
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                        $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $compcount++;
                    }
                }
            }
        }

        $compobj = (object) array('complete' => $compcount, 'total' => $trackcount, 'progresshtml' => '');
        if ($trackcount > 0) {
                $progress = get_string('progresstotal', 'completion', $compobj);
             // TODO - we should be putting our HTML in a renderer.
                $progresspercent = ceil(($compcount / $trackcount) * 100);
             /* $progressinfo = '<div class="completionstatus outoftotal">'.$progress.'<span class="pull-right">'.$progresspercent.'%</span></div>
             <div class="completion-line" style="width:'.$progresspercent.'%"></div>
             '; */
            $progressinfo = '<div id="course-info">'.$progress.'</div><div class="progress" style="display:inline-block; width:100%">
                <div class="progress-bar active" role="progressbar" aria-valuenow='.$progresspercent.' aria-valuemin="0" aria-valuemax="100" style="width:'.$progresspercent.'%">
                    '.$progresspercent.'%<span class="sr-only">'.$progresspercent.'% Complete</span>
                </div>
             </div>';
            $compobj->progresshtml = $progressinfo;
        }
        return $compobj;
    }
    
    /**
     * Construct contents of course_overview_gl block
     *
     * @param array $courses list of courses in sorted order
     * @param array $overviews list of course overviews
     * @return string html to be displayed in course_overview_gl block
     */
    public function course_overview_gl($courses, $overviews) {
        $html = '';
        $config = get_config('block_course_overview_gl');
        if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_NONE) {
            global $CFG;
            require_once($CFG->libdir.'/coursecatlib.php');
        }
        $ismovingcourse = false;
        $courseordernumber = 0;
        $maxcourses = count($courses);
        $userediting = false;
        // Intialise string/icon etc if user is editing and courses > 1
        if ($this->page->user_is_editing() && (count($courses) > 1)) {
            $userediting = true;
            $this->page->requires->js_init_call('M.block_course_overview_gl.add_handles');

            // Check if course is moving
            $ismovingcourse = optional_param('movecourse', FALSE, PARAM_BOOL);
            $movingcourseid = optional_param('courseid', 0, PARAM_INT);
        }

        // Render first movehere icon.
        if ($ismovingcourse) {
            // Remove movecourse param from url.
            $this->page->ensure_param_not_in_url('movecourse');

            // Show moving course notice, so user knows what is being moved.
            $html .= $this->output->box_start('notice');
            $a = new stdClass();
            $a->fullname = $courses[$movingcourseid]->fullname;
            $a->cancellink = html_writer::link($this->page->url, get_string('cancel'));
            $html .= get_string('movingcourse', 'block_course_overview_gl', $a);
            $html .= $this->output->box_end();

            $moveurl = new moodle_url('/blocks/course_overview_gl/move.php',
                        array('sesskey' => sesskey(), 'moveto' => 0, 'courseid' => $movingcourseid));
            // Create move icon, so it can be used.
            $movetofirsticon = html_writer::empty_tag('img',
                    array('src' => $this->output->pix_url('movehere'),
                        'alt' => get_string('movetofirst', 'block_course_overview_gl', $courses[$movingcourseid]->fullname),
                        'title' => get_string('movehere')));
            $moveurl = html_writer::link($moveurl, $movetofirsticon);
            $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
        }

        foreach ($courses as $key => $course) {
            $course_image_src = '';
            $courseurl = '';
            $progress = self::course_completion_progress($course);
            // If moving course, then don't show course which needs to be moved.
            if ($ismovingcourse && ($course->id == $movingcourseid)) {
                continue;
            }
            
            if($course->id > 0) {
                    // Create a course_in_list object to use the get_course_overviewfiles() method.
                global $CFG;
                require_once($CFG->libdir . '/coursecatlib.php');
                $course_list = new course_in_list($course);

                foreach ($course_list->get_course_overviewfiles() as $file) {
                    if ($file->is_valid_image()) {
                        $imagepath = '/' . $file->get_contextid() .
                                '/' . $file->get_component() .
                                '/' . $file->get_filearea() .
                                $file->get_filepath() .
                                $file->get_filename();
                        $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath,
                                false);

                        $course_image_src = $imageurl;
                        break;
                    }
                }
            }
            
            

            $html .= $this->output->box_start('coursebox', "course-{$course->id}");
            $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
            $html .= html_writer::start_tag('a', array('href' => $courseurl));
            if(strlen($course_image_src) == 0) {
                $course_image_src = '/blocks/course_overview_gl/static_files/nopic.jpg';
            }
            $html .= html_writer::start_tag('div', array('class' => 'course-image',
                                'style' => 'border-radius:14px 14px 0 0;min-height:300px;background-position:center;background-repeat:no-repeat;background-size:cover; background-image:url("'.$course_image_src.'"'));
            
            
            $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('a');
            $html .= html_writer::start_tag('div', array('class' => 'course_title', 'style' => 'border-radius:0 0 14px 14px;'));
            if(isset($progress)) {
                $html .= '<div class="progress-section">'.$progress->progresshtml.'</div>';
            }         
            
            
            // If user is editing, then add move icons.
            if ($userediting && !$ismovingcourse) {
                $moveicon = html_writer::empty_tag('img',
                        array('src' => $this->pix_url('t/move')->out(false),
                            'alt' => get_string('movecourse', 'block_course_overview_gl', $course->fullname),
                            'title' => get_string('move')));
                $moveurl = new moodle_url($this->page->url, array('sesskey' => sesskey(), 'movecourse' => 1, 'courseid' => $course->id));
                $moveurl = html_writer::link($moveurl, $moveicon);
                $html .= html_writer::tag('div', $moveurl, array('class' => 'move'));

            }

            // No need to pass title through s() here as it will be done automatically by html_writer.
            $attributes = array('title' => $course->fullname);
            if ($course->id > 0) {
                if (empty($course->visible)) {
                    $attributes['class'] = 'dimmed';
                }
                $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
                $coursefullname = format_string(get_course_display_name_for_list($course), true, $course->id);
                $link = html_writer::link($courseurl, $coursefullname, $attributes);
                $html .= $this->output->heading($link, 2, 'title');
            } else {
                $html .= $this->output->heading(html_writer::link(
                    new moodle_url('/auth/mnet/jump.php', array('hostid' => $course->hostid, 'wantsurl' => '/course/view.php?id='.$course->remoteid)),
                    format_string($course->shortname, true), $attributes) . ' (' . format_string($course->hostname) . ')', 2, 'title');
            }
            $html .= $this->output->box('', 'flush');
            
            if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_NONE) {
                // List category parent or categories path here.
                $currentcategory = coursecat::get($course->category, IGNORE_MISSING);
                if ($currentcategory !== null) {
                    $html .= html_writer::start_tag('div', array('class' => 'categorypath'));
                    if ($config->showcategories == BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_FULL_PATH) {
                        foreach ($currentcategory->get_parents() as $categoryid) {
                            $category = coursecat::get($categoryid, IGNORE_MISSING);
                            if ($category !== null) {
                                $html .= $category->get_formatted_name().' / ';
                            }
                        }
                    }
                    $html .= $currentcategory->get_formatted_name();
                    $html .= html_writer::end_tag('div');
                }
            }
            
            $html .= html_writer::end_tag('div');

            if (!empty($config->showchildren) && ($course->id > 0)) {
                // List children here.
                if ($children = block_course_overview_gl_get_child_shortnames($course->id)) {
                    $html .= html_writer::tag('span', $children, array('class' => 'coursechildren'));
                }
            }

            // If user is moving courses, then down't show overview.
            if (isset($overviews[$course->id]) && !$ismovingcourse) {
                $html .= $this->activity_display($course->id, $overviews[$course->id]);
            }

//            if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_NONE) {
//                // List category parent or categories path here.
//                $currentcategory = coursecat::get($course->category, IGNORE_MISSING);
//                if ($currentcategory !== null) {
//                    $html .= html_writer::start_tag('div', array('class' => 'categorypath'));
//                    if ($config->showcategories == BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_FULL_PATH) {
//                        foreach ($currentcategory->get_parents() as $categoryid) {
//                            $category = coursecat::get($categoryid, IGNORE_MISSING);
//                            if ($category !== null) {
//                                $html .= $category->get_formatted_name().' / ';
//                            }
//                        }
//                    }
//                    $html .= $currentcategory->get_formatted_name();
//                    $html .= html_writer::end_tag('div');
//                }
//            }
            
//            $html .= html_writer::end_tag('div');
//            $html .= html_writer::end_tag('a');

            $html .= $this->output->box('', 'flush');
            $html .= $this->output->box_end();
            $courseordernumber++;
            if ($ismovingcourse) {
                $moveurl = new moodle_url('/blocks/course_overview_gl/move.php',
                            array('sesskey' => sesskey(), 'moveto' => $courseordernumber, 'courseid' => $movingcourseid));
                $a = new stdClass();
                $a->movingcoursename = $courses[$movingcourseid]->fullname;
                $a->currentcoursename = $course->fullname;
                $movehereicon = html_writer::empty_tag('img',
                        array('src' => $this->output->pix_url('movehere'),
                            'alt' => get_string('moveafterhere', 'block_course_overview_gl', $a),
                            'title' => get_string('movehere')));
                $moveurl = html_writer::link($moveurl, $movehereicon);
                $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
            }
        }
        // Wrap course list in a div and return.
        return html_writer::tag('div', $html, array('class' => 'course_list'));
    }

    /**
     * Coustuct activities overview for a course
     *
     * @param int $cid course id
     * @param array $overview overview of activities in course
     * @return string html of activities overview
     */
    protected function activity_display($cid, $overview) {
        $output = html_writer::start_tag('div', array('class' => 'activity_info'));
        foreach (array_keys($overview) as $module) {
            $output .= html_writer::start_tag('div', array('class' => 'activity_overview'));
            $url = new moodle_url("/mod/$module/index.php", array('id' => $cid));
            $modulename = get_string('modulename', $module);
            $icontext = html_writer::link($url, $this->output->pix_icon('icon', $modulename, 'mod_'.$module, array('class'=>'iconlarge')));
            if (get_string_manager()->string_exists("activityoverview", $module)) {
                $icontext .= get_string("activityoverview", $module);
            } else {
                $icontext .= get_string("activityoverview", 'block_course_overview_gl', $modulename);
            }

            // Add collapsible region with overview text in it.
            $output .= $this->collapsible_region($overview[$module], '', 'region_'.$cid.'_'.$module, $icontext, '', true);

            $output .= html_writer::end_tag('div');
        }
        $output .= html_writer::end_tag('div');
        return $output;
    }

    /**
     * Constructs header in editing mode
     *
     * @param int $max maximum number of courses
     * @return string html of header bar.
     */
    public function editing_bar_head($max = 0) {
        $output = $this->output->box_start('notice');

        $options = array('0' => get_string('alwaysshowall', 'block_course_overview_gl'));
        for ($i = 1; $i <= $max; $i++) {
            $options[$i] = $i;
        }
        $url = new moodle_url('/my/index.php', ['sesskey' => sesskey()]);
        $select = new single_select($url, 'mynumber', $options, block_course_overview_gl_get_max_user_courses(), array());
        $select->set_label(get_string('numtodisplay', 'block_course_overview_gl'));
        $output .= $this->output->render($select);

        $output .= $this->output->box_end();
        return $output;
    }

    /**
     * Show hidden courses count
     *
     * @param int $total count of hidden courses
     * @return string html
     */
    public function hidden_courses($total) {
        if ($total <= 0) {
            return;
        }
        $output = $this->output->box_start('notice');
        $plural = $total > 1 ? 'plural' : '';
        $config = get_config('block_course_overview_gl');
        // Show view all course link to user if forcedefaultmaxcourses is not empty.
        if (!empty($config->forcedefaultmaxcourses)) {
            $output .= get_string('hiddencoursecount'.$plural, 'block_course_overview_gl', $total);
        } else {
            $a = new stdClass();
            $a->coursecount = $total;
            $a->showalllink = html_writer::link(new moodle_url('/my/index.php', array('mynumber' => block_course_overview_gl::SHOW_ALL_COURSES)),
                    get_string('showallcourses'));
            $output .= get_string('hiddencoursecountwithshowall'.$plural, 'block_course_overview_gl', $a);
        }

        $output .= $this->output->box_end();
        return $output;
    }

    /**
     * Creates collapsable region
     *
     * @param string $contents existing contents
     * @param string $classes class names added to the div that is output.
     * @param string $id id added to the div that is output. Must not be blank.
     * @param string $caption text displayed at the top. Clicking on this will cause the region to expand or contract.
     * @param string $userpref the name of the user preference that stores the user's preferred default state.
     *      (May be blank if you do not wish the state to be persisted.
     * @param bool $default Initial collapsed state to use if the user_preference it not set.
     * @return bool if true, return the HTML as a string, rather than printing it.
     */
    protected function collapsible_region($contents, $classes, $id, $caption, $userpref = '', $default = false) {
            $output  = $this->collapsible_region_start($classes, $id, $caption, $userpref, $default);
            $output .= $contents;
            $output .= $this->collapsible_region_end();

            return $output;
        }

    /**
     * Print (or return) the start of a collapsible region, that has a caption that can
     * be clicked to expand or collapse the region. If JavaScript is off, then the region
     * will always be expanded.
     *
     * @param string $classes class names added to the div that is output.
     * @param string $id id added to the div that is output. Must not be blank.
     * @param string $caption text displayed at the top. Clicking on this will cause the region to expand or contract.
     * @param string $userpref the name of the user preference that stores the user's preferred default state.
     *      (May be blank if you do not wish the state to be persisted.
     * @param bool $default Initial collapsed state to use if the user_preference it not set.
     * @return bool if true, return the HTML as a string, rather than printing it.
     */
    protected function collapsible_region_start($classes, $id, $caption, $userpref = '', $default = false) {
        // Work out the initial state.
        if (!empty($userpref) and is_string($userpref)) {
            user_preference_allow_ajax_update($userpref, PARAM_BOOL);
            $collapsed = get_user_preferences($userpref, $default);
        } else {
            $collapsed = $default;
            $userpref = false;
        }

        if ($collapsed) {
            $classes .= ' collapsed';
        }

        $output = '';
        $output .= '<div id="' . $id . '" class="collapsibleregion ' . $classes . '">';
        $output .= '<div id="' . $id . '_sizer">';
        $output .= '<div id="' . $id . '_caption" class="collapsibleregioncaption">';
        $output .= $caption . ' ';
        $output .= '</div><div id="' . $id . '_inner" class="collapsibleregioninner">';
        $this->page->requires->js_init_call('M.block_course_overview_gl.collapsible', array($id, $userpref, get_string('clicktohideshow')));

        return $output;
    }

    /**
     * Close a region started with print_collapsible_region_start.
     *
     * @return string return the HTML as a string, rather than printing it.
     */
    protected function collapsible_region_end() {
        $output = '</div></div></div>';
        return $output;
    }

    /**
     * Cretes html for welcome area
     *
     * @param int $msgcount number of messages
     * @return string html string for welcome area.
     */
    public function welcome_area($msgcount) {
        global $CFG, $USER;
        $output = $this->output->box_start('welcome_area');

        $picture = $this->output->user_picture($USER, array('size' => 75, 'class' => 'welcome_userpicture'));
        $output .= html_writer::tag('div', $picture, array('class' => 'profilepicture'));

        $output .= $this->output->box_start('welcome_message');
        $output .= $this->output->heading(get_string('welcome', 'block_course_overview_gl', $USER->firstname));

        if (!empty($CFG->messaging)) {
            $plural = 's';
            if ($msgcount > 0) {
                $output .= get_string('youhavemessages', 'block_course_overview_gl', $msgcount);
                if ($msgcount == 1) {
                    $plural = '';
                }
            } else {
                $output .= get_string('youhavenomessages', 'block_course_overview_gl');
            }
            $output .= html_writer::link(new moodle_url('/message/index.php'),
                    get_string('message'.$plural, 'block_course_overview_gl'));
        }
        $output .= $this->output->box_end();
        $output .= $this->output->box('', 'flush');
        $output .= $this->output->box_end();

        return $output;
    }
}
