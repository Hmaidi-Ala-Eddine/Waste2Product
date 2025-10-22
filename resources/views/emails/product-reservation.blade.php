<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reservation Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 24px;
        }
        .product-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .product-info h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        .reservation-details {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            color: #555;
        }
        .notes {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #17a2b8;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🛍️ Product Reservation Request</h1>
            <p>Someone is interested in reserving a product from your store</p>
        </div>

        <div class="product-info">
            <h3>📦 Product Details</h3>
            <div class="detail-row">
                <span class="detail-label">Product Name:</span>
                <span class="detail-value">{{ $product->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Category:</span>
                <span class="detail-value">{{ ucfirst($product->category) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Price:</span>
                <span class="detail-value">{{ $product->isFree() ? 'FREE' : number_format($product->price, 2) . ' TND' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Condition:</span>
                <span class="detail-value">{{ ucfirst($product->condition ?? 'Not specified') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Seller:</span>
                <span class="detail-value">{{ $product->user->name }}</span>
            </div>
        </div>

        <div class="reservation-details">
            <h3>👤 Reservation Request Details</h3>
            <div class="detail-row">
                <span class="detail-label">First Name:</span>
                <span class="detail-value">{{ $reservationData['first_name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Name:</span>
                <span class="detail-value">{{ $reservationData['last_name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $reservationData['email'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Request Date:</span>
                <span class="detail-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        @if(!empty($reservationData['notes']))
        <div class="notes">
            <h4>📝 Additional Notes:</h4>
            <p>{{ $reservationData['notes'] }}</p>
        </div>
        @endif

        <div class="contact-info">
            <h4>📞 Next Steps</h4>
            <p>Please contact the customer at <strong>{{ $reservationData['email'] }}</strong> to discuss the reservation details and arrange pickup or delivery.</p>
        </div>

        <div class="footer">
            <p>This email was sent from your Waste2Product platform.</p>
            <p>Please respond to the customer as soon as possible to maintain good customer service.</p>
        </div>
    </div>
</body>
</html>