<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class Replied extends Notification
{
    use Queueable;

    private $user_id;
    private $addon_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_id, $addon_id)
    {
        $this->user_id = $user_id;
        $this->addon_id = $addon_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user_id,
            'addon_id' => $this->addon_id,
            'message' => 'replied to your commented on'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
