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
 * This file handles mootyper exercises.
 *
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID.

if ($id) {
    $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true);
$context = context_course::instance($id);

// Trigger module exercise_viewed event.
$event = \mod_mootyper\event\course_exercises_viewed::create(array(
    'objectid' => $course->id,
    'context' => $context
));
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/mootyper/exercises.php', array('id' => $course->id));
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();
require_once(dirname(__FILE__).'/locallib.php');

$lessonpo = optional_param('lesson', 0, PARAM_INT);
$jlnk2 = $CFG->wwwroot . '/mod/mootyper/eins.php?id='.$id;
echo '<a href="'.$jlnk2.'">'.get_string('eaddnew', 'mootyper').'</a><br><br>';

$lessons = get_mootyperlessons($USER->id, $id);

if ($lessonpo == 0 && count($lessons) > 0) {
    $lessonpo = $lessons[0]['id'];
}

echo '<form method="post">';
echo get_string('excategory', 'mootyper').': <select onchange="this.form.submit()" name="lesson">';
$selectedlessonindex = 0;
for ($ij = 0; $ij < count($lessons); $ij++) {
    if ($lessons[$ij]['id'] == $lessonpo) {
        echo '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        $selectedlessonindex = $ij;
    } else {
        echo '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    }
}
echo '</select>';
// Preload not editable by me message for current user.
$jlink = get_string('noteditablebyme', 'mootyper');
if (is_editable_by_me($USER->id, $lessonpo)) {
    // Add a remove all from lesson link.
    echo '<br>';
    echo ' <a onclick="return confirm(\''.get_string('removelsnconfirm', 'mootyper').$lessons[$selectedlessonindex]['lessonname'].
    '\')" href="erem.php?id='.$course->id.'&l='.$lessons[$selectedlessonindex]['id'].'">'.
    get_string('removeall', 'mootyper').'\''.$lessons[$selectedlessonindex]['lessonname'].'\'</a>';
    echo '<br>';
    // Add a export lesson link next to the remove all link.
    echo ' <a onclick="return confirm(\''.get_string('exportconfirm', 'mootyper').$lessons[$selectedlessonindex]['lessonname'].
    '\')" href="lsnexport.php?id='.$course->id.'&lsn='.$lessons[$selectedlessonindex]['id'].'">'.
    get_string('export', 'mootyper').'\''.$lessons[$selectedlessonindex]['lessonname'].'\'</a>';


}
echo '</form><br>';

// Create border and alignment styles for use as needed.
$style1 = 'style="border-color: #000000; border-style: solid; border-width: 3px; text-align: center;"';
$style2 = 'style="border-color: #000000; border-style: solid; border-width: 3px; text-align: left;"';

// Create a link with course id and lsn options to export the current Lesson.
$jlink = '<a onclick="return confirm(\''.get_string('exportconfirm', 'mootyper')
         .$lessons[$selectedlessonindex]['lessonname'].'\')" href="lsnexport.php?id='
         .$course->id.'&lsn='.$lessons[$selectedlessonindex]['id']
         .'"><img src="pix/download_all.svg" alt='
         .get_string('export', 'mootyper').'> '
         .$lessons[$selectedlessonindex]['lessonname'].'';

// Print header row for Lesson table currently being viewed.
echo '<table><tr><td '.$style1.'>'.get_string('ename', 'mootyper').'</td>
                 <td '.$style1.'>'.$lessons[$selectedlessonindex]['lessonname'].'</td>
                 <td '.$style1.'>'.$jlink.'</td></tr>';

// Print table row for each of the exercises in the lesson currently being viewed.
$exercises = get_typerexercisesfull($lessonpo);
foreach ($exercises as $ex) {
    $strtocut = $ex['texttotype'];
    $strtocut = str_replace('\n', '<br>', $strtocut);
    if (strlen($strtocut) > 65) {
        $strtocut = substr($strtocut, 0, 65).'...';
    }
    // If user can edit, create an edit link to the current exerise.
    $jlink1 = '<a onclick="return confirm(\''.get_string('removeexconfirm', 'mootyper')
              .$lessons[$selectedlessonindex]['lessonname']
              .'\')" href="erem.php?id='.$course->id.'&r='
              .$ex['id'].'"><img src="pix/delete.png" alt="'
              .get_string('eremove', 'mootyper').'"></a>';

    // If user can edit, create a remove link to the current exerise.
    $jlink2 = '<a href="eedit.php?id='.$course->id.'&ex='.$ex['id']
              .'"><img src="pix/edit.png" alt='
              .get_string('eeditlabel', 'mootyper').'></a>';

    echo '<tr><td '.$style1.'>'.$ex['exercisename'].'</td><td '.$style2.'>'.$strtocut.'</td>';
    if (is_editable_by_me($USER->id, $lessonpo)) {
        echo '<td '.$style1.'>'.$jlink2.' | '.$jlink1.'</td>';
    } else {
        echo '<td '.$style2.'></td>';
    }
    echo '</tr>';
}
echo '</table>';

echo $OUTPUT->footer();
