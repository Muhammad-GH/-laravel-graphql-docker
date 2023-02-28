<?php

namespace App\GraphQL\Queries;

use App\Models\Company;
use App\Models\Station;

final class Stations
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $radius = isset($args['radius']) ? $args['radius'] : 0;
        $lat = isset($args['lat']) ? $args['lat'] : 0.0;
        $lng = isset($args['lng']) ? $args['lng'] : 0.0;

        if(!$radius) return Station::all();

        return Station::byRadius($radius, $lat, $lng);

    }
}
