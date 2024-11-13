<?php

namespace App\Http\Controllers;

use App\Mail\Booking;
use App\Models\Movie;
use App\Models\Order;
use App\Models\Point;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Voucher;
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
        $defaultPrice = $request->input('default_price');
        $discountValue = $request->input('discount_value');
        $amount = $request->input('total_amount');
        $customerId = $request->input('customer_id');
        $selectedSeats = $request->input('selected_seats');
        $scheduleId = $request->input('schedule_id');
        $showtimeId = $request->input('showtime_id');
        $voucherCode = $request->input('voucher_code');
        $movieId = $request->input('movie_id');
        session()->flash('customer_id', $customerId);
        session()->flash('selected_seats', $selectedSeats);
        session()->flash('schedule_id', $scheduleId);
        session()->flash('showtime_id', $showtimeId);
        session()->flash('voucher_code', $voucherCode);
        session()->flash('movie_id', $movieId);
        session()->flash('default_price', $defaultPrice);
        session()->flash('discount_value', $discountValue);
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
        $data = $request->all();
        $customer = auth('customer')->user();
        $voucherCode = session('voucher_code');
        if (isset($data['resultCode']) && $data['resultCode'] == 0) {
            $amount = $data['amount'];
            $defaultPrice = session('default_price');
            $discountValue = session('discount_value');
            $points = intval($amount / 1000);
            $this->handlePoints($customer, $points);
            $selectedSeats = session('selected_seats');
            $scheduleId = session('schedule_id');
            $showtimeId = session('showtime_id');
            $movie_id = session('movie_id');
            $voucherId = $voucherCode ? Voucher::where('code', $voucherCode)->first()->id : null;
            $selectedSeats = explode(',', $selectedSeats);
            foreach ($selectedSeats as $seatId) {
                Ticket::create([
                    'seat_id' => $seatId,
                    'customer_id' => $customer->id,
                    'price' => $amount / count($selectedSeats),
                    'schedule_id' => $scheduleId,
                    'showtime_id' => $showtimeId,
                    'voucher_id' => $voucherId,
                    'status' => 'ordered',
                    'movie_id' => $movie_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            if ($voucherCode) {
                $voucher = $customer->vouchers->where('code', $voucherCode)->first();
                if ($voucher) {
                    $voucher->customers()->updateExistingPivot($customer->id, ['status' => 1]);
                }
            }
            $tikets = Ticket::where('customer_id', $customer->id)
                ->where('created_at', '=', now())
                ->get();
            $showtime = Showtime::findOrFail($showtimeId);
            $movie = Movie::select('name')->find($movie_id);
            $seats = Seat::with('auditorium')->find($seatId);

            $orders = Order::create([
                'customer_id' => $customer->id,
                'movie' => $movie->name,
                'start_time' => $showtime->start_time,
                'end_time' => $showtime->end_time,
                'price' => $defaultPrice,
                'auditorium' => $seats->auditorium->name,
                'quantity' => count($selectedSeats),
                'ticket_ids' => $tikets->pluck('id')->implode(','),
                'voucher' => $discountValue,
                'total' => $amount,
            ]);
            $orders->save();
            Mail::to($customer->email)->send(new Booking());
            return redirect(route('home'));
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
                'date_expire' => now()->addMinutes(5),
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
                'date_expire' => now()->addMinutes(5),
                'last_updated' => now(),
            ]);
        }
    }
}
