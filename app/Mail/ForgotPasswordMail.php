<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
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
        return $this->subject('SAVR — Password Reset Code')
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
                <h2 style="color: #2d6a4f;">Password Reset</h2>
                <p style="color: #555;">Hello, <strong>' . htmlspecialchars($this->userName) . '</strong>!</p>
                <p style="color: #555;">
                    We received a request to reset your SAVR password.<br>
                    Use the code below. It expires in <strong>10 minutes</strong>.
                </p>
                <div style="background: #c0392b; color: white; font-size: 36px;
                            font-weight: bold; letter-spacing: 12px; padding: 20px 32px;
                            border-radius: 12px; display: inline-block; margin: 20px 0;">
                    ' . $this->code . '
                </div>
                <p style="color: #999; font-size: 13px;">
                    If you did not request a password reset, please ignore this email.<br>
                    Your password will not be changed.
                </p>
            </div>
        </body>
        </html>';
    }
}