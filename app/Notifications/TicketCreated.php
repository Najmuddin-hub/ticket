<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketCreated extends Notification
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tiket Baharu: ' . $this->ticket->title)
            ->line('Tiket baharu telah dibuat.')
            ->line('Tajuk: ' . $this->ticket->title)
            ->line('Kategori: ' . $this->ticket->category->name)
            ->line('Dibuat Oleh: ' . $this->ticket->user->name)
            ->action('Lihat Tiket', route('tickets.show', $this->ticket))
            ->line('Sila semak tiket untuk maklumat lanjut.');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'created_by' => $this->ticket->user->name,
            'category' => $this->ticket->category->name,
            'message' => "Tiket baharu '{$this->ticket->title}' dibuat oleh {$this->ticket->user->name}",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}