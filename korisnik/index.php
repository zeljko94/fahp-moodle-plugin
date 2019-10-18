<?php

echo "<div class='btn btn-primary' onclick='btnUnosMatriceOnClick()'>Unos matrice</div>";
echo "<br>";
echo "<br>";
echo "<div class='btn btn-primary' onclick='btnPregledMatricaOnClick()'>Pregled matrica</div>";

$unosMatriceURL = new moodle_url('/mod/testnimodul/korisnik/unosMatrice.php', array('id' => $cm->id));
$pregledMatricaURL = new moodle_url('/mod/testnimodul/korisnik/pregledMatrica.php', array('id' => $cm->id));

echo "<script>";
	echo "function btnUnosMatriceOnClick(){ window.location.assign('" . $unosMatriceURL . "'); }";
	echo "function btnPregledMatricaOnClick(){ window.location.assign('" . $pregledMatricaURL . "'); }";
echo "</script>";


