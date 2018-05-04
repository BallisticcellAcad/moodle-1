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
 * This file adds grade and performance info to mdl_mootyper_grades after an exercise.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

global $DB;

require_login(0, true, null, false);
if (optional_param('rpAccInput', '', PARAM_FLOAT) >= optional_param('rpGoal', '', PARAM_FLOAT)) {
    $passfield = 1;
} else {
    $passfield = 0;
}

$record = new stdClass();
$record->mootyper = optional_param('rpSityperId', '', PARAM_INT);
$record->userid = optional_param('rpUser', '', PARAM_INT);
// Gradebook entry has not been implemented, 10/10/17.
$record->grade = 0;
$record->mistakes = optional_param('rpMistakesInput', '', PARAM_INT);
$record->timeinseconds = optional_param('rpTimeInput', '', PARAM_INT);
$record->hitsperminute = optional_param('rpSpeedInput', '', PARAM_FLOAT);
$record->fullhits = optional_param('rpFullHits', '', PARAM_INT);
$record->precisionfield = optional_param('rpAccInput', '', PARAM_FLOAT);
$record->timetaken = time();
$record->exercise = optional_param('rpExercise', '', PARAM_INT);
$record->pass = $passfield;
$record->attemptid = optional_param('rpAttId', '', PARAM_INT);
// Modification needed to prevent negative WPM entries for exercises.
$record->wpm = (max(0, (($record->hitsperminute / 5) - $record->mistakes)));

$DB->insert_record('mootyper_grades', $record, false);

$webdir = $CFG->wwwroot . '/mod/mootyper/view.php?n='.$record->mootyper;
echo '<script type="text/javascript">window.location="'.$webdir.'";</script>';
