<?php
namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait MillingAuthorizable
{
    public function __construct() {
        $this->middleware('permission:milling-view')->only("index");
        $this->middleware('permission:milling-modify')->only("create", "store");
        $this->middleware('permission:milling-delete')->only("destroy");
    }
}
