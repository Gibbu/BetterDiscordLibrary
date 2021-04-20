<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>BetterDiscordLibrary</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
		<link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>
		<script>
			// check for theme preference before page loads
			if (!('theme' in localStorage)) {
				localStorage.theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
			}
			document.documentElement.setAttribute('class', localStorage.theme);
		</script>
	</head>
	<body class="font-body font-medium bg-gray-200 text-gray-700 dark:text-gray-300 dark:bg-gray-900">
		@inertia
		<div id="layers"></div>
	</body>
</html>