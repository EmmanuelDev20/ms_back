<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_translations = [
            [
            'locale' => 'es',
            'config_id' => 1,
            'home_description' => 'Somos una empresa de diseño y construcción dedicada a hacerle la vida fácil a nuestros clientes.',
            'about_us_description' => '
            MS Ingeniería Diseño y Construcción fue fundada en el año 2016 por el ingeniero en construcción Marco Salas, quien inició sus labores profesionales en infraestructura para desarrollos turísticos y habitacionales. Luego se trasladó a Panamá donde estuvo por más de 8 años realizando trabajos en proyectos de generación de energía.

            MS tiene operación en Costa Rica Y Panamá. Gracias a la amplia experiencia de nuestro fundador, realizamos en Panamá trabajos de mantenimiento de hidroeléctricas. En Costa Rica por otro lado, nos dedicamos al diseño y construcción de proyectos habitacionales y comercio, siempre con el afán de ayudar a nuestros clientes a realizar sus sueños de forma fácil y exitosa.

            Para nosotros nuestro trabajo significa: compromiso, calidad, innovación, honestidad, trabajo en equipo y sobre todo, servicio al cliente.
            ',
            ],
            [
            'locale' => 'en',
            'config_id' => 1,
            'home_description' => 'English Somos una empresa de diseño y construcción dedicada a hacerle la vida fácil a nuestros clientes.',
            'about_us_description' => '
            English MS Ingeniería Diseño y Construcción fue fundada en el año 2016 por el ingeniero en construcción Marco Salas, quien inició sus labores profesionales en infraestructura para desarrollos turísticos y habitacionales. Luego se trasladó a Panamá donde estuvo por más de 8 años realizando trabajos en proyectos de generación de energía.

            MS tiene operación en Costa Rica Y Panamá. Gracias a la amplia experiencia de nuestro fundador, realizamos en Panamá trabajos de mantenimiento de hidroeléctricas. En Costa Rica por otro lado, nos dedicamos al diseño y construcción de proyectos habitacionales y comercio, siempre con el afán de ayudar a nuestros clientes a realizar sus sueños de forma fácil y exitosa.

            Para nosotros nuestro trabajo significa: compromiso, calidad, innovación, honestidad, trabajo en equipo y sobre todo, servicio al cliente.
            ',
            ]
        ];

        DB::table('config_translations')->insert($data_translations);
    }
}
