<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class Test extends Controller {
    //
    function index() {

        bang('here');
        bang(getenv());
        bang($_ENV);
        bang($_SERVER);

        // $c = Company::find(2);
        // bang($c->stations);

        // $c = Company::with('childrenRecursive')->find(2)->get();
        // foreach($c as $cc) {
        //     bang($cc->id);
        //     bang($cc->name);
        //     bang($cc->parent_id);
        //     bang('--');
        // }

        // $company = Company::find(2);
        // $children = $company->getChildren();
        // foreach($children as $child) {
        //     bang($child->id . '-' . $child->name);
        // }
    }
}
