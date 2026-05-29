<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Flash success messages to session
        session()->flash('message', 'You have successfully logged in!');
        session()->flash('flash.banner', 'You have successfully logged in!');
        session()->flash('flash.bannerStyle', 'success');

        return $request->wantsJson()
            ? response()->empty()
            : redirect()->intended(config('fortify.home'));
    }
}
