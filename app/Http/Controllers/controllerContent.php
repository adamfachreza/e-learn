<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facaces\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use illuminate\Http\Response; // buat nerima respond dari inputan
use Illuminate\Support\Facades\Validator;// librari untuk validasi inputan
use Illuminate\Contracts\Encryption\DecryptException;
use App\modelAdmin;
use App\modelContent;

class controllerContent extends Controller
{
    public function tambahContent(Request $request){
        $validator = Validator::make($request-> all(),[
            'judul' => 'required | unique:contents,judul',
            'keterangan' => 'required',
            'link_thumbnail' => 'required',
            'link_video' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }

        //admin login dulu baru bisa tambah content
        $token = $request->token;
        $tokenDb = modelAdmin::where('token',$token)->count();
        if($tokenDb > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;

            if($decoded_array['extime'] > time()){
                if(modelContent::create([
                    'judul' => $request -> judul,
                    'keterangan' => $request -> keterangan,
                    'link_thumbnail' => $request -> link_thumbnail,
                    'link_video' => $request -> link_video,

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

}
