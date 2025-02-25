<?php

namespace App\Notifications;

use App\Models\garage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatedRdv extends Notification
{
    use Queueable;

    private $reservation;
    private $message;
    private $ifAdmin;
    private $admin;


    public function __construct($reservation, $message, $ifAdmin, $admin)
    {
        $this->reservation = $reservation;
        $this->message = $message;
        $this->ifAdmin = $ifAdmin;
        $this->admin = $admin;
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
        if ($this->ifAdmin) {

            $url = route('admin.gestionReservations.show', $this->reservation);
            $garage = garage::where('ref', $this->reservation->garage_ref)->first();
            return (new MailMessage)
                ->subject('❌ Réservation Modifiée - ' . $this->reservation->client_name)
                ->view('emails.UpdatedRdv', [
                    'title' => '❌ Une réservation a été modifiée',
                    'garage' => $garage,
                    'admin' => $this->admin,
                    'reservation' => $this->reservation, // Pass reservation data
                    'messageContent' => $this->message, // Custom message
                    'actionText' => 'Confirmer le rendez-vous',
                    'dashboardUrl' => $url // Example link to reservation
                ]);
        } else {

            $url = route('mechanic.reservation.show', $this->reservation);
            $garage = garage::where('ref', $this->reservation->garage_ref)->first();
            return (new MailMessage)
                ->subject('❌  Réservation annulée -' . $this->reservation->client_name)
                ->view('emails.UpdatedRdv', [
                    'title' => '❌ Une réservation a été annulée',
                    'garage' => $garage,
                    'reservation' => $this->reservation, // Pass reservation data
                    'messageContent' => $this->message, // Custom message
                    'actionText' => 'Confirmer le rendez-vous',
                    'dashboardUrl' => $url // Example link to reservation
                ]);
        }
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
