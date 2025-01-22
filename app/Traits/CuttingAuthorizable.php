<?php
namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait CuttingAuthorizable
{
    public function __construct() {
        $this->middleware('permission:cutting-view')->only("index");
        $this->middleware('permission:cutting-modify')->only("edit", "update");
        $this->middleware('permission:cutting-delete')->only("destroy");
    }
}
