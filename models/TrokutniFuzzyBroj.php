<?php

/**
* Klasa koja sluzi za rukovanje trokutnim neizrazitim brojevima.
*
* @package fahp
* @subpackage models
* @version 1
* @since 08-12-2016
* @author <zeljkokrnjic94@gmail.com>
**/
class TrokutniFuzzyBroj
{
	/**
	* Vrijednost prve komponente neizrazitog trokutnog broja.
	* @var double
	* @access private
	**/
	private $v1;

	/**
	* Vrijednost druge komponente neizrazitog trokutnog broja.
	* @var double
	* @access private
	**/
	private $v2;

	/**
	* Vrijednost trece komponente neizrazitog trokutnog broja.
	* @var double
	* @access private
	**/
	private $v3;

	/**
	* Konstruktor
	*
	* @access public
	*/
	public function TrokutniFuzzyBroj($v1,$v2,$v3)
	{
		$this->v1 = $v1;
		$this->v2 = $v2;
		$this->v3 = $v3;
	}

	/**
	* Metoda za zbrajanje 2 trokutna broja.
	*
	* @access public
	* @param $other TrokutniFuzzyBroj
	* @return TrokutniFuzzyBroj
	**/
	public function zbroji($other)
	{
		return new TrokutniFuzzyBroj($this->v1 + $other->v1, $this->v2 + $other->v2, $this->v3 + $other->v3);
	}
	
	/**
	* Metoda za inicijalizaciju trokutnog broja iz stringa oblika (x,y,z).
	* 
	* @access public
	* @param $str string
	* @return TrokutniFuzzyBroj
	**/
	public function fromString($str)
	{
		$var = str_replace(")", "", $str);
		$var = str_replace("(", "", $var);
		$var = explode(",", $var);
		
		$tfb = new TrokutniFuzzyBroj($var[0], $var[1], $var[2]);
		return $tfb;
	}

	/**
	* Metoda za pretvaranje trokutnog broja u string oblika (x,y,z)
	*
	* @access public
	* @return string
	**/
	public function toString()
	{
		$rez = "(" . $this->v1 . "," . $this->v2 . "," . $this->v3 . ")";
		return $rez;
	}

	public function getV1(){ return $this->v1; }
	public function getV2(){ return $this->v2; }
	public function getV3(){ return $this->v3; }


	public function setV1($v1){ $this->v1 = $v1; }
	public function setV2($v2){ $this->v2 = $v2; }
	public function setV3($v3){ $this->v3 = $v3; }
}