<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $payment = new Payment();
        $payment->phone_number = $request->input('phone_number');
        $payment->amount = $request->input('amount');
        $token = $payment->getToken();
        $description = 'This is a test payment';
        $reference = $payment->requestToPay($token, $payment->phone_number, $description, $payment->amount);
        $status = $payment->paymentStatus($reference, $token);

        while ($status === 'PENDING')
        {
            $status = $payment->paymentStatus($reference, $token);
            if ($status === 'SUCCESSFUL') {
                $payment->payment_date = date('Y-m-d H:i:s');
                $payment->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Transaction Successful'
                ]);
                break;
            } else if ($status === 'FAILED') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Transaction Failed'
                ]);
                break;
            }
        }
    }
}
