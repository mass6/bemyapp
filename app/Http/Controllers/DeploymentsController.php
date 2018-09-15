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
        return Deployment::all();
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
        /** @var Deployment $deployment */
        $deployment = Deployment::find($id);

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function launch($id)
    {
        /** @var Deployment $deployment */
        $deployment = Deployment::with('events')->find($id);


        if (!$deployment->events->contains('name', 'launched')) {
            $event = $deployment->events()->create([
                'name' => 'launched',
            ]);
            $event->load('deployment');
        }

        return $event;
    }

    public function arrive($id)
    {
        /** @var Deployment $deployment */
        $deployment = Deployment::with('events')->find($id);

        if ($deployment->events->contains('name', 'launched') && !$deployment->events->contains('name', 'arrived')) {
            $event = $deployment->events()->create([
                'name' => 'arrived',
            ]);
            $event->load('deployment');
        }

        return $event;
    }

    public function deploy($id)
    {
        /** @var Deployment $deployment */
        $deployment = Deployment::with('events')->find($id);

        if ($deployment->events->contains('name', 'arrived') && !$deployment->events->contains('name', 'deployed')) {
            $event = $deployment->events()->create([
                'name' => 'deployed',
            ]);
            $event->load('deployment');
        }

        return $event;
    }
}
