<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User as ThisUser;

class UserType extends Model
{
	public static function getThisUser() {
		return ThisUser::find(6);
	}

	public static function user_types($exclude = NULL)
	{
		return UserType::where('user_type', '!=', $exclude)
	        ->select('user_type')
	        ->get();
	}
}
