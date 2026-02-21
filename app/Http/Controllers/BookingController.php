<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
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
            'phone' => 'required|string|max:20',
            'hours' => 'required|in:1,2,3,4',
            'skate_id' => 'nullable|exists:skates,id',
            'skate_size' => 'required_if:skate_id,!=,null|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking = Booking::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'hours' => $request->hours,
            'skate_id' => $request->skate_id,
            'skate_size' => $request->skate_size,
            'is_paid' => false
        ]);

        $booking->total_amount = $booking->calculateTotal();
        $booking->save();

        if ($request->skate_id) {
            $skate = Skate::find($request->skate_id);
            $skate->quantity -= 1;
            $skate->save();
        }

        $payment = $this->paymentService->createPayment(
            $booking->total_amount,
            'Оплата бронирования катка',
            $booking
        );

        return response()->json([
            'payment_url' => $payment->getConfirmation()->getConfirmationUrl(),
            'booking_id' => $booking->id
        ]);
    }
}