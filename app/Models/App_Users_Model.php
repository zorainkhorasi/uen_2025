<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class App_Users_Model extends Model
{
    use HasFactory;

    protected $table = 'users_app';


    public static function getAllData()
    {
        $sql = DB::table('users_app')->select('*');
        $sql->where('users_app.enabled', '=', '1');
        $data = $sql->get();
        return $data;
    }

    public static function checkName($userName)
    {
        $data = DB::table('users_app')
            ->where('users_app.enabled', '=', '1')
            ->where('username', $userName)
            ->get();
        return $data;
    }

    public static function getUserDetails($id)
    {
        $data = DB::table('users_app')
            ->where('id', $id)
            ->get();
        return $data;
    }

}
