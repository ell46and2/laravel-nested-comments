<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
	
	<style>
		body {
			font-family: 'Avenir';
		}

		.comment:not(:first-child) {
			margin: 20px 0 40px 60px;
			border-left: 1px solid #ccc;
			padding-left: 20px;
		}
	</style>
</head>
<body>
	<h1>{{ $article->title }}</h1>
	
	
		@include('comments.partial._comment', ['comments' => $comments])

		{{ $comments->links() }}
	
</body>
</html>