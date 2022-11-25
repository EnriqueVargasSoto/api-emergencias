<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\emergencia;

class EmergenciaController extends Controller
{
    public function btnEmergencia(Request $request) {
        
        $data = $request->json()->all();

        $emergencia = Emergencia::create([
            'idUsuario' => $data['idUsuario'],
            'lat' => $data['lat'],
            'lon' => $data['lon'],
            'estado' => 'activo'
        ]);

        if ($emergencia) {
            $respuesta = [
                'codigo' => '200',
                'data' => 'Su emergencia fue creada, se notificará a sus contactos elegidos.'
            ];
        } else {
            $respuesta =  [
                'codigo' => '500',
                'data' => 'La emergencia no fue creada.'
            ];
        }
        
        return json_encode($respuesta);
    }

    public function listEmergencia($id) {

        $emergencias = Emergencia::where('idUsuario', $id)->get();

        $respuesta = [
            'codigo' => '200',
            'data' => $emergencias
        ];

        return json_encode($respuesta);
    }
}
