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
 * Prints a particular instance of mootyper
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');

global $USER, $CFG, $THEME;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n = optional_param('n', 0, PARAM_INT); // Mootyper instance ID - it should be named as the first character of the module.
$userpassword = optional_param('userpassword', '', PARAM_RAW);
$backtocourse = optional_param('backtocourse', false, PARAM_RAW);

if ($id) {
    $cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course) , '*', MUST_EXIST);
    $mootyper = $DB->get_record('mootyper', array('id' => $cm->instance) , '*', MUST_EXIST);
} else if ($n) {
    $mootyper = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

if ($backtocourse) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
}
// I have moved set_title and set_heading to renederer.php.
$PAGE->set_url('/mod/mootyper/view.php', array('id' => $cm->id));

$context = context_module::instance($cm->id);

$mootyperoutput = $PAGE->get_renderer('mod_mootyper');

// Output starts here.
echo $mootyperoutput->header($mootyper, $cm);
echo '<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>';

if ($mootyper->intro) {
    echo $OUTPUT->box(format_module_intro('mootyper', $mootyper, $cm->id) , 'generalbox mod_introbox', 'mootyperintro');
}
if ($mootyper->lesson != null) {
    // Availability restrictions applied to students only.
    if ((!(is_available($mootyper)))&& (!(has_capability('mod/mootyper:viewgrades', $context)))) {
        if ($mootyper->timeclose != 0 && time() > $mootyper->timeclose) {
            echo $mootyperoutput->mootyper_inaccessible(get_string('mootyperclosed', 'mootyper', userdate($mootyper->timeclose)));
        } else {
            echo $mootyperoutput->mootyper_inaccessible(get_string('mootyperopen', 'mootyper', userdate($mootyper->timeopen)));
        }
        echo $OUTPUT->footer();
        exit();
    } else if ($mootyper->usepassword && empty($USER->mootyperloggedin[$mootyper->id])) { // Password protected mootyper code.
        $correctpass = false;
        if (!empty($userpassword) && (($mootyper->password == md5(trim($userpassword))) ||
            ($mootyper->password == trim($userpassword)))) {
            require_sesskey();
            // With or without md5 for backward compatibility (MDL-11090).
            $correctpass = true;
            $USER->mootyperloggedin[$mootyper->id] = true;

        } else if (isset($mootyper->extrapasswords)) {

            // Group overrides may have additional passwords.
            foreach ($mootyper->extrapasswords as $password) {
                if (strcmp($password, md5(trim($userpassword))) === 0 || strcmp($password, trim($userpassword)) === 0) {
                    require_sesskey();
                    $correctpass = true;
                    $USER->mootyperloggedin[$mootyper->id] = true;
                }
            }
        }
        if (!$correctpass) {
            echo $mootyperoutput->login_prompt($mootyper, $userpassword !== '');
            echo $mootyperoutput->footer();
            exit();
        }
    }

    if ($mootyper->isexam) {
        $exerciseid = $mootyper->exercise;
        $exercise = get_exercise_record($exerciseid);
        $texttoenter = $exercise->texttotype;
        $insertdir = $CFG->wwwroot . '/mod/mootyper/gadd.php?words=' . str_word_count($texttoenter);
    } else {
        $reqiredgoal = $mootyper->requiredgoal;
        $exercise = get_exercise_from_mootyper($mootyper->id, $mootyper->lesson, $USER->id);
        if ($exercise != false) {
            $exerciseid = $exercise->id;
            $texttoenter = $exercise->texttotype;
        }
        if (isset($texttoenter)) {
            $insertdir = $CFG->wwwroot . '/mod/mootyper/gcnext.php?words=' . str_word_count($texttoenter);
        }
    }

    if (exam_already_done($mootyper, $USER->id) && $mootyper->isexam) {
        echo get_string('examdone', 'mootyper');
        echo "<br>";
        if (has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
            $jlnk4 = $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $id . '&n=' . $mootyper->id;
            echo '<a href="' . $jlnk4 . '">' . get_string('viewgrades', 'mootyper') . '</a><br /><br />';
        }

        if (has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
            $jlnk7 = $CFG->wwwroot . "/mod/mootyper/owngrades.php?id=" . $id . "&n=" . $mootyper->id;
            echo '<a href="' . $jlnk7 . '">' . get_string('viewmygrades', 'mootyper') . '</a><br /><br />';
        }
    } else if ($exercise != false) {
        if ($mootyper->showkeyboard) {
            $displaynone = false;
        } else {
            $displaynone = true;
        }
        $keyboardjs = get_instance_layout_js_file($mootyper->layout);
        echo '<script type="text/javascript" src="' . $keyboardjs . '"></script>';
        echo '<script type="text/javascript" src="typer.js"></script>';
?>
<div id="mainDiv">
<form name='form1' id='form1' method='post' action='<?php echo $insertdir; ?>'> 
<div id="keyboard" style="float: left; text-align:center; margin-left: auto; margin-right: auto;">
<h4>
        <?php
        if (!$mootyper->isexam) {
            // Need to get count of exercises in the current lesson.
            $sqlc = "SELECT COUNT(mte.texttotype)
                    FROM {mootyper_lessons} mtl
                    LEFT JOIN {mootyper_exercises} mte
                    ON mte.lesson =  mtl.id
                    WHERE mtl.id = $mootyper->lesson";

            $count = $DB->count_records_sql($sqlc, $params = null);
            echo get_string('exercise', 'mootyper', $exercise->exercisename).$count;
        }
        ?>
</h4>
<br />
<div style="float: left; padding-bottom: 10px;" id="texttoenter"></div><br />
        <?php

        if ($mootyper->showkeyboard) {
            $displaynone = false;
        } else {
            $displaynone = true;
        }
        $keyboard = get_instance_layout_file($mootyper->layout);
        include($keyboard);
        ?>
<br />
    <textarea name="tb1" wrap="off" id="tb1" class="tb1" onfocus="return focusSet(event)"  
            onpaste="return false" onselectstart="return false"
            onCopy="return false" onCut="return false" 
            onDrag="return false" onDrop="return false" autocomplete="off">
            <?php
            echo get_string('chere', 'mootyper') . '...';
            ?>
    </textarea>
                         
</div>
<div id="reportDiv" style="float: right; /*position: relative; right: 90px; top: 35px;*/">
        <?php
        if (has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
            $jlnk4 = $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $id . '&n=' . $mootyper->id;;
            echo '<a href="' . $jlnk4 . '">' . get_string('viewgrades', 'mootyper') . '</a><br /><br />';
        }

        if (has_capability('mod/mootyper:aftersetup', context_module::instance($cm->id))) {
            $jlnk6 = $CFG->wwwroot . "/mod/mootyper/mod_setup.php?n=" . $mootyper->id . "&e=1";
            echo '<a href="' . $jlnk6 . '">' . get_string('fsettings', 'mootyper') . '</a><br /><br />';
        }

        if (has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
            $jlnk7 = $CFG->wwwroot . "/mod/mootyper/owngrades.php?id=" . $id . "&n=" . $mootyper->id;
            echo '<a href="' . $jlnk7 . '">' . get_string('viewmygrades', 'mootyper') . '</a><br /><br />';
        }

        ?>
<input name='rpCourseId' type='hidden' value='<?php
        echo $course->id; ?>'>
<input name='rpSityperId' type='hidden' value='<?php
        echo $mootyper->id; ?>'>
<input name='rpUser' type='hidden' value='<?php
        echo $USER->id; ?>'>
<input name='rpExercise' type='hidden' value='<?php
        echo $exerciseid; ?>'>
<input name='rpAttId' type='hidden' value=''>
<input name='rpFullHits' type='hidden' value=''>
<input name='rpGoal' type='hidden' value='
        <?php
        if (isset($reqiredgoal)) {
            echo $reqiredgoal;
        }
        ?>
'>
    <input name='rpTimeInput' type='hidden'>
    <input name='rpMistakesInput' type='hidden'>
    <input name='rpAccInput' type='hidden'>
    <input name='rpSpeedInput' type='hidden'>
<div id="rdDiv2">
<strong><?php
        echo get_string('rtime', 'mootyper'); ?></strong> <span id="jsTime">0</span> s<br />
<strong><?php
        echo get_string('rprogress', 'mootyper'); ?></strong> <span id="jsProgress"> 0</span><br />
<strong><?php
        echo get_string('rmistakes', 'mootyper'); ?></strong> <span id="jsMistakes">0</span><br />
<strong><?php
        echo get_string('rprecision', 'mootyper'); ?></strong> <span id="jsAcc"> 0</span>%<br />
<strong><?php
        echo get_string('rhitspermin', 'mootyper'); ?></strong> <span id="jsSpeed">0</span><br />
<strong><?php
        echo get_string('wpm', 'mootyper'); ?></strong>: <span id="jsWpm">0</span>
<br />
</div>
<br /><input style="visibility: hidden;" id="btnContinue" name='btnContinue' type="submit" value=<?php
        echo "'" . get_string('fcontinue', 'mootyper') . "'"; ?>> 
</div>

</form>
</div>
        <?php
        $texttoinit = '';
        for ($it = 0; $it < strlen($texttoenter); $it++) {
            if ($texttoenter[$it] == "\n") {
                $texttoinit .= '\n';
            } else if ($texttoenter[$it] == '"') {
                $texttoinit .= '\"';
            } else if ($texttoenter[$it] == "\\") {
                $texttoinit .= '\\';
            } else {
                $texttoinit .= $texttoenter[$it];
            }
        }

        $record = get_last_check($mootyper->id);
        if (is_null($record)) {
            echo '<script type="text/javascript">inittexttoenter("' . $texttoinit . '", 0, 0, 0, 0, 0, "' .
                $CFG->wwwroot . '", ' . $mootyper->showkeyboard . ', ' . $mootyper->continuoustype . ');</script>';
        } else {
            echo '<script type="text/javascript">inittexttoenter("' . $texttoinit . '", 1, ' . $record->mistakes . ', ' .
                $record->hits . ', ' . $record->timetaken . ', ' . $record->attemptid . ', "' . $CFG->wwwroot . '", ' .
                $mootyper->showkeyboard . ', ' . $mootyper->continuoustype . ');</script>';
        }
    } else {
        echo get_string('endlesson', 'mootyper');
        echo "<br />";
        if (has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
            $jlnk4 = $CFG->wwwroot . '/mod/mootyper/gview.php?id=' . $id . '&n=' . $mootyper->id;
            echo '<a href="' . $jlnk4 . '">' . get_string('viewgrades', 'mootyper') . '</a><br /><br />';
        }
        if (has_capability('mod/mootyper:viewmygrades', context_module::instance($cm->id))) {
            $jlnk7 = $CFG->wwwroot . "/mod/mootyper/owngrades.php?id=" . $id . "&n=" . $mootyper->id;
            echo '<a href="' . $jlnk7 . '">' . get_string('viewmygrades', 'mootyper') . '</a><br /><br />';
        }
    }

} else {
    if (has_capability('mod/mootyper:setup', context_module::instance($cm->id))) {
        $valnk = $CFG->wwwroot . "/mod/mootyper/mod_setup.php?n=" . $mootyper->id;
        echo '<a href="' . $valnk . '">' . get_string('fsetup', 'mootyper') . '</a>';
    } else {
        echo get_string('notreadyyet', 'mootyper');
    }
}

// Trigger module viewed event.
$event = \mod_mootyper\event\course_module_viewed::create(array(
   'objectid' => $mootyper->id,
   'context' => $context
));
$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('mootyper', $mootyper);
$event->trigger();
echo $mootyperoutput->footer();

