<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        try {
            Log::info('Booking request received', $request->all());
            
            // Базовые правила валидации
            $rules = [
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'hours' => 'required|in:1,2,3,4',
            ];
            
            // Добавляем правила для коньков только если они переданы
            if ($request->has('skate_id') && !empty($request->skate_id)) {
                $rules['skate_id'] = 'required|exists:skates,id';
                $rules['skate_size'] = 'required|integer|min:30|max:47';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::warning('Booking validation failed', ['errors' => $validator->errors()]);
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Подготовка данных для создания бронирования
            $bookingData = [
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'hours' => $request->hours,
                'is_paid' => false
            ];
            
            // Добавляем данные о коньках если они есть
            if ($request->has('skate_id') && !empty($request->skate_id)) {
                // Проверяем наличие коньков
                $skate = Skate::find($request->skate_id);
                if (!$skate) {
                    return response()->json(['error' => 'Выбранные коньки не найдены'], 422);
                }
                if ($skate->quantity < 1) {
                    return response()->json(['error' => 'Выбранные коньки отсутствуют в наличии'], 422);
                }
                
                $bookingData['skate_id'] = $request->skate_id;
                $bookingData['skate_size'] = $request->skate_size;
            }

            // Создание бронирования
            $booking = Booking::create($bookingData);
            
            // Рассчитываем сумму
            $booking->total_amount = $booking->calculateTotal();
            $booking->save();

            Log::info('Booking created', [
                'booking_id' => $booking->id, 
                'total' => $booking->total_amount,
                'has_skates' => !is_null($booking->skate_id)
            ]);

            // Уменьшаем количество коньков если они выбраны
            if ($booking->skate_id) {
                $skate = Skate::find($booking->skate_id);
                $skate->quantity -= 1;
                $skate->save();
                Log::info('Skate quantity updated', [
                    'skate_id' => $skate->id, 
                    'new_quantity' => $skate->quantity
                ]);
            }

            // Создание платежа
            $payment = $this->paymentService->createPayment(
                $booking->total_amount,
                'Оплата бронирования катка',
                $booking
            );

            return response()->json([
                'success' => true,
                'payment_url' => $payment->getConfirmation()->getConfirmationUrl(),
                'booking_id' => $booking->id
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Booking error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Произошла ошибка при бронировании: ' . $e->getMessage()], 500);
        }
    }
}