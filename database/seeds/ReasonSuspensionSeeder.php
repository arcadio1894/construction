<?php

use Illuminate\Database\Seeder;
use \App\ReasonSuspension;

class ReasonSuspensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReasonSuspension::create([
            'reason' => 'Faltar injustificadamente al trabajo.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Faltar su palabra de compromiso a sus superiores y/o colaboradores.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Utilizar materiales de oficina para afines ajenos al trabajo y al uso destinado.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Dedicarse a trabajos particulares dentro del centro de trabajo.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Reiterada comision de faltas que determinen amonestación (tardanzas, EPP, etc).',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Alterar el orden de la organización mediante altercados, riñas o peleas.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Desobedecer a su jefe inmediato o Jefe de Recursos Humanos en el cumplimiento de las labores encomendadas.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Manejar u operar equipos, maquinarias o vehículos sin estar debidamente autorizado.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'No permitir o encubrir la revisión de paquetes o maletines en la puerta de ingreso y/o salida del Centro de Trabajo por el personal encargado de la custodia el mismo.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'No portar diariamente su Documento Nacional de Identidad (DNI).',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'No devolver oportunamente los valores que la empresa le hubiera entregado para el desempeño de labores.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'No usar lentes oscuros o claros, zapatos de punta cero, guantes, careta, casco de seguridad, arnés de seguridad y respiradores.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Realizar eventual juegos durante la jornada laboral que pueden originar la distracción del personal.',
            'days' => 1,
        ]);
        ReasonSuspension::create([
            'reason' => 'Dejar máquinas y/o herramientas encendidas después de haber concluida su labor.',
            'days' => 1,
        ]);

        ReasonSuspension::create([
            'reason' => 'Falsificar la firma se algún compañero de trabajo en cualquier tipo de documento y/o registro de planilla.',
            'days' => 2,
        ]);
        ReasonSuspension::create([
            'reason' => 'Faltar el respeto a cualquier compañero de manera verbal o escrita.',
            'days' => 2,
        ]);
        ReasonSuspension::create([
            'reason' => 'Faltar el respeto a los compañeros de trabajo con tocamientos indebidos.',
            'days' => 2,
        ]);
        ReasonSuspension::create([
            'reason' => 'Realizar tomas fotográficas a las máquinas y equipos que pertenezcan a la empresa.',
            'days' => 2,
        ]);
        ReasonSuspension::create([
            'reason' => 'Difundir o divulgar información, al interior o exterior de la empresa que atente contra la imagen de la misma, de sus directivos o del personal.',
            'days' => 2,
        ]);
        ReasonSuspension::create([
            'reason' => 'Divulgar información confidencial o reservada , a la cual haya tenido acceso en el desarrollo de sus funciones o por terceros.',
            'days' => 2,
        ]);
    }
}
