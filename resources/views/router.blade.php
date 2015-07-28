
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
	  <form id="detailsForm">
	  	<div class="form-group">
	  		<label for="comment">Commentaire:</label> <input type="text" name="comment" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="srv_name">Nom du service:</label> <input type="text" name="srv_name" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="mod_name">Module de routage:</label> <input type="text" name="mod_name" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="mod_params">Paramètre du routeur:</label> <input type="text" name="mod_params" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="from">Émetteur:</label> <input type="text" name="from" value="" />
	  	</div>
	  	<div class="form-group">
	  		<label for="to">Destinataire:</label> <input type="text" name="to" value="" />
	  	</div>
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

	$(function() {

		var db = {

			loadData: function(filter) {
				return $.ajax({
					type: "GET",
					url: "/api/routes",
					data: filter,
					dataType: "json"
				});
			},
	        insertItem: function(item) {
	            return $.ajax({
	                type: "POST",
	                url: "/api/routes",
	                data: item,
	                dataType: "json"
	            });
	        },
	        updateItem: function(item) {
	            return $.ajax({
	                type: "PUT",
	                url: "/api/routes",
	                data: item,
	                dataType: "json"
	            });
	        },
	        deleteItem: function(item) {
	            return $.ajax({
	                type: "DELETE",
	                url: "/api/routes",
	                data: item,
	                dataType: "json"
	            });
	        },

		};

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

			controller: db,

			noDataContent: "Not found",

		    confirmDeleting: true,
		    deleteConfirm: "Are you sure?",

			fields: [
				{ name: "id", type: "number" },
				{ name: "comment", type: "text" },
				{ name: "srv_name", type: "text" },
				{ name: "mod_name", type: "text" },
				{ name: "from", type: "text" },
				{ name: "to", type: "text" },
				{ name: "mod_params", type: "text" },
		        { type: "control",
		        	modeSwitchButton: false,
					editButton: true,
					headerTemplate: function() {
			            return $("<button>")
			            	.attr("type", "button")
			            	.text("Add")
	                        .on("click", function () {
	                        	showDetailsDialog("Add", {});
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

	    $("#detailsForm").validate({
	        rules: {
	            name: "required",
	            age: { required: true, range: [18, 150] },
	            address: { required: true, minlength: 10 },
	            country: "required"
	        },
	        messages: {
	            name: "Please enter name",
	            age: "Please enter valid age",
	            address: "Please enter address (more than 10 chars)",
	            country: "Please select country"
	        },
	        submitHandler: function() {
	            formSubmitHandler();
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

	        $("#detailsDialog").dialog("option", "title", dialogType + " Route")
	                .dialog("open");
	    };

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