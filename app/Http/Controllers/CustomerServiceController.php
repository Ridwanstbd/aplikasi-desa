<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    public function index() {
        $customerServices = CustomerService::latest()->get();
        return view('pages.admin.customer-services.index', compact('customerServices'));
    }
    public function create(Request $request) {
        $cs = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric',
        ]);
        CustomerService::create($cs);
        return redirect()->route('customer-service.index')->with('success','CS Berhasil ditambahkan!');
    }
    public function update(Request $request,$id) {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);
        try {
            $cs = CustomerService::find($id);
            $cs->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
            return redirect()->route('customer-service.index')->with('success', 'CS berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function destroy($id) {
        CustomerService::find($id)->delete();
        return redirect()->route('customer-service.index')->with('success', 'CS berhasil dihapus.');
    }
}
