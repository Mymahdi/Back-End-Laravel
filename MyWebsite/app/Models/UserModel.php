<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserModel
{
    public static function create($data)
    {
        return DB::table('users')->insert([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public static function find($id)
    {
        return DB::table('users')->where('id', $id)->first();
    }

    public static function findByEmail($email)
    {
        return DB::table('users')->where('email', $email)->first();
    }
}
