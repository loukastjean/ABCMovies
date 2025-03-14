<?php
declare(strict_types=1);

$email = "gexij90758@eoilup.com";
$password = "Gexij90758@eoilup.com";
$access_token = "";
$claims_token = "";

# Le URL de login de toutv
$login_url = "https://login.cbc.radio-canada.ca/bef1b538-1950-4283-9b27-b096cbc18070/B2C_1A_SSO_Login/SelfAsserted";
# Y veut absoulement un URL de redirection, so je donne celui par defaut de toutv
$redirected_url = "https://ici.tou.tv/auth-changed";

# Les droits qu'on accorde au login de microsoft (J'ai juste pris ceux par defaut)
$scope = urlencode(string: "
openid
offline_access
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/email
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.account.create
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.account.delete
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.account.info
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.account.modify
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.account.reset-password
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.account.send-confirmation-email
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/id.write
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/media-drmt
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/media-meta
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/media-validation
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/media-validation.read
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/metrik
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/norah.write
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/oidc4ropc
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/ott-profiling
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/ott-subscription
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/profile
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/subscriptions.validate
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/subscriptions.write
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/toutv
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/toutv-presentation
https://rcmnb2cprod.onmicrosoft.com/84593b65-0ef6-4a72-891c-d351ddd50aab/toutv-profiling
");

function Get_Access_Token() {
    $ch = curl_init();

    curl_setopt_array(handle: $ch, options: array(
        CURLOPT_URL => "https://services.radio-canada.ca/ott/catalog/v1/toutv/search?device=web&pageNumber=1&pageSize=999999999&term=$",
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE
        ));
}


// Azure B2C Configuration
$tenantName = "rcmnb2cprod";
$clientId = "84593b65-0ef6-4a72-891c-d351ddd50aab";
$policyName = "B2C_1A_ExternalClient_FrontEnd_Login"; // Replace with your policy name // B2C_1_SignIn

// Token Endpoint URL
$tokenUrl = "https://$tenantName.b2clogin.com/$tenantName.onmicrosoft.com/$policyName/oauth2/v2.0/token";

// POST Data for ROPC Flow
$postData = [
    'grant_type' => 'password',
    'client_id' => $clientId,
    'scope' => $scope,
    'username' => $email,
    'password' => $password,
];

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Add headers
$headers = [
    'Content-Type: application/x-www-form-urlencoded',
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


$response = curl_exec($ch);


curl_close($ch);

echo "Authentication successful!\n";

$responseData = json_decode($response, true);

$accessToken = $responseData['access_token'] ?? null;
$refreshToken = $responseData['refresh_token'] ?? null;
$idToken = $responseData['id_token'] ?? null;

echo "Access Token: $accessToken\n";
echo "Refresh Token: $refreshToken\n";
echo "ID Token: $idToken\n";

?>
