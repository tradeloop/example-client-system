<?php 

declare(strict_types=1);

namespace App\Http\Handlers\Register;

use App\Http\Handlers\Handler;

class RegisterUser extends Handler {
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function __invoke()
    {
        return view('login.index');
    }
}
