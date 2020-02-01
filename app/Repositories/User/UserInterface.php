<?php

namespace App\Repositories\User;

interface UserInterface
{
	public function login($username, $password);

	public function register();

	public function pending_requests();

	public function post_pending_requests($user_type);

	public function add_users($id);

	public function change_user_type($id, $type);

	public function remove_users($id);

	public function regd_users();

	// public function post_regd_users($user_type);

	public function render_admin_dashboard();

	public function render_teacher_dashboard($code);

	public function profile();

	public function verify_mail($code);

	public function update_mail($hash, $email);

	public function send_password_mail($username);

	public function reset_password_form($token, $expiry_time);

	public function reset_password($password);

}
