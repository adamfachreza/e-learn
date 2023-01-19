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
use App\Peserta;
use Symfony\Component\Console\Input\Input;

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

    public function ubahContent(Request $request){
        $validator = Validator::make($request-> all(),[
            'judul' => 'required | unique:contents,judul,'.$request->id.',id',
            'keterangan' => 'required',
            'link_thumbnail' => 'required',
            'link_video' => 'required',
            'id' => 'required'
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
                if(modelContent::where('id',$request->id)->update([
                    'judul' => $request -> judul,
                    'keterangan' => $request -> keterangan,
                    'link_thumbnail' => $request -> link_thumbnail,
                    'link_video' => $request -> link_video,

                ])){
                    return response()->json([
                    'status' => 'berhasil',
                    'message' => 'Data Berhasil Diubah'
                ]);
                }else{
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Data Gagal Diubah'
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

    public function hapusContent(Request $request){
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

        //admin login dulu baru bisa tambah content
        $token = $request->token;
        $tokenDb = modelAdmin::where('token',$token)->count();
        if($tokenDb > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;

            if($decoded_array['extime'] > time()){
                if(modelContent::where('id',$request->id)->delete()){
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

    public function listContent(Request $request){
        $validator = Validator::make($request-> all(),[
            'token' => 'required'
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
                $content = modelContent::get();

                return response()->json([
                    'status' => 'berhasil',
                    'message' => 'Data Berhasil Diambil',
                    'data' => $content
                ]);
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

    public function listContentPeserta(Request $request){
        $validator = Validator::make($request-> all(),[
            'token' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }

        //admin login dulu baru bisa tambah content
        $token = $request->token;
        $tokenDb = Peserta::where('token',$token)->count();
        if($tokenDb > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;

            if($decoded_array['extime'] > time()){
                $content = modelContent::get();

                return response()->json([
                    'status' => 'berhasil',
                    'message' => 'Data Berhasil Diambil',
                    'data' => $content
                ]);
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

    public function cariContent(Request $request){
        $validator = Validator::make($request-> all(),[
            'token' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'gagal',
                'message' => $validator->messages()
            ]);
        }
        $token = $request->token;
        $tokenDb = Peserta::where('token', $token)->count();
        if($tokenDb>0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            if ($decoded_array['extime'] > time()) {
                $cari = $request->cari;
                $content = modelContent::where('judul','like',"%$cari%")->get();
                return response()->json([
                    'status' => 'berhasil',
                    'message' => 'data berhasil diambil',
                    'content' => $content,
                ]);
            }else{
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Token Kadaluarsa',
                ]);
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Token Tidak Valid',
            ]);
        }
    }

}
