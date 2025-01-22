<?php
namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait TurningAuthorizable
{
    public function __construct() {
        $this->middleware('permission:turning-view')->only("index");
        $this->middleware('permission:turning-modify')->only("create", "store");
        $this->middleware('permission:turning-delete')->only("destroy");
    }
}
