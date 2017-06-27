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
 * This file replaces the legacy STATEMENTS section in:
 *
 * db/install.xml,
 * lib.php/modulename_install()
 * post installation hook and partially defaults.php
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Post installation procedure.
 *
 * @see upgrade_plugins_modules().
 */
function xmldb_mootyper_install() {
    require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
    global $CFG, $USER;
    require_login(0, true, null, false);
    $pth = $CFG->dirroot."/mod/mootyper/lessons";
    $res = scandir($pth);
    for ($i = 0; $i < count($res); $i++) {
        if (is_file($pth."/".$res[$i])) {
            $fl = $res[$i]; // Argument list daFile, authorid_arg, visible_arg, editable_arg, course_arg.
            read_lessons_file($fl, $USER->id, 0, 2);
        }
    }
    $pth2 = $CFG->dirroot."/mod/mootyper/layouts";
    $res2 = scandir($pth2);
    for ($j = 0; $j < count($res2); $j++) {
        if (is_file($pth2."/".$res2[$j]) && ( substr($res2[$j], (strripos($res2[$j], '.') + 1) ) == 'php')) {
            $fl2 = $res2[$j];
            add_keyboard_layout($fl2);
        }
    }
}

/**
 * Install keyboard layouts into the database.
 *
 * @param string $dafile
 */
function add_keyboard_layout($dafile) {
    require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
    global $DB, $CFG;
    $thefile = $CFG->dirroot."/mod/mootyper/layouts/".$dafile;
    $wwwfile = $CFG->wwwroot."/mod/mootyper/layouts/".$dafile;
    $record = new stdClass();
    $periodpos = strrpos($dafile, '.');
    $layoutname = substr($dafile, 0, $periodpos);
    $record->filepath = $thefile;
    $record->name = $layoutname;
    $record->jspath = substr($wwwfile, 0, strripos($wwwfile, '.')).'.js';
    $DB->insert_record('mootyper_layouts', $record, true);
}

/**
 * Read lesson file and add into the database.
 *
 * @param string $dafile
 * @param int $authoridarg
 * @param int $visiblearg
 * @param int $editablearg
 * @param int $coursearg
 */
function read_lessons_file($dafile, $authoridarg, $visiblearg, $editablearg, $coursearg=null) {
    require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
    global $DB, $CFG;
    $thefile = $CFG->dirroot."/mod/mootyper/lessons/".$dafile;
    // Echo thefile.
    $record = new stdClass();
    $periodpos = strrpos($dafile, '.');
    $lessonname = substr($dafile, 0, $periodpos);
    // Echo lessonname.
    $record->lessonname = $lessonname;
    $record->authorid = $authoridarg;
    $record->visible = $visiblearg;
    $record->editable = $editablearg;
    if (!is_null($coursearg)) {
        $record->courseid = $coursearg;
    }
    $lessonid = $DB->insert_record('mootyper_lessons', $record, true);
    $fh = fopen($thefile, 'r');
    $thedata = fread($fh, filesize($thefile));
    fclose($fh);
    $haha = "";
    for ($i = 0; $i < strlen($thedata); $i++) {
        $haha .= $thedata[$i];
    }
    $haha = trim($haha);
    $splitted = explode ('/**/' , $haha);
    for ($j = 0; $j < count($splitted); $j++) {
        $vaja = trim($splitted[$j]);
        $allowed = array('\\', '~', '!', '@', '#', '$', '%', '^', '&', '(', ')', '*', '_',
        '+', ':', ';', '"', '{', '}', '>', '<', '?', '\'', '-', '/', '=', '.', ',', ' ',
        '|', '¡', '`', 'ç', 'ñ', 'º', '¿', 'ª', '·', '\n', '\r', '\r\n', '\n\r', ']', '[', '¬', '´', '`');
        $nm = "".($j + 1);
        $texttotype = "";
        for ($k = 0; $k < strlen($vaja); $k++) {
            // TODO
            // * If it is not a letter
            // * and if it is not a number
            // * compare against $allowed array.
            // * Iif not included die
            // * or something.
            $ch = $vaja[$k];
            if ($ch == "\n") {
                $texttotype .= '\n';
            } else {
                $texttotype .= $ch;
            }
        }
        $erecord = new stdClass();
        $erecord->texttotype = $texttotype;
        $erecord->exercisename = $nm;
        $erecord->lesson = $lessonid;
        $erecord->snumber = $j + 1;
        $DB->insert_record('mootyper_exercises', $erecord, false);
    }
}

/**
 * Post installation recovery procedure.
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_mootyper_install_recovery() {
}
