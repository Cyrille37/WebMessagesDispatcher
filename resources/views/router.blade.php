
@extends('layout')

@section('title', 'WMD Router')

@section('css')
	@parent
	<link type="text/css" rel="stylesheet" href="/jsgrid-1.1.0/jsgrid.min.css" />
	<link type="text/css" rel="stylesheet" href="/jsgrid-1.1.0/jsgrid-theme.min.css" />
	<link type="text/css" rel="stylesheet" href="/jquery-ui-1.11.4.custom/jquery-ui.min.css" />
	<link type="text/css" rel="stylesheet" href="/jquery-ui-1.11.4.custom/jquery-ui.structure.min.css" />
	<link type="text/css" rel="stylesheet" href="/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" />
@stop

@section('content')

<h1>Router</h1>

	<div id="detailsDialog" title="Basic dialog">
	  <form id="editForm" class="form-horizontal">
	  	<div class="form-group">
	  		<label for="comment">Commentaire:</label>
	  		<input type="text" name="comment" id="comment" class="form-control" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="srv_name">Nom du service:</label>
	  		<select name="srv_name" id="srv_name" class="form-control"></select>
	  	</div>
	  	<div class="form-group">
	  		<label for="mod_name">Module de routage:</label>
	  		<select name="mod_name" id="mod_name" class="form-control"></select>
	  	</div>
	  	<div class="form-group">
	  		<label for="mod_params">Paramètre du routeur:</label>
	  		<input type="text" name="mod_params" id="mod_params" class="form-control" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="from">Émetteur:</label>
	  		<input type="text" name="from" id="from" class="form-control" value="" />
	  		<p class="help-block">une "*" pour n'importe quel émetteur (tous).</p>
	  	</div>
	  	<div class="form-group">
	  		<label for="to">Destinataire:</label>
	  		<input type="text" name="to" id="to" class="form-control" value="" />
	  		<p class="help-block">une "*" pour n'importe quel destinataire (tous).</p>
	  	</div>
	  	<button type="submit" id="save" class="btn btn-primary">Save</button>
	  </form>
	</div>

	<table id="routesGrid" class="table table-striped table-bordered">
	</table>

	<h3>Route test</h3>
	<form id="testRouteForm" class="form-inline">
		<div class="form-group">
			<label for="test_srv_name">Srvive:</label>
			<select name="test_srv_name" id="test_srv_name" class="form-control"></select>
		</div>
		<div class="form-group">
			<label for="test_mod_name">Srvive:</label>
			<select name="test_mod_name" id="test_mod_name" class="form-control"></select>
		</div>
	  	<div class="form-group">
	  		<label for="test_from">Émetteur:</label>
	  		<input type="text" name="test_from" id="test_from" class="form-control" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="test_to">Destinataire:</label>
	  		<input type="text" name="test_to" id="test_to" class="form-control" value="" />
	  	</div>
		<button type="button" id="test_route" class="btn btn-default">Test</button>
		<span id="test_success" class="label label-success">Message distribué</span>
		<span id="test_failed" class="label label-warning">Message non distribué</span>
		<span id="test_notdone" class="label label-default">...</span>
	</form>

@stop

@section('javascript')

	@parent

	<script src="/jsgrid-1.1.0/jsgrid.min.js"></script>
	<!-- script src="/vendor/bootstrap-jqueryui/bootstrap-jqueryui.min.js"></script -->
	<script src="/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	<script src="/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
	<script src="/jquery-validation-1.14.0/dist/localization/messages_fr.js"></script>
	<script src="/jquery-validation-1.14.0/dist/additional-methods.min.js"></script>
	<script type="text/javascript">
	"use strict";

	var CONST = {
		DIALOGTYPE_ADD: 'Add',
		EMPTY_OBJECT: {}
	};
	Object.freeze(CONST);

	var db = {
		// List of route services for IHM selects
		routeServices: [],
		// List of route modules for IHM selects
		routeModules: [],
		// jsGrid loadData
		loadData: function(filter) {
			return $.ajax({ type: 'GET', data: filter, url: '/api/routes', dataType: 'json' });
		},
		// jsGrid insertItem
        insertItem: function(item) {
            return $.ajax({ type: 'POST', data: item, url: '/api/routes', dataType: 'json' });
        },
		// jsGrid updateItem
        updateItem: function(item) {
            return $.ajax({ type: 'PUT', data: item, url: '/api/routes', dataType: 'json' });
        },
		// jsGrid deleteItem
        deleteItem: function(item) {
            return $.ajax({ type: 'DELETE', data: item, url: '/api/routes', dataType: 'json' });
        }
	};

	$(function() {

		function loadRouteServices() {
			return $.getJSON('/api/routeServices', function(data){
				db.routeServices = data ;
				formSelectFill( '#srv_name', data );
				formSelectFill( '#test_srv_name', data );
			});
		}
		function loadRouteModules() {
			return $.getJSON('/api/routeModules', function(data){
				db.routeModules = data ;
				formSelectFill( '#mod_name', data );
				formSelectFill( '#test_mod_name', data );
			});
		}

		$.when( loadRouteServices(), loadRouteModules() )
			.then(
			// allRequestsCompleteCallback	
		    function(res1, res2) {
				setupGrid();
				setupTestForm();
			},
		    // someRequestFailedCallback()
		    function(data) {
			    alert('Failed to initialize IHM. Http status='+data.status);
		    }
		);

	});

	function formSelectFill( selectSelector, data, options ){

		var fieldValue = options && options.fieldValue ? options.fieldValue : 'id' ;
		var fieldLabel = options && options.fieldLabel ? options.fieldLabel : 'label' ;
		var options = [];
	    for (var i = 0; i < data.length; i++) {
	        options.push('<option value="',data[i][fieldValue], '">',data[i][fieldLabel], '</option>');
	    }
	    $(selectSelector).html(options.join(''));
	}

	function setupGrid(){
		
		$("#routesGrid").jsGrid({
			width: "100%",
			height: "400px",

			autoload: true ,
		    loadIndication: true,
		    loadIndicationDelay: 250,
		    loadMessage: 'Please, wait...',
		    loadShading: true,

			heading: true,
			//filtering: true,
			editing: true,
			//sorting: true,
			paging: true,
		    pageSize: 15,
		    //pageButtonCount: 5,

			controller: db ,
			noDataContent: "Not found",

		    confirmDeleting: true,
		    deleteConfirm: "Are you sure?",

			fields: [
				{ name: 'id', type: 'number' },
				{ name: 'comment', type: 'text' },
				{ name: 'srv_name', type: 'select',
					items: db.routeServices, valueField: 'id', textField: 'label', autosearch: true },
				{ name: 'mod_name', type: 'select',
					items: db.routeModules, valueField: 'id', textField: 'label', autosearch: true },
				{ name: 'mod_params', type: 'text' },
				{ name: 'from', type: 'text' },
				{ name: 'to', type: 'text' },
		        { type: 'control',
		        	modeSwitchButton: false,
					editButton: true,
					headerTemplate: function() {
			            return $('<button>')
			            	.attr('type', 'button')
			            	.text('Add')
	                        .on('click', function () {
	                        	showDetailsDialog( CONST.DIALOGTYPE_ADD, CONST.EMPTY_OBJECT );
	                        	//$("#routes").jsGrid("editItem", { id: "1" });
	                    	});
		            }
                }
			]
		});

	    $('#detailsDialog').dialog({
	        autoOpen: false,
	        width: 400,
	        close: function() {
	            $('#editForm').validate().resetForm();
	            $('#editForm').find('.error').removeClass('error');
	        }
	    });

	    var formSubmitHandler = $.noop;

	    var showDetailsDialog = function(dialogType, data) {

			$('#comment').val(data.comment);
			$('#srv_name').val(data.srv_name);
			$('#mod_name').val(data.mod_name);
			$('#mod_params').val(data.to);
			$('#from').val(data.from);
			$('#to').val(data.to);

			formSubmitHandler = function() {
	        	saveClient( data, dialogType === CONST.DIALOGTYPE_ADD );
	        }

			$('#detailsDialog')
				.dialog('option', 'title', dialogType + ' Route')
				.dialog('open');
	    };

	    $('#editForm').validate({
	        rules: {
				comment: 'required',
				srv_name: { required: true },
				mod_name: { required: true },
				from: 'required',
				to: 'required',
	        },
	        messages: {
	            comment: 'Un commentaire est nécessaire',
	            srv_name: 'Un nom de service est nécessaire',
	            mod_name: 'Un nom de routeur est nécessaire',
	            from: 'Un identifiant d\'émetteur est nécessaire',
	            to: 'Un identifiant de destinataire est nécessaire',
	        },

	        submitHandler: function() {
	            formSubmitHandler();
	        }
		});

	    var saveClient = function(data, isNew) {

			$.extend(data, {
	            comment: $('#comment').val(),
	            srv_name: $('#srv_name').val(),
	            mod_name: $('#mod_name').val(),
	            mod_params: $('#mod_params').val(),
	            from: $('#from').val(),
	            to: $('#to').val(),
	        });
	        $('#routesGrid').jsGrid(isNew ? 'insertItem' : 'updateItem', data);
	        $('#detailsDialog').dialog('close');
	    };
	}

	function setupTestForm(){

		$('#test_success').hide();
		$('#test_failed').hide();
		$('#test_notdone').show();
		$('#test_route').click(function(){
			$.getJSON('/api/routeTest',
				{
					srv_name: $('#test_srv_name').val(),
					mod_name: $('#test_mod_name').val(),
					from: $('#test_from').val(),
					to: $('#test_to').val(),
				},
				function(data){
					$('#test_notdone').hide();
					if( data === true ){
						$('#test_success').show();
						$('#test_failed').hide();
					}else{
						$('#test_success').hide();
						$('#test_failed').show();
					}
				});
		});
		$('#testRouteForm').change(function(data){
			$('#test_success').hide();
			$('#test_failed').hide();
			$('#test_notdone').show();
		});

	}
	</script>
@stop
