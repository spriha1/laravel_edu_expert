<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <pre>
        {{ $msg }},
                    
        @if ($login_link == 1)
        	You can now <a href="/">login</a>
        @endif
    </pre>
</body>
</html>