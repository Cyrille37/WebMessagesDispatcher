
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
	  <form id="detailsForm" class="form-horizontal">
	  	<div class="form-group">
	  		<label for="comment">Commentaire:</label>
	  		<input type="text" name="comment" id="comment" class="form-control" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="srv_name">Nom du service:</label>
	  		<input type="text" name="srv_name" id="srv_name" class="form-control" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="mod_name">Module de routage:</label>
	  		<select name="mod_name" id="mod_name" class="form-control" />
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
	  	<button type="submit" class="btn btn-primary">Annuler</button>
	  	<button type="submit" class="btn btn-primary">Enregister</button>
	  </form>
	</div>

	<table id="routes" class="table table-striped table-bordered">
	</table>

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

	var db = {
		loadData: function(filter) {
			return $.ajax({ type: "GET", data: filter, url: "/api/routes", dataType: "json" });
		},
        insertItem: function(item) {
            return $.ajax({ type: "POST", data: item, url: "/api/routes", dataType: "json" });
        },
        updateItem: function(item) {
            return $.ajax({ type: "PUT", data: item, url: "/api/routes", dataType: "json" });
        },
        deleteItem: function(item) {
            return $.ajax({ type: "DELETE", data: item, url: "/api/routes", dataType: "json" });
        },
        routerModules: [{id:'id1',name:'titi'},{id:'id2',name:'tata'}],
	};

	$(function() {

		$("#routes").jsGrid({
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
				{ name: 'srv_name', type: 'text' },
				{ name: 'mod_name', type: 'select',
					items: db.routerModules, valueField: 'id', textField: 'name', autosearch: true },
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
	                        	//showDetailsDialog('Add', {});
	                        	$("#routes").jsGrid("editItem", { id: "1" });
	                    	});
		            }
                }
			]
		});

	    $("#detailsDialog").dialog({
	        autoOpen: false,
	        width: 400,
	        close: function() {
	            $("#detailsForm").validate().resetForm();
	            $("#detailsForm").find(".error").removeClass("error");
	        }
	    });

	    var formSubmitHandler = $.noop;

	    var showDetailsDialog = function(dialogType, route) {

			$("#comment").val(route.comment);
			$("#srv_name").val(route.srv_name);
			$("#mod_name").val(route.mod_name);
			$("#from").val(route.from);
			$("#to").val(route.to);
			$("#mod_params").val(route.to);

	        formSubmitHandler = function() {
				saveClient( route, dialogType === "Add");
	        };

			$("#detailsDialog")
				.dialog("option", "title", dialogType + " Route")
				.dialog("open");
	    };

	    $("#detailsForm").validate({
	        rules: {
				comment: "required",
				srv_name: { required: true, minlength: 2 },
				mod_name: { required: true, minlength: 2 },
				from: "required",
				to: "required",
	        },
	        messages: {
	            comment: "Un commentaire est nécessaire",
	            srv_name: "Un nom de service est nécessaire",
	            mod_name: "Un nom de routeur est nécessaire",
	            from: "Un identifiant d'émetteur est nécessaire",
	            to: "Un identifiant de destinataire est nécessaire",
	        },
	        submitHandler: function() {
	            formSubmitHandler();
	        }
	    });

	    var saveClient = function(client, isNew) {
	        $.extend(client, {
	            Name: $("#name").val(),
	            Age: parseInt($("#age").val(), 10),
	            Address: $("#address").val(),
	            Country: parseInt($("#country").val(), 10),
	            Married: $("#married").is(":checked")
	        });
	 
	        $("#jsGrid").jsGrid(isNew ? "insertItem" : "updateItem", client);
	 
	        $("#detailsDialog").dialog("close");
	    };

	});
	</script>

@stop
