<?php
namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait DispatchAuthorizable
{
    public function __construct() {
        $this->middleware('permission:dispatch-view')->only("index");
        $this->middleware('permission:dispatch-modify')->only("create", "store");
        $this->middleware('permission:dispatch-delete')->only("destroy");
    }
}
