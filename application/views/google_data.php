<?php $this->load->view('header'); ?>
</head>
<body>

<section class="container">
	<nav class="navbar navbar-default" style="margin-top: 5px; max-height: 30px;">
		<div class="container-fluid">
			
			<div class="collapse navbar-collapse" id="alignment-example">
				<ul class="nav navbar-nav">
					<li class="active"><a href="javascript:;">Google Sheets Data <span class="sr-only">(current)</span></a></li>
					<li><a href="<?php echo base_url(); ?>send-sms">TWILIO SMS</a></li>
					<li><a href="<?php echo base_url(); ?>logout" style="float: right;">Logout</a></li>
				</ul>
			</div>

		</div>
	</nav>

	<div class="container-fluid" style="box-shadow: inset 0px 1px lightgrey; padding: 20px;">
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-primary" style="float: left;">Google Sheet - Fetch & Update</h4>
				<a href="https://docs.google.com/spreadsheets/d/1drnqn_5NHXAxJzbULbJ5rnoc_23FI0ae_30uubTGtE4/edit#gid=0" class="btn btn-md btn-success" target="_blank" style="float: right;">Open Sheet</a>
			</div>
			<hr>
			<div class="col-md-12 text-center">
				<div id="msg"><?php echo ($this->session->flashdata('msg')) ? '<span class="alert alert-success">'.$this->session->flashdata('msg').'</span>' : ''; ?></div>
			</div>
			<br>
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>Email</th>
							<th>Age</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if( !empty($values) ) {
								$i = intval(2);
								foreach ($values as $k => $v) {
									?>
							<tr>
								<td id="Name_<?php echo $i; ?>"><?php echo $v[0]; ?></td>
								<td id="Mobile_<?php echo $i; ?>"><?php echo $v[1]; ?></td>
								<td id="Email_<?php echo $i; ?>"><?php echo $v[2]; ?></td>
								<td id="Age_<?php echo $i; ?>"><?php echo $v[3]; ?></td>
								<td><button type="button" id="edit_<?php echo $k; ?>" class="btn btn-sm btn-primary edit" data-id="<?php echo $i; ?>">Edit</button></td>
							</tr>							
									<?php
									$i++;
								}
							} else {
								?>
							<tr>
								<td colspan="5">
									<div class="alert alert-danger text-center">No Records Found.</div>
								</td>
							</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="clearfix"></div>
		</div>
		
	</div>

</section>

<div class="modal" tabindex="-1" id="editModal" data-backdrop="false">
	<div class="modal-dialog">
	  	<div class="modal-content">
	  	<form id="editForm" action="<?php echo base_url(); ?>update-data" method="post">
	      <div class="modal-header">
	          <h4 class="modal-title">Edit Sheet Data</h4>
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	      </div>
	      <div class="modal-body">
      		<input type="hidden" name="row" id="row" value="">
      		<div class="row">
	      		<div class="col-md-12">
	      			<div class="form-group">
	      				<label for="Name">Name</label>
	      				<input type="text" name="Name" id="Name" class="form-control" required>
	      			</div>
	      		</div>
	      		<div class="col-md-12">
	      			<div class="form-group">
	      				<label for="Mobile">Mobile</label>
	      				<input type="number" name="Mobile" id="Mobile" class="form-control" required>
	      			</div>
	      		</div>
	      		<div class="col-md-12">
	      			<div class="form-group">
	      				<label for="Email">Email</label>
	      				<input type="email" name="Email" id="Email" class="form-control" required>
	      			</div>
	      		</div>
	      		<div class="col-md-12">
	      			<div class="form-group">
	      				<label for="Age">Age</label>
	      				<input type="number" name="Age" id="Age" class="form-control" required step="1" min="1" max="100">
	      			</div>
	      		</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	      	<button type="submit" id="Update" class="btn btn-md btn-success">Update</button>
	         <button type="button" id="Cancel" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
	      </div>
	   </form>
	  </div>
	</div>
</div>

<?php $this->load->view('footer'); ?>
<script type="text/javascript">
	$(function() {

		var modal = $('#editModal').modal('hide');

		$(document).on('click', '.edit', function() {
			var id = $(this).data('id');
			modal.modal('show');
			$('#row').val( id );
			$('#Name').val( $('#Name_'+id).text() );
			$('#Mobile').val( $('#Mobile_'+id).text() );
			$('#Email').val( $('#Email_'+id).text() );
			$('#Age').val( $('#Age_'+id).text() );
		});
		
		$('#editForm').validate({
			submitHandler : function(form) {
				var form = $(form);
				var data = form.serializeArray();			
				$.ajax({
	 				url : "<?php echo base_url(); ?>update-data",
	 				type : "POST",
	 				data : data,
	 				dataType : 'json',
	 				beforeSend : function() {
	 					$('#Update').text('Updating...');
	 				},
	 				success : function(resp) {
	 					$('#Update').text('Update');
	 					$('#editForm').trigger('reset');
	 					modal.modal('hide');
	 					if(resp.status) {
	 						$('#Name_'+data[0].value).text( data[1].value );
	 						$('#Mobile_'+data[0].value).text( data[2].value );
	 						$('#Email_'+data[0].value).text( data[3].value );
	 						$('#Age_'+data[0].value).text( data[4].value );
	 					}
	 					var msg = (resp.status) ? '<span class="alert alert-success">Success! Data updated in google sheet.</span>' : '<span class="alert alert-danger">Error! Failed to update.</span>';
	 					$('#msg').html(msg);
	 					setTimeout(function() { $('#msg').html(''); }, 5000);
	 				},
	 				error : function(err) {
	 					$('#Update').text('Update');
	 					alert(err);
	 				}
	 			});
			}
		});

		setTimeout(function() { $('#msg').html(''); }, 5000);

	});
</script>
</body>
</html>