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
        Log::info('Meta webhook received.', [
            'object' => $request->input('object'),
            'payload' => $request->all(),
        ]);

        return response('EVENT_RECEIVED', 200);
    }
}
