<?php

namespace App\Controllers;

class OngkirController extends BaseController
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('COST_KEY', 'ffecd68fbfb82b40792a758ae5e688b7');
    }

    public function index()
    {
        $addressFile = WRITEPATH . 'store_address.json';
        $storeAddress = ['id' => '17473', 'name' => 'GROGOL, GROGOL PETAMBURAN, JAKARTA BARAT, DKI JAKARTA, 11450'];
        if (file_exists($addressFile)) {
            $storeAddress = json_decode(file_get_contents($addressFile), true);
        }

        return view('v_ongkir', ['store_address' => $storeAddress]);
    }

    public function lokasi()
    {
        $search = $this->request->getGet('search');
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request(
            'GET',
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
                'http_errors' => false
            ]
        );

        $body = json_decode($response->getBody(), true);
        if (isset($body['data'])) {
            return $this->response->setJSON($body['data']);
        }
        return $this->response->setJSON([]);
    }

    public function biaya()
    {
        $origin = $this->request->getPost('origin');
        $destination = $this->request->getPost('destination');
        $weight = $this->request->getPost('weight');
        $courier = $this->request->getPost('courier');

        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'multipart' => [
                    [ 'name' => 'origin', 'contents' => $origin ],
                    [ 'name' => 'destination', 'contents' => $destination ],
                    [ 'name' => 'weight', 'contents' => $weight ],
                    [ 'name' => 'courier', 'contents' => $courier ]
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
                'http_errors' => false
            ]
        );

        $body = json_decode($response->getBody(), true);
        return $this->response->setJSON($body);
    }
}
