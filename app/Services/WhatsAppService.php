<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp Message using Fonnte API or similar
     * 
     * @param string $target Target phone number (e.g. 08123456789)
     * @param string $message Message content
     * @return array Response from provider
     */
    public static function sendMessage($target, $message)
    {
        // Ambil data dari tabel Setting (UI Management)
        $apiKey = \App\Models\Setting::get('whatsapp_api_key');
        
        if (empty($apiKey)) {
            // Fallback ke config/env jika di database kosong
            $apiKey = config('services.whatsapp.api_key');
        }
        
        if (empty($apiKey)) {
            Log::warning('WhatsApp API Key is empty (both DB & Env). Message not sent: ' . $message);
            return ['status' => false, 'reason' => 'API Key empty'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'delay' => '2',
                'countryCode' => '62', // Indonesia
            ]);

            Log::info('WhatsApp Notification Sent to ' . $target, [
                'response' => $response->json()
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Error: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }
}
