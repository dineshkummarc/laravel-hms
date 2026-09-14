<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Notice;
use App\Models\Vital;
use Illuminate\View\View;

class NurseDashboardController extends Controller
{
    public function index(): View
    {
        $activeAdmissions = Admission::where('status', 'admitted')->with(['patient', 'bed.room', 'doctor'])->latest('admission_date')->get();

        return view('nurse.dashboard', [
            'activeAdmissions' => $activeAdmissions,
            'todayVitals' => Vital::whereDate('recorded_at', today())->count(),
            'notices' => Notice::active()->forAudience('staff')->latest()->take(5)->get(),
        ]);
    }
}
