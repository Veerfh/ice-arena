<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        // Получаем последний билет
        $ticket = Ticket::latest()->first();
        
        if ($ticket && $ticket->payment_id) {
            // Проверяем статус платежа напрямую в ЮKassa
            $status = $this->checkPaymentStatus($ticket->payment_id);
            
            if ($status === 'succeeded') {
                $ticket->is_paid = true;
                $ticket->payment_status = 'succeeded';
                $ticket->save();
            }
        
        } else {
            $accessCode = 'Ошибка получения билета';
        }
        
        return view('payment.success');
    }
    
    private function checkPaymentStatus($paymentId)
    {
        $shopId = '1256411';
        $secretKey = 'test_GwDYsNcaphOsEjGywVZdGm3XQk8Se4p_9bryt8XiKnI';
        
        $response = Http::withBasicAuth($shopId, $secretKey)
            ->get("https://api.yookassa.ru/v3/payments/{$paymentId}");
        
        if ($response->successful()) {
            $payment = $response->json();
            return $payment['status'];
        }
        
        return 'unknown';
    }
}