<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        if($request->user()) {
            return view('profile.edit', [
                'user' => $request->user(),
            ]);
        } else {
            throw new \HttpException();
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        if($request->password && $request->password_confirmation &&
            $request->password == $request->password_confirmation) {

            $request->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'locale' => $request->locale,
                'password' => Hash::make($request->password),
            ]);
        } else {
            $request->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'locale' => $request->locale,
            ]);
        }

        return redirect('/profile');
    }
}
