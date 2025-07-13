<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BillReminderNotification extends Notification
{
    use Queueable;

    public $type;
    public $items;

    public function __construct($type, $items)
    {
        $this->type = $type;
        $this->items = $items;
    }

    public function via($notifiable)
    {
        return ['database']; // agar tersimpan di database
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->type === 'bill' ? 'Tagihan Jatuh Tempo' : 'Hutang Jatuh Tempo',
            'message' => 'Terdapat ' . $this->items->count() . ' ' . ($this->type === 'bill' ? 'tagihan' : 'hutang') . ' yang akan jatuh tempo dalam 7 hari.',
            'url' => $this->type === 'bill' ? route('bill.index') : route('debt.index'),
        ];
    }
}
