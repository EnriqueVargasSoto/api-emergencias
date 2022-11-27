<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function login(Request $request) {

        $data = $request->json()->all();

        $user = User::where('email', $data['email'])->first();

        if (isset($user->id)) {
            if (Hash::check($data['password'], $user->password)) {
                //$token = $user->createToken("auth_token")->plainTextToken;
                
                $respuesta = [
                    'codigo' => '200',
                    'data' => $user
                ];
            } else {
                $respuesta = [
                    'codigo' => '500',
                    'data' => 'La contraseña es incorrecta.'
                ];
            }
        } else {
            $respuesta = [
                'codigo' => '500',
                'data' => 'El usuario no existe.'
            ];
        }

        return json_encode($respuesta);

    }

    public function registro(Request $request) {

        $data = $request->json()->all();

        $return $data;

        $image = $this->getB64Image($data['image']);

        $extension = $this->getB64Extension($foto);

        $urltemp = "avatar/".$data['nombres'].'foto_avatar'.$extension;//'.jpeg';

        $img = \Image::make($image)->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('img/' . $urltemp);

        $usuario = User::where('email', $data['email'])->first();

        if (!$usuario) {
            $user = User::create([
                'idRol' => 2,
                'nombres' => $data['nombres'],
                'email' => $data['email'],
                'estado' => 'activo',
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
                'data' => 'El correo ya fue registrado.'
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

    public function updateUsuario(Request $request) {

        $data = $request->json()->all();

        if ($data['image'] != '') {
            $image = $this->getB64Image($data['image']);

            $urltemp = "avatar/".$data['nombres'].time().'foto' . '_avatar'.'.jpeg';

            $img = \Image::make($image)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('img/' . $urltemp);

        }
        
        $usuario = User::find($data['idUsuario']);

        if ($usuario) {

            $usuario->nombres = $data['nombres'];
            if ($data['image'] != '') {
                $usuario->image = $urltemp;
            }
            $usuario->password = Hash::make($data['password']);
            $usuario->save();

            $respuesta = [
                'codigo' => '200',
                'data' => $usuario
            ];

        } else {
            
            $respuesta =  [
                'codigo' => '500',
                'data' => 'Hubo un problema al actualizar tus datos.'
            ];

        }
        
        return json_encode($respuesta);

    }
}
