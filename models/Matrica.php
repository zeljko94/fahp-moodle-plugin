<?php
require_once('TrokutniFuzzyBroj.php');

class Matrica{
	private $id;
	private $brojKriterija;
	private $kriteriji;
	private $vrijednosti;
	private $json;
	private $idKorisnika;
	private $cilj;
	private $datumKreiranja;

	public function Matrica($json)
	{
		$this->id_korisnika = 0;
		$this->id = 0;
		$this->json = $json;
		$this->kriteriji = [];
		$this->vrijednosti = array(array());

		$this->parse();
	}

	public function parse()
	{
		$parsedJson = json_decode($this->json);
		
		foreach($parsedJson as $key=>$val){
			foreach($val as $k=>$v){
				$this->vrijednosti[$key][$k] = $v;

			}
		}	
		$this->cilj = $this->vrijednosti[0][0];
		if(!$this->cilj) $this->cilj = "";
		$this->vrijednosti[0][0] = "";

		// postavi kriterije
		$this->kriteriji = [];
		for($i=0; $i < count($this->vrijednosti[0]); $i++)
		{
			if($i > 0)
			{
				array_push($this->kriteriji, $this->vrijednosti[0][$i]);
			}
		}
	}

	public function toString()
	{
		$str = "";
		foreach($this->vrijednosti as $key=>$val){
			foreach($val as $k=>$v){
				$str .= "m[$key][$k] = $v</br>";
			}
		}
		return $str;
	}

	public function draw()
	{
		echo "<table border='1'>";
		for($i=0; $i<count($this->vrijednosti); $i++)
		{
			echo "<tr>";
			for($j=0; $j<count($this->vrijednosti[$i]); $j++)
			{
				echo "<td style='padding: 15px 15px 15px 15px;'>";
					echo $this->vrijednosti[$i][$j];
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}

	public function getSumeRedaka()
	{
		$rez = [];
		for($i=0; $i<count($this->vrijednosti); $i++)
		{
			$redakSuma = new TrokutniFuzzyBroj(0,0,0);
			for($j=0; $j<count($this->vrijednosti[$i]); $j++)
			{
				if($i>0 && $j>0)
				{
					$tfb = new TrokutniFuzzyBroj(0, 0, 0);
					$tfb = $tfb->fromString($this->vrijednosti[$i][$j]);

					$redakSuma = $redakSuma->zbroji($tfb);
				}
			}
			array_push($rez, $redakSuma);
		}
		unset($rez[0]);
		return $rez;
	}

	public function getNeizrazitiVektor()
	{

		$sumeRedaka = $this->getSumeRedaka();
		$rez = new TrokutniFuzzyBroj(0,0,0);
		foreach($sumeRedaka as $broj)
		{
			$rez = $rez->zbroji($broj);
		}
		return $rez;
	}

	public function getNeizraziteVrijednosti()
	{
		$sumeRedaka = $this->getSumeRedaka();
		$neizrazitiVektor = $this->getNeizrazitiVektor();

		$rez = [];
		foreach($sumeRedaka as $val)
		{
			$neizrazitaVrijednost = new TrokutniFuzzyBroj(round($val->getV1() * (1 / $neizrazitiVektor->getV3()), 3),
										 round($val->getV2() * (1 / $neizrazitiVektor->getV2()), 3),
										 round($val->getV3() * (1 / $neizrazitiVektor->getV1()), 3));

			 array_push($rez, $neizrazitaVrijednost);
		}
		return $rez;
	}

	public function getTezinskeVrijednostiAtributaTraga()
	{
		$neizraziteVrijednosti = $this->getNeizraziteVrijednosti();
		$rez = [];

		for($i=0; $i<count($neizraziteVrijednosti); $i++)
		{
			$min = 1.1;
			for($j=0; $j<count($neizraziteVrijednosti); $j++)
			{
				$i1 = $i+1;
				$j1 = $j+1;
				if($i != $j)
				{
					$vr = 0;
					if($neizraziteVrijednosti[$i]->getV2() >= $neizraziteVrijednosti[$j]->getV2())
					{
						$vr = 1.0;
					}
					else if($neizraziteVrijednosti[$i]->getV1() >= $neizraziteVrijednosti[$j]->getV3())
					{
						$vr = 0.0;
					}
					else
					{
						$vr = ($neizraziteVrijednosti[$j]->getV1() - $neizraziteVrijednosti[$i]->getV3()) / (($neizraziteVrijednosti[$i]->getV2() - $neizraziteVrijednosti[$i]->getV3()) - ($neizraziteVrijednosti[$j]->getV2() - $neizraziteVrijednosti[$j]->getV1()));
					}
					if($vr < $min) $min = $vr;
				}
			}
			array_push($rez, $min);
		}
		return $rez;
	}

	public function getNormaliziraniVektorTezinskeVrijednosti()
	{
		$tezinskeVrijednosti = $this->getTezinskeVrijednostiAtributaTraga();
		$suma = 0;
		foreach($tezinskeVrijednosti as $vr)
		{
			$suma += $vr;
		}

		$rez = [];
		foreach($tezinskeVrijednosti as $vr)
		{
			$tvr = $vr / $suma;
			array_push($rez, $tvr);
		}
		return $rez;
	}


	
	/**
	* Metoda za postavljanje id-a.
	*
	* @access public
	* @param integer $id
	* @return void
	**/
	public function setId($id){ $this->id = $id; }

	/**
	* Metoda za dohvacanje id-a.
	*
	* @access public
	* @return integer
	**/
	public function getId(){ return $this->id; }
 

	/**
	* Metoda za postavljanje cilja.
	*
	* @access public
	* @param string $cilj
	* @return void
	**/
	public function setCilj($cilj){ $this->cilj = $cilj; }

	/**
	* Metoda za dohvacanje cilja.
	*
	* @access public
	* @return string
	**/
	public function getCilj(){ return $this->cilj; }

	/**
	* Metoda za postavljanje datuma kreiranja.
	*
	* @access public
	* @param Timestamp $datum_kreiranja
	* @return void
	**/
	public function setDatumKreiranja($datum_kreiranja)
	{
		$this->datum_kreiranja = $datum_kreiranja;
	}

	/**
	* Metoda za dohvacanje datuma kreiranja.
	*
	* @access public
	* @return Timestamp
	**/
	public function getDatumKreiranja(){ return $this->datum_kreiranja; }

	/**
	* Metoda za dohvacanje dvodimenzionalnog niza koji sadrzi vrijednosti matrice.
	*
	* @access public
	* @return Array(Array())
	**/
	public function getVrijednosti()
	{
		return $this->vrijednosti;
	}

	/**
	* Metoda za dohvacanje vrijednosti matrice u zadanom retku i stupcu.
	*
	* @access public
	* @param integer $i Broj retka
	* @param integer $j Broj stupca
	* @return string
	**/
	public function getElement($i,$j)
	{
		return $this->vrijednosti[$i][$j];
	}


	/**
	* Metoda za dohvacanje broja redaka matrice.
	*
	* @access public
	* @return integer
	**/
	public function getRows(){ return count($this->vrijednosti); }
	
	/**
	* Metoda za dohvacanje broja stupaca matrice.
	*
	* @access public
	* @return integer
	**/
	public function getCols(){ return count($this->vrijednosti[0]); }

	/**
	* Metoda za dohvacanje kriterija.
	*
	* @access public
	* @return Array()
	**/
	public function getKriterije()
	{
		return $this->kriteriji;
	}

	/**
	* Metoda koja vraca kriterije matrice u obliku stringa odvojene znakom ','
	*
	* @access public
	* @return string
	**/
	public function getKriterijeAsString()
	{
		$rez = "";
		for($i=0; $i<count($this->kriteriji); $i++)
		{
			$rez .= $this->kriteriji[$i];
			if($i < count($this->kriteriji)-1)
			{
				$rez .= ",";
			}
		}
		return $rez;
	}
	/**
	* Metoda za postavljanje id-a korisnika kojem pripada matrica.
	*
	* @access public
	* @param integer $id_korisnika
	* @return void
	**/
	public function setIdKorisnika($id_korisnika)
	{
		$this->id_korisnika = $id_korisnika;
	}

	/**
	* Metoda za dohvacanje id-a korisnika kojem pripada matrica.
	*
	* @access public
	* @return integer
	**/
	public function getIdKorisnika()
	{
		return $this->id_korisnika;
	}

	/**
	* Metoda za dohvacanje matrice u obliku jsona.
	*
	* @access public
	* @return string
	**/
	public function getJSON()
	{
		return $this->json;
	}
}
