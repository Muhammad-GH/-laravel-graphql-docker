<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model {
    use HasFactory;

    protected $appends = array('distance');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];


    static function byRadius($radius, $lat, $lng) {
        $return = collect();
        $stations = self::all();
        foreach ($stations as $station) {
            $distance = haversine_great_circle_distance($lat, $lng, $station->lat, $station->lng, 'mi');
            if ($distance <= $radius) {
                $station->distance = $distance;
                $return->push($station);
            }
        }
        return $return;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class);
    }
}
