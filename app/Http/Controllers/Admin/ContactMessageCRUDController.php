<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Contact messages',
            'resource' => 'admin.contact-messages',
            'items'    => ContactMessage::latest()->paginate(20),
            'columns'  => ['name', 'email', 'phone', 'subject', 'status', 'created_at'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Contact message',
            'resource' => 'admin.contact-messages',
            'fields'   => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'required' => false],
                ['name' => 'subject', 'label' => 'Subject', 'type' => 'text', 'required' => false],
                ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]));

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message saved.');
    }

    public function show(ContactMessage $contact_message): View
    {
        if ($contact_message->status === 'new') {
            $contact_message->update(['status' => 'read']);
        }

        return view('admin.contact.show', ['item' => $contact_message]);
    }

    public function edit(ContactMessage $contact_message): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Contact message',
            'resource' => 'admin.contact-messages',
            'item'     => $contact_message,
            'fields'   => [
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived']],
                ['name' => 'admin_reply', 'label' => 'Admin reply', 'type' => 'textarea', 'required' => false],
            ],
        ]);
    }

    public function update(Request $request, ContactMessage $contact_message): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived',
            'admin_reply' => 'nullable|string',
        ]);

        if (! empty($validated['admin_reply'])) {
            $validated['status'] = 'replied';
            $validated['replied_at'] = now();
        }

        $contact_message->update($validated);

        return redirect()->route('admin.contact-messages.index')->with('success', 'Inbox item updated.');
    }

    public function destroy(ContactMessage $contact_message): RedirectResponse
    {
        $contact_message->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted.');
    }
}
