<?php

namespace App\Http\Controllers;

use App\Mail\Booking;
use App\Models\Order;
use App\Models\Point;
use App\Models\Schedule;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
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

    public function momo_payment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $orderData = base64_decode($request->input('order_data'));
        $jsonData = json_decode($orderData, true);
        $amount = $jsonData['totalPrice'];
        session()->flash('orderData', $orderData);
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

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        return redirect()->to($jsonResult['payUrl']);
    }

    public function handleMoMoReturn(Request $request)
    {
        $data = $request->all();
        $customer = auth('customer')->user();
        if (isset($data['resultCode']) && $data['resultCode'] == 0) {
            $orderData = session('orderData');
            $jsonData = json_decode($orderData, true);
            $schedule = Schedule::with('showtimes')
                ->where('movie_id', $jsonData['movieId'])
                ->whereDate('date', $jsonData['date'])
                ->first();
            $amount = $data['amount'];
            $points = intval($amount / 1000);
            $this->handlePoints($customer, $points);
            $ticketIds = [];

            foreach ($jsonData['seatIds'] as $seatId) {
                $ticket = Ticket::create([
                    'seat_id' => $seatId,
                    'customer_id' => $customer->id,
                    'price' => $jsonData['totalPrice'],
                    'schedule_id' => $schedule->id,
                    'showtime_id' => $jsonData['showtimeId'],
                    'voucher_id' => $jsonData['voucherId'],
                    'status' => 'ordered',
                    'movie_id' => $jsonData['movieId'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $ticketIds[] = $ticket->id;
            }

            if ($jsonData['voucherId']) {
                $customer->vouchers()->updateExistingPivot($jsonData['voucherId'], ['status' => 1]);
            }

            Order::create([
                'customer_id' => $customer->id,
                'movie' => $jsonData['movieName'],
                'start_time' => $jsonData['startTime'],
                'end_time' => $jsonData['endTime'],
                'price' => $jsonData['defaultPrice'],
                'auditorium' => $schedule->auditorium->name,
                'quantity' => count($jsonData['seatIds']),
                'ticket_ids' => implode(',', $ticketIds),
                'voucher' => $jsonData['discount'],
                'total' => $amount,
            ]);
            Mail::to($customer->email)->send(new Booking());
            return redirect()->route('home')->with('success', 'Thanh toán thành công!');
        } else {
            return response()->json([
                'message' => 'Thanh toán thất bại',
                'data' => $data
            ]);
        }
    }

    public function handleMoMoIPN(Request $request)
    {
        $data = $request->all();
        if (isset($data['resultCode']) && $data['resultCode'] == 0) {
            return response()->json([
                'message' => 'IPN nhận thành công',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'message' => 'IPN thất bại',
                'data' => $data
            ]);
        }
    }

    public function handlePoints($customer, $points)
    {
        $customerPoint = $customer->point;

        if ($customerPoint) {
            $customerPoint->increment('total_points', $points);
            $customerPoint->increment('points_earned', $points);
            $customerPoint->update([
                'date_expire' => now()->addDay(),
                'last_updated' => now(),
            ]);

            if ($customerPoint->total_points > 200) {
                $customerPoint->update([
                    'ranking_level' => 'Gold',
                ]);
            } elseif ($customerPoint->total_points > 150) {
                $customerPoint->update([
                    'ranking_level' => 'Silver',
                ]);
            }
        } else {
            Point::create([
                'customer_id' => $customer->id,
                'total_points' => $points,
                'points_earned' => $points,
                'date_expire' => now()->addDay(),
                'last_updated' => now(),
            ]);
        }
    }
}
