<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    private $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = env('WHATSAPP_BOT_URL', 'http://localhost:3000');
    }

    public function getStatus()
    {
        try {
            $response = Http::get("{$this->baseUrl}/status");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }

    public function sendMessage($phone, $message)
    {
        try {
            $response = Http::post("{$this->baseUrl}/send-message", [
                'phone' => $phone,
                'message' => $message
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}