<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ThirdAppModel extends BaseModel
{
    protected $table = 'third_app';

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    public static function check(string $username, string $password)
    {
        $user = self::where('app_id', '=', $username)
            ->where('app_secret', '=', md5(md5($password)))
            ->first();

        return resultToArray($user);
    }
}
