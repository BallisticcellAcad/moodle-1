<?php

defined('MOODLE_INTERNAL') || die;

function quizoff_add_instance($quizoff, $mform) {
    global $CFG, $DB;

    require_once($CFG->dirroot.'/mod/quizoff/locallib.php');

    $parameters = array();
    for ($i=0; $i < 100; $i++) {
        $parameter = "parameter_$i";
        $variable  = "variable_$i";
        if (empty($quizoff->$parameter) or empty($quizoff->$variable)) {
            continue;
        }
        $parameters[$quizoff->$parameter] = $quizoff->$variable;
    }
    $quizoff->parameters = serialize($parameters);
    $quizoff->externalurl = quizoff_fix_submitted_url($quizoff->externalurl);
    $quizoff->id = $DB->insert_record('quizoff', $quizoff);
    
    quizoff_grade_item_update($quizoff);

    return $quizoff->id;
}

function quizoff_update_instance($quizoff, $mform) {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/mod/quizoff/locallib.php');

    $parameters = array();
    for ($i = 0; $i < 100; $i++) {
        $parameter = "parameter_$i";
        $variable = "variable_$i";
        if (empty($quizoff->$parameter) or empty($quizoff->$variable)) {
            continue;
        }
        $parameters[$quizoff->$parameter] = $quizoff->$variable;
    }
    $quizoff->parameters = serialize($parameters);
    $quizoff->externalurl = quizoff_fix_submitted_url($quizoff->externalurl);
    $quizoff->id = $quizoff->instance;

    $DB->update_record('quizoff', $quizoff);
    
    quizoff_grade_item_update($quizoff);
    
    return true;
}

function quizoff_delete_instance($id) {
    global $DB;

    if (!$quizoff = $DB->get_record('quizoff', array('id'=>$id))) {
        return false;
    }

    // note: all context files are deleted automatically

    $DB->delete_records('quizoff', array('id'=>$quizoff->id));
    
    grade_update('mod/quizoff', $quizoff->course, 'mod', 'quizoff', $quizoff->id, 0, NULL, array('deleted'=>1));

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

function quizoff_supports($feature) {
    switch($feature) {
        case FEATURE_GRADE_HAS_GRADE:         return true;
        case FEATURE_GRADE_OUTCOMES:          return true;
 
        default: return null;
    }
}

function quizoff_grade_item_update($quizoff, $grades = NULL) {
    global $CFG;

    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir . '/gradelib.php');
    }

    $params = array('itemname' => $quizoff->name, 'idnumber' => $quizoff->cmidnumber);
    
    
    $params['grademax'] = $quizoff->grade;
    $params['gradepass'] = $quizoff->gradepass;

    if ($grades === 'reset') {
        $params['reset'] = true;
        $grades = NULL;
    }    
    return grade_update('mod/quizoff', $quizoff->course, 'mod', 'quizoff', $quizoff->id, 0, $grades, $params);
}
