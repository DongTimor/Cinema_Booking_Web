<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Customer\CollectionController;
use App\Mail\Booking;
use App\Models\Movie;
use App\Models\Order;
use App\Models\Point;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $amount = $request->input('total_amount');
        $customer_id = $request->input('customer_id');
        $selected_seats = $request->input('selected_seats');
        $schedule_id = $request->input('schedule_id');
        $showtime_id = $request->input('showtime_id');
        $voucherCode = $request->input('voucher_code');
        $movie_id = $request->input('movie_id');
        session()->flash('customer_id', $customer_id);
        session()->flash('selected_seats', $selected_seats);
        session()->flash('schedule_id', $schedule_id);
        session()->flash('showtime_id', $showtime_id);
        session()->flash('voucher_code', $voucherCode);
        session()->flash('movie_id', $movie_id);
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
        $customer = auth('customer')->user();
        $voucherCode = session('voucher_code');
        if (isset($allData['resultCode']) && $allData['resultCode'] == 0) {
            $amount = $allData['amount'];
            $points = intval($amount / 1000);
            $customer = auth('customer')->user();
            $point = Point::firstOrCreate(
                ['customer_id' => $customer->id],
                ['total_points' => 0, 'points_earned' => 0, 'points_redeemed' => 0, 'ranking_level' => 'Bronze']
            );
            $point->total_points += $points;
            $point->points_earned += $points;
            $point->date_expire = now()->addMinute(5);
            $point->last_updated = now();
            $point->save();
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
                $voucher = Voucher::where('code', $voucherCode)->first();
                DB::table('customer_voucher')
                ->where('customer_id', $customer['id'])
                ->where('voucher_id', $voucher->id)
                ->update(['status' => '1']);
            }
            $tikets = Ticket::where('customer_id', $customer->id)
            ->where('schedule_id', $scheduleId)
            ->where('showtime_id', $showtimeId)
            ->get();
            $showtime = Showtime::findOrFail($showtimeId);
            $movie = Movie::select('name')->find($movie_id);
            $seats = Seat::with('auditorium')->find($seatId);
            $orders = Order::create([
                'customer_id' => $customer->id,
                'movie' => $movie->name,
                'start_time' => $showtime->start_time,
                'end_time' => $showtime->end_time,
                'price' => $amount / count($selectedSeats),
                'auditorium' => $seats->auditorium->name,
                'quantity' => count($selectedSeats),
                'ticket_ids' => $tikets->pluck('id')->implode(','),
                'voucher_id' => $voucherId,
            ]);
            $orders->save();
            app(CollectionController::class)->checkAndUpdatePoints();
            Mail::to($customer->email)->send(new Booking());
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
