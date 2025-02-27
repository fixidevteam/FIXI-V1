<?php

namespace App\Notifications;

use App\Models\garage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientAddRdvManuelle extends Notification
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
        $url = route('RDV.show', $this->reservation);
        $garage = garage::where('ref', $this->reservation->garage_ref)->first();

        return (new MailMessage)
            ->subject('📌 Votre demande de rendez-vous est en attente de confirmation')
            ->view('emails.PendingRdvClient', [
                'title' => '⏳ Votre demande de réservation a bien été enregistrée !',
                'garage' => $garage,
                'reservation' => $this->reservation, // Pass reservation data
                'messageContent' => "Nous avons bien reçu votre demande de rendez-vous chez {$garage->name}. 
                Votre demande est en attente de confirmation par le garage. Vous recevrez une notification dès qu'il sera validé.",
                'actionText' => 'Voir la demande',
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
