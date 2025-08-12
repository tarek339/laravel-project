<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Driver License Expiry Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .driver-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Driver License Expiry Notification</h1>
    </div>

    @if($daysUntilExpiry <= 0)
        <div class="alert alert-danger">
            <strong>⚠️ URGENT: Your driver license has expired!</strong>
        </div>
        <p>Dear {{ $driver->first_name }} {{ $driver->last_name }},</p>
        <p>Your driver license has <strong>expired</strong> as of {{ $licenseExpiryDate->format('F j, Y') }}. You must renew your license immediately to continue driving legally.</p>
    @elseif($daysUntilExpiry <= 30)
        <div class="alert alert-danger">
            <strong>⚠️ URGENT: Your driver license expires in {{ $daysUntilExpiry }} day(s)!</strong>
        </div>
        <p>Dear {{ $driver->first_name }} {{ $driver->last_name }},</p>
        <p>This is an urgent reminder that your driver license will expire on <strong>{{ $licenseExpiryDate->format('F j, Y') }}</strong>, which is in only {{ $daysUntilExpiry }} day(s).</p>
    @else
        <div class="alert alert-warning">
            <strong>📅 Reminder: Your driver license expires in {{ $daysUntilExpiry }} days</strong>
        </div>
        <p>Dear {{ $driver->first_name }} {{ $driver->last_name }},</p>
        <p>This is a friendly reminder that your driver license will expire on <strong>{{ $licenseExpiryDate->format('F j, Y') }}</strong>, which is in {{ $daysUntilExpiry }} days.</p>
    @endif

    <div class="driver-info">
        <h3>Your Driver Information:</h3>
        <p><strong>Name:</strong> {{ $driver->first_name }} {{ $driver->last_name }}</p>
        <p><strong>License Number:</strong> {{ $driver->license_number }}</p>
        <p><strong>Current Expiry Date:</strong> {{ $licenseExpiryDate->format('F j, Y') }}</p>
        <p><strong>Email:</strong> {{ $driver->email }}</p>
    </div>

    <h3>What you need to do:</h3>
    <ul>
        <li>Contact your local DMV or licensing authority</li>
        <li>Gather required documentation for renewal</li>
        <li>Schedule an appointment if necessary</li>
        <li>Complete the renewal process before the expiry date</li>
    </ul>

    @if($daysUntilExpiry <= 0)
        <p><strong>Important:</strong> Driving with an expired license may result in fines, penalties, and legal issues. Please renew your license immediately.</p>
    @elseif($daysUntilExpiry <= 7)
        <p><strong>Important:</strong> With only {{ $daysUntilExpiry }} day(s) remaining, please prioritize renewing your license to avoid any disruption to your driving privileges.</p>
    @endif

    <div class="footer">
        <p>This is an automated notification. Please do not reply to this email.</p>
        <p>If you have any questions, please contact your supervisor or HR department.</p>
    </div>
</body>
</html>
