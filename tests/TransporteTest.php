<?php
namespace TarjetaMovi;

use PHPUnit\Framework\TestCase;

class TransporteTest extends TestCase {
	public $transporte, $viaje, $colectivo, $bicicleta, $tarjeta, $medioBoleto, $paseLibre, $valor_boleto = 8.50, $boleto;

	public function setUp() {
		$this->transporte = new Transporte();
		$this->colectivo = new Colectivo("131 Único", "Semtur");
		$this->viaje = new Viaje("Colectivo", 8.50, $this->colectivo, "2016/10/19 19:07");
		$this->bicicleta = new Bicicleta("asd 123");
		$this->tarjeta = new Tarjetas("123123123");
		$this->medioBoleto = new Medio();
		$this->paseLibre = new PaseLibre();
		$this->boleto = new Boleto("2016/10/19 19:07", 1, 91.5, "131 Único", "15945652");
	}

	//Test Class Transporte
	public function testTransporte() {
		$this->transporte->tipo = "Colectivo";
		$type = $this->transporte->tipo();
		$this->assertEquals($type, $this->transporte->tipo);
	}

	//Test Class Viaje
	public function testViaje() {
		//Test Función Tipo
		$tipo = $this->viaje->tipo();
		$this->assertEquals($tipo, "Colectivo");

		//Test Función Monto
		$monto = $this->viaje->monto();
		$this->assertEquals($monto, $this->valor_boleto);

		//Test Función Transporte
		$transporte = $this->viaje->transporte();
		$this->assertEquals($transporte, "Colectivo");

		//Test Función Tiempo
		$tiempo = $this->viaje->fecha_y_hora();
		$this->assertEquals($tiempo, "2016/10/19 19:07");
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
		//Test Función Saldo
		$this->tarjeta->monto = 100;
		$saldo_aux = $this->tarjeta->saldo();
		$this->assertEquals($saldo_aux, $this->tarjeta->saldo());

		//Test Función Recargar
		$this->tarjeta->monto = 0;
		$this->tarjeta->recargar(280);
		$this->assertEquals($this->tarjeta->saldo(), 328);

		$this->tarjeta->monto = 0;
		$this->tarjeta->recargar(600);
		$this->assertEquals($this->tarjeta->saldo(), 740);

		$this->tarjeta->monto = 0;
		$this->tarjeta->recargar(100);
		$this->assertEquals($this->tarjeta->saldo(), 100);

		//Test Función Pagar (Con tarjeta comun) -> Colectivo
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 8.50;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo());

		//Test Función Pagar (Trasbordo) -> Colectivo
		$trasbordo = new Colectivo("142 Rojo", "Rosario Bus");
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($trasbordo, "2016/09/13 16:10");
		$saldo_final = $saldo_inicial - 2.81;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo());

		//Test Función Pagar (Con pase libre) -> Colectivo
		$saldo_inicial = $this->paseLibre->saldo();
		$this->paseLibre->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial;
		$this->assertEquals($saldo_final, $this->paseLibre->saldo());

		//Test Función Pagar Trasbordo Sábado después de las 6 hs y antes de las 14 hs
		$this->tarjeta2 = new Tarjetas ("894561");
		$sabado1 = "2016/10/01 8:00";
		$sabado2 = "2016/10/01 8:30";
		$this->tarjeta2->recargar(100);
		$saldo_inicial = $this->tarjeta2->saldo();
		$this->tarjeta2->pagar($trasbordo, $sabado1);
		$this->tarjeta2->pagar($this->colectivo, $sabado2);
		$boleto = $this->tarjeta2->valor_boleto + round($this->tarjeta2->valor_boleto * 0.33,2);
		$saldo_final = $saldo_inicial - $boleto;
		$this->assertEquals($saldo_final, $this->tarjeta2->saldo());

		//Test Función Pagar Trasbordo Sábado después de las 14 hs y antes de las 22 hs
		/*$sabado1 = "2016/10/01 15:00";
		$sabado2 = "2016/10/01 16:10";
		$saldo_inicial = $this->tarjeta2->saldo();
		$this->tarjeta2->pagar($trasbordo, $sabado1);
		$this->tarjeta2->pagar($this->colectivo, $sabado2);
		$saldo_final = $saldo_inicial - $boleto;
		$this->assertEquals($saldo_final, $this->tarjeta2->saldo());*/

		//Test Función Pagar Trasbordo Turno Noche
		$noche1 = "2016/11/03 22:10";
		$noche2 = "2016/11/03 23:20";
		$saldo_inicial = $this->tarjeta2->saldo();
		$this->tarjeta2->pagar($trasbordo, $noche1);
		$this->tarjeta2->pagar($this->colectivo, $noche2);
		$saldo_final = $saldo_inicial - $boleto;
		$this->assertEquals($saldo_final, $this->tarjeta2->saldo());

		//Test Función Pagar Trasbordo Domingo después de las 6 hs y antes de las 22 hs
		$domingo1 = "2016/10/09 15:00";
		$domingo2 = "2016/10/09 16:10";
		$saldo_inicial = $this->tarjeta2->saldo();
		$this->tarjeta2->pagar($trasbordo, $domingo1);
		$this->tarjeta2->pagar($this->colectivo, $domingo2);
		$saldo_final = $saldo_inicial - $boleto;
		$this->assertEquals($saldo_final, $this->tarjeta2->saldo());

		//Test Función Pagar (Con medio boleto) -> Colectivo
		$this->medioBoleto->recargar(290);
		$saldo_inicial = $this->medioBoleto->saldo();
		$this->medioBoleto->pagar($this->colectivo, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 4.25;
		$this->assertEquals($saldo_final, $this->medioBoleto->saldo());

		//Test Función Pagar -> Bicicleta
		$saldo_inicial = $this->tarjeta->saldo();
		$this->tarjeta->pagar($this->bicicleta, "2016/09/13 15:50");
		$saldo_final = $saldo_inicial - 12;
		$this->assertEquals($saldo_final, $this->tarjeta->saldo());

		//Test Función ViajesRealizados 
		$this->tarjeta->viajes = 3;
		$viajes = $this->tarjeta->viajesRealizados();
		$this->assertEquals($viajes, $this->tarjeta->viajes);

		//Test Función Primer Pasaje Plus
		$bondi1 = new Colectivo("131 Único", "Semtur");
		$fecha1 = "2016/11/04 10:00";
		$this->tarjeta->monto = 7;
		$this->tarjeta->pagar($bondi1, $fecha1);
		$plus = 1;
		$this->assertEquals($plus, $this->tarjeta->plus);

		//Test Función 2do Pasaje Plus
		$bondi2 = new Colectivo("132 Único", "Semtur");
		$fecha2 = "2016/11/04 12:00";
		$this->tarjeta->monto = 0;
		$this->tarjeta->pagar($bondi2, $fecha2);
		$plus = 2;
		$this->assertEquals($plus, $this->tarjeta->plus);

		//Test Función 2do Pasaje Plus y sin saldo para pagarlos
		$bondi3 = new Colectivo("115 Único", "Semtur");
		$fecha3 = "2016/11/04 14:00";
		$this->tarjeta->monto = 0;
		$retorno = $this->tarjeta->pagar($bondi3, $fecha3);
		$this->assertEquals("Saldo insuficiente", $retorno);

		

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
