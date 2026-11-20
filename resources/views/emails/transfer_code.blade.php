<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer Verification Code</title>
</head>
<body style="font-family: sans-serif; background-color: #f7f7f7; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px;">
        <h2 style="color: #333;">Hello {{ $name }},</h2>

        <p>You're trying to initiate a transfer. Please use the code below to confirm the operation:</p>

        <h1 style="color: #E2443B; font-size: 36px; text-align: center;">{{ $code }}</h1>

        <p>This code will expire in <strong>5 minutes</strong>.</p>

        <p>If you did not request this transfer, please ignore this email or contact support.</p>

        <br>
        <p>Thank you,<br><strong>VantexCard</strong> team</p>
    </div>
</body>
</html>
