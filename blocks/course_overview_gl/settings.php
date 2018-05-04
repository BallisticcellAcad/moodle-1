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
 * course_overview_gl block settings
 *
 * @package    block_course_overview_gl
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('block_course_overview_gl/defaultmaxcourses', new lang_string('defaultmaxcourses', 'block_course_overview_gl'),
        new lang_string('defaultmaxcoursesdesc', 'block_course_overview_gl'), 10, PARAM_INT));
    $settings->add(new admin_setting_configcheckbox('block_course_overview_gl/forcedefaultmaxcourses', new lang_string('forcedefaultmaxcourses', 'block_course_overview_gl'),
        new lang_string('forcedefaultmaxcoursesdesc', 'block_course_overview_gl'), 1, PARAM_INT));
    $settings->add(new admin_setting_configcheckbox('block_course_overview_gl/showchildren', new lang_string('showchildren', 'block_course_overview_gl'),
        new lang_string('showchildrendesc', 'block_course_overview_gl'), 1, PARAM_INT));
    $settings->add(new admin_setting_configcheckbox('block_course_overview_gl/showwelcomearea', new lang_string('showwelcomearea', 'block_course_overview_gl'),
        new lang_string('showwelcomeareadesc', 'block_course_overview_gl'), 1, PARAM_INT));
    $showcategories = array(
        BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_NONE => new lang_string('none', 'block_course_overview_gl'),
        BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_ONLY_PARENT_NAME => new lang_string('onlyparentname', 'block_course_overview_gl'),
        BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_FULL_PATH => new lang_string('fullpath', 'block_course_overview_gl')
    );
    $settings->add(new admin_setting_configselect('block_course_overview_gl/showcategories', new lang_string('showcategories', 'block_course_overview_gl'),
        new lang_string('showcategoriesdesc', 'block_course_overview_gl'), BLOCKS_COURSE_OVERVIEW_GL_SHOWCATEGORIES_NONE, $showcategories));
}
