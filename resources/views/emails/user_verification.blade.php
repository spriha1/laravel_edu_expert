<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
	<pre>
		Thanks for signing up!
		Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
		 
		------------------------
		Username: {{ $username }}
		Password: {{ $password }}
		------------------------
		 
		Please click this link to activate your account:
		
		http://eduexpert.local.com/verify_mail/{{ $code }}
	</pre>
</body>
</html>