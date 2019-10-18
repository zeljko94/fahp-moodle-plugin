<?php


require_once('../../../config.php');
require_once('../lib.php');


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

//$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/jquery-3.2.1.min.js'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/js/TrokutniFuzzyBroj.js'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/js/TrokutniBrojSelect.js'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/js/Kriterij.js'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/js/Odnos.js'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/mod/testnimodul/js/Matrica.js'));



$PAGE->set_url('/mod/testnimodul/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($testnimodul->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($testnimodul->intro) {
    echo $OUTPUT->box(format_module_intro('testnimodul', $testnimodul, $cm->id), 'generalbox mod_introbox', 'testnimodulintro');
}

echo '<input type="text" name="ciljMatrice" id="ciljMatrice" placeholder="Cilj matrice..."/>';
echo '<hr>';

echo '<table id="kriterijiTable"></table>';
echo '<div class="btn btn-primary" onclick="btnDodajKriterijOnClick()">Dodaj kriterij</div>';
echo '<hr>';

echo '<table id="odnosiTable"></table>';
echo '<hr>';

echo '<table id="matricaTable"></table>';
echo '<hr>';

echo '<div class="btn btn-primary" onclick="btnSpremiMatricuOnClick()">Spremi matricu</div>';

echo "<br>";
echo "<br>";
echo "<div class='btn btn-primary' onclick='btnNazadOnClick()'>NAZAD NA POCETNU</div>";


$pocetna = new moodle_url('/mod/testnimodul/view.php', array('id' => $cm->id));
$spremiMatricuUrl = new moodle_url('/mod/testnimodul/korisnik/spremiMatricu.php',  array('id' => $cm->id));

echo "<script>";
    echo "function btnNazadOnClick(){ window.location.assign('" . $pocetna . "'); }";
echo "</script>";

echo '<script>function btnSpremiMatricuOnClick(){
    if(confirm("Jeste li sigurni da želite spremiti matricu?")){
        if(odnosi.length > 0){
            var ciljMatriceTxt = document.getElementById("ciljMatrice").value;
            matrica.json[0][0] = ciljMatriceTxt;
            if(ciljMatriceTxt != ""){
                window.location.assign("' . $spremiMatricuUrl . '&data=" + matrica.toJSON());
            }
            else{
                alert("Unesite cilj matrice!");
            }
        }
        else{
            alert("Morate unijeti minimalno 2 kriterija!");
        }
    }
}</script>';

echo "<script src='../js/unosMatrice.js'></script>";

echo $OUTPUT->footer();