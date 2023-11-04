<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ruta = '/storage/images/';

        $data = [
            'home_image' => $ruta . 'home_image.jpg',
            'home_description' => 'Somos una empresa de diseño y construcción dedicada a hacerle la vida fácil a nuestros clientes.',
            'first_image' => $ruta . 'first_image.jpg',
            'second_image' => $ruta . 'second_image.jpg',
            'third_image' => $ruta . 'third_image.jpg',
            'about_us_description' => '
            MS Ingeniería Diseño y Construcción fue fundada en el año 2016 por el ingeniero en construcción Marco Salas, quien inició sus labores profesionales en infraestructura para desarrollos turísticos y habitacionales. Luego se trasladó a Panamá donde estuvo por más de 8 años realizando trabajos en proyectos de generación de energía.

            MS tiene operación en Costa Rica Y Panamá. Gracias a la amplia experiencia de nuestro fundador, realizamos en Panamá trabajos de mantenimiento de hidroeléctricas. En Costa Rica por otro lado, nos dedicamos al diseño y construcción de proyectos habitacionales y comercio, siempre con el afán de ayudar a nuestros clientes a realizar sus sueños de forma fácil y exitosa.

            Para nosotros nuestro trabajo significa: compromiso, calidad, innovación, honestidad, trabajo en equipo y sobre todo, servicio al cliente.
            ',
            'about_image' => $ruta . 'about_image.jpg'
        ];

        DB::table('configs')->insert($data);
    }
}
