<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data['title'] }}</title>
</head>
@php
    $count = count($data['data']['seats']);
@endphp
<body>
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ccc; background-color: #f9f9f9;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000; width: 81px">Customer:</td>
                            @if (isset($data['data']['customer']))
                                <td style="text-align: start;">{{ $data['data']['customer'] }}</td>
                            @else
                                <td style="text-align: start;">Guest</td>
                            @endif
                            <td style="text-align: right; width: 100%;">{{ $data['data']['order_date'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Movie:</td>
                            <td>{{ $data['data']['movie'] }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Showtime:</td>
                            <td>{{ $data['data']['showtime'] }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Auditorium:</td>
                            <td>{{ $data['data']['auditorium'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <p style="font-weight: 600; font-size: 16px; color: #000000;">Seats</p>
                    @foreach ($data['data']['seats'] as $seat)
                        <p style="margin-left: 20px;">Seat: {{ $seat }}</p>
                    @endforeach
                    <table style="width: 100%; margin-top: 10px;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Cost:</td>
                            <td style="text-align: right; width: 100%;">{{ $data['data']['total'] * $count }} VND</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px; border-bottom: 1px solid #ccc;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Event Discount:</td>
                            @if ($data['data']['event_discount'] > 0)
                                <td style="text-align: right; width: 100%;">
                                    {{ $data['data']['event_discount'] * $count }} VND</td>
                            @else
                                <td style="text-align: right; width: 100%;">None</td>
                            @endif
                        </tr>
                        <tr>
                            <td style="font-weight: 600; font-size: 16px; color: #000000;">Voucher:</td>
                            <td style="text-align: right; width: 100%;">
                                @if (isset($data['data']['voucher']))
                                    ({{ $data['data']['voucher']['code'] }})
                                    {{ $data['data']['voucher']['type'] == 'percent' ? $data['data']['voucher']['value'] * (1 / 100) * $data['data']['total'] * $count . 'VND' : $data['data']['voucher']['value'] . ' VND' }}
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
                            <td style="text-align: right; width: 100%;">{{ $data['data']['cost'] * $count }} VND</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="margin-bottom: 20px; margin-left: 20px;">
            <h2>MT Cinema</h2>
            <p>69 Quang Trung, Da Nang - open at 06:00 AM - 23:00 PM every day</p>
            <p>welcome to MT Cinema !</p>
        </div>
        <div style="border: 1px solid #ccc; padding: 10px; background-color: #fff;">
            <p>This event takes place online.</p>
            <a href="http://localhost/home" style="color: #007bff; text-decoration: none;">Visit website</a>
        </div>
    </div>
</body>
</html>
