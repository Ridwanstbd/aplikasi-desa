<?php

namespace App\Http\Controllers\Admin;

use App\Services\WhatsAppService;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    private $whatsappService;

    public function __construct(WhatsAppService $whatsAppService){
        $this->whatsappService = $whatsAppService;
    }

    public function index() {
        return view('pages.admin.dashboard');
    }
    public function whatsappSetup()
    {
        $status = $this->whatsappService->getStatus();
        return view('pages.admin.whatsapp-setup', compact('status'));
    }
    public function whatsappStatus()
    {
        return response()->json($this->whatsappService->getStatus());
    }
}
