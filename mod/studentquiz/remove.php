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
 * Ajax requests to this script removes comment. Only teacher or higher roles can remove comments.
 *
 * @package    mod_studentquiz
 * @copyright  2017 HSR (http://www.hsr.ch)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

// Get parameters.
$cmid = required_param('cmid', PARAM_INT);
$commentid = required_param('id', PARAM_INT);

// Load course and course module requested.
if ($cmid) {
    if (!$module = get_coursemodule_from_id('studentquiz', $cmid)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $module->course))) {
        print_error('coursemisconf');
    }
} else {
    print_error('invalidcoursemodule');
}

// Authentication check.
require_login($module->course, false, $module);
require_sesskey();

header('Content-Type: text/html; charset=utf-8');

$comment = $DB->get_record('studentquiz_comment', array('id' => $commentid));
// TODO strange return 401?
if (mod_studentquiz_check_created_permission($cmid)) {
    // In this case an manager has deleted the comment.
    mod_studentquiz_notify_comment_deleted($comment, $course, $module);
    $success = $DB->delete_records('studentquiz_comment', array('id' => $commentid));
    if (!$success) {
        return http_response_code(401);
    }
} else {
    // TODO: we could add here the same notification command, but it would here delete his own comment, just made in a strange way.
    $success = $DB->delete_records('studentquiz_comment', array('id' => $commentid, 'userid' => $USER->id));
    if (!$success) {
        return http_response_code(401);
    }
}