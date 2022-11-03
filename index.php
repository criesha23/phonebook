<?php 
    include 'lib/header.php';
    include_once('lib/query_process.php');
	$contact_list = $phonebook->get_contact_list();
?>
<script>
	var contact_list = <?php echo json_encode($contact_list)?>;

	$(document).ready(function(){
		"use strict";
		$("#tbl_contacts_jqgrid").jqGrid({
			idPrefix: "g1_",	
			datatype: "local",
			data: contact_list,
			mtype: "GET",
			colModel: [
				{ name: "contact_id", label: "", width: 150, 
					formatter: contact_action_row,
					stype: "select",	
					key: true,			
				},
				
				{ name: "contact_name", label: "Name", width: 250, template: "text",
					searchoptions: { sopt: ["bw","ew", "cn", "nc", "eq", "ne","em", "nm"] }
				},
				{ name: "contact_company", label: "Company ", width: 250, template: "text",
					searchoptions: { sopt: ["bw","ew", "cn", "nc", "eq", "ne","em", "nm"] }
				},
				{ name: "contact_phone", label: "Phone ", width: 150, template: "text",
					searchoptions: { sopt: ["bw","ew", "cn", "nc", "eq", "ne","em", "nm"] }
				},
				{ name: "contact_address", label: "Address ", width: 250, template: "text",
					searchoptions: { sopt: ["bw","ew", "cn", "nc", "eq", "ne","em", "nm"] }
				},
				{ name: "contact_email", label: "Email ", width: 150, template: "text",
					searchoptions: { sopt: ["bw","ew", "cn", "nc", "eq", "ne","em", "nm"] }
				},

				{ name: "contact_image", label: "Image", width: 200, 
					formatter: conatact_display_image,
					stype: "select"		
				},
									
			],
			loadonce: true, //prevents reloading of server data which prevents filtering and sorting featues from working
			iconSet: "fontAwesome",
			rownumbers: true,	
			rowList: [10,20,50], //options on how many rows to show at a time
			sortname: "contact_name", //sort field
			sorttype: "text",  //sorttype options are "text", "integer", "number", "date" (works for d-m-y style format)
			sortorder: "asc",
			toppager: true,
			pagerpos:"left",
			rowNum: 10,  //number of rows to show
			viewrecords: true,  // show range of records currently displayed
			threeStateSort: true, //allow 3 state sorting by column (asc, desc, unsorted )
			multiSort: true,  //allow sort with multiple column criteria
			searching: {
				defaultSearch: "cn"  // "cn"-> contains, "bw" -> begins with, "ew" -> ends with
			},

			//Select row only if the row selector checkbox was clicked
			onSelectRow : function (rowId, status) {
				var selectorCheckboxIdPrefix='jqg_'+this.id+'_'; //Change this to whatever is the checkbox id/name prefix as shown by inspect element tool of browser
				var elem = document.activeElement; //Row element that has been clicked. 
				//console.log('rowId='+rowId+' | status='+status +' | elem.id='+elem.id); //Enable for debugging only
				
				// Check if selector checkbox has been clicked in the row which is identified if its id starts with value of selectorCheckboxIdPrefix variable
				if (elem.id.startsWith(selectorCheckboxIdPrefix)==true) {   
					return true; //allow row selection according to default behavior
				}else{
					$(this).setSelection(rowId, false); //unselect current selection because some other element in the row has been clicked.
				}
				
			},
			loadComplete: function(data) {
				var filter_msg = "Found "+$(this).getGridParam('records');
				if ($(this).getGridParam('records') == 1){
					filter_msg=filter_msg+' record';
				}else{
					filter_msg=filter_msg+' records';
				}
				$('#span_tab1_recordcount').text(filter_msg);
			},
			
			searching: {
				//showQuery: true,
				//loadFilterDefaults: false,
				multipleSearch: true,
				multipleGroup: true,
				closeOnEscape: true,
				searchOperators: true,
				searchOnEnter: true
			},
			customUnaryOperations: ["em", "nm"],
			customSortOperations: {
				em: {
					operand: "=''",
					text: "is empty",
					filter: function (options) {
						var v = options.item[options.cmName];
						if (v === undefined || v === "") {
							return true;
						}
					}
				},
				nm: {
					operand: "!=''",
					text: "is not empty",
					filter: function (options) {
						var v = options.item[options.cmName];
						if (v !== undefined && v !== "") {
							return true;
						}
					}
				}
			},
			
		}).jqGrid("filterToolbar",
			{
			}
		) //this method adds the filtertoolbar
		
		
		function conatact_display_image(cellvalue, options, rowObject) {
			var imageHtml = "";
			if (cellvalue != ''){
				imageHtml = "<img src='public/images/" + cellvalue + "' originalValue='" + cellvalue + "' height='100'/>";
			}
			return imageHtml;
		}; 

		function contact_action_row(cellvalue, options, rowObject) {
			var buttonHtml = "<button class='btn btn-info btn-xs ' onclick='edit_contact_form(" + rowObject.contact_id + ")'>Update</button> " +
							"<button class='btn btn-info btn-xs' onclick='delete_contact(" + rowObject.contact_id + ")' this.onlick=null;>Delete</button>";
			return buttonHtml;
		}; 
	});
</script>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
			<div class="row placeholders">
				<h1 class="page-header" style="border-bottom: 0px solid #eee;">Phonebook</h1>

				<div class="table-responsive">
					<div id="contact_table">
						<button type="button" class="btn btn-primary btn-xs" onclick="add_contact_form()" >Add contact</button>
						<span id='span_tab1_recordcount'></span>&nbsp;|&nbsp;
						<button class="btn btn-default btn-xs" onClick="filterToolbar_clearFilters('tbl_contacts_jqgrid');">Clear filters</button>		
						<br>	
						<br>	
						<table class="table" id="tbl_contacts_jqgrid"></table>
					</div>

					<div id="contact_form" style="display: none;">
						<h3></h3>
						<form action="<?php echo base_url()?>lib/query_process.php" method="post"  enctype="multipart/form-data">
							<br>
							<div class="form-group">
								<label for="txt_name">Name</label>
								<input type="text" class="form-control" id="txt_name" name="contact_name"  required=true>
							</div>
							<div class="form-group">
								<label for="txt_company">Company</label>
								<input type="text" class="form-control" id="txt_company" name="contact_company"  required=true>
							</div>
							<div class="form-group">
								<label for="txt_phone">Phone</label>
								<input type="text" class="form-control" id="txt_phone" name="contact_phone"  required=true>
							</div>
							<div class="form-group">
								<label for="txt_address">Address</label>
								<input type="text" class="form-control" id="txt_address" name="contact_address"  required=true>
							</div>
							<div class="form-group">
								<label for="txt_email">Email</label>
								<input type="email" class="form-control" id="txt_email" name="contact_email"  required=true>
							</div>	

							<div class="form-group">
								<label for="txt_email">Photo</label>
								<input type="file" id="contact_image" name="contact_image" accept="image/jpeg,image/png">
							</div>
							<input type="hidden" name="action" value="save"/>
							<input type="text" id="contact_id" name="contact_id" value="" style="display:none" />							
							<div class='form-group'>
								<br/>
								<button class='btn btn-default btn-md' type="button" id="Clear" style="float:right;" onclick="cancel_form()" >Cancel</button>
								<input class="btn btn-primary btn-md" type="submit" value="Submit"  style="float:right;margin-right:2px;" >
							</div>
						</form>
					</div>
					
				</div>
			</div>   
		</div>
		<div class="col-sm-1 col-md-1"></div>
	</div>
</div>
	</body>
</html>