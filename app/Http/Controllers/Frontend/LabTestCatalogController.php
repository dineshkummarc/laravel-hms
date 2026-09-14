<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabTestCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = LabTest::with('department')->where('is_active', true);

        // Optional filtering by medical department
        if ($request->has('department')) {
            $query->where('department_id', $request->department);
        }

        $labTests    = $query->paginate(24);
        $departments = Department::whereHas('labTests')->get();

        return view('frontend.catalog.index', compact('labTests', 'departments'));
    }
}
