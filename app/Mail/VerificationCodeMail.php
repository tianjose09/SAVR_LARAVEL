<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $userName;

    public function __construct(string $code, string $userName = 'User')
    {
        $this->code     = $code;
        $this->userName = $userName;
    }

    public function build(): self
    {
        return $this->subject('Your SAVR Verification Code')
            ->html($this->buildHtml());
    }

    private function buildHtml(): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
            <div style="max-width: 500px; margin: 0 auto; background: white;
                        border-radius: 16px; padding: 32px; text-align: center;">
                <img src="https://i.imgur.com/placeholder.png"
                     alt="SAVR FoodBank" style="height: 60px; margin-bottom: 16px;" />
                <h2 style="color: #2d6a4f;">Email Verification</h2>
                <p style="color: #555;">Hello, <strong>' . htmlspecialchars($this->userName) . '</strong>!</p>
                <p style="color: #555;">Use the code below to verify your account.
                   It expires in <strong>10 minutes</strong>.</p>
                <div style="background: #2d6a4f; color: white; font-size: 36px;
                            font-weight: bold; letter-spacing: 12px; padding: 20px 32px;
                            border-radius: 12px; display: inline-block; margin: 20px 0;">
                    ' . $this->code . '
                </div>
                <p style="color: #999; font-size: 13px;">
                    If you did not request this, please ignore this email.
                </p>
            </div>
        </body>
        </html>';
    }
}