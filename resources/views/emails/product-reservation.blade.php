<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvelle réservation de produit</title>
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
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
            border-left: 4px solid #667eea;
        }
        .reservation-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .message-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #5a6fd8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛍️ Nouvelle réservation de produit</h1>
        <p>Quelqu'un souhaite réserver votre produit !</p>
    </div>

    <div class="content">
        <h2>Informations du produit</h2>
        <div class="product-info">
            <h3>{{ $product->name }}</h3>
            <p><strong>Catégorie:</strong> {{ ucfirst($product->category) }}</p>
            <p><strong>Prix:</strong> {{ $product->price ? number_format($product->price, 2) . ' TND' : 'Gratuit' }}</p>
            <p><strong>État:</strong> {{ ucfirst($product->condition ?? 'Non spécifié') }}</p>
            @if($product->description)
                <p><strong>Description:</strong> {{ $product->description }}</p>
            @endif
        </div>

        <h2>Informations du demandeur</h2>
        <div class="reservation-info">
            <p><strong>Nom:</strong> {{ $reservationData['name'] }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $reservationData['email'] }}">{{ $reservationData['email'] }}</a></p>
            <p><strong>Téléphone:</strong> {{ $reservationData['phone'] }}</p>
            <p><strong>Date de demande:</strong> {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        <h2>Message du demandeur</h2>
        <div class="message-box">
            <p><em>"{{ $reservationData['message'] }}"</em></p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('front.shop.show', $product->id) }}" class="btn">Voir le produit</a>
            <a href="mailto:{{ $reservationData['email'] }}" class="btn">Répondre par email</a>
        </div>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement par le système Waste2Product.</p>
        <p>Vous recevez cet email car vous êtes le propriétaire de ce produit.</p>
    </div>
</body>
</html>