<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Confirmation</title>
</head>

<body>
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ccc; background-color: #f9f9f9;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000; width: 81px">Customer:</td>
                            <td style="text-align: start;">{{ $customer->name ?? "Guest" }}</td>
                            <td style="text-align: end; width: 100%;">{{ $orderData["date"] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Movie:</td>
                            <td>{{ $orderData["movie"] }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Auditorium:</td>
                            <td>{{ $orderData["auditorium"] }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Showtime:</td>
                            <td>{{ $orderData["showtime"] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <p style="font-weight: 600; font-size: 16px; color: #000000;">Seats</p>
                    @foreach ($orderData["seats"] as $seat)
                        <p style="margin-left: 20px;">{{ $seat["seatName"] }}</p>
                    @endforeach
                    <table style="width: 100%; margin-top: 10px;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Cost:</td>
                            <td style="text-align: end;">{{ number_format($orderData["price"]) }} VND</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Event Discount:</td>
                            <td style="text-align: end">{{ number_format($orderData["eventDiscount"]) }} VND</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Voucher:</td>
                            <td style="text-align: end">
                                @if (!empty($orderData["discount"]))
                                    {{ $orderData["discount"] }}
                                @else
                                    None
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000; width: min-content;">Total:
                            </td>
                            <td style="text-align: end;">{{ number_format($orderData["price"]) }} VND</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="margin-bottom: 20px; margin-left: 20px;">
            <h2>MT Cinema</h2>
            <p>123 Le Loi, Da Nang - open at 08:00 AM - 23:00 PM every day</p>
            <p>Welcome to mt cinema !</p>
        </div>
        <a href="{{ route("ticket-confirmation-pdf", base64_encode(json_encode($orderData))) }}"
            style="width: 100%;
            margin-top: 20px;
            display: inline-block;
            background-color: #5c2d91;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;">DOWNLOAD
        </a>

        <div style="border: 1px solid #ccc; padding: 10px; background-color: #fff;">
            <p>This event takes place online.</p>
            <a href="http://localhost/home" style="color: #007bff; text-decoration: none;">Visit website</a>
        </div>
    </div>
</body>

</html>
