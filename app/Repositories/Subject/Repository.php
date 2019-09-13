<?php

namespace App\Repositories\Subject;

interface Repository
{
	public function add_subject($request);

	public function remove_subject($request);
	
	public function display_subjects();
}
