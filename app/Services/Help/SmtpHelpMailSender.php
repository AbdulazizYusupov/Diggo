<?php

namespace App\Services\Help;

use App\DTOs\HelpTicketData;
use App\Mail\HelpTicketMail;
use App\Services\Help\Contracts\MailSenderInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * SRP: Faqat SMTP orqali email yuborish.
 * DIP: MailSenderInterface ni implement qiladi.
 * Barcha sozlamalar env → config() orqali o'qiladi.
 */
class SmtpHelpMailSender implements MailSenderInterface
{
    public function send(HelpTicketData $data): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('SmtpHelpMailSender: MAIL_* sozlanmagan — ticket log ga yozildi.', [
                'user'    => $data->userName,
                'role'    => $data->role,
                'subject' => $data->subject,
                'message' => $data->message,
            ]);
            return false;
        }

        try {
            Mail::to(
                config('help.admin.email'),
                config('help.admin.name'),
            )->send(new HelpTicketMail($data));

            Log::info('HelpTicket email yuborildi.', [
                'to'      => config('help.admin.email'),
                'user'    => $data->userName,
                'subject' => $data->subject,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('SmtpHelpMailSender: xatolik.', [
                'error'   => $e->getMessage(),
                'user'    => $data->userName,
                'subject' => $data->subject,
            ]);
            return false;
        }
    }

    private function isConfigured(): bool
    {
        return ! empty(config('mail.mailers.smtp.host'))
            && ! empty(config('mail.mailers.smtp.username'));
    }
}