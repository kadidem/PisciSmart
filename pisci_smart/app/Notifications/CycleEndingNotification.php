<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Cycle;

class CycleEndingNotification extends Notification
{
    use Queueable;

    protected $cycle;

    public function __construct(Cycle $cycle)
    {
        $this->cycle = $cycle;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Ici, on utilise le canal 'database' pour stocker la notification dans la base de données
        return ['database'];
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
            'cycle_id' => $this->cycle->id,
            'num_cycle' => $this->cycle->NumCycle,
            'message' => 'Votre cycle numéro ' . $this->cycle->NumCycle . ' approche de sa date de fin. Vous pouvez choisir de prolonger ou de terminer le cycle.',
            'actions' => [
                'prolonger_url' => url('/cycles/prolonger/' . $this->cycle->id),
                'terminer_url' => url('/cycles/terminer/' . $this->cycle->id),
            ]
        ];
    }
}
