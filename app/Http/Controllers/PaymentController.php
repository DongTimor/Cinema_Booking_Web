<?php

namespace App\Http\Controllers;

use App\Mail\Booking;
use App\Models\Point;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function momo_payment(Request $request)
    {
        function execPostRequest($url, $data)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data)
                )
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $request->input('total_amount');
        $voucherCode = $request->input('voucher_code'); 
        Session::put('voucher_code', $voucherCode);
        $orderId = time() . "";
        $redirectUrl = "http://localhost/momopayment/paymentsuccess"; 
        $ipnUrl = "http://localhost"; 
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";
        // $requestType = "captureWallet";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        return redirect()->to($jsonResult['payUrl']);
    }

    public function handleMoMoReturn(Request $request)
    {
        $allData = $request->all();
        $user = Auth::user();
        $userVouchers = $user->vouchers->pluck('pivot.voucher_id');
        $voucherCode = Session::get('voucher_code');
        Session::forget('voucher_code'); 
        if (isset($allData['resultCode']) && $allData['resultCode'] == 0) {
            $amount = $allData['amount'];
            $points = intval($amount / 1000);
            $user = Auth::user();
            $point = Point::firstOrCreate(
                ['user_id' => $user->id],
                ['total_points' => 0, 'points_earned' => 0, 'points_redeemed' => 0, 'ranking_level' => 'Bronze']
            );
            $point->total_points += $points;
            $point->points_earned += $points;
            $point->date_expire = now()->addMinute(5);
            $point->last_updated = now();
            $point->save();

            if ($voucherCode) {
                $voucher = Voucher::where('code', $voucherCode)->first();
                if ($voucher && $userVouchers->contains($voucher->id)) {
                    DB::table('user_voucher')
                        ->where('user_id', $user->id)
                        ->where('voucher_id', $voucher->id)
                        ->update(['status' => 1]);
                }
            }
            app(PointController::class)->checkAndUpdatePoints();
            Mail::to($user->email)->send(new Booking());
            return redirect(route('home'));
        } else {
            return response()->json([
                'message' => 'Thanh toán thất bại',
                'data' => $allData
            ]);
        }
    }

    public function handleMoMoIPN(Request $request)
    {
        $allData = $request->all();
        if (isset($allData['resultCode']) && $allData['resultCode'] == 0) {
            return response()->json([
                'message' => 'IPN nhận thành công',
                'data' => $allData
            ]);
        } else {
            return response()->json([
                'message' => 'IPN thất bại',
                'data' => $allData
            ]);
        }
    }
}
