<?php

// Script pour configurer MySQL
echo "Configuration de la base de données MySQL...\n";

// Configuration de la base de données
$config = [
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'waste2product',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
];

// Lire le fichier .env s'il existe
$envFile = '.env';
$envContent = '';

if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
} else {
    // Créer un fichier .env basique
    $envContent = "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n\nLOG_CHANNEL=stack\nLOG_DEPRECATIONS_CHANNEL=null\nLOG_LEVEL=debug\n\nDB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=waste2product\nDB_USERNAME=root\nDB_PASSWORD=\n\nBROADCAST_DRIVER=log\nCACHE_DRIVER=file\nFILESYSTEM_DISK=local\nQUEUE_CONNECTION=sync\nSESSION_DRIVER=database\nSESSION_LIFETIME=120\n\nMEMCACHED_HOST=127.0.0.1\n\nREDIS_HOST=127.0.0.1\nREDIS_PASSWORD=null\nREDIS_PORT=6379\n\nMAIL_MAILER=smtp\nMAIL_HOST=mailpit\nMAIL_PORT=1025\nMAIL_USERNAME=null\nMAIL_PASSWORD=null\nMAIL_ENCRYPTION=null\nMAIL_FROM_ADDRESS=\"hello@example.com\"\nMAIL_FROM_NAME=\"\${APP_NAME}\"\n\nAWS_ACCESS_KEY_ID=\nAWS_SECRET_ACCESS_KEY=\nAWS_DEFAULT_REGION=us-east-1\nAWS_BUCKET=\nAWS_USE_PATH_STYLE_ENDPOINT=false\n\nPUSHER_APP_ID=\nPUSHER_APP_KEY=\nPUSHER_APP_SECRET=\nPUSHER_HOST=\nPUSHER_PORT=443\nPUSHER_SCHEME=https\nPUSHER_APP_CLUSTER=mt1\n\nVITE_APP_NAME=\"\${APP_NAME}\"\nVITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"\nVITE_PUSHER_HOST=\"\${PUSHER_HOST}\"\nVITE_PUSHER_PORT=\"\${PUSHER_PORT}\"\nVITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"\nVITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"";
}

// Mettre à jour les variables d'environnement
foreach ($config as $key => $value) {
    $pattern = "/^{$key}=.*/m";
    $replacement = "{$key}={$value}";
    
    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
    } else {
        $envContent .= "\n{$key}={$value}";
    }
}

// Écrire le fichier .env
file_put_contents($envFile, $envContent);

echo "Fichier .env configuré avec MySQL\n";
echo "Base de données: waste2product\n";
echo "Host: 127.0.0.1\n";
echo "Port: 3306\n";
echo "Username: root\n";
echo "Password: (vide)\n";
