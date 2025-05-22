<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;


class PaypalController extends Controller
{
    public function getAccessToken(Request $request)
    {
        //$clientId = env('PAYPAL_CLIENT_ID');
        $clientId = 'AX1KEHhem6-_NEIUQEN3q-QrIv1HPFIOZkvRODv1C8hhqQOQ9eAKiwuXUfPQkXISnaxvSwZygw8k7mbC';
        //$clientSecret = env('PAYPAL_CLIENT_SECRET');
        $clientSecret = 'EIfC3t8z3HEwMdy90YokZ4caWUd3cWj-3-PmToYOmiHpqJd9i3Ppt8DKHNkiBWg7_51E9U-KVjmr-HAd';
        $url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';

        // Verificar que las credenciales estén presentes
        if (!$clientId || !$clientSecret) {
            return response()->json(['error' => 'Missing PayPal credentials'], 400);
        }

        // Preparar los encabezados y datos
        $headers = [
            'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $data = [
            'grant_type' => 'client_credentials',
        ];

        // Realizar la solicitud POST
        $response = Http::withHeaders($headers)->asForm()->post($url, $data);

        // Verificar la respuesta
        if ($response->successful()) {
            return response()->json([
                'access_token' => $response->json('access_token'),
                'expires_in' => $response->json('expires_in'),
            ]);
        } else {
            // Manejar errores de la API de PayPal
            return response()->json([
                'error' => 'Failed to get access token',
                'details' => $response->json(),
            ], $response->status());
        }
        
    }
}
