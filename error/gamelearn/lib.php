<?php

defined('MOODLE_INTERNAL') || die;

function gamelearn_add_instance($gamelearn, $mform) {
    global $CFG, $DB;

    require_once($CFG->dirroot.'/mod/gamelearn/locallib.php');

    $parameters = array();
    for ($i=0; $i < 100; $i++) {
        $parameter = "parameter_$i";
        $variable  = "variable_$i";
        if (empty($gamelearn->$parameter) or empty($gamelearn->$variable)) {
            continue;
        }
        $parameters[$gamelearn->$parameter] = $gamelearn->$variable;
    }
    $gamelearn->parameters = serialize($parameters);
    $gamelearn->externalurl = gamelearn_fix_submitted_url($gamelearn->externalurl);
    $gamelearn->id = $DB->insert_record('gamelearn', $gamelearn);

    return $gamelearn->id;
}

function gamelearn_update_instance($gamelearn, $mform) {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/mod/gamelearn/locallib.php');

    $parameters = array();
    for ($i = 0; $i < 100; $i++) {
        $parameter = "parameter_$i";
        $variable = "variable_$i";
        if (empty($gamelearn->$parameter) or empty($gamelearn->$variable)) {
            continue;
        }
        $parameters[$gamelearn->$parameter] = $gamelearn->$variable;
    }
    $gamelearn->parameters = serialize($parameters);
    $gamelearn->externalurl = gamelearn_fix_submitted_url($gamelearn->externalurl);
    $gamelearn->id = $gamelearn->instance;

    $DB->update_record('gamelearn', $gamelearn);
    
    return true;
}

function gamelearn_delete_instance($id) {
    global $DB;

    if (!$gamelearn = $DB->get_record('gamelearn', array('id'=>$id))) {
        return false;
    }

    // note: all context files are deleted automatically

    $DB->delete_records('gamelearn', array('id'=>$gamelearn->id));
    
    return true;
}

function gamelearn_view($gamelearn, $course, $cm, $context) {

    // Trigger course_module_viewed event.
    $params = array(
        'context' => $context,
        'objectid' => $gamelearn->id
    );

    $event = \mod_url\event\course_module_viewed::create($params);
    $event->add_record_snapshot('course_modules', $cm);
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot('gamelearn', $gamelearn);
    $event->trigger();

    // Completion.
    $completion = new completion_info($course);
    $completion->set_module_viewed($cm);
}

function gamelearn_supports($feature) {
    switch($feature) {
        default: return null;
    }
}
