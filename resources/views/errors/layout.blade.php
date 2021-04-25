<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Error - BetterDiscordLibrary</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="icon" href="/images/favicon.png">
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
		<link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
		<script>
			// check for theme preference before page loads
			if (!('theme' in localStorage)) {
				localStorage.theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
			}
			document.documentElement.setAttribute('class', localStorage.theme);
		</script>
	</head>
	<body class="font-body flex flex-col justify-center font-medium bg-gray-200 text-gray-700 dark:text-gray-300 dark:bg-gray-900">
		<div class="max-w-screen-xl w-full mx-auto">
			<h1 class="text-9xl font-display text-gray-800 dark:text-white">@yield('code', __('Oh no'))</h1>
			<p class="text-lg">@yield('message')</p>

			@if (in_array($exception->getStatusCode(), [401, 403, 404, 419, 429]))
				<a href="/" class="btn btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40 inline-flex mt-12">
					{{__('Go home')}}
				</a>
			@endif
		</div>
	</body>
</html>