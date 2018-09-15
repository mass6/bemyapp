<?php

namespace App\Http\Controllers;

use App\AED;
use App\Deployment;
use Illuminate\Http\Request;
use League\Geotools\Coordinate\Coordinate;

class DeploymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'here';
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $latitude =  $request->get('latitude');
        $longitude =  $request->get('longitude');

        $deployment = Deployment::create(['latitude' => $latitude, 'longitude' => $longitude]);

        return $deployment;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function launch($id)
    {
        /** @var Deployment $deployment */
        return $deployment = Deployment::find($id);

        $latitude = $deployment->getLatitude();
        $longitude = $deployment->getLongitude();

        $aeds = AED::where('latitude', '<=', $latitude + .003)
                   ->where('latitude', '>=', $latitude - .003)
                   ->where('longitude', '<=', $longitude + .004)
                   ->where('longitude', '>=', $longitude - .004)
                   ->get();

        $geotools = new \League\Geotools\Geotools();
        $result = collect([]);
        $aeds->each(function(AED $aed, $index) use (&$result, $geotools, $latitude, $longitude) {
            $coordA   = new Coordinate([$latitude, $longitude]);
            $coordB   = new Coordinate([$aed->getLatitude(), $aed->getLongitude()]);
            $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);
            $result->push(array_merge($aed->toArray(), ['distance' => $distance->flat()]));
        });

        return $result->sortBy('distance')->values();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
