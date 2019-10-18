<?php

echo "<div class='btn btn-primary' onclick='btnPregledMatricaOnClick()'>Pregled korisnickih matrica</div>";


$adminPregledMatricaURL = new moodle_url($CFG->wwwroot . "/mod/testnimodul/admin/pregledMatrica.php", array('id' => $cm->id));
echo "<script type='text/javascript'>
	function btnPregledMatricaOnClick(){ window.location.assign('" . $adminPregledMatricaURL . "'); }
</script>";