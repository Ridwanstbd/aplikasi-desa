<?php

namespace App\Http\Controllers;

use App\Models\VetConsultation;
use Illuminate\Http\Request;

class VetConsultationController extends Controller
{
    // Menampilkan daftar konsultasi
    public function index()
    {
        $consultations = VetConsultation::all();
        return view('pages.admin.vet_consultations.index', compact('consultations'));
    }

    // Menyimpan konsultasi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'consultation_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        VetConsultation::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Konsultasi berhasil dikirim. Silakan menunggu balasan kami.');
    }

    // Menghapus konsultasi
    public function destroy($id)
    {
        $consultation = VetConsultation::findOrFail($id);
        $consultation->delete();
        return redirect()->route('vet-consult.index')->with('success', 'Konsultasi berhasil dihapus.');
    }
}
