<?php

namespace App\Http\Controllers;

use App\Mail\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $amount = "50000";
        $orderId = time() . "";
        $redirectUrl = "http://localhost/momopayment/paymentsuccess"; // URL chuyển hướng về localhost
        $ipnUrl = "http://localhost"; // URL cho IPN trên localhost (sẽ không hoạt động từ bên ngoài)
        $extraData = "";

        $requestId = time() . "";
        // $requestType = "payWithATM";
        $requestType = "captureWallet";
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
        // Nhận toàn bộ dữ liệu sau khi thanh toán thành công
        $allData = $request->all();

        // Xử lý dữ liệu hoặc lưu vào cơ sở dữ liệu nếu cần thiết
        if (isset($allData['resultCode']) && $allData['resultCode'] == 0) {
            Mail::to('vinhtoan552@gmail.com')->send(new Booking());
            return redirect(route('home'));
        } else {
            // Thanh toán thất bại
            return response()->json([
                'message' => 'Thanh toán thất bại',
                'data' => $allData
            ]);
        }
    }

    public function handleMoMoIPN(Request $request)
    {
        // Xử lý thông báo IPN từ MoMo
        $allData = $request->all();

        // Kiểm tra và xử lý dữ liệu IPN
        if (isset($allData['resultCode']) && $allData['resultCode'] == 0) {
            // Xác nhận thanh toán thành công
            // Thực hiện các hành động cần thiết như cập nhật trạng thái đơn hàng
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
