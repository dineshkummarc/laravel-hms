<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Notices',
            'resource' => 'admin.notices',
            'items'    => Notice::with('author')->latest()->paginate(20),
            'columns'  => ['title', 'audience', 'priority', 'is_published', 'published_at', 'expires_at'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Notice',
            'resource' => 'admin.notices',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_published'] = $request->boolean('is_published');
        $data['created_by'] = auth()->id();
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? now()) : $data['published_at'] ?? null;
        Notice::create($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice created.');
    }

    public function show(Notice $notice): View
    {
        return view('admin.crud.show', [
            'title'    => 'Notice',
            'resource' => 'admin.notices',
            'item'     => $notice,
            'columns'  => ['title', 'body', 'audience', 'priority', 'is_published', 'published_at', 'expires_at'],
        ]);
    }

    public function edit(Notice $notice): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Notice',
            'resource' => 'admin.notices',
            'item'     => $notice,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_published'] = $request->boolean('is_published');
        $notice->update($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'audience' => 'required|in:public,patient,staff,all',
            'priority' => 'required|in:normal,important,urgent',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
            ['name' => 'body', 'label' => 'Body', 'type' => 'textarea', 'required' => true],
            ['name' => 'audience', 'label' => 'Audience', 'type' => 'select', 'required' => true, 'options' => ['public' => 'Public', 'patient' => 'Patients', 'staff' => 'Staff', 'all' => 'All']],
            ['name' => 'priority', 'label' => 'Priority', 'type' => 'select', 'required' => true, 'options' => ['normal' => 'Normal', 'important' => 'Important', 'urgent' => 'Urgent']],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'required' => false],
            ['name' => 'published_at', 'label' => 'Published at', 'type' => 'datetime-local', 'required' => false],
            ['name' => 'expires_at', 'label' => 'Expires at', 'type' => 'datetime-local', 'required' => false],
        ];
    }
}
