<?php

namespace App\Http\Controllers;

use App\Http\helpers\PropertyTransformer;
use App\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Property;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Properties $properties
     * @return Properties[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Properties $properties)
    {
        return  response()->json([
            'data' => (new PropertyTransformer)->transformCollection($properties->all()->toArray())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Properties $properties
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Properties $properties)
    {
        $validator = Validator::make($request->all(),[
            'line1' => 'required|string|max:100',
            'line2' => 'nullable|string|max:100',
            'line3' => 'nullable|string|max:100',
            'town' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'error' =>  $validator->messages()
            ];
            return response()->json($response, 406);
        }

        $address = [
            'line1' => $request->input('line1') ?: '',
            'line2' => $request->input('line2') ?: '',
            'line3' => $request->input('line3') ?: '',
            'town' => $request->input('town') ?: '',
            'city' => $request->input('city') ?: '',
            'country' => $request->input('country') ?: '',
            'postcode' => $request->input('postcode') ?: ''
        ];

       $addressAsString = implode(',', $address);
       $geometry = $properties->getGeometryLocation($addressAsString);

       if (count($geometry['response'] ) > 1 ){
           $response = [
             'message'  => 'Multiple locations found'
           ];

           return response()->json($response, 400);
       }

        if (count($geometry['response']) == 0 ){
            $response = [
                'message'  => 'No locations found'
            ];

            return response()->json($response, 400);
        }

       $address['geocode'] = \GuzzleHttp\json_encode($geometry['response']);

       $properties->create($address);

        $response = [
            'data' => $address
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Properties $properties
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Properties $properties, $id)
    {
        $address = implode(' ', explode('+', $id));
        $property = $properties->where('line1', '=', $address)->latest()->get();

        if (! $property) {
            return response()->json([
                'error' =>[
                    'message' => 'address not found'
                ]
            ], 404);
        }

        return response()->json([
                'data' => (new PropertyTransformer)->transformCollection($property->toArray())
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Properties  $properties
     * @return \Illuminate\Http\Response
     */
    public function edit(Properties $properties)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Properties  $properties
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Properties $properties)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Properties  $properties
     * @return \Illuminate\Http\Response
     */
    public function destroy(Properties $properties)
    {
        //
    }
}
