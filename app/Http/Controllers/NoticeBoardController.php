<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\View\View;

class NoticeBoardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $audience = $user->hasRole('patient') ? 'patient' : 'staff';

        $notices = Notice::active()
            ->where(function ($query) use ($audience) {
                $query->whereIn('audience', ['all', 'public', $audience]);
            })
            ->latest('published_at')
            ->paginate(12);

        return view('notices.index', compact('notices'));
    }
}
