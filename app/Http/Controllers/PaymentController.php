<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YooKassa\Client;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function success()
    {
        return view('payment.success');
    }

    public function webhook(Request $request)
    {
        try {
            $source = file_get_contents('php://input');
            $requestBody = json_decode($source, true);

            if ($requestBody['event'] === 'payment.waiting_for_capture') {
                $client = new Client();
                $client->setAuth(
                    config('services.yookassa.shop_id'),
                    config('services.yookassa.secret_key')
                );

                $payment = $client->capturePayment([
                    'amount' => $requestBody['object']['amount'],
                ], $requestBody['object']['id']);

                $metadata = $requestBody['object']['metadata'];
                $modelClass = $metadata['model_type'];
                $model = $modelClass::find($metadata['model_id']);
                
                if ($model) {
                    $model->is_paid = true;
                    $model->payment_status = 'succeeded';
                    $model->save();
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}