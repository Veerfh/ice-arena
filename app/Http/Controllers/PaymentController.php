<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        // Получаем последний билет
        $ticket = Ticket::latest()->first();
        
        // Получаем последнее бронирование
        $booking = Booking::latest()->first();
        
        // Обрабатываем билет если есть
        if ($ticket && $ticket->payment_id) {
            // Проверяем статус платежа напрямую в ЮKassa
            $status = $this->checkPaymentStatus($ticket->payment_id);
            
            if ($status === 'succeeded') {
                $ticket->is_paid = true;
                $ticket->payment_status = 'succeeded';
                $ticket->save();
                
                Log::info('Ticket payment succeeded', ['ticket_id' => $ticket->id]);
            }
        }
        
        // Обрабатываем бронирование если есть
        if ($booking && $booking->payment_id) {
            // Проверяем статус платежа напрямую в ЮKassa
            $status = $this->checkPaymentStatus($booking->payment_id);
            
            if ($status === 'succeeded') {
                $booking->is_paid = true;
                $booking->payment_status = 'succeeded';
                $booking->save();
                
                Log::info('Booking payment succeeded', ['booking_id' => $booking->id]);
            }
        }
        
        return view('payment.success');
    }
    
    private function checkPaymentStatus($paymentId)
    {
        $shopId = '1256411';
        $secretKey = 'test_GwDYsNcaphOsEjGywVZdGm3XQk8Se4p_9bryt8XiKnI';
        
        try {
            $response = Http::withBasicAuth($shopId, $secretKey)
                ->get("https://api.yookassa.ru/v3/payments/{$paymentId}");
            
            if ($response->successful()) {
                $payment = $response->json();
                return $payment['status'];
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
        }
        
        return 'unknown';
    }
}