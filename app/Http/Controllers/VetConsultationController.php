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
        return view('vet_consultations.index', compact('consultations'));
    }

    // Menyimpan konsultasi baru
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'consultation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        VetConsultation::create($request->all());
        return redirect()->back()->with('success', 'Konsultasi berhasil ditambahkan. Akan Kami hubuungi Kembali dalam 1x24 jam.');
    }

    // Menampilkan form untuk mengedit konsultasi
    public function edit($id)
    {
        $consultation = VetConsultation::findOrFail($id);
        return view('vet_consultations.edit', compact('consultation'));
    }

    // Memperbarui konsultasi yang ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:15',
            'consultation_date' => 'date',
            'notes' => 'nullable|string',
        ]);

        $consultation = VetConsultation::findOrFail($id);
        $consultation->update($request->all());
        return redirect()->route('vet_consultations.index')->with('success', 'Konsultasi berhasil diperbarui.');
    }

    // Menghapus konsultasi
    public function destroy($id)
    {
        $consultation = VetConsultation::findOrFail($id);
        $consultation->delete();
        return redirect()->route('vet_consultations.index')->with('success', 'Konsultasi berhasil dihapus.');
    }
}
