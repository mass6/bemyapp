<?php

namespace App\Http\Controllers;

use App\AED;
use App\Deployment;
use Illuminate\Http\Request;
use JavaScript;
use League\Geotools\Coordinate\Coordinate;

/**
 * Class MapController.
 */
class MapController extends Controller
{
    /**
     * Offset to apply for creating an AED search polygon
     */
    const COORDINATE_OFFSET = .004;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function test(Request $request)
    {
        $location = ['latitude' => $request->get('latitude'), 'longitude' => $request->get('longitude')];
        $lat = $location['latitude'];
        $long = $location['longitude'];

        $aeds = $this->getNearbyAeds($lat, $long);

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
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function latest(Request $request)
    {
        $deployment = Deployment::with('events')->orderBy('id', 'desc')->limit(1)->first();
        $this->buildData($deployment);

        if ($request->wantsJson()) {
            return response()->json($deployment);
        }

        return view('deployment', compact('deployment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        /** @var Deployment $deployment */
        $deployment = Deployment::with('events')->find($id);
        $this->buildData($deployment);

        if ($request->wantsJson()) {
            return response()->json($deployment);
        }

        return view('deployment', compact('deployment'));
    }

    /**
     * @param $deployment
     *
     */
    protected function buildData($deployment)
    {
        $location = ['latitude' => $deployment->getLatitude(), 'longitude' => $deployment->getLongitude()];
        $lat = $location['latitude'];
        $long = $location['longitude'];

        $aeds = $this->getNearbyAeds($lat, $long);

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
            'deploymnet' => $deployment,
            'incidentLocation' => $location,
            'aedLocations' => $result,
            'aedClosest' => $result->first(),
        ]);
    }

    /**
     * @param $lat
     * @param $long
     *
     * @return mixed
     */
    protected function getNearbyAeds($lat, $long)
    {
        $aeds = AED::where('inside', 0)
                   ->where('latitude', '<=', $lat + self::COORDINATE_OFFSET)
                   ->where('latitude', '>=', $lat - self::COORDINATE_OFFSET)
                   ->where('longitude', '<=', $long + self::COORDINATE_OFFSET)
                   ->where('longitude', '>=', $long - self::COORDINATE_OFFSET)->get();

        return $aeds;
    }
}
