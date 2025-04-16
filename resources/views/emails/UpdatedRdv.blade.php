<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e3342f;
        }

        .alert-details {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .alert-details p {
            margin: 5px 0;
        }

        a.button {
            display: block;
            margin: 20px auto;
            padding: 15px 25px;
            background-color: #1f2937;
            /* Gris foncé */
            color: #ffffff;
            text-decoration: none;
            border-radius: 24px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        a.button:hover {
            background-color: #374151;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>{{ $title }}</h1>

        <h3>Bonjour, {{$admin->name ??  $garage->name }}</h3>
        <p>Nous vous informons que le rendez-vous suivant a été modifié par le client :</p>
        <div class="alert-details">
            <p style="text-align: center;"> <strong>{{ $garage->name }}</strong>  </p>
            <p style="text-align: center;"> <strong>{{ $garage->ref }}</strong>  </p>
            <hr style="height: 2px;">
            <p>🛠️ <strong>Nom du client :</strong> {{ $reservation->user_full_name }}</p>
            <p>📞 <strong>Téléphone du client :</strong> {{ $reservation->user_phone ?? 'N/A' }}</p>
            <p>📧 <strong>Email du client :</strong> {{ $reservation->user_email ?? 'N/A' }}</p>
            <p>🔧 <strong>Catégorie de service :</strong> {{ $reservation->categorie_de_service }}</p>
            <p>🚗 <strong>Modèle du véhicule :</strong> {{ $reservation->modele ?? 'N/a'}}</p>
            <p>🚙 <strong>N° de châssis (VIN) :</strong> {{ $reservation->vin ?? 'N/A' }}</p>
            <p>📝 <strong>Objet du RDV :</strong> {{ $reservation->objet_du_RDV  ?? 'N/A'}}</p>
            <p>📅 <strong>Date de la réservation :</strong> {{ $reservation->appointment_day }}</p>
            <p>⏰ <strong>Heure :</strong> {{ $reservation->appointment_time }}</p>
        </div>

        <p>Vous pouvez consulter les détails complets dans votre tableau de bord :</p>
        <a href="{{ $dashboardUrl }}" class="button">{{ $actionText }}</a>

        <p>{{ $messageContent }}</p>

        <footer>
            <p>Cordialement,<br>L'équipe FIXI+ et <a href="https://fixi.ma/">Fixi.ma</a></p>
        </footer>
    </div>
</body>

</html>