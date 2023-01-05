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

class controllerAdmin extends Controller
{
    public function tambahAdmin(Request $request){
        $validator = Validator::make($request-> all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'token' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }

        if(modelAdmin::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => encrypt($request -> password)
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

        $token = $request->token;
        $tokenDb = modelAdmin::where('token',$token)->count();
        if($tokenDb > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, $key, array('HS256'));
            $decoded_array = (array) $decoded;

            if($decoded_array['extime'] > time()){
                if(modelAdmin::create([
                    'name' => $request -> name,
                    'email' => $request -> email,
                    'password' => encrypt($request -> password)
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
            }else{
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Token Kadaluarsa'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Token Tidak Valid'
            ]);
        }
    }

    public function loginAdmin(Request $request){
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

        $cek = modelAdmin::where('email', $request->email)->count();
        $admin = modelAdmin::where('email', $request->email)->get();

        if($cek > 0){
            foreach($admin as $adm){
                if($request->password == decrypt($adm->password)){
                    $key = env('APP_KEY');
                    $data = array(
                        "extime" => time()+(60*120),
                        "id" => $adm->id,
                    );
                    $jwt = JWT::encode($data,$key,'HS256');

                    modelAdmin::where('id',$adm->id)->update([
                        'token' => $jwt
                    ]);
                    return response()->json([
                        'status' => 'berhasil',
                        'message' => 'Berhasil Login',
                        'token' => $jwt
                    ]);
                }else{
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Password Salah'
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

    public function hapusAdmin(Request $request){
        $validator = Validator::make($request-> all(),[
            'id' => 'required',
            'token' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }

        $token = $request->token;
        $tokenDb = modelAdmin::where('token',$token)->count();
        if($tokenDb > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;

            if($decoded_array['extime'] > time()){
                if(modelAdmin::where('id', $request->id)->delete()){
                    return response()->json([
                    'status' => 'berhasil',
                    'message' => 'Data Berhasil Dihapus'
                ]);
                }else{
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Data Gagal Dihapus'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Token Kadaluarsa'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Token Tidak Valid'
            ]);
        }
    }


}
