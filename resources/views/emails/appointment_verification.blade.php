<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de Rendez-vous FIXI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #333333;
            font-size: 24px;
        }
        .content {
            color: #555555;
            line-height: 1.6;
        }
        .code {
            font-size: 28px;
            font-weight: bold;
            color: #007BFF;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777777;
        }
        .footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔑 Vérifiez votre rendez-vous FIXI</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $userName }},</p>
            <p>Vous avez demandé un rendez-vous sur <a href="http://FIXI.ma">FIXI.ma</a>.</p>
            <p>Pour confirmer votre réservation, veuillez utiliser le code de vérification suivant :</p>
            <div class="code"><strong>{{ $code }}</strong></div>
            <p>⏳ Ce code est valide pendant 10 minutes.</p>
            <p>Si vous n’avez pas initié cette demande, veuillez ignorer ce message.</p>
            <p>📌 Besoin d’aide ? Contactez notre support à <a href="mailto:contact@fixi.ma">contact@fixi.ma</a>.</p>
        </div>
        <div class="footer">
            <p>Merci d’utiliser FIXI,</p>
            <p>🚗 L’équipe <a href="http://FIXI.ma">FIXI.ma</a></p>
        </div>
    </div>
</body>
</html>