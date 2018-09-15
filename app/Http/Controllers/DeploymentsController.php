<?php

namespace App\Http\Controllers;

use App\Deployment;
use Illuminate\Http\Request;

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
            return $event;
        }
    }

    public function enroute($id)
    {
        /** @var Deployment $deployment */
        $deployment = Deployment::with('events')->find($id);

        if ($deployment->events->contains('name', 'launched')) {
            $event = $deployment->events()->create([
                'name' => 'enroute',
            ]);
            $event->load('deployment');
            return $event;
        }
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
            return $event;
        }
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
            return $event;
        }
    }
}
