<!-- This file will work as the common wrapper for all email template  -->
<!DOCTYPE html>
<html lang="en" style="margin:0px;padding:0px;">

<body style="width:100%;min-width:600px;background-color:white;margin:0px;padding:0px;font-family:Open Sans,Arial,sans-serif;">
	@if (env("APP_ENV") != "production")
		<div style="display:block;">
			@include('mails.common.environment-information')
		</div>
	@endif

	<div style="display:block;margin:40px auto;">
		<div style="display:block;">	
			@yield('header')
		</div>

		<div style="display:block;">
			@yield('body')
			
		</div>

		<div style="display:block;">
			@yield('footer')
		</div>
	</div>

	<div style="display:block;">
		@include('mails.common.environment-information')
	</div>
</body>

</html>