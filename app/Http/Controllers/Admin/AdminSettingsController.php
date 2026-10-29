<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\WhatsAppApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    public function __construct(protected WhatsAppApiService $whatsappService)
    {
    }

    /**
     * Tampilkan status konfigurasi resmi integrasi WhatsApp Cloud API dan Email SMTP.
     */
    public function whatsapp(): View
    {
        $whatsappStatus = $this->whatsappService->getConfigurationStatus();
        $webhookUrl = url('/api/v1/whatsapp/webhook');

        $mailConfig = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        return view('admin.settings.whatsapp', compact('whatsappStatus', 'webhookUrl', 'mailConfig'));
    }
}
