<?php


require_once('../../../config.php');
require_once('../lib.php');
require_once('../models/Matrica.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... testnimodul instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('testnimodul', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $testnimodul  = $DB->get_record('testnimodul', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $testnimodul  = $DB->get_record('testnimodul', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $testnimodul->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('testnimodul', $testnimodul->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_testnimodul\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $testnimodul);
$event->trigger();

// Print the page header.

//$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/js/test.js'));



$PAGE->set_url('/mod/testnimodul/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($testnimodul->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($testnimodul->intro) {
    echo $OUTPUT->box(format_module_intro('testnimodul', $testnimodul, $cm->id), 'generalbox mod_introbox', 'testnimodulintro');
}

$matricaId = isset($_GET['matricaId']) ? $_GET['matricaId'] : 0;

if($matricaId){
    $DB->delete_records('mdl_matrica', array('id' => $matricaId));
    $url = new moodle_url('/mod/testnimodul/korisnik/pregledMatrica.php', array('id' => $cm->id));
    redirect($url, "Matrica uspjesno obrisana", 1);
}


echo $OUTPUT->footer();