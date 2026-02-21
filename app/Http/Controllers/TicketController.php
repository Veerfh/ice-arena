<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        try {
            Log::info('Ticket purchase started', $request->all());

            // Валидация
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string',
            ]);

            // Создаем билет
            $ticket = Ticket::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_paid' => false
            ]);

            Log::info('Ticket created', ['id' => $ticket->id]);

            // Создаем платеж
            $payment = $this->paymentService->createPayment(300, 'Билет на каток', $ticket);

            // Получаем URL для редиректа
            $paymentUrl = $payment->confirmation->confirmation_url;

            Log::info('Payment created', ['url' => $paymentUrl]);

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Ticket purchase error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}