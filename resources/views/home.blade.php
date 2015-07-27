
@extends('layout')

@section('title', 'Welcome on WMD')

@section('content')

<p class="bg-primary">
	Hop là !
</p>

@stop

@section('javascript')

	@parent
	<script type="text/javascript">

	require(['jquery'], function($) {
		//$.getJSON( '/api/stats', function( data ) {
		//	$('#messagesCount').text( data.messagesCount );
		//	$('#channelsCount').text( data.channelsCount );
		//});
		console.log('coucou');
	});
	</script>

@stop
