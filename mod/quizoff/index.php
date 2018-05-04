<?php
require_once('../../config.php');

$id = required_param('id', PARAM_INT); // course id

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

$params = array(
    'context' => context_course::instance($course->id)
);

$strname = get_string('name');
$strurl = get_string('modulename', 'quizoff');
$strquizoffs = get_string('modulenameplural', 'quizoff');
$strintro = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$PAGE->set_url('/mod/quizoff/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.$strquizoffs);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strquizoffs);

echo $OUTPUT->header();
echo $OUTPUT->heading($strquizoffs);

if (!$quizoffs = get_all_instances_in_course('quizoff', $course)) {
    notice(get_string('thereareno', 'moodle', $strquizoffs), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_'.$course->format);
    $table->head  = array ($strsectionname, $strname, $strintro);
    $table->align = array ('center', 'left', 'left');
} else {
    $table->head  = array ($strlastmodified, $strname, $strintro);
    $table->align = array ('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';

foreach ($quizoffs as $quizoff) {
    $cm = $modinfo->cms[$quizoff->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($quizoff->section !== $currentsection) {
            if ($quizoff->section) {
                $printsection = get_section_name($course, $quizoff->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $quizoff->section;
        }
    } else {
        $printsection = '<span class="smallinfo">'.userdate($quizoff->timemodified)."</span>";
    }

    $extra = empty($cm->extra) ? '' : $cm->extra;
    $icon = '';
    if (!empty($cm->icon)) {
        // each url has an icon in 2.0
        $icon = '<img src="'.$OUTPUT->pix_url($cm->icon).'" class="activityicon" alt="'.get_string('modulename', $cm->modname).'" /> ';
    }

    $class = $quizoff->visible ? '' : 'class="dimmed"'; // hidden modules are dimmed
    $table->data[] = array (
        $printsection,
        "<a $class $extra href=\"view.php?id=$cm->id\">".$icon.format_string($quizoff->name)."</a>",
        format_module_intro('quizoff', $quizoff, $cm->id));
}

echo html_writer::table($table);

echo $OUTPUT->footer();