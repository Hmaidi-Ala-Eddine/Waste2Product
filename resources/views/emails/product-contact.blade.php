<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message pour votre produit</title>
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
            background: linear-gradient(135deg, #4a90e2, #5aa3f0);
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
            border-left: 4px solid #4a90e2;
        }
        .contact-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
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
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background: #357abd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛍️ Nouveau message pour votre produit</h1>
        <p>Quelqu'un s'intéresse à votre produit sur Waste2Product</p>
    </div>

    <div class="content">
        <h2>Détails du produit</h2>
        <div class="product-info">
            <h3>{{ $product->name }}</h3>
            <p><strong>Catégorie:</strong> {{ ucfirst($product->category) }}</p>
            <p><strong>Prix:</strong> {{ $product->formatted_price }}</p>
            <p><strong>Condition:</strong> {{ ucfirst($product->condition) }}</p>
            <p><strong>Statut:</strong> {{ ucfirst($product->status) }}</p>
        </div>

        <h2>Informations du contact</h2>
        <div class="contact-info">
            <p><strong>Nom:</strong> {{ $contactData['name'] }}</p>
            <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
            @if(isset($contactData['phone']))
                <p><strong>Téléphone:</strong> {{ $contactData['phone'] }}</p>
            @endif
        </div>

        <h2>Message</h2>
        <div class="message-box">
            <p>{{ $contactData['message'] }}</p>
        </div>

        <div style="text-align: center;">
            <a href="mailto:{{ $contactData['email'] }}" class="btn">Répondre par email</a>
        </div>

        <div class="footer">
            <p>Ce message a été envoyé via le système Waste2Product</p>
            <p>Vous recevez cet email car quelqu'un s'est intéressé à votre produit.</p>
        </div>
    </div>
</body>
</html>
