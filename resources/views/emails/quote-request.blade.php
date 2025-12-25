<!DOCTYPE html>
<html>
<head>
    <title>{{ T('email.new_quote_request') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="background-color: #f4f4f4; padding: 20px;">
        <div style="background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="color: #2463eb; border-bottom: 2px solid #2463eb; padding-bottom: 10px;">{{ T('email.new_quote_request') }}</h2>
            
            <p><strong>{{ T('email.company_name') }}:</strong> {{ data_get($details, 'company_name', '-') }}</p>
            <p><strong>{{ T('email.full_name') }}:</strong> {{ data_get($details, 'name', '-') }}</p>
            <p><strong>{{ T('email.email_address') }}:</strong> {{ data_get($details, 'email', '-') }}</p>
            <p><strong>{{ T('email.service_type') }}:</strong> {{ data_get($details, 'service_type', '-') }}</p>
            
            <div style="margin-top: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 5px;">
                <strong>{{ T('email.message_details') }}:</strong><br>
                {!! nl2br(e(data_get($details, 'message') ?? T('email.no_message'))) !!}
            </div>

            <p style="margin-top: 20px; font-size: 0.9em; color: #777;">{{ T('email.footer_note') }}</p>
        </div>
    </div>
</body>
</html>
