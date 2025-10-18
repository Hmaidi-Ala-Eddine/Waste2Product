<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réservation pour votre produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .product-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .reservation-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #17a2b8;
        }
        .alert {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎯 Nouvelle réservation pour votre produit</h1>
        <p>Quelqu'un a réservé votre produit sur Waste2Product</p>
    </div>

    <div class="content">
        <div class="alert">
            <strong>⚠️ Action requise:</strong> Votre produit a été réservé. Vous avez 24 heures pour contacter l'acheteur potentiel.
        </div>

        <h2>Détails du produit réservé</h2>
        <div class="product-info">
            <h3>{{ $product->name }}</h3>
            <p><strong>Catégorie:</strong> {{ ucfirst($product->category) }}</p>
            <p><strong>Prix:</strong> {{ $product->formatted_price }}</p>
            <p><strong>Condition:</strong> {{ ucfirst($product->condition) }}</p>
            <p><strong>Nouveau statut:</strong> <span style="color: #ffc107; font-weight: bold;">RÉSERVÉ</span></p>
        </div>

        <h2>Informations de la réservation</h2>
        <div class="reservation-info">
            <p><strong>Nom:</strong> {{ $reservationData['name'] }}</p>
            <p><strong>Email:</strong> {{ $reservationData['email'] }}</p>
            <p><strong>Téléphone:</strong> {{ $reservationData['phone'] }}</p>
            <p><strong>Date de réservation:</strong> {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        @if(isset($reservationData['message']) && $reservationData['message'])
            <h2>Message du client</h2>
            <div class="message-box">
                <p>{{ $reservationData['message'] }}</p>
            </div>
        @endif

        <div style="text-align: center;">
            <a href="mailto:{{ $reservationData['email'] }}" class="btn">Contacter le client</a>
        </div>

        <div class="alert">
            <strong>📋 Prochaines étapes:</strong>
            <ul style="margin: 10px 0;">
                <li>Contactez le client dans les 24 heures</li>
                <li>Confirmez la vente ou annulez la réservation</li>
                <li>Mettez à jour le statut du produit (vendus/disponible)</li>
            </ul>
        </div>

        <div class="footer">
            <p>Ce message a été envoyé via le système Waste2Product</p>
            <p>Votre produit est maintenant marqué comme "Réservé" et n'apparaîtra plus dans les recherches.</p>
        </div>
    </div>
</body>
</html>
