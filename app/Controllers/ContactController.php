<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Contact;

class ContactController extends BaseController
{
    /**
     * Сохранение сообщения из формы обратной связи
     */
    public function store(array $params = []): void
    {
        $data = $this->getJsonInput();

        $name    = trim($data['name'] ?? '');
        $email   = trim($data['email'] ?? '');
        $phone   = trim($data['phone'] ?? '');
        $message = trim($data['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            $this->error('Name, email and message are required');
            return;
        }

        $contactId = Contact::create([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'message' => $message,
        ]);

        $this->success(['id' => $contactId], 'Message sent successfully!', 201);
    }
}