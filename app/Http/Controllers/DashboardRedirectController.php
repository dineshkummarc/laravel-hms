<?php

namespace App\Http\Controllers;

use App\Support\Portal;
use Illuminate\Http\RedirectResponse;

class DashboardRedirectController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->to(Portal::homeUrl());
    }
}
