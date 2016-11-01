<?php
namespace TarjetaMovi;

class Viaje {
	public $tipo, $monto, $transporte, $fecha_y_hora;
	function __construct ($tipo, $monto, Transporte $transporte, $fecha_y_hora){
		$this->tipo = $tipo;
		$this->monto = $monto;
		$this->transporte = $transporte;
		$this->fecha_y_hora = $fecha_y_hora;
	}
	public function tipo () {
		return $this->tipo;
	}
	public function monto () {
		return $this->monto;
	}
	public function transporte () {
		return $this->transporte->tipo;
	}
	public function fecha_y_hora () {
		return $this->fecha_y_hora;
	}
}
