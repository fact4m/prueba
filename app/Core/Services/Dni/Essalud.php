<?php

namespace App\Core\Services\Dni;

use App\Core\Services\Helpers\Functions;
use App\Core\Services\Models\Person;
use GuzzleHttp\Client;

class Essalud
{
    public static function search($number)
    {
        if (strlen($number) !== 8) {
            return [
                'success' => false,
                'message' => 'DNI tiene 8 digitos.'
            ];
        }

        $client = new  Client(['base_uri' => 'https://ww1.essalud.gob.pe/sisep/postulante/postulante/']);
        $response = $client->request('GET', 'postulante_obtenerDatosPostulante.htm?strDni='.$number);
        if ($response->getStatusCode() == 200 && $response != "") {
            $json = (object) json_decode($response->getBody()->getContents(), true);
            $data_person = $json->DatosPerson[0];
            if (isset($data_person) && count($data_person) > 0 &&
                strlen($data_person['DNI']) >= 8 && $data_person['Nombres'] !== '') {
                $person = new Person();
                $person->name = $data_person['ApellidoPaterno'].' '.$data_person['ApellidoMaterno'].', '.$data_person['Nombres'];
                $person->number = $data_person['DNI'];
                $person->verification_code = Functions::verificationCode($data_person['DNI']);
                $person->first_name = $data_person['ApellidoPaterno'];
                $person->last_name = $data_person['ApellidoMaterno'];
                $person->names = $data_person['Nombres'];
                $person->date_of_birthday = $data_person['FechaNacimiento'];
                $person->sex = ((string)$data_person['Sexo'] === '2')?'Masculino':'Femenino';
                $person->voting_group = null;

                return [
                    'success' => true,
                    'data' => $person
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Datos no encontrados.'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Coneccion fallida.'
        ];
    }
}
?>