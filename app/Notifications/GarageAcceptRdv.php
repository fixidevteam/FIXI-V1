<?php

namespace App\Notifications;

use App\Models\garage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GarageAcceptRdv extends Notification
{
    use Queueable;

    private $reservation;
    private $message;



    public function __construct($reservation, $message)
    {
        $this->reservation = $reservation;
        $this->message = $message;
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
            ->subject('✅ Réservation Acceptée - ' . $this->reservation->client_name)
            ->view('emails.AcceptedRdvClient', [
                'title' => '✅ Votre réservation a été accepté',
                'garage' => $garage,
                'reservation' => $this->reservation, // Pass reservation data
                'messageContent' => $this->message, // Custom message
                'actionText' => 'Voir la réservation',
                'dashboardUrl' => $url // Example link to reservation
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
