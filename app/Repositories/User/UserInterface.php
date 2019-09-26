<?php

namespace App\Repositories\User;

interface UserInterface
{
	public function login($username, $password);

	public function register();

	public function pending_requests();

	public function post_pending_requests($user_type);

	public function add_users($id);

	public function block_users($id);

	public function unblock_users($id);

	public function remove_users($id);

	public function regd_users();

	public function post_regd_users($user_type);

	public function render_admin_dashboard();

	public function render_teacher_dashboard($code);

	public function profile();
}
