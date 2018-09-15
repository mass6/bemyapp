<?php

namespace App\Http\Controllers;

use App\AED;
use App\Deployment;
use Illuminate\Http\Request;
use JavaScript;
use League\Geotools\Coordinate\Coordinate;

class MapController extends Controller
{

    public function test(Request $request)
    {

        $location = ['latitude' => $request->get('latitude'), 'longitude' => $request->get('longitude')];
        $lat = $location['latitude'];
        $long = $location['longitude'];



        $aeds = AED::where('latitude', '<=', $lat + .002)
            ->where('latitude', '>=', $lat - .002)
            ->where('longitude', '<=', $long + .002)
            ->where('longitude', '>=', $long - .002)
            ->get();

        $geotools = new \League\Geotools\Geotools();
        $result = collect([]);
        $aeds->each(function(AED $aed, $index) use (&$result, $geotools, $lat, $long) {
            $coordA   = new Coordinate([$lat, $long]);
            $coordB   = new Coordinate([$aed->latitude, $aed->longitude]);
            $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);
            $result->push(array_merge($aed->toArray(), ['distance' => $distance->flat()]));
        });


        $result = $result->sortBy('distance')->values();



        JavaScript::put([
            'incidentLocation' => $location,
            'aedLocations' => $result,
            'aedClosest' => $result->first(),
        ]);

        return view('map');

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
        //
    }

    public function latest()
    {
        $deployment = Deployment::orderBy('id', 'desc')->limit(1)->first();
        $this->buildData($deployment);

        return view('map');
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
        $this->buildData($deployment);

        return view('map');
    }

    protected function buildData($deployment)
    {
        $location = ['latitude' => $deployment->getLatitude(), 'longitude' => $deployment->getLongitude()];

        // 55.673733&longitude=12.564636
        $lat = $location['latitude'];
        $long = $location['longitude'];

        $aeds = AED::where('latitude', '<=', $lat + .012)
                   ->where('latitude', '>=', $lat - .012)
                   ->where('longitude', '<=', $long + .012)
                   ->where('longitude', '>=', $long - .012)
                   ->get();

        $geotools = new \League\Geotools\Geotools();
        $result = collect([]);
        $aeds->each(function(AED $aed, $index) use (&$result, $geotools, $lat, $long) {
            $coordA   = new Coordinate([$lat, $long]);
            $coordB   = new Coordinate([$aed->getLatitude(), $aed->getLongitude()]);
            $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);
            $result->push(array_merge($aed->toArray(), ['distance' => $distance->flat()]));
        });

        $result = $result->sortBy('distance')->values();
        JavaScript::put([
            'incidentLocation' => $location,
            'aedLocations' => $result,
            'aedClosest' => $result->first(),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
