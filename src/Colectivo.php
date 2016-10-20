<?php
namespace TarjetaMovi;

class Colectivo extends Transporte {
	public $nombre
	public $empresa;
	function __construct ($nombre, $empresa){
		$this->nombre = $nombre;
		$this->empresa = $empresa;
		$this->tipo = 'Colectivo';
	}
	public function nombre (){
		return $this->nombre;
	}
	public function empresa (){
		return $this->empresa;
	}
}