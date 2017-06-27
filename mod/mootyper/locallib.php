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
 * Internal library of functions for module mootyper
 *
 * All the mootyper specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Check to see if this MooTyper is available for use.
 * @param int $mootyper
 */
function is_available($mootyper) {
    $timeopen = $mootyper->timeopen;
    $timeclose = $mootyper->timeclose;
    return (($timeopen == 0 || time() >= $timeopen) && ($timeclose == 0 || time() < $timeclose));
}

/**
 * Get a list of MooTyper keyboards.
 * @return array
 */
function get_keyboard_layouts_db() {
    global $DB;
    $lss = array();
    if ($layouts = $DB->get_records('mootyper_layouts')) {
        foreach ($layouts as $ex) {
            $lss[$ex->id] = $ex->name;
        }
    }
    return $lss;
}

/**
 * Get the MooTyper keyboard definition to use.
 * @param int $lid
 */
function get_instance_layout_file($lid) {
    global $DB;
    $dbrec = $DB->get_record('mootyper_layouts', array('id' => $lid));
    return $dbrec->filepath;
}

/**
 * Get the MooTyper keyboard keystroke checker to use.
 * @param int $lid
 */
function get_instance_layout_js_file($lid) {
    global $DB;
    $dbrec = $DB->get_record('mootyper_layouts', array('id' => $lid));
    return $dbrec->jspath;
}

/**
 * Get the last keystroke and check if correct.
 * @param int $mid
 */
function get_last_check($mid) {
    global $USER, $DB, $CFG;
    $sql = "SELECT * FROM ".$CFG->prefix."mootyper_checks".
           " JOIN ".$CFG->prefix."mootyper_attempts ON "
                   .$CFG->prefix."mootyper_attempts.id = "
                   .$CFG->prefix."mootyper_checks.attemptid".
           " WHERE ".$CFG->prefix."mootyper_attempts.mootyperid = "
                    .$mid." AND "
                    .$CFG->prefix."mootyper_attempts.userid = "
                    .$USER->id.
           " AND ".$CFG->prefix."mootyper_attempts.inprogress = 1".
           " ORDER BY ".$CFG->prefix."mootyper_checks.checktime DESC LIMIT 1";
    if ($rec = $DB->get_record_sql($sql, array())) {
        return $rec;
    } else {
        return null;
    }
}

/**
 * Check for suspicous results.
 * @param int $checks
 * @param int $starttime
 * @return boolean
 */
function suspicion($checks, $starttime) {
    for ($i = 1; $i < count($checks); $i++) {
        $udarci1 = $checks[$i]['mistakes'] + $checks[$i]['hits'];
        $udarci2 = $checks[($i - 1)]['mistakes'] + $checks[($i - 1)]['hits'];
        if ($udarci2 > ($udarci1 + 60)) {
            return true;
        }
        if ($checks[($i - 1)]['checktime'] > ($starttime + 300)) {
            return true;
        }
    }
    return false;
}

/** 3/22/16 Changed call from mod_setup so this is no longer used.
 * Keeping it here until I'm sure of the change.
 *
 * Get the current lesson.
 *
 * @return string
 */
function get_typerlessons() {
    global $CFG, $DB;
    $params = array();
    $lstoreturn = array();
    $sql = "SELECT id, lessonname
              FROM ".$CFG->prefix."mootyper_lessons
              ORDER BY id";
    if ($lessons = $DB->get_records_sql($sql, $params)) {
        foreach ($lessons as $ex) {
            $lss = array();
            $lss['id'] = $ex->id;
            $lss['lessonname'] = $ex->lessonname;
            $lstoreturn[] = $lss;
        }
    }
    return $lstoreturn;
}

/** Improved get_typerlessons() function.
 * Modified 3/22/16 to improve reliability of correctly listing edit/remove capability.
 *
 * If correct user and in a course, get list of lessons.
 * @param int $u
 * @param int $c
 * @return string
 */
function get_mootyperlessons($u, $c) {
    global $CFG, $DB;
    $params = array();
    $lstoreturn = array();           // DETERMINE IF USER IS INSIDE A COURSE???
    $sql = "SELECT id, lessonname
              FROM ".$CFG->prefix."mootyper_lessons
              WHERE ((visible = 2 AND authorid = ".$u.") OR
                    (visible = 1 AND editable <= 2 AND courseid = ".$c.") OR
                    (visible = 0) OR
                    (".can_view_edit_all($u, $c)."))
              ORDER BY id";
    /*
    /// This was taken out, because we have some context_module::instance confusion
      OR
                    (visible = 0)) OR ".can_view_edit_all($u, $c).")
    /// ... 3/22/16 Was added again due to changes for 2.7.1 release.

    */
    if ($lessons = $DB->get_records_sql($sql, $params)) {
        foreach ($lessons as $ex) {
            $lss = array();
            $lss['id'] = $ex->id;
            $lss['lessonname'] = $ex->lessonname;
            $lstoreturn[] = $lss;
        }
    }
    return $lstoreturn;
}

/**
 * Check if admin or other user.
 * 22 Mar 16 Changed so that ONLY someone who is a site admin can modify sample lessons.
 * Old method allowed everyone to modify everything.
 * @param int $usr
 * @param int $c
 * @return boolean
 */
function can_view_edit_all($usr, $c) {
    if (is_siteadmin($usr)) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Check if current user can edit.
 * @param int $usr
 * @param int $lsn
 * @return boolean
 */
function is_editable_by_me($usr, $lsn) {
    global $DB;
    $lesson = $DB->get_record('mootyper_lessons', array('id' => $lsn));
    if (is_null($lesson->courseid)) {
        $crs = 0;
    } else {
        $crs = $lesson->courseid;
    }
    if ((($lesson->editable == 0) ||
       ($lesson->editable == 1 && is_user_enrolled($usr, $crs)) ||
       ($lesson->editable == 2 && $lesson->authorid == $usr))
       || can_view_edit_all($usr, $crs)) {
        return true;
    } else {
        return false;
    }
}

/** 3/22/16 Modified Where clause. Previously, it was comparing a
 * course number to modifierid which was never going to match
 * except in the very rare case of being in course 2 in all of my Moodles.
 *
 * Check to see if user is enrolled in current course.
 * @param int $usr
 * @param int $crs
 * @return string
 */
function is_user_enrolled($usr, $crs) {
    global $DB, $CFG;
    $sql2 = "SELECT * FROM ".$CFG->prefix."user_enrolments
             WHERE userid = ".$usr;
    $enrolls = $DB->get_records_sql($sql2, array());
    $rt = count($enrolls) > 0 ? 1 : 0;
    return $rt;
}

/**
 * Calculate averages.
 * @param int $grades
 * @return string
 */
function get_grades_avg($grades) {
    $avg = array();
    $avg['mistakes'] = 0;
    $avg['timeinseconds'] = 0;
    $avg['hitsperminute'] = 0;
    $avg['fullhits'] = 0;
    $avg['precisionfield'] = 0;
    foreach ($grades as $g) {
        $avg['mistakes'] += $g->mistakes;
        $avg['timeinseconds'] += $g->timeinseconds;
        $avg['hitsperminute'] += $g->hitsperminute;
        $avg['fullhits'] += $g->fullhits;
        $avg['precisionfield'] += $g->precisionfield;
    }
    $c = count($grades);
    $avg['mistakes'] = $avg['mistakes'] / $c;
    $avg['timeinseconds'] = $avg['timeinseconds'] / $c;
    $avg['hitsperminute'] = $avg['hitsperminute'] / $c;
    $avg['fullhits'] = $avg['fullhits'] / $c;
    $avg['precisionfield'] = $avg['precisionfield'] / $c;

    $avg['mistakes'] = round($avg['mistakes'], 0);
    $avg['timeinseconds'] = round($avg['timeinseconds'], 0);
    $avg['hitsperminute'] = round($avg['hitsperminute'], 2);
    $avg['fullhits'] = round($avg['fullhits'], 0);
    $avg['precisionfield'] = round($avg['precisionfield'], 2);
    return $avg;
}

/**
 * Get current exercise name for current lesson.
 *
 * @return string
 */
function get_typerexercises() {
    global $USER, $CFG, $DB;
    $params = array();
    $exestoreturn = array();
    $sql = "SELECT id, exercisename
              FROM ".$CFG->prefix."mootyper_exercises";
    if ($exercises = $DB->get_records_sql($sql, $params)) {
        foreach ($exercises as $ex) {
            $exestoreturn[$ex->id] = $ex->exercisename;
        }
    }
    return $exestoreturn;
}

/**
 * Get current exercise for current lesson.
 *
 * @param int $less
 * @return string
 */
function get_exercises_by_lesson($less) {
    global $USER, $CFG, $DB;
    $params = array();
    $toreturn = array();
    $sql = "SELECT * FROM ".$CFG->prefix."mootyper_exercises WHERE lesson=".$less;
    if ($exercises = $DB->get_records_sql($sql, $params)) {
        foreach ($exercises as $ex) {
            $exestoreturn = array();
            $exestoreturn['id'] = $ex->id;
            $exestoreturn['exercisename'] = $ex->exercisename;
            $exestoreturn['snumber'] = $ex->snumber;
            $toreturn[] = $exestoreturn;
        }
    }
    return $toreturn;
}

/**
 * Get keystroke count for this lesson.
 *
 * @param int $lsnid
 * @return int
 */
function get_new_snumber($lsnid) {
    $exes = get_exercises_by_lesson($lsnid);
    if (count($exes) == 0) {
        return 1;
    }
    $max = $exes[0]['snumber'];
    for ($i = 0; $i < count($exes); $i++) {
        if ($exes[$i]['snumber'] > $max) {
            $max = $exes[$i]['snumber'];
        }
    }
    return $max + 1;
}

/**
 * Get info for this lesson.
 *
 * @param int $lsn
 * @return array
 */
function get_typerexercisesfull($lsn = 0) {
    global $USER, $CFG, $DB;
    $params = array();
    $toreturn = array();
    $sql = "SELECT * FROM ".$CFG->prefix."mootyper_exercises WHERE lesson=".$lsn." OR 0=".$lsn;
    if ($exercises = $DB->get_records_sql($sql, $params)) {
        foreach ($exercises as $ex) {
            $exestoreturn = array();
            $exestoreturn['id'] = $ex->id;
            $exestoreturn['exercisename'] = $ex->exercisename;
            $exestoreturn['texttotype'] = $ex->texttotype;
            $exestoreturn['snumber'] = $ex->snumber;
            $toreturn[] = $exestoreturn;
        }
    }
    return $toreturn;
}

