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
 * Shows the setup of a particular instance of mootyper.
 *
 * You can set whether this instance is a lesson or exam,
 * select the exercise category, required precision, as
 * well as which keyboard to show and use.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // Mootyper instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mootyper  = $DB->get_record('mootyper', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mootyper  = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

$moocfg = get_config('mod_mootyper');
$epo = optional_param('e', 0, PARAM_INT);
$modepo = optional_param('mode', $mootyper->isexam, PARAM_INT);
$exercisepo = optional_param('exercise', $mootyper->exercise, PARAM_INT);
$lessonpo = optional_param('lesson', $mootyper->lesson, PARAM_INT);
$showkeyboardpo = optional_param('showkeyboard', "off", PARAM_CLEAN);
$continuoustypepo = optional_param('continuoustype', "off", PARAM_CLEAN);
if (empty($_POST)) {
    $showkeyboardpo = $mootyper->showkeyboard == 1 ? "on" : "off";
}
if (empty($_POST)) {
    $continuoustypepo = $mootyper->continuoustype == 1 ? "on" : "off";
}
if ($mootyper->layout == null || is_null($mootyper->layout)) {
    $dfly = $moocfg->defaultlayout;
} else {
    $dfly = $mootyper->layout;
}
$layoutpo = optional_param('layout', $dfly, PARAM_INT);
if ($mootyper->requiredgoal == null || is_null($mootyper->requiredgoal)) {
    $dfgoal = $moocfg->defaultprecision;
} else {
    $dfgoal = $mootyper->requiredgoal;
}
$goalpo = optional_param('requiredgoal', $dfgoal, PARAM_INT);
require_login($course, true, $cm);
$context = context_module::instance($cm->id);

if (isset($_POST['button'])) {
    $param1 = $_POST['button'];
}
if (isset($param1) && get_string('fconfirm', 'mootyper') == $param1) {
    $modepo = optional_param('mode', null, PARAM_INT);
    $lessonpo = optional_param('lesson', null, PARAM_INT);

    $goalpo = optional_param('requiredgoal', $moocfg->defaultprecision, PARAM_INT);
    if ($goalpo == 0) {
        $goalpo = $moocfg->defaultprecision;
    }
    $layoutpo = optional_param('layout', 0, PARAM_INT);

    $showkeyboardpo = optional_param('showkeyboard', null, PARAM_CLEAN);
    $continuoustypepo = optional_param('continuoustype', null, PARAM_CLEAN);
    global $DB, $CFG;
    $mootyper  = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $mootyper->lesson = $lessonpo;
    $mootyper->showkeyboard = $showkeyboardpo == 'on';
    $mootyper->continuoustype = $continuoustypepo == 'on';
    $mootyper->layout = $layoutpo;
    $mootyper->isexam = $modepo;
    $mootyper->requiredgoal = $goalpo;
    if ($modepo == 1) {
        $exercisepo = optional_param('exercise', null, PARAM_INT);
        $mootyper->exercise = $exercisepo;
    }
    $DB->update_record('mootyper', $mootyper);
    header('Location: '.$CFG->wwwroot.'/mod/mootyper/view.php?n='.$n);
}

// Print the page header.
$PAGE->set_url('/mod/mootyper/mod_setup.php', array('id' => $cm->id));
$PAGE->set_title(format_string($mootyper->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_cacheable(false);

echo $OUTPUT->header();
echo $OUTPUT->heading($mootyper->name);
$htmlout = '';
$htmlout .= '<script type="text/javascript">
function removeAtts()
{
    document.getElementById("lesson").disabled = false;
    document.getElementById("mode").disabled = false;
    document.getElementById("exercise").disabled = false;
}
</script>';
$htmlout .= '<form id="setupform" onsubmit="removeAtts();" name="setupform" method="POST">';
$disselect = $epo == 1 ? ' disabled="disabled"' : '';
$htmlout .= '<table><tr><td>'.get_string('fmode', 'mootyper').'</td>
                        <td><select'.$disselect.' onchange="this.form.submit()" name="mode" id="mode">';

// 3/22/16 Modified to use only improved function get_mootyperlessons.
if (has_capability('mod/mootyper:aftersetup', context_module::instance($cm->id))) {
    $lessons = get_mootyperlessons($USER->id, $course->id);
}

if ($modepo == 0 || is_null($modepo)) {
    $htmlout .= '<option selected="true" value="0">'.
            get_string('sflesson', 'mootyper').'</option><option value="1">'.
            get_string('isexamtext', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('excategory', 'mootyper').'</td>
                <td><select'.$disselect.' onchange="this.form.submit()" id="lesson" name="lesson">';
    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr><tr><td>'.get_string('requiredgoal', 'mootyper').'</td>
                 <td><input value="'.$goalpo.'" style="width: 20px;" type="text" name="requiredgoal"> % </td></tr>';
} else if ($modepo == 1) {
    $htmlout .= '<option value="0">'.
            get_string('sflesson', 'mootyper').'</option><option value="1" selected="true">'.
            get_string('isexamtext', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('flesson', 'mootyper').'</td>
                <td><select'.$disselect.' onchange="this.form.submit()" id="lesson" name="lesson">';
    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr>';
    $exercises = get_exercises_by_lesson($lessonpo);
    $htmlout .= '<tr><td>'.get_string('fexercise', 'mootyper').'</td><td><select'.$disselect.' name="exercise" id="exercise">';
    for ($ik = 0; $ik < count($exercises); $ik++) {
        if ($exercises[$ik]['id'] == $exercisepo) {
            $htmlout .= '<option selected="true" value="'.$exercises[$ik]['id'].'">'.$exercises[$ik]['exercisename'].'</option>';
        } else {
            $htmlout .= '<option value="'.$exercises[$ik]['id'].'">'.$exercises[$ik]['exercisename'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr>';
}

$htmlout .= '<tr><td>'.get_string('showkeyboard', 'mootyper').'</td><td>';
$showkeyboardchecked = $showkeyboardpo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$showkeyboardchecked.' onchange="this.form.submit()" name="showkeyboard">';
$htmlout .= '<tr><td>'.get_string('continuoustype', 'mootyper').'</td><td>';
$continuoustypechecked = $continuoustypepo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$continuoustypechecked.' onchange="this.form.submit()" name="continuoustype">';
$layouts = get_keyboard_layouts_db();

$deflayout = $moocfg->defaultlayout;
$htmlout .= '<tr><td>'.get_string('layout', 'mootyper').'</td><td><select name="layout">';

foreach ($layouts as $lkey => $lval) {
    if ((count($_POST) > 1) && ($lkey == $deflayout)) {
        $htmlout .= '<option value="'.$lkey.'" selected="true">'.$lval.'</option>';
    } else if ($lkey == $layoutpo) {
        $htmlout .= '<option value="'.$lkey.'" selected="true">'.$lval.'</option>';
    } else {
        $htmlout .= '<option value="'.$lkey.'">'.$lval.'</option>';
    }
}
$htmlout .= '</select>';

$htmlout .= '</td></tr>';

$htmlout .= '</table>';
$htmlout .= '<br><input name="button" value="'.get_string('fconfirm', 'mootyper').'" type="submit">';
$htmlout .= '</form>';
echo $htmlout;
// Finish the page.
echo $OUTPUT->footer();
