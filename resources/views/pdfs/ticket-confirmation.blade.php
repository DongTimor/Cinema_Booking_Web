<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ccc; background-color: #f9f9f9;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000; width: 81px">Customer:</td>
                            @if (!empty($data['customer']))
                                <td style="text-align: start;">{{ $data['customer'] }}</td>
                            @else
                                <td style="text-align: start;">Guest</td>
                            @endif
                            <td style="text-align: right; width: 100%;">{{ $data['date'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Movie:</td>
                            <td>{{ $data['movie'] }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Showtime:</td>
                            <td>{{ $data['showtime'] }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Auditorium:</td>
                            <td>{{ $data['auditorium'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <p style="font-weight: 600; font-size: 16px; color: #000000;">Seats</p>
                    @foreach ($data['seats'] as $seat)
                        <p style="margin-left: 20px;">Seat: {{ $seat['seatName'] }}</p>
                    @endforeach
                    <table style="width: 100%; margin-top: 10px;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Cost:</td>
                            <td style="text-align: right; width: 100%;">{{ number_format($data['price']) }} VND</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Event Discount:</td>
                            @if ($data['eventDiscount'] > 0)
                                <td style="text-align: right; width: 100%;">
                                    {{ number_format($data["eventDiscount"]) }} VND</td>
                            @else
                                <td style="text-align: right; width: 100%;">None</td>
                            @endif
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Voucher:</td>
                            <td style="text-align: right; width: 100%;">
                                @if (!empty($data["discount"]))
                                    {{ $data["discount"] }}
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
                            <td style="text-align: right; width: 100%;">{{ number_format($data["price"]) }} VND</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="margin-bottom: 20px; margin-left: 20px;">
            <h2>MT Cinema</h2>
            <p>69 Quang Trung, Da Nang - open at 08:00 AM - 23:00 PM every day</p>
            <p>Welcome to MT Cinema !</p>
        </div>
        <div style="border: 1px solid #ccc; padding: 10px; background-color: #fff;">
            <p>This event takes place online.</p>
            <a href="http://localhost/" style="color: #007bff; text-decoration: none;">Visit website</a>
        </div>
    </div>
</body>
</html>
