<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->only([
            'id',
            'first_name',
            'last_name',
            'username',
            'photo_url',
            'auth_date',
            'hash',
        ]);

        if (!$this->checkTelegramAuthorization($data)) {
            return redirect()->route('login')
                ->withErrors(['telegram' => 'Telegram authentication failed.']);
        }

        $name = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        if ($name === '') {
            $name = $data['username'] ?? 'Telegram User';
        }

        $user = User::updateOrCreate(
            [
                'telegram_id' => (string) $data['id'],
            ],
            [
                'name' => $name,
                'telegram_username' => $data['username'] ?? null,
                'telegram_photo_url' => $data['photo_url'] ?? null,
                'auth_provider' => 'telegram',
                'password' => bcrypt(Str::random(32)),
                'status' => 'active',
                'last_login_at' => now(),
            ]
        );

        Auth::login($user, true);

        return redirect('/');
    }

    private function checkTelegramAuthorization(array $data): bool
    {
        if (empty($data['hash']) || empty($data['auth_date']) || empty($data['id'])) {
            return false;
        }

        $checkHash = $data['hash'];
        unset($data['hash']);

        $dataCheckArray = [];

        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') {
                $dataCheckArray[] = $key . '=' . $value;
            }
        }

        sort($dataCheckArray);

        $dataCheckString = implode("\n", $dataCheckArray);

        $botToken = config('services.telegram.bot_token');

        if (!$botToken) {
            return false;
        }

        $secretKey = hash('sha256', $botToken, true);

        $hash = hash_hmac('sha256', $dataCheckString, $secretKey);

        return hash_equals($hash, $checkHash);
    }
}