<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de réservation</title>
</head>
<body>
    <h2>Confirmation de réservation</h2>
    
    <p>Bonjour {{ $reservationData['name'] }},</p>
    
    <p>Votre réservation pour le produit <strong>{{ $product->name }}</strong> a été confirmée.</p>
    
    <div style="background: #f8f9fa; padding: 15px; margin: 20px 0;">
        <h3>Détails du produit :</h3>
        <ul>
            <li><strong>Nom :</strong> {{ $product->name }}</li>
            <li><strong>Prix :</strong> {{ $product->formatted_price }}</li>
            <li><strong>Catégorie :</strong> {{ $product->category }}</li>
            <li><strong>État :</strong> {{ ucfirst($product->condition) }}</li>
        </ul>
    </div>
    
    <div style="background: #e3f2fd; padding: 15px; margin: 20px 0;">
        <h3>Vos informations :</h3>
        <ul>
            <li><strong>Nom :</strong> {{ $reservationData['name'] }}</li>
            <li><strong>Téléphone :</strong> {{ $reservationData['phone'] }}</li>
            @if($reservationData['message'])
                <li><strong>Message :</strong> {{ $reservationData['message'] }}</li>
            @endif
        </ul>
    </div>
    
    <p>Nous vous contacterons bientôt pour finaliser votre achat.</p>
    
    <p>Merci pour votre confiance !</p>
    
    <hr>
    <p><small>Cet email a été envoyé automatiquement par le système Waste2Product.</small></p>
</body>
</html>

