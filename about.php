<?php

require_once('config.php');

$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("About page");
$PAGE->set_heading("About");
$PAGE->set_url($CFG->wwwroot . '/about.php');


echo $OUTPUT->header();

// Actual content goes here
echo "Hello World";

echo $OUTPUT->footer();

?>