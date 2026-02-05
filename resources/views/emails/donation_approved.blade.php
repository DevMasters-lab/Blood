<!DOCTYPE html>
<html>
<head>
    <title>Donation Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    
    <div style="max-w-600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; border-top: 5px solid #dc2626;">
        <h2 style="color: #333;">Hello, {{ $user->name }}!</h2>
        
        <p style="font-size: 16px; color: #555;">
            Great news! Your recent blood donation proof has been reviewed and <b style="color: green;">APPROVED</b> by our team.
        </p>

        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Status:</strong> <span style="color: green;">Verified & Active</span><br>
            <strong>Thank you:</strong> Your contribution helps save lives.
        </div>

        <p>You can view your history on your dashboard.</p>
        
        <a href="{{ route('user.dashboard') }}" style="background-color: #dc2626; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Go to Dashboard
        </a>

        <p style="margin-top: 30px; font-size: 12px; color: #999;">
            BloodShare KH Team
        </p>
    </div>

</body>
</html>