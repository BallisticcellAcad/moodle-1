<?php

require('../../config.php');
require_once("$CFG->dirroot/mod/quizoff/lib.php");
require_once("$CFG->dirroot/mod/quizoff/locallib.php");
require_once($CFG->libdir . '/completionlib.php');

$id = optional_param('id', 0, PARAM_INT);        // Course module ID
$u = optional_param('u', 0, PARAM_INT);         // URL instance id
$redirect = optional_param('redirect', 0, PARAM_BOOL);

if ($u) {  // Two ways to specify the module
    $quizoff = $DB->get_record('quizoff', array('id'=>$u), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('quizoff', $url->id, $quizoff->course, false, MUST_EXIST);

} else {
    $cm = get_coursemodule_from_id('quizoff', $id, 0, false, MUST_EXIST);
    $quizoff = $DB->get_record('quizoff', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/quizoff:view', $context);

// Completion and trigger events.
quizoff_view($course, $course, $cm, $context);

$PAGE->set_url('/mod/quizoff/view.php', array('id' => $cm->id));

// Make sure URL exists before generating output - some older sites may contain empty urls
// Do not use PARAM_URL here, it is too strict and does not support general URIs!
$exturl = trim($quizoff->externalurl);
if (empty($exturl) or $exturl === 'http://') {
    quizoff_print_header($quizoff, $cm, $course);
    quizoff_print_heading($quizoff, $cm, $course);
    notice(get_string('invalidstoredurl', 'url'), new moodle_url('/course/view.php', array('id'=>$cm->course)));
    die;
}
unset($exturl);

if ($redirect) {
    // coming from course page or url index page,
    // the redirection is needed for completion tracking and logging
    $fullurl = str_replace('&amp;', '&', quizoff_get_full_url($quizoff, $cm, $course));

    if (!course_get_format($course)->has_view_page()) {
        // If course format does not have a view page, add redirection delay with a link to the edit page.
        // Otherwise teacher is redirected to the external URL without any possibility to edit activity or course settings.
        $editurl = null;
        if (has_capability('moodle/course:manageactivities', $context)) {
            $editurl = new moodle_url('/course/modedit.php', array('update' => $cm->id));
            $edittext = get_string('editthisactivity');
        } else if (has_capability('moodle/course:update', $context->get_course_context())) {
            $editurl = new moodle_url('/course/edit.php', array('id' => $course->id));
            $edittext = get_string('editcoursesettings');
        }
        if ($editurl) {
            redirect($fullurl, html_writer::link($editurl, $edittext)."<br/>" . get_string('pageshouldredirect'), 10);
        }
    }
    redirect($fullurl);
}

quizoff_display_embed($quizoff, $cm, $course);