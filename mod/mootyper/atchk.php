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
 * This file checks results and for suspicious performance.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $DB;

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_login($course, true, $cm);
$record = new stdClass();
$st = $_GET['status'];
if ($st == 1) {

    $record->mootyperid = $_GET['mootyperid'];
    $record->userid = $_GET['userid'];
    $record->timetaken = $_GET['time'];
    $record->inprogress = 1;
    $record->suspicion = 0;
    $newid = $DB->insert_record('mootyper_attempts', $record, true);
    echo $newid;
} else if ($st == 2) {
    $record->attemptid = $_GET['attemptid'];
    $record->mistakes = $_GET['mistakes'];
    $record->hits = $_GET['hits'];
    $record->checktime = time();
    $DB->insert_record('mootyper_checks', $record, false);
} else if ($st == 3) {
    $attid = optional_param('attemptid', 0, PARAM_INT);
    $attemptold = $DB->get_record('mootyper_attempts', array('id' => $attid), '*', MUST_EXIST);
    $attemptnew = new stdClass();
    $attemptnew->id = $attemptold->id;
    $attemptnew->mootyperid = $attemptold->mootyperid;
    $attemptnew->userid = $attemptold->userid;
    $attemptnew->timetaken = $attemptold->timetaken;
    $attemptnew->inprogress = 0;
    $dbchcks = $DB->get_records('mootyper_checks', array('attemptid' => $attemptold->id));
    $checks = array();
    foreach ($dbchcks as $c) {
        $checks[] = array('id' => $c->id, 'mistakes' => $c->mistakes, 'hits' => $c->hits, 'checktime' => $c->checktime);
    }
    if (suspicion($checks, $attemptold->timetaken)) {
        $attemptnew->suspicion = 1;
    } else {
        $attemptnew->suspicion = $attemptold->suspicion;
    }
    $DB->update_record('mootyper_attempts', $attemptnew);
    $DB->delete_records('mootyper_checks', array('attemptid' => $attid));
}
