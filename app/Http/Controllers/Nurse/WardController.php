<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\View\View;

class WardController extends Controller
{
    public function index(): View
    {
        $admissions = Admission::where('status', 'admitted')
            ->with(['patient', 'bed.room', 'doctor'])
            ->latest('admission_date')
            ->get();

        return view('nurse.wards.index', compact('admissions'));
    }
}
