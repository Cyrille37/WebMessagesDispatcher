
@extends('layout')

@section('title', 'Welcome on WMD')

@section('content')

<p class="bg-primary">
	La base de données contient
	<span id="messagesCount">...</span> message(s)
	et <span id="routesCount">...</span> route(s).
</p>

@stop

@section('javascript')

	@parent
	<script type="text/javascript">

	require(['jquery'], function($) {
		$.getJSON( '/api/stats', function( data ) {
			$('#messagesCount').text( data.messagesCount );
			$('#routesCount').text( data.routesCount );
		});
		console.log('coucou');
	});
	</script>

@stop
