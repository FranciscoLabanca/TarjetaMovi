<?php
namespace TarjetaMovi;

class Tarjetas implements Tarjeta{
	public $monto, $viajes = [], $descuento, $plus = 0, $valor = 8.50;

	function __construct (){
		$this->monto = 0;
		$this->descuento = 1;
	}
	public function pagar(Transporte $transporte, $fecha_y_hora){
		if($this->monto < $this->valor && $this->plus < 2){
			$this->plus++;
			$this->viajes[] = new Viajes($transporte->tipo(), $this->plus, $transporte, strtotime($fecha_y_hora));
		}
		else if ($this->monto < $this->valor && $this->plus == 2){
			echo "Saldo insuficiente";
		}
		else{
			if ($transporte->tipo() == "Colectivo") {
				$trasbordo = false;
				if (count($this->viajes) > 0) {
					if (end($this->viajes)->tiempo() - strtotime($fecha_y_hora) < 3600) {
						$trasbordo = true;
					}
				}
				$monto = 0;
				if ($trasbordo) {
					$monto = round(($this->valor * 0.33), 2) * $this->descuento + $this->valor * $this->plus;
					$this->plus = 0;
				}
				else {
					$monto = $this->valor * $this->descuento + $this->valor * $this->plus;
					$this->plus = 0;
				}
				$this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fecha_y_hora));
				$this->monto -= $monto;
			} 
			else if ($transporte->tipo() == "Bicicleta") {
				$this->viajes[] = new Viaje($transporte->tipo(), 12, $transporte, strtotime($fecha_y_hora));
				$this->monto -= 12;
			}
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
