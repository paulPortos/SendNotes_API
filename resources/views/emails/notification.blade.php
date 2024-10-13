<!DOCTYPE html>
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
</html>
