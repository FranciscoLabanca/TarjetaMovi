<?php
namespace TarjetaMovi;

use PHPUnit\Framework\TestCase;

class TransporteTest extends TestCase {
	public $transporte;
	public $viaje;
	public $colectivo;
	public $patente;

	public function setUp() {
		$this->transporte = new Transporte();
		$this->colectivo = new Colectivo("131 Único", "Semtur");
		$this->viaje = new Viaje("Colectivo", 8.50, $this->colectivo, "19/10/16 19:07");
		$this->bicicleta = new Bicicleta("asd 123");
		$this->tarjeta = new Tarjetas();
		$this->medioBoleto = new Medio();
		$this->paseLibre = new PaseLibre();
	}

	//Test Class Transporte
	public function testTransporte() {
		$this->transporte->tipo = "Colectivo";
		$type = $this->transporte->tipo();
		$this->assertEquals($type, $this->transporte->tipo);
	}

	//Test Class Viaje
	public function testViaje() {
		//Test Function Tipo
		$tipo = $this->viaje->tipo();
		$this->assertEquals($tipo, "Colectivo");

		//Test Function Monto
		$monto = $this->viaje->monto();
		$this->assertEquals($monto, 8.50);

		//Test Function Transporte
		$transporte = $this->viaje->transporte();
		$this->assertEquals($transporte, "131 Único");

		//Test Function Tiempo
		$tiempo = $this->viaje->fecha_y_hora();
		$this->assertEquals($tiempo, "19/10/16 19:07");
	}

	//Test Class Colectivo
	public function testColectivo() {
		$nombre = $this->colectivo->nombre();
		$this->assertEquals($nombre, "131 Único");

		$empresa = $this->colectivo->empresa();
		$this->assertEquals($empresa, "Semtur");
	}

	//Test Class Bicicleta
	public function testBicicleta() {
		$tipo = $this->bicicleta->tipo();
		$this->assertEquals($tipo, "Bicicleta");

		$patente = $this->bicicleta->nombre();
		$this->assertEquals($patente, "asd 123");
	}

	//Test Class Tarjeta
	public function testTarjeta() {
		//Test Function Saldo
		$this->tarjeta->monto = 100;
		$saldo_aux = $this->tarjeta->saldo();
		$this->assertEquals($saldo_aux, $this->tarjeta->saldo);

		//Test Function Recargar
		$this->tarjeta->saldo = 0;
		$this->tarjeta->recargar(272);
		$this->assertEquals($this->tarjeta->saldo, 320);

		//Test Function Pagar (Con tarjeta comun) -> Colectivo
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 8.50;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo);

		//Test Function Pagar (Trasbordo) -> Colectivo
		$trasbordo = new Colectivo("142 Rojo", "Rosario Bus");
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($trasbordo, "2016/09/13 16:10");
		$saldo_final = $saldo_inicial - 2.81;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo);

		//Test Function Pagar (Con medio boleto) -> Colectivo
		$this->medioBoleto->recargar(290);
		$saldo_inicial = $this->medioBoleto->saldo();
		$this->medioBoleto->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 4.25;
		$this->assertEquals($saldo_final, $this->medioBoleto->saldo);

		//Test Function Pagar (Con pase libre) -> Colectivo
		$saldo_inicial = $this->paseLibre->saldo();
		$this->paseLibre->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial;
		$this->assertEquals($saldo_final, $this->paseLibre->saldo);

		//Test Function Pagar -> Bicicleta
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->bicicleta, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 12;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo);

		//Test Function ViajesRealizados 
		$this->tarjeta->viajes = 3;
		$viajes = $this->tarjeta->viajesRealizados();
		$this->assertEquals($viajes, $this->tarjeta->viajes);
	}
}
