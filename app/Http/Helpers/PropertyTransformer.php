<?php

namespace App\Http\helpers;

class PropertyTransformer extends Transformer {

    public function transform($property)
    {
        return [
            'address' => [
                'line1' => $property['line1'] ?: null,
                'line2' => $property['line2'] ?: null,
                'line3' => $property['line3'] ?: null,
                'town' => $property['town'] ?: null,
                'city' => $property['city'] ?: null,
                'country' => $property['country'] ?: null,
                'postcode' => $property['postcode'] ?: null,
                'geocode' => $property['geocode'] ?: null,
            ],
            'meta' => json_decode($property['metadata']) ?: []
        ];
    }
}