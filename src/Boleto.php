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
		public function f_fecha (){
			return $this->fecha;
		}
		public function f_tipo (){
			return $this->tipo;
		}

		public function f_saldo (){
			return $this->saldo;
		}

		public function f_nro_linea (){
			return $this->nro_linea;
		}

		public function f_id (){
			return $this->id;
		}
	}