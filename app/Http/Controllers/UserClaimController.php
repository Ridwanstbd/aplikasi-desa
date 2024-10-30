<?php

namespace App\Http\Controllers;

use App\Models\CsRotation;
use App\Models\CustomerService;
use App\Models\Voucher;
use Illuminate\Http\Request;

class UserClaimController extends Controller
{
    public function show($slug)
    {
        $voucher = Voucher::where('slug', $slug)->first();

        return view('pages.claim-voucher.index', compact('voucher'));
    }

    public function claim(Request $request, $slug)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_whatsapp' => 'required|string|max:20',
        ]);

        $voucher = Voucher::where('slug', $slug)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        $voucher->userClaims()->create([
            'user_name' => $request->input('user_name'),
            'user_whatsapp' => $request->input('user_whatsapp'),
        ]);

        $rotation = CsRotation::first();
        if (!$rotation) {
            $rotation = CsRotation::create(['current_cs_id' => 1]);
        }
        $customerService = CustomerService::find($rotation->current_cs_id);
        if (!$customerService) {
            return redirect()->back()->with('error', 'Customer service tidak ditemukan.');
        }
        $minCsId = CustomerService::min('id');

        $nextCsId = CustomerService::where('id', '>', $rotation->current_cs_id)
            ->min('id');
        if (!$nextCsId) {
            $nextCsId = $minCsId;
        }

        $rotation->update(['current_cs_id' => $nextCsId]);
        $whatsappMessage = 'Halo Kak ' . $customerService->name . " saya mau klaim voucher loyalty card DISKON ONGKIR dan FREE GIFT SPESIAL, mohon dibantu\u{00A0}proses\u{00A0}ya \\n" . $slug;

        $whatsappUrl = 'https://wa.me/' . $customerService->phone . '?text=' . urlencode($whatsappMessage);

        return redirect($whatsappUrl);
    }
}
