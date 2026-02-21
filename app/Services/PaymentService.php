<?php

namespace App\Services;

use YooKassa\Client;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuth(
            config('services.yookassa.shop_id'),
            config('services.yookassa.secret_key')
        );
    }

    public function createPayment($amount, $description, $model)
    {
        try {
            $payment = $this->client->createPayment(
                [
                    'amount' => [
                        'value' => $amount,
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => route('payment.success'),
                    ],
                    'capture' => true,
                    'description' => $description,
                    'metadata' => [
                        'model_type' => get_class($model),
                        'model_id' => $model->id
                    ]
                ],
                uniqid('', true)
            );

            $model->payment_id = $payment->getId();
            $model->payment_status = $payment->getStatus();
            $model->save();

            return $payment;
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}