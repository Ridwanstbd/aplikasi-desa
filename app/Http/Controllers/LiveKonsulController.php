<?php

namespace App\Http\Controllers;

use App\Models\LiveConsult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LiveKonsulController extends Controller
{
    public function index(){
        return view("pages.live-consultation.index");
    }
    public function store(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'address' => 'required|string',
            'user_whatsapp' => 'required|string|max:15',
            'name_kandang' => 'required|string|max:255',
            'jenis_hewan' => 'required|string|max:255',
            'data_pembelian' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Format nomor WhatsApp (menambahkan +62)
            $whatsapp = $request->user_whatsapp;
            if (substr($whatsapp, 0, 1) === '0') {
                $whatsapp = substr($whatsapp, 1);
            }
            $request->merge(['user_whatsapp' => '62' . $whatsapp]);

            // Simpan data
            LiveConsult::create($request->all());

            return redirect()
                ->route('live.konsul')
                ->with('success', 'Konsultasi berhasil dikirim!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan! Silakan coba lagi.')
                ->withInput();
        }
    }
    public function stored(){
        $liveConsults = LiveConsult::latest()->get();
        return view("pages.admin.live-consultation.index",compact("liveConsults"));
    }
    public function delete($id){
        try {
            $consultation = LiveConsult::findOrFail($id);
            $consultation->delete();

            return redirect()
                ->route('admin.live-konsul')
                ->with('success', 'Data konsultasi berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data konsultasi!');
        }        
    }
}
