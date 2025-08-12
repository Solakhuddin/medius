<?php

namespace App\Notifications;

use App\Models\Comment; 
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentOnPost extends Notification
{
    use Queueable;

    protected $comment;
    protected $commenter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment, User $commenter)
    {
        $this->comment = $comment;
        $this->commenter = $commenter;
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
            'commenter_name' => $this->commenter->name,
            'post_title' => $this->comment->post->title,
            'message' => "{$this->commenter->name} mengomentari postingan Anda: \"{$this->comment->post->title}\".",
            'url' => route('post.show', ['username' => $this->comment->post->user->username, 'post' => $this->comment->post->slug]) . '#comment-' . $this->comment->id,
        ];
    }
}
