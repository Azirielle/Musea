<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $user = auth()->user();

        $conversations = Conversation::where('buyer_id', $user->id)
            ->orWhere('artist_id', $user->id)
            ->with(['buyer', 'artist', 'artwork', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conv) use ($user) {
                $otherUser = $conv->getOtherUser($user);
                return [
                    'id' => $conv->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->first_name . ' ' . $otherUser->last_name,
                        'avatar' => $otherUser->imageUrl(),
                    ],
                    'artwork' => $conv->artwork ? [
                        'id' => $conv->artwork->id,
                        'title' => $conv->artwork->title,
                        'image' => $conv->artwork->image_url,
                    ] : null,
                    'last_message' => $conv->lastMessage ? [
                        'body' => $conv->lastMessage->body,
                        'time' => $conv->lastMessage->created_at->diffForHumans(),
                    ] : null,
                    'unread_count' => $conv->messages()
                        ->where('sender_id', '!=', $user->id)
                        ->where('is_read', false)
                        ->count(),
                ];
            });

        return Inertia::render('Messages/Index', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Show the messages for a specific conversation.
     */
    public function show(Conversation $conversation)
    {
        // Ensure user is part of conversation
        if ($conversation->buyer_id !== auth()->id() && $conversation->artist_id !== auth()->id()) {
            abort(403);
        }

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Mark database notifications as read
        auth()->user()->unreadNotifications()
            ->where('type', 'App\Notifications\NewMessageNotification')
            ->where('data->conversation_id', $conversation->id)
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'is_mine' => $msg->sender_id === auth()->id(),
                    'time' => $msg->created_at->format('M d, g:i a'),
                    'sender_name' => $msg->sender->first_name,
                ];
            });

        return response()->json([
            'messages' => $messages,
            'conversation' => [
                'id' => $conversation->id,
                'other_user' => $conversation->getOtherUser(auth()->user())->first_name,
            ]
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, Conversation $conversation)
    {
        if ($conversation->buyer_id !== auth()->id() && $conversation->artist_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        DB::beginTransaction();
        try {
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => auth()->id(),
                'body' => $request->body,
            ]);

            $conversation->update(['last_message_at' => now()]);

            // Notify Other User
            $otherUser = $conversation->getOtherUser(auth()->user());
            $otherUser->notify(new \App\Notifications\NewMessageNotification($message));

            DB::commit();

            return response()->json([
                'id' => $message->id,
                'body' => $message->body,
                'is_mine' => true,
                'time' => $message->created_at->format('M d, g:i a'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Start or find a conversation from an artwork page.
     */
    public function start(Artwork $artwork)
    {
        $buyerId = auth()->id();
        $artistId = $artwork->artist_id;

        if ($buyerId === $artistId) {
            return back()->with('error', 'You cannot message yourself!');
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id' => $buyerId,
            'artist_id' => $artistId,
            'artwork_id' => $artwork->id,
        ]);

        if (!$conversation->wasRecentlyCreated) {
            // Already exists, just redirect
        } else {
            $conversation->update(['last_message_at' => now()]);
        }

        return redirect()->route('messages.index', ['selected' => $conversation->id]);
    }
}
