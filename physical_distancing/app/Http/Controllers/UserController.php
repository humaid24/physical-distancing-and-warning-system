<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(Request $request){
      return  User::where('name', 'like', '%'.$request->searchText.'%')
                    ->orWhere('email', 'like', '%'.$request->searchText.'%')
                    ->orWhere('username', 'like', '%'.$request->searchText.'%')
                    ->paginate(10);
    }

    public function approveUser(Request $request){
        return User::find($request->id)->update(['isApprovedByAdmin' => 'Y']);
    }
}
