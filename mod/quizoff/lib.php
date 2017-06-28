<?php

defined('MOODLE_INTERNAL') || die;

function quizoff_add_instance($data, $mform) {
    global $CFG, $DB;

    require_once($CFG->dirroot.'/mod/quizoff/locallib.php');

    $parameters = array();
    for ($i=0; $i < 100; $i++) {
        $parameter = "parameter_$i";
        $variable  = "variable_$i";
        if (empty($data->$parameter) or empty($data->$variable)) {
            continue;
        }
        $parameters[$data->$parameter] = $data->$variable;
    }
    $data->parameters = serialize($parameters);
    $data->externalurl = quizoff_fix_submitted_url($data->externalurl);
    $data->id = $DB->insert_record('quizoff', $data);

    return $data->id;
}

function quizoff_update_instance($data, $mform) {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/mod/quizoff/locallib.php');

    $parameters = array();
    for ($i = 0; $i < 100; $i++) {
        $parameter = "parameter_$i";
        $variable = "variable_$i";
        if (empty($data->$parameter) or empty($data->$variable)) {
            continue;
        }
        $parameters[$data->$parameter] = $data->$variable;
    }
    $data->parameters = serialize($parameters);
    $data->externalurl = quizoff_fix_submitted_url($data->externalurl);
    $data->id = $data->instance;

    $DB->update_record('quizoff', $data);

    return true;
}

function quizoff_delete_instance($id) {
    global $DB;

    if (!$quizoff = $DB->get_record('quizoff', array('id'=>$id))) {
        return false;
    }

    // note: all context files are deleted automatically

    $DB->delete_records('quizoff', array('id'=>$quizoff->id));

    return true;
}

function quizoff_view($quizoff, $course, $cm, $context) {

    // Trigger course_module_viewed event.
    $params = array(
        'context' => $context,
        'objectid' => $quizoff->id
    );

    $event = \mod_url\event\course_module_viewed::create($params);
    $event->add_record_snapshot('course_modules', $cm);
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot('quizoff', $quizoff);
    $event->trigger();

    // Completion.
    $completion = new completion_info($course);
    $completion->set_module_viewed($cm);
}