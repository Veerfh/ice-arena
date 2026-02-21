<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ticket = Ticket::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_paid' => false
        ]);

        $payment = $this->paymentService->createPayment(300, 'Оплата входного билета', $ticket);

        return response()->json([
            'payment_url' => $payment->getConfirmation()->getConfirmationUrl(),
            'ticket_id' => $ticket->id
        ]);
    }
}