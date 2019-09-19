<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User as ThisUser;

class UserType extends Model
{
	public static function getThisUser() {
		return ThisUser::find(6);
	}
}
