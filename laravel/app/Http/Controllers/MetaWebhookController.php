<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $verifyToken = config('services.meta.webhook_verify_token');

        if (
            $request->query('hub_mode') === 'subscribe'
            && $request->query('hub_verify_token') === $verifyToken
        ) {
            return response($request->query('hub_challenge'), 200);
        }

        return response('Forbidden', 403);
    }

    public function receive(Request $request)
    {
        if (!$this->hasValidSignature($request)) {
            return response('Invalid signature', 401);
        }

        Log::info('Meta webhook received.', [
            'object' => $request->input('object'),
            'messages' => $this->extractMessages($request),
            'payload' => $request->all(),
        ]);

        return response('EVENT_RECEIVED', 200);
    }

    private function hasValidSignature(Request $request): bool
    {
        $appSecret = config('services.meta.app_secret');

        if (!$appSecret) {
            return true;
        }

        $signature = $request->header('x-hub-signature-256');

        if (!$signature || !str_starts_with($signature, 'sha256=')) {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $appSecret);

        return hash_equals($expected, $signature);
    }

    private function extractMessages(Request $request): array
    {
        $messages = [];

        foreach ($request->input('entry', []) as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                foreach (data_get($change, 'value.messages', []) as $message) {
                    $messages[] = [
                        'channel' => 'whatsapp',
                        'from' => $message['from'] ?? null,
                        'type' => $message['type'] ?? null,
                        'text' => data_get($message, 'text.body'),
                    ];
                }
            }

            foreach ($entry['messaging'] ?? [] as $messageEvent) {
                $messages[] = [
                    'channel' => $request->input('object') === 'instagram' ? 'instagram' : 'messenger',
                    'from' => data_get($messageEvent, 'sender.id'),
                    'type' => data_get($messageEvent, 'message.attachments') ? 'attachment' : 'text',
                    'text' => data_get($messageEvent, 'message.text'),
                ];
            }
        }

        return $messages;
    }
}
