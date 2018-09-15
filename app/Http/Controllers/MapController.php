<?php

namespace App\Http\Controllers;

use App\AED;
use Illuminate\Http\Request;
use JavaScript;

class MapController extends Controller
{

    public function test()
    {

        JavaScript::put([
            'aedLocations' => AED::where('city','København K')->take(10)->get(),
            'aedClosest' => AED::where('city','København K')->first()
        ]);

        return view('map');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $location = ['latitude' => 55.681878, 'longitude' => 12.590597];
        $lat = $location['latitude'];
        $long = $location['longitude'];

        $aeds = AED::where('latitude', '<=', $lat + .003)
                   ->where('latitude', '>=', $lat - .003)
                   ->where('longitude', '<=', $long + .004)
                   ->where('longitude', '>=', $long - .004)
                   ->get();

        $geotools = new \League\Geotools\Geotools();
        $result = collect([]);
        $aeds->each(function(AED $aed, $index) use (&$result, $geotools, $lat, $long) {
            $coordA   = new \League\Geotools\Coordinate\Coordinate([$lat, $long]);
            $coordB   = new \League\Geotools\Coordinate\Coordinate([$aed->latitude, $aed->longitude]);
            $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);
            $result->push(array_merge($aed->toArray(), ['distance' => $distance->flat()]));
        });

        return $result->sortBy('distance')->values();
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
