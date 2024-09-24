<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Controller
{
    public function ChangePass(Request $request){
        $fields = $request -> validate([
            'old_password' => 'required',
            'password' => 'required|min:6|max:100',
           
        ]);
       $user = $request ->user();

       if(Hash::check($request ->old_password,$user->password)){
            $user -> update([
                'password'=>Hash::make($request->password)
            ]);
          
            return response() -> json([
                'message'=> 'Password changes successfully'
             ],200);
            
       }else{
         return response() -> json([
            'message'=> 'Old password doesnt match'
         ],422);
       }
    }
}
