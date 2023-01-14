<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facaces\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use illuminate\Http\Response; // buat nerima respond dari inputan
use Illuminate\Support\Facades\Validator;// librari untuk validasi inputan
use Illuminate\Contracts\Encryption\DecryptException; // buat encryp decrypt
use App\modelAdmin;
use App\Peserta;

class controllerPeserta extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request-> all(),[
            'nama' => 'required',
            'email' => 'required|unique:pesertas,email',
            'password' => 'required | confirmed',
            'password_confirmation' => 'required',
            // 'token' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }
                if(Peserta::create([
                    'nama' => $request -> nama,
                    'email' => $request -> email,
                    'password' => encrypt($request -> password),

                ])){
                    return response()->json([
                    'status' => 'berhasil',
                    'message' => 'Data Berhasil Disimpan'

                ]);
                }else{
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Data Gagal Disimpan'
                    ]);
                }
    }

    public function loginPeserta(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }

        $cek = Peserta::where('email', $request->email)->count();
        $peserta = Peserta::where('email', $request->email)->get();

        if($cek>0){
            foreach($peserta as $psrt){
                if($request->password == decrypt($psrt->password)){
                    $key = env('APP_KEY');
                    $data = array(
                        "extime" => time()+(60*120),
                        "id" => $psrt->id,
                    );

                    $jwt = JWT::encode($data,$key,'HS256');

                    Peserta::where('id', $psrt->id)->update([
                        'token' => $jwt,
                        'status' => 1
                    ]);

                    return response()->json([
                        'status' => 'berhasil',
                        'message' => 'Berhasil Login',
                        'token' => $jwt,
                    ]);
                }else{
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Gagal Login',
                    ]);
                }
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Email atau Password Salah'
            ]);
        }
    }
}
