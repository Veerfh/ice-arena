<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function createPayment($amount, $description, $model)
    {
        try {
            $shopId = '1256411';
            $secretKey = 'test_GwDYsNcaphOsEjGywVZdGm3XQk8Se4p_9bryt8XiKnI';
            
            $data = [
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => 'http://localhost:8000/payment/success',
                ],
                'capture' => true,
                'description' => $description,
                'metadata' => [ // Добавляем метаданные для webhook
                    'model_type' => get_class($model),
                    'model_id' => $model->id
                ]
            ];

            Log::info('Sending to YooKassa', $data);

            $ch = curl_init('https://api.yookassa.ru/v3/payments');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Idempotence-Key: ' . uniqid('', true)
            ]);
            curl_setopt($ch, CURLOPT_USERPWD, $shopId . ':' . $secretKey);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('CURL Error: ' . $error);
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                throw new \Exception('HTTP Error: ' . $httpCode . ' Response: ' . $response);
            }

            $payment = json_decode($response);
            
            Log::info('YooKassa response', ['payment' => $payment]);

            // Сохраняем информацию о платеже
            $model->payment_id = $payment->id;
            
            // ВНИМАНИЕ: Не устанавливаем is_paid = true сразу!
            // Платеж может быть в статусе pending (ожидает подтверждения)
            // is_paid станет true только после подтверждения платежа
            $model->payment_status = $payment->status; // 'pending', 'succeeded', 'canceled'
            
            // Если статус уже succeeded (редко, но бывает)
            if ($payment->status === 'succeeded') {
                $model->is_paid = true;
            }
            
            $model->save();

            Log::info('Payment saved', [
                'payment_id' => $model->payment_id,
                'status' => $model->payment_status,
                'is_paid' => $model->is_paid
            ]);

            return $payment;

        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage());
            throw $e;
        }
    }
}