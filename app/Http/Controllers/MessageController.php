<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        return view('messages.index', [
            'inbox' => Message::with('sender')->where('receiver_id', $userId)->latest()->paginate(10, ['*'], 'inbox'),
            'sent' => Message::with('receiver')->where('sender_id', $userId)->latest()->paginate(10, ['*'], 'sent'),
            'users' => User::where('id', '!=', $userId)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        if ((int) $validated['receiver_id'] === (int) auth()->id()) {
            return back()->withErrors(['receiver_id' => 'You cannot message yourself.']);
        }

        Message::create([
            ...$validated,
            'sender_id' => auth()->id(),
        ]);

        return back()->with('success', 'Message sent.');
    }

    public function show(Message $message): View
    {
        abort_unless(in_array(auth()->id(), [$message->sender_id, $message->receiver_id], true), 403);

        if ($message->receiver_id === auth()->id() && $message->isUnread()) {
            $message->update(['read_at' => now()]);
        }

        $message->load(['sender', 'receiver']);

        return view('messages.show', compact('message'));
    }
}
