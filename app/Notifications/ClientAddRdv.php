<?php

namespace App\Notifications;

use App\Models\garage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientAddRdv extends Notification
{
    use Queueable;

    private $reservation;



    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        
        $url = route('mechanic.reservation.show', $this->reservation);
        $garage = garage::where('ref', $this->reservation->garage_ref)->first();

        return (new MailMessage)
            ->subject('📅 Nouveau rendez-vous programmé chez ' . $garage->name)
            ->view('emails.ClientAddRdv', [
                'title' => '📢 Un client a pris un rendez-vous dans votre garage !',
                'garage' => $garage,
                'reservation' => $this->reservation, // Passer les données de la réservation
                'actionText' => 'Voir le rendez-vous',
                'dashboardUrl' => $url // Lien vers la réservation
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
