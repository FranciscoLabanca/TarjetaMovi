<?php
namespace TarjetaMovi;

use PHPUnit\Framework\TestCase;

class TransporteTest extends TestCase {
	public $transporte, $viaje, $colectivo, $bicicleta, $tarjeta, $medioBoleto, $paseLibre, $valor_boleto = 8.50, $boleto;

	public function setUp() {
		$this->transporte = new Transporte();
		$this->colectivo = new Colectivo("131 Único", "Semtur");
		$this->viaje = new Viaje("Colectivo", 8.50, $this->colectivo, "19/10/16 19:07");
		$this->bicicleta = new Bicicleta("asd 123");
		$this->tarjeta = new Tarjetas();
		$this->medioBoleto = new Medio();
		$this->paseLibre = new PaseLibre();
		$this->boleto = new Boleto("19/10/16 19:07", 1, 91.5, "131 Único", "15945652");
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
		$this->assertEquals($monto, $this->valor_boleto);

		//Test Function Transporte
		$transporte = $this->viaje->transporte();
		$this->assertEquals($transporte, "Colectivo");

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
	public function testTarjetas() {
		//Test Function Saldo
		$this->tarjeta->monto = 100;
		$saldo_aux = $this->tarjeta->saldo();
		$this->assertEquals($saldo_aux, $this->tarjeta->monto);

		//Test Function Recargar
		$this->tarjeta->monto = 0;
		$this->tarjeta->recargar(280);
		$this->assertEquals($this->tarjeta->monto, 328);

		$this->tarjeta->monto = 0;
		$this->tarjeta->recargar(600);
		$this->assertEquals($this->tarjeta->monto, 740);

		$this->tarjeta->monto = 0;
		$this->tarjeta->recargar(100);
		$this->assertEquals($this->tarjeta->monto, 100);

		//Test Function Pagar (Con tarjeta comun) -> Colectivo
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 8.50;
		$this->assertEquals($saldo_final, $this->tarjeta->monto);

		//Test Function Pagar (Trasbordo) -> Colectivo
		$trasbordo = new Colectivo("142 Rojo", "Rosario Bus");
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($trasbordo, "2016/09/13 16:10");
		$saldo_final = $saldo_inicial - 2.81;
		$this->assertEquals($saldo_final, $this->tarjeta->monto);

		//Test Function Pagar (Con medio boleto) -> Colectivo
		$this->medioBoleto->recargar(290);
		$saldo_inicial = $this->medioBoleto->saldo();
		$this->medioBoleto->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 4.25;
		$this->assertEquals($saldo_final, $this->medioBoleto->monto);

		//Test Function Pagar (Con pase libre) -> Colectivo
		$saldo_inicial = $this->paseLibre->saldo();
		$this->paseLibre->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial;
		$this->assertEquals($saldo_final, $this->paseLibre->monto);

		//Test Function Pagar -> Bicicleta
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->bicicleta, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 12;
		$this->assertEquals($saldo_final, $this->tarjeta->monto);

		//Test Function ViajesRealizados 
		$this->tarjeta->viajes = 3;
		$viajes = $this->tarjeta->viajesRealizados();
		$this->assertEquals($viajes, $this->tarjeta->viajes);

		//Test Function Pagar Sin Saldo y Sin Plus
		$bondi = new Colectivo ("115 Único", "Semtur");
		$this->tarjeta->monto = 0;
		$this->tarjeta->plus = 2;
		$retorno = $this->tarjeta->pagar($this->colectivo, "02/11/2016 9:42"):
		$this->assertEquals($retorno, "Saldo insuficiente");
		
	}
	//Test Class Boleto
	public function testBoleto () {

		$fechaTest = $this->boleto->f_fecha();
		$this->assertEquals($fechaTest, $this->boleto->fecha);

		$tipoTest = $this->boleto->f_tipo();
		$this->assertEquals($tipoTest, $this->boleto->tipo);

		$saldoTest = $this->boleto->f_saldo();
		$this->assertEquals($saldoTest, $this->boleto->saldo);

		$nro_lineaTest = $this->boleto->f_nro_linea();
		$this->assertEquals($nro_lineaTest, $this->boleto->nro_linea);

		$idTest = $this->boleto->f_id();
		$this->assertEquals($idTest, $this->boleto->id);
	}
}
