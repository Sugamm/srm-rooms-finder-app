<!DOCTYPE html>
<html>
<head>
	<title>Email Send Using Laravel</title>
</head>
<body>
<div class="flex-center position-ref full-height">
	<form action="{{ route('sendmail') }}" method="post">
		<input type="email" name="mail" placeholder="mail Address">
		<input type="text" name="title" placeholder="Title">
		<button type="submit" > Send Me A Mail</button>
		{{ csrf_field() }}
	</form>
</div>
</body>
</html>