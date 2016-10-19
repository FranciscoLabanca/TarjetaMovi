<?php
namespace TarjetaMovi;

class Tarjetas implements Tarjeta{
	public $monto, $viajes = [], $descuento;

	function __construct (){
		$this->monto = 0;
		$this->descuento = 1;
	}
	public function pagar(Transporte $transporte, $fecha_y_hora){
		if ($transporte->tipo() == "Colectivo") {
			$trasbordo = false;
			if (count($this->viajes) > 0) {
				if (end($this->viajes)->tiempo() - strtotime($fechaHora) < 3600) {
					$trasbordo = true;
				}
			}

			$monto = 0;
			if ($trasbordo) {
				$monto = 2.81 * $this->descuento;
			}
			else {
				$monto = 8.50 * $this->descuento;
			}

			$this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fechaHora));
			$this->saldo -= $monto;
		} 
		else if ($transporte->tipo() == "Bicicleta") {
			$this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fechaHora));
			$this->saldo -= 12;
		}
	}
 	public function recargar($monto){
 		if($monto >= 500){
 			$this->monto = $this->monto + $monto + 140;
 		}
 		else if ($monto >= 272) {
 			$this->monto = $this->monto + $monto + 48;
 		}
 		else{
 			$this->monto = $this->monto + $monto;
 		}
 	}
 	public function saldo(){
 		return $this->monto;
 	}
 	public function viajesRealizados(){
 		return $this->viajes;
 	}
}

?>