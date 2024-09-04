<?php

namespace App\Helpers;

use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GetClientSunat
{
    public function getClient($document)
    {
  
        $documentLength = Str::length(trim($document));

        try {

            $client = Client::where('document', $document)->first();

            if ($client) {
                $json = [
                    'success' => true,
                    'name' => $client->name,
                    'direccion' => $client->direccion,
                    'nacimiento' => $client->nacimiento,
                    'telefono' => $client->telefono,
                ];
            } else {

                $token = config('services.apisunat.token');
                $urlruc = config('services.apisunat.urlruc');
                $urldni = config('services.apisunat.urldni');

                $urldni2 = 'https://dniruc.apisperu.com/api/v1/dni/';
                $token2 = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImxleW5lcnZlZ2EwNDEzQGdtYWlsLmNvbSJ9.DoE6oH5Dix6-jsmzOFmAIURQ6el3fg2vFN8HlmfQOcM';
                $json = [];

                if ($documentLength == 8) {

                    $response = Http::withHeaders([
                        'Referer' => 'https://apis.net.pe/consulta-dni-api',
                        'Authorization' => 'Bearer ' . $token,
                    ])->get($urldni . $document);

                    if ($response) {
                        $json = [
                            'success' => true,
                            'name' => $response["nombre"],
                            'direccion' => $response["direccion"]
                        ];
                    } else {
                        $response = Http::get($urldni2  . $document . '?token=' . $token2);
                        if (isset($response["success"])) {
                            $json = [
                                'success' => true,
                                'name' => $response["nombres"] . " " . $response['apellidoPaterno'] . " " . $response['apellidoMaterno'],
                                'direccion' => ""
                            ];
                        } else {
                            $json = [
                                'success' => false,
                                'mensaje' => "No se pudo obtener los datos del cliente."
                            ];
                        }
                    }
                } else {

                    $response = Http::withHeaders([
                        'Referer' => 'http://apis.net.pe/api-ruc',
                        'Authorization' => 'Bearer ' . $token,
                    ])->get($urlruc . $document);


                    if ($response) {
                        $json = [
                            'success' => true,
                            'name' => $response["nombre"],
                            'direccion' => $response["direccion"] . " - " . $response["departamento"] . " - " .
                                $response["provincia"] . " - " . $response["distrito"],
                            'ubigeo' => $response["ubigeo"],
                            'estado' => $response["estado"],
                            'condicion' => $response["condicion"],
                        ];
                    } else {
                        $json = [
                            'success' => false,
                            'mensaje' => "No se pudo obtener los datos del cliente."
                        ];
                    }
                }
            }

            return response()->json($json);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}
