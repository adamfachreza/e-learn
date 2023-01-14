<?php

namespace App\Http\Controllers;

use App\Jawaban;
use App\Peserta;
use App\Skor;
use App\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facaces\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use illuminate\Http\Response; // buat nerima respond dari inputan
use Illuminate\Support\Facades\Validator; // librari untuk validasi inputan
use Illuminate\Contracts\Encryption\DecryptException; // buat encryp decrypt

class controllerUjian extends Controller
{
    public function listSoal(Request $request)
    {
        $token = $request->token;
        $tokenDb = Peserta::where('token', $token)->count();
        if ($tokenDb > 0) {
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            if ($decoded_array['extime'] > time()) {
                $cal_skor = Skor::where('id_peserta', $decoded_array['id'] /* dari id tabel peserta*/)->where('status', 1)->count();

                $id_s = "";

                if ($cal_skor > 0) {
                    $id_s = Skor::where('id_peserta', $decoded_array['id'])->where('status', 1)->first();
                } else {
                    Skor::create([
                        'id_peserta' => $decoded_array['id'],
                        'status' => 1
                    ]);
                    $id_s = Skor::where('id_peserta', $decoded_array['id'])->where('status', 1)->first();
                }

                $skor = Skor::where('id_peserta', $decoded_array['id'])->where('status', 1)->first();
                $jawaban = Jawaban::where('id_peserta', $decoded_array['id'])->first();

                $jum_jawaban = Jawaban::where('id_peserta', $decoded_array['id'])->where('id_skor', $skor->id)->count();

                $jumlah_soal = Soal::count(); // itung jumlah soal
                $max_rand = $jumlah_soal - 10; // ngambil 10 soal dari jumlah soal
                $mulai = rand(0, $max_rand); // ngambil soal random
                $soal = Soal::skip($mulai)->take(10)->get();

                $data = array();
                foreach ($soal as $p) {
                    $data[] = array(
                        'id_soal' => $p->id,
                        'pertanyaan' => $p->pertanyaan,
                        'opsi1' => $p->opsi1,
                        'opsi2' => $p->opsi2,
                        'opsi3' => $p->opsi3,
                        'opsi4' => $p->opsi4,
                        'jumlah_jawaban' => $jum_jawaban,
                    );
                }
                return response()->json([
                    'status' => 'berhasil',
                    'message' => 'Data Berhasil Diambil',
                    'id_skor' => $id_s->id,
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Token Kadaluarsa',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'gagal',
                'message' => 'Token Tidak Valid',
            ]);
        }
    }

    public function jawab(Request $request)
    {
        $token = $request->token;
        $tokenDb = Peserta::where('token', $token)->count();
        if ($tokenDb > 0) {
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            if ($decoded_array['extime'] > time()) {
                $soal = Soal::where('id', $request->id_soal)->get();
                foreach ($soal as $s) {
                    if ($request->jawaban == $s->jawaban) {
                        if (Jawaban::create([
                            'id_peserta' => $decoded_array['id'],
                            'id_soal' => $s->id,
                            'jawaban' => $request->jawaban,
                            'id_skor' => $request->id_skor,
                            'status_jawaban' => 1
                        ])) {
                            return response()->json([
                                'status' => 'berhasil',
                                'message' => 'Data Berhasil Disimpan',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 'berhasil',
                                'message' => 'Data Gagal Disimpan',
                            ]);
                        }
                    } else {
                        if (Jawaban::create([
                            'id_peserta' => $decoded_array['id'],
                            'id_soal' => $s->id,
                            'jawaban' => $request->jawaban,
                            'id_skor' => $request->id_skor,
                            'status_jawaban' => 0,
                        ])) {
                            return response()->json([
                                'status' => 'berhasil',
                                'message' => 'data Berhasil Disimpan',
                            ]);
                        } else {
                            return response()->json([
                                'status' => 'gagal',
                                'message' => 'Data Gagal Disimpan',
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'token kadaluarsa',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'gagal',
                'message' => 'token tidak valid',
            ]);
        }
    }

    public function hitungSkor(Request $request){
        $token = $request->token;
        $tokenDb = Peserta::where('token',$token)->count();
        if($tokenDb>0){
            $key=env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            if($decoded_array['extime']> time()){
                $id_s = Skor::where('id_peserta', $decoded_array['id_peserta'])->where('status',1)->first();
                $jawaban = Jawaban::where('status', 1)->where('id_skor', $id_s->id)->count();
                return  response()->json([
                    'status' => 'berhasil',
                    'skor' => $jawaban,
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
