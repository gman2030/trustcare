<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SparePartOrder;

class NewOrderNotification extends Notification
{
    use Queueable;
    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   public function via(object $notifiable): array
{
    return ['mail', 'database']; // أضف database لكي يتم التخزين في الجدول الذي أنشأناه الآن
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
    public function toArray($notifiable)
    {
        return [
            'message' => 'New spare parts request from ' . $this->order->worker->name,
            'order_id' => $this->order->id,
            'url' => route('admin.orders.index'), // رابط صفحة الطلبات
        ];
    }
}
