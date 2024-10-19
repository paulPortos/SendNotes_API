<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
</head>
<body>
    <h1>Hello!</h1>
    <p>Your note "{{ $details['title'] }}" {{ $details['message'] }}</p>

    <p>{{ $details['admin_message'] }}.<br>-Send Notes Team</p>
</body>
</html> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, darkblue, white);
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: darkblue;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
        }

        .email-body {
            padding: 20px;
            line-height: 1.6;
        }

        .email-body h2 {
            color: darkblue;
        }

        .email-body p {
            margin: 0 0 10px;
        }

        .email-footer {
            text-align: center;
            padding: 10px;
            background-color: #f7f7f7;
            font-size: 12px;
            color: #888;
        }

        .email-footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Notification</h1>
        </div>
        <div class="email-body">
            <h2>Hello!</h2>
            <p>Your note titled "<strong>{{ $details['title'] }}</strong>" {{ $details['message'] }}</p>
            <p>{{ $details['admin_message'] }}</p>
        </div>
        <div class="email-footer">
            <p>- Send Notes Team</p>
        </div>
    </div>
</body>
</html>
