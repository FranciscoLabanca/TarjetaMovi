<?php
namespace TarjetaMovi;

class Bicicleta extends Transporte {
	public $patente;
	function __construct ($patente){
		$this->patente = $patente;
		$this->tipo = 'Bicicleta';
	}
	public function nombre (){
		return $this->patente;
	}
}

?>