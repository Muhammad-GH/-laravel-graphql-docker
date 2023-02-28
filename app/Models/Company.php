<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model {

    use HasFactory;

    private static $children_ids = [];
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'about'
    ];

    private static function findChildren($company_id) {
        $sql = 'SELECT `id`, `parent_id` from `companies` where `parent_id` = ?';
        $results = DB::select($sql, [$company_id]);

        foreach ($results as $result) {
            self::$children_ids[$result->id] = $result->id;
            $children = self::findChildren($result->id);
            if ($children) {
                self::$children_ids = array_merge(self::$children_ids, $children);
            }
        }
    }

    static function childrenResolver(Company $company) {
        return $company->getChildren();
    }

    static function stationsResolver(Company $company){
        $collection = collect();
        foreach($company->stations as $station) {
            $collection->push($station);
        }

        $children = $company->getChildren();
        foreach($children as $child) {
            foreach($child->stations as $station) {
                $collection->push($station);
            }
        }
        return $collection;
    }


    function getChildren() {
        $companies = [];
        self::$children_ids = [];
        self::findChildren($this->id);
        foreach (self::$children_ids as $company_id) {
            $companies[] = Company::find($company_id);
        }
        return $companies;
    }

    public function stations() {
        return $this->hasMany(Station::class);
    }

    public function parent() {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Company::class, 'parent_id');
    }
}
