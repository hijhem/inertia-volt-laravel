<?php

declare(strict_types=1);

namespace App\Http\Pages;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

final class ProfileEditForm
{
    public function __invoke(Request $request)
    {
        return [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ];
    }
}