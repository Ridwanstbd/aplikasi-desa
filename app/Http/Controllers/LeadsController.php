<?php

namespace App\Http\Controllers;

use App\Models\Leads;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    public function index() {
        $leads = Leads::latest()->get();
        return view('pages.admin.leads.index',compact('leads'));
    }
    public function destroy($id) {
        Leads::find($id)->delete();
        return redirect()->route('leads.index')->with('success','Leads Berhasil dihapus!');
    }
}
