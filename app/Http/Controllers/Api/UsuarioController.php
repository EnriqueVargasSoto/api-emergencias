<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function login(Request $request) {

    }

    public function registro(Request $request) {

        $data = $request->json()->all();

        $image = $this->getB64Image($data['image']);

        $urltemp = "avatar/".$data['nombres'].time().'foto' . '_avatar'.'.jpeg';

        //$ruta = public_path("avatar/");

        $img = \Image::make($image)->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('img/' . $urltemp);

        $usuario = User::where('email', $data['email'])->first();

        $user = User::create([
            'idRol' => 2,
            'nombres' => $data['nombres'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image' => $urltemp
        ]);

        if ($usuario == null) {
            $user = User::create([
                'idRol' => 2,
                'nombres' => $data['nombres'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'image' => $urltemp
            ]);
            $respuesta = [
                'codigo' => '200',
                'data' => $user
            ];
        } else {
            $respuesta =  [
                'codigo' => '500',
                'data' => 'El correo ya fue registrado'
            ];
        }
        

        return json_encode($respuesta);

    }

    public function getB64Extension($base64_image, $full=null){  
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"        
        preg_match("/^data:image\/(.*);base64/i",$base64_image, $img_extension);   
        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        return ($full) ?  $img_extension[0] : $img_extension[1];  
    }

    public function getB64Image($base64_image){  
        // Obtener el String base-64 de los datos         
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen        
        $image = base64_decode($image_service_str);   
        // Retornamos el string decodificado
        return $image; 
        
        
   }
}
