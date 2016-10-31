<?php
namespace TarjetaMovi;

	class Boleto {
		public $fecha, $tipo, $saldo, $nro_linea, $id;
		function __construct ($fecha, $tipo, $saldo, $nro_linea, $id){
			$this->fecha = $fecha;
			$this->tipo = $tipo;
			$this->saldo = $saldo;
			$this->nro_linea = $nro_linea;
			$this->id = $id;
		}
		public function fecha (){
			return $this->fecha;
		}
		public function tipo (){
			return $this->tipo;
		}

		public function saldo (){
			return $this->saldo;
		}

		public function nro_linea (){
			return $this->nro_linea;
		}

		public function id (){
			return $this->id;
		}
	}