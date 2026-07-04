<?php

namespace App\Services;

class ContactSubmissionService
{
    public function submit(array $payload): array
    {
        return [
            'submitted' => true,
            'name' => $payload['name'],
            'email' => $payload['email'],
            'subject' => $payload['subject'],
        ];
    }
}