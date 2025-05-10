<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotifyAdmin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        Log::info('NotifyAdmin listener called for new user registration');
        Log::info('New user details:', ['user_id' => $event->user->id, 'name' => $event->user->name]);

        try {
            // Get all admin users through the role_user relationship
            $admins = \App\Models\User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->get();

            Log::info('Found admin users:', ['count' => $admins->count(), 'admins' => $admins->pluck('id')->toArray()]);

            // Create notification for each admin
            foreach ($admins as $admin) {
                try {
                    $notification = \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title' => 'New User Registration',
                        'message' => "A new user {$event->user->name} has registered in the system.",
                        'type' => NotificationType::USER_REGISTRATION->value,
                        'is_read' => false
                    ]);

                    Log::info('Created notification:', [
                        'notification_id' => $notification->id,
                        'admin_id' => $admin->id,
                        'user_id' => $event->user->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create notification for admin ' . $admin->id . ': ' . $e->getMessage());
                    Log::error('Exception trace: ' . $e->getTraceAsString());
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in NotifyAdmin listener: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
        }
    }
}
