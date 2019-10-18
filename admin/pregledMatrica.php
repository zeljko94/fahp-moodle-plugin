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




$PAGE->set_url('/mod/testnimodul/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($testnimodul->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($testnimodul->intro) {
    echo $OUTPUT->box(format_module_intro('testnimodul', $testnimodul, $cm->id), 'generalbox mod_introbox', 'testnimodulintro');
}



echo "<h1>Pregled matrica</h1>";
echo "<hr>";
echo "<div class='btn btn-primary' onclick='btnNazadOnClick()'>NAZAD</div><br>";

$matriceFromDB = $DB->get_records('mdl_matrica');
$matrice = array();

if(count($matriceFromDB) > 0){
    foreach($matriceFromDB as $matrica){
        $m = new Matrica($matrica->matrica_json);
        $m->setDatumKreiranja($matrica->datum_kreiranja);
        $m->setId($matrica->id);
        $m->setIdKorisnika($matrica->id_korisnika);
        array_push($matrice, $m);
    }

    $i = 1;
    foreach($matrice as $matrica){
        $korisnik = $DB->get_record('user', array('id' => $matrica->getIdKorisnika()));

        echo "<h5><strong>$i.&nbsp;&nbsp;" . $matrica->getCilj() . "</strong></h5>";
        $matrica->draw();
        echo "<p>Datum kreiranja: " . $matrica->getDatumKreiranja() . "</p>";
        echo "<p>Korisnik: " . $korisnik->firstname . " " . $korisnik->lastname . "</p>";
        echo "<div class='btn btn-primary' style='margin-right: 10px' onclick='btnPregledMatriceOnClick(" . $matrica->getId() .")'>Pregled matrice</div>";
        //echo "<div class='btn btn-primary' onclick='btnBrisiMatricuOnClick(" . $matrica->getId() .")'>Brisi matricu</div>";
        echo "<hr>";
        $i++;
    }
}
else{
    echo "<h3>Nema rezultata!</h3>";
}


$pocetna = new moodle_url('/mod/testnimodul/view.php', array('id' => $cm->id));
$brisiMatricu = new moodle_url('/mod/testnimodul/korisnik/brisiMatricu.php', array('id' => $cm->id));
$pregledMatrice = new moodle_url('/mod/testnimodul/korisnik/pregledMatrice.php', array('id' => $cm->id));

echo "<script>";
    echo "function btnNazadOnClick(){ window.location.assign('" . $pocetna . "'); }";
    echo "function btnBrisiMatricuOnClick(id){
        if(confirm('Jeste li sigurni da želite obrisati matricu?')){
            window.location.assign('" . $brisiMatricu . "&matricaId='+id);
        }
     }";
    echo "function btnPregledMatriceOnClick(id){
        window.location.assign('" . $pregledMatrice . "&matricaId='+id);
    }";
echo "</script>";

echo $OUTPUT->footer();