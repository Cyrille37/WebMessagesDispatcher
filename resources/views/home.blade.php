
@extends('layout')

@section('title', 'Welcome on WMD')

@section('content')

<h1>Web Messages Dispatcher (WMD)</h1>

<p class="bg-primary">
	La base de données contient
	<span id="messagesCount">...</span> message(s)
	et <span id="routesCount">...</span> route(s).
</p>

@stop

@section('javascript')

	@parent
	<script type="text/javascript">

	$(function() {
		$.getJSON( '/api/stats', function( data ) {
			$('#messagesCount').text( data.messagesCount );
			$('#routesCount').text( data.routesCount );
		});
	});

	</script>
@stop
