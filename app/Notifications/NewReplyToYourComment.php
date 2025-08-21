<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReplyToYourComment extends Notification
{
    use Queueable;

    protected $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail'];
        return ['database']; 
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'replier_name' => $this->reply->user->name,
            'post_title' => $this->reply->post->title,
            'message' => "{$this->reply->user->name} membalas komentar Anda di postingan: \"{$this->reply->post->title}\".",
            'url' => route('post.show', ['username' => $this->reply->post->user->username, 'post' => $this->reply->post->slug]) . '#comment-' . $this->reply->id,
        ];
    }
}
