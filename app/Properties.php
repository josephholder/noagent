<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{
    protected $table = 'properties';

    protected $guarded = ['id'];

    public function getGeometryLocation($address)
    {
        $options = [
            'query' => [
                'address' => str_replace(' ' , '+', $address),
                'key'=> env('GOOGLE_MAPS_API_KEY')
            ]
        ];

        $services = new GoogleMapsService(
            'GET',
            '',
            $options
        );

        $response = $services->send();

        $geometry = ($response['data']);
        $geometryDecodedData = json_encode( \GuzzleHttp\json_decode($geometry));
        $geometryData = \GuzzleHttp\json_decode($geometryDecodedData);

        $geometryResponse = [];
        foreach($geometryData->results as $value) {
            $geometryResponse[] = $value->geometry;
        }

        return [
            'response' => $geometryResponse
        ];
    }
}
