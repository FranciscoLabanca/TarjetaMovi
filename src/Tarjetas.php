<?php
namespace TarjetaMovi;

class Tarjetas implements Tarjeta{
	public $monto, $viajes = [], $descuento, $plus = 0, $valor_boleto = 8.50, $valor_bici = 12, $id;

	function __construct ($id){
		$this->monto = 0;
		$this->descuento = 1;
		$this->id = $id;
	}
	public function pagar(Transporte $transporte, $fecha_y_hora){
		if($this->monto < $this->valor_boleto && $this->plus < 2){
			$this->plus++;
			//$monto [0] = $this->monto;
			//$this->viajes[] = new Viaje($transporte->tipo(), $monto[0], $transporte, strtotime($fecha_y_hora));
			if($this->plus == 1){
				$boleto = new Boleto ($fecha_y_hora, "Plus", $this->monto, $transporte->nombre, $this->id);
			}
			else{
				$boleto = new Boleto ($fecha_y_hora, "Ultimo Plus", $this->monto, $transporte->nombre, $this->id);
			}
		}
		else if ($this->monto < $this->valor_boleto && $this->plus == 2){
			return "Saldo insuficiente";
		}
		else{
			if ($transporte->tipo() == "Colectivo") {
				$trasbordo = false;
				if (count($this->viajes) > 0) {
					$ultimo = end($this->viajes);
					$ultViaje = $ultimo->fecha_y_hora();
					if ($ultViaje - strtotime($fecha_y_hora) < 3600) {
						if(date("N",$ultViaje) >= 1 && date("N",$ultViaje) <= 5 && date("G",$ultViaje) >= 6 && date("G",$ultViaje) < 22) {
							$trasbordo = true;
						}
						else if (date("N",$ultViaje) == 6 && date("G",$ultViaje) >= 6 && date("G",$ultViaje) < 14) {
							$trasbordo = true;
						}
					}
					if ($ultViaje - strtotime($fecha_y_hora) < 5400){
						if (date("G",$ultViaje) >= 22 || date("G",$ultViaje) < 6){
							$trasbordo = true;
						}
						else if(date("N", $ultViaje) == 6 && date("G", $ultViaje) >= 14 && date("G", $ultViaje) < 22){
							$trasbordo = true;
						}
						else if(date("N", $ultViaje) == 7 && date("G",$ultViaje) >= 6 && date("G",$ultViaje) < 22){
							$trasbordo = true;
						}
					}
				}
				$monto = 0;
				if ($trasbordo) {
					$monto = round(($this->valor_boleto * 0.33), 2) * $this->descuento + $this->valor_boleto * $this->plus;
					$this->plus = 0;
					$this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fecha_y_hora));
					$this->monto -= $monto;
				}
				else {
					$monto = $this->valor_boleto * $this->descuento + $this->valor_boleto * $this->plus;
					$this->plus = 0;
					$this->viajes[] = new Viaje($transporte->tipo(), $monto, $transporte, strtotime($fecha_y_hora));
					$this->monto -= $monto;
				}
			} 
			else if ($transporte->tipo() == "Bicicleta") {
				$this->viajes[] = new Viaje($transporte->tipo(), $this->valor_bici, $transporte, strtotime($fecha_y_hora));
				$this->monto -= $this->valor_bici;
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
