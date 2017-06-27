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
 * This file is used to remove all exercises from a category/lesson.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_login($course, true, $cm);
global $DB;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID.
if ($id) {
    $course     = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}
$context = context_course::instance($id);

if (isset($_GET['r'])) {
    $exerciseid = $_GET['r'];
    $DB->delete_records('mootyper_exercises', array('id' => $exerciseid));
} else {
    $lessonid = $_GET['l'];
    $DB->delete_records('mootyper_exercises', array('lesson' => $lessonid));
    $DB->delete_records('mootyper_lessons', array('id' => $lessonid));
}

// Trigger module exercise_removed event.
$event = \mod_mootyper\event\exercise_removed::create(array(
    'objectid' => $course->id,
    'context' => $context
));
$event->trigger();

$cid = $_GET['id'];
$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$cid;
header('Location: '.$webdir);

