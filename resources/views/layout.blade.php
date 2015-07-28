<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>WMD - @yield('title', 'Home')</title>
	<meta name="description" content="WMD Web Messages Dispatcher to dispatch over the web messages received from the web" />
	<meta name="author" content="The Citizen Crew & Co, Cyrille Giquello" />

	@section('css')
	<!-- link rel="stylesheet" href="/vendor/require.css" /-->
	<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap-theme.css" />	
	<link rel="stylesheet" href="/style.css" />	
	@show

</head>
<body role="document">

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar-items-collapse">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand " href="/">WMD</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="navbar-items-collapse">
				<ul class="nav navbar-nav">
					<li><a href="/router">Routes</a></li>
					<li><a href="/about">À propos</a></li>
				</ul>
			</div>

		</div>
	</nav>

	<div class="container-fluid" role="main">
		@yield('content')
	</div>

	<br />
	<div class="container-fuild" role="footer">
		<blockquote>
			<footer>
				&copy; 2015 <a href="https://github.com/TheCitizenCrew">The Citizen Crew &amp; Co</a>
				<br /> Powered with Php &amp; Javascript (Lumen, Bootstrap, JQuery ...).
			</footer>
		</blockquote>
	</div>

	@section('javascript')
	<!--script type="text/javascript" src="/vendor/require.js"></script-->
	<script type="text/javascript" src="/vendor/jquery/jquery.min.js"></script>
	<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	
	//require(['jquery'], function($) {
	//});
	</script>
	@show

</body>
</html>
