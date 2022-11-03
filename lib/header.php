<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/phonebook/lib/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Phonebook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>

    <!-- <link rel="stylesheet" type="text/css" href="../public/css/styles.css"/> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="public/bootstrap-3.3.7/dist/css/bootstrap.min.css">
 
  	<script src="public/jquery/3.2.1/jquery.min.js"></script>
  	<script src="public/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>

   	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/redmond/jquery-ui.min.css">
  	<link rel="stylesheet" href="public/free-jqgrid/4.15.5/ui.jqgrid.min.css">

  	<script src="public/free-jqgrid/4.15.5/jquery.jqgrid.min.js"></script>
	<script src="public/free-jqgrid/4.15.5/grid.locale-en.js"></script> 

	<script type="text/javascript">
		function filterToolbar_clearFilters(grid_name){
			var sgrid = $("#"+grid_name)[0];
			sgrid.clearToolbar();
		}

		function add_contact_form(){
			$("#contact_form").show();
			$("#contact_table").hide();
			$("#contact_form h3").html("Add Contact Form");
			clear_contact_form();
		}

		function edit_contact_form(contact_id){
			$("#contact_form").show();
			$("#contact_table").hide();
			$("#contact_form h3").html("Edit Contact Form");
			clear_contact_form();


			var contact_name;
			var contact_company;
			var contact_phone;
			var contact_address;
			var contact_email;
			var contact_image;

			Object.keys(contact_list).forEach(function(key) {
				contact = contact_list[key];
				if(contact.contact_id == contact_id) {
					contact_name = contact.contact_name;
					contact_company = contact.contact_company;
					contact_phone = contact.contact_phone;
					contact_address = contact.contact_address;
					contact_email = contact.contact_email;
					contact_image = contact.contact_image;
				}
			});

			document.getElementById("txt_name").value = contact_name;
			document.getElementById("txt_company").value = contact_company;
			document.getElementById("txt_phone").value = contact_phone;
			document.getElementById("txt_address").value = contact_address;
			document.getElementById("txt_email").value = contact_email;
			document.getElementById("contact_id").value = contact_id;
		}

		function clear_contact_form(){
			$("#contact_form h3").html("");
			$("#txt_name").val('');
			$("#txt_company").val('');
			$("#txt_phone").val('');
			$("#txt_address").val('');
			$("#txt_email").val('');
			$("#contact_image").val('');
		}
		
		function cancel_form(){
			$("#contact_form").hide();
			$("#contact_table").show();
			
			clear_contact_form();
		}

		function delete_contact(contact_id) {
			var xhttp = new XMLHttpRequest();
			if(confirm('Are you sure you want to delete this contact?')) {
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState === 4 && xhttp.status === 200) {
						window.location.reload(true);
					}
				};
				xhttp.open("POST", "lib/query_process.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("action=delete&contact_id="+contact_id);
			}
		}

		
	</script>
</head>
<body>
    