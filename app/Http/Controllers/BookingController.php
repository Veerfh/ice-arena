<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use App\Services\PaymentService;
use Illuminate\Http\Request;
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
            Log::info('Booking purchase started', $request->all());

            // Базовая валидация
            $rules = [
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'hours' => 'required|in:1,2,3,4',
            ];
            
            // Добавляем валидацию для коньков, если они выбраны
            if ($request->has('skate_id') && !empty($request->skate_id)) {
                $rules['skate_id'] = 'required|exists:skates,id';
                $rules['skate_size'] = 'required|integer|min:26|max:47';
            }

            $request->validate($rules);

            // Рассчитываем сумму
            $totalAmount = 300; // Входной билет
            if ($request->has('skate_id') && !empty($request->skate_id)) {
                // Проверяем наличие коньков
                $skate = Skate::find($request->skate_id);
                if (!$skate) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Выбранные коньки не найдены'
                    ], 422);
                }
                if ($skate->quantity < 1) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Выбранные коньки отсутствуют в наличии'
                    ], 422);
                }
                
                $totalAmount += 150 * (int)$request->hours;
            }

            // Создаем бронирование
            $bookingData = [
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'hours' => (int)$request->hours,
                'total_amount' => $totalAmount,
                'is_paid' => false
            ];
            
            // Добавляем данные о коньках если они есть
            if ($request->has('skate_id') && !empty($request->skate_id)) {
                $bookingData['skate_id'] = $request->skate_id;
                $bookingData['skate_size'] = $request->skate_size;
            }

            $booking = Booking::create($bookingData);

            Log::info('Booking created', [
                'id' => $booking->id,
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

            // Создаем платеж
            $payment = $this->paymentService->createPayment(
                $booking->total_amount,
                'Бронирование катка' . ($booking->skate_id ? ' с коньками' : ''),
                $booking
            );

            // Получаем URL для редиректа
            $paymentUrl = $payment->confirmation->confirmation_url;

            Log::info('Payment created', ['url' => $paymentUrl]);

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl,
                'booking_id' => $booking->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Booking validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Booking purchase error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Ошибка при создании бронирования: ' . $e->getMessage()
            ], 500);
        }
    }
}