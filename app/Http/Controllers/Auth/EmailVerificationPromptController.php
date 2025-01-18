<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
<<<<<<< HEAD
                    ? redirect()->intended(route('dashboard', absolute: false))
=======
                    ? redirect()->intended(route('products.index', absolute: false))
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
                    : view('auth.verify-email');
    }
}
