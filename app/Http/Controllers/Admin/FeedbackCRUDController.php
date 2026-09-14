<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Patient feedback',
            'resource' => 'admin.feedback',
            'items'    => Feedback::with(['patient', 'doctor'])->latest()->paginate(20),
            'columns'  => ['patient.name', 'doctor.name', 'rating', 'category', 'is_published'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Feedback',
            'resource' => 'admin.feedback',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_published'] = $request->boolean('is_published');
        Feedback::create($data);

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback saved.');
    }

    public function show(Feedback $feedback): View
    {
        $feedback->load(['patient', 'doctor']);

        return view('admin.crud.show', [
            'title'    => 'Feedback',
            'resource' => 'admin.feedback',
            'item'     => $feedback,
            'columns'  => ['patient.name', 'doctor.name', 'rating', 'category', 'comment', 'is_published'],
        ]);
    }

    public function edit(Feedback $feedback): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Feedback',
            'resource' => 'admin.feedback',
            'item'     => $feedback,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Feedback $feedback): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_published'] = $request->boolean('is_published');
        $feedback->update($data);

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback updated.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'nullable|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'required|string|max:100',
            'comment' => 'nullable|string',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'patient_id', 'label' => 'Patient', 'type' => 'select', 'required' => false, 'options' => User::role('patient')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'doctor_id', 'label' => 'Doctor', 'type' => 'select', 'required' => false, 'options' => User::role('doctor')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'rating', 'label' => 'Rating (1-5)', 'type' => 'number', 'required' => true],
            ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'required' => true, 'options' => ['general' => 'General', 'doctor' => 'Doctor', 'nursing' => 'Nursing', 'facility' => 'Facility', 'billing' => 'Billing']],
            ['name' => 'comment', 'label' => 'Comment', 'type' => 'textarea', 'required' => false],
            ['name' => 'is_published', 'label' => 'Publish on website', 'type' => 'checkbox', 'required' => false],
        ];
    }
}
