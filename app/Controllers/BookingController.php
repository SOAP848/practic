<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Booking;

class BookingController extends BaseController
{
    /**
     * Сохранение бронирования
     */
    public function store(array $params = []): void
    {
        $data = $this->getJsonInput();

        $name   = trim($data['name'] ?? '');
        $email  = trim($data['email'] ?? '');
        $phone  = trim($data['phone'] ?? '');
        $guests = trim($data['guests'] ?? '');
        $date   = trim($data['date'] ?? '');
        $time   = trim($data['time'] ?? '');

        if (empty($name) || empty($email) || empty($phone)) {
            $this->error('Name, email and phone are required');
            return;
        }

        $bookingId = Booking::create([
            'name'   => $name,
            'email'  => $email,
            'phone'  => $phone,
            'guests' => $guests,
            'date'   => $date ?: null,
            'time'   => $time ?: null,
        ]);

        // Отправка email-уведомления (заглушка)
        $this->sendEmailNotification($data);

        $this->success(['id' => $bookingId], 'Booking confirmed!', 201);
    }

    /**
     * Отправка email-уведомления о бронировании
     */
    private function sendEmailNotification(array $data): void
    {
        $to = $data['email'];
        $subject = 'Booking Confirmation - Hungry People';
        $message = "Dear {$data['name']},\n\n";
        $message .= "Your table booking has been confirmed!\n";
        $message .= "Date: {$data['date']}\n";
        $message .= "Time: {$data['time']}\n";
        $message .= "Guests: {$data['guests']}\n\n";
        $message .= "Thank you for choosing Hungry People!\n";

        $headers = 'From: booking@hungrypeople.com' . "\r\n" .
                   'Reply-To: booking@hungrypeople.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        @mail($to, $subject, $message, $headers);
    }
}