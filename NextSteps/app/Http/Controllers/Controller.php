<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{   
    // 1. AuthorizesRequests: Allows you to use the $this->authorize() method 
    //    for checking user permissions (e.g., $this->authorize('delete', $user)).
    use AuthorizesRequests; 

    // 2. ValidatesRequests: Allows you to use the $this->validate() method 
    //    for easy validation of incoming HTTP requests.
    use ValidatesRequests;
}