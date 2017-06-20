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
 * This is is used to add a new lesson/category.
 *
 * Settings for category name, visibility and who can edit the exercise, are included.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 **/

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

global $USER, $DB;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
// $n = optional_param('n', 0, PARAM_INT); // Mootyper instance ID - it should be named as the first character of the module.

if ($id) {
    $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}
require_login($course, true);
$lessonpo = optional_param('lesson', -1, PARAM_INT);
if (isset($_POST['button'])) {
    $param1 = $_POST['button'];
}
$context = context_course::instance($id);


// DB insert.
if (isset($param1) && get_string('fconfirm', 'mootyper') == $param1 ) {

    $texttotypeepo = $_POST['texttotype'];

    if ($lessonpo == -1) {
        $lsnnamepo = $_POST['lessonname'];
        $lsnrecord = new stdClass();
        $lsnrecord->lessonname = $lsnnamepo;
        $lsnrecord->visible = $_POST['visible'];
        $lsnrecord->editable = $_POST['editable'];
        $lsnrecord->authorid = $USER->id;
        $lsnrecord->courseid = $course->id;
        $lessonid = $DB->insert_record('mootyper_lessons', $lsnrecord, true);
    } else {
        $lessonid = $lessonpo;
    }
    $snum = get_new_snumber($lessonid);
    $erecord = new stdClass();
    $erecord->exercisename = "".$snum;
    $erecord->snumber = $snum;
    $erecord->lesson = $lessonid;
    $erecord->texttotype = str_replace("\r\n", '\n', $texttotypeepo);
    $DB->insert_record('mootyper_exercises', $erecord, false);
    $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;

    echo '<script type="text/javascript">window.location="'.$webdir.'";</script>';
    // Trigger module exercise_added event.
    $event = \mod_mootyper\event\exercise_added::create(array(
        'objectid' => $course->id,
        'context' => $context
    ));
    $event->trigger();
}

// Print the page header.

$PAGE->set_url('/mod/mootyper/eins.php', array('id' => $course->id));
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();

$lessonsg = get_typerlessons();
if (has_capability('mod/mootyper:editall', context_course::instance($course->id))) {
    $lessons = $lessonsg;
} else {
    $lessons = array();
    foreach ($lessonsg as $lsng) {
        if (is_editable_by_me($USER->id, $lsng['id'])) {
            $lessons[] = $lsng;
        }
    }
}
echo '<form method="POST">';
echo get_string('fnewexercise', 'mootyper').'&nbsp;';
echo '<select onchange="this.form.submit()" name="lesson">';
echo '<option value="-1">'.get_string('fnewlesson', 'mootyper').'</option>';
for ($ij = 0; $ij < count($lessons); $ij++) {
    if ($lessons[$ij]['id'] == $lessonpo) {
        echo '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    } else {
        echo '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    }
}
echo '</select>';
if ($lessonpo == -1) {
    echo '<br><br>...'.get_string('lsnname', 'mootyper').': <input type="text" name="lessonname" id="lessonname">
          <span style="color:red;" id="namemsg"></span>';
    echo '<br><br>'.get_string('visibility', 'mootyper').': <select name="visible">';
    echo '<option value="2">'.get_string('vaccess2', 'mootyper').'</option>';
    echo '<option value="1">'.get_string('vaccess1', 'mootyper').'</option>';
    echo '<option value="0">'.get_string('vaccess0', 'mootyper').'</option>';
    echo '</select><br><br>'.get_string('editable', 'mootyper').': <select name="editable">';
    echo '<option value="2">'.get_string('eaccess2', 'mootyper').'</option>';
    echo '<option value="1">'.get_string('eaccess1', 'mootyper').'</option>';
    echo '<option value="0">'.get_string('eaccess0', 'mootyper').'</option>';
    echo '</select>';

}
?>

<script type="text/javascript">
function isLetter(str) {
    var pattern = /[a-zčšžđćüöäèéàçâêîôº¡çñ]/i;
    return str.length === 1 && str.match(pattern);
}
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

var ok = true;

function clClick()
{
    var exercise_text = document.getElementById("texttotype").value;
    var allowed_chars = ['\\', '~', '!', '@', '#', '$', '%', '^', '&', '(', ')', '*', '_',
                         '+', ':', ';', '"', '{', '}', '>', '<', '?', '\'', '-', '/', '=',
                         '.', ',', ' ', '|', '¡', '`', 'ç', 'ã', 'ê', 'í', 'ñ', 'º', '¿',
                         'ª', '·', '\n', '\r', '\r\n', '\n\r', ']', '[', '¬', '´', '`',
                         '§', '°', '€', '¦', '¢', '£', '₢', '¹', '²', '³', '¨'];
    var shown_text = "";
    ok = true;
    for(var i=0; i<exercise_text.length; i++) {
        if(!isLetter(exercise_text[i]) && !isNumber(exercise_text[i]) && allowed_chars.indexOf(exercise_text[i]) == -1) {
            shown_text += '<span style="color: red;">'+exercise_text[i]+'</span>';
            ok = false;
        }
        else
            shown_text += exercise_text[i];
    }
    if(!ok) {
        document.getElementById('text_holder_span').innerHTML = shown_text;
        return false;
    }
    if(document.getElementById("lessonname").value == "") {
        document.getElementById("namemsg").innerHTML = '<?php echo get_string('reqfield', 'mootyper');?>';
        return false;
    }
    else
        return true;
}
</script>

<?php
echo '<br><span id="text_holder_span" class=""></span><br>'.get_string('fexercise', 'mootyper').':<br>'.
     '<textarea rows="4" cols="40" name="texttotype" id="texttotype"></textarea><br>'.
     '<br><input name="button" onClick="return clClick()" type="submit" value="'.get_string('fconfirm', 'mootyper').'">'.
     '</form>';

echo $OUTPUT->footer();
