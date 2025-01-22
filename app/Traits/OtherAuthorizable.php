<?php
namespace App\Traits;

use Closure;
use Illuminate\Http\Request;

trait OtherAuthorizable
{
    public function __construct() {
        $this->middleware('permission:other-view')->only("index");
        $this->middleware('permission:other-modify')->only("create", "store");
        $this->middleware('permission:other-delete')->only("destroy");
    }
}
