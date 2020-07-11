<?php $this->load->view('header'); ?>
</head>
<body>

<section class="container">
	<nav class="navbar navbar-default" style="margin-top: 5px; max-height: 30px;">
		<div class="container-fluid">
			
			<div class="collapse navbar-collapse" id="alignment-example">
				<ul class="nav navbar-nav">
					<li><a href="<?php echo base_url(); ?>get-data">Google Sheets Data</a></li>
					<li class="active"><a href="javascript:;">TWILIO SMS <span class="sr-only">(current)</span></a></li>
					<li><a href="<?php echo base_url(); ?>logout" style="float: right;">Logout</a></li>
				</ul>
			</div>

		</div>
	</nav>

	<div class="container-fluid" style="box-shadow: inset 0px 1px lightgrey; padding: 20px;">
		<div class="row">
			<div class="col-md-12 text-center">
				<div id="msg"><?php echo ($this->session->flashdata('msg')) ? $this->session->flashdata('msg') : ''; ?></div>
			</div>
			<br>
			<div class="col-md-12">
				<h4 class="text-primary">Twilio SMS - Gateway</h4>
				<hr>
			</div>
			<div class="col-md-8">

				<div class="row">
					<div class="col-xs-3">
		            <ul class="nav nav-tabs tabs-left">
		               <li class="active"><a href="#send_sms" data-toggle="tab">Send SMS</a></li>
		               <li><a href="#verify_phone" data-toggle="tab">Verify Phone</a></li>
		            </ul>
		        	</div>
		        	<div class="col-xs-8">
		            <div class="tab-content">
		               <div class="tab-pane active" id="send_sms">
		               	<div class="row" style="max-width: 500px;">
		               		<form id="sendForm" action="<?php echo base_url(); ?>create-sms" method="post">
										<div class="col-xs-12 form-group">
			               			<label for="Phone">Phone Number</label>
			               			<div class="input-group">
												<div class="input-group-addon">
													<span class="input-group-text" id="basic-addon1">+91</span>
												</div>
				               			<input type="text" name="Phone" id="Phone" class="form-control" placeholder="Enter Phone number">
			               			</div>
			               			<span><small>Note : Verified Number ( <i class="text-success">8190023811</i> ).</small></span>
			               		</div>
			               		<div class="col-xs-12 form-group">
			               			<label for="Message">Message</label>
			               			<textarea name="Message" id="Message" class="form-control" placeholder="Enter your message"></textarea>
			               		</div>

			               		<div class="col-xs-12 form-group">
			               			<button type="submit" name="Send" id="Send" class="btn btn-md btn-success" value="Send">Send</button>
			               		</div>
		               		</form>
		               	</div>
		               </div>


		               <div class="tab-pane" id="verify_phone">
		               	<input type="hidden" name="hidden_otp" id="hidden_otp" value="">
	               		<div class="row otp-section-1" style="max-width: 500px;">
									<div class="col-xs-12 form-group">
		               			<label for="Mobile">Phone Number</label>
		               			<div class="input-group">
											<div class="input-group-addon">
												<span class="input-group-text" id="basic-addon1">+91</span>
											</div>
			               			<input type="text" name="Mobile" id="Mobile" class="form-control" placeholder="Enter Phone number">
		               			</div>
		               			<span><small>Note : Verified Number ( <i class="text-success">8190023811</i> ).</small></span>
		               		</div>
		               		<div class="col-xs-12 form-group opt-btn-section">
		               			<button type="button" name="send_otp" id="send_otp" class="btn btn-md btn-info">Send OTP</button>
		               		</div>
	               		</div>

	               		<div class="row otp-section-2 hide" style="max-width: 500px;">
	               			<div class="col-xs-12 form-group">
		               			<label for="otp">Your OTP</label>
		               			<input type="number" name="otp" id="otp" class="form-control" placeholder="Enter OTP">
		               		</div>

		               		<div class="col-xs-12 form-group">
		               			<button type="button" name="verify_otp" id="verify_otp" class="btn btn-md btn-success">Verify OTP</button>
		               			<button type="button" name="resend_otp" id="resend_otp" class="btn btn-md btn-primary">Resend OTP</button>
		               			<button type="button" name="cancel_otp" id="cancel_otp" class="btn btn-md btn-danger">Cancel</button>
		               		</div>
	               		</div>
		               </div>
		            </div>
		        	</div>
		        	<div class="col-xs-1"></div>
				</div>

			</div>
			<div class="col-md-4"></div>
		</div>
		
	</div>

</section>

<?php $this->load->view('footer'); ?>
<script type="text/javascript">
	$(function() {

		$('#sendForm').validate({
			rules : {
				Phone : { required : true, digits : true, minlength : 10, maxlength : 10 },
				Message : { required : true, maxlength : 250 }
			},
			submitHandler : function(form) {
				var form = $(form);
				var data = form.serializeArray();
				$.ajax({
	 				url : "<?php echo base_url(); ?>create-sms",
	 				type : "POST",
	 				data : data,
	 				dataType : 'json',
	 				beforeSend : function() {
	 					$('#Send').text('Sending...');
	 				},
	 				success : function(resp) {
	 					$('#Send').text('Send');
	 					$('#sendForm').trigger('reset');
	 					var msg = (!resp.data.IsError) ? '<span class="alert alert-success">Success! SMS Sent!</span>' : '<span class="alert alert-danger">The number  is unverified. Trial accounts cannot send messages to unverified numbers.</span>';
	 					$('#msg').html(msg);
	 					setTimeout(function() { $('#msg').html(''); }, 5000);
	 				},
	 				error : function(err) {
	 					$('#Send').text('Send');
	 					alert(err);
	 				}
	 			});
			}
		});

		$('#send_otp').on('click', function() {
			var mob = $('#Mobile');
			if( mob.val() == '' ) {
				mob.parent().find('.error').remove();
				mob.after('<label id="Mobile-error" class="error">Mobile number is required</label>');
			} else {
				var numbers = /^[0-9]+$/;
				if( mob.val().match(numbers) == false ) {
					mob.parent().find('.error').remove();
					mob.after('<label id="Mobile-error" class="error">Enter valid mobile number</label>');
				} else if( mob.val().length != 10 ) {
					mob.parent().find('.error').remove();
					mob.after('<label id="Mobile-error" class="error">Mobile number must be 10 digits</label>');
				} else {
					$.ajax({
		 				url : "<?php echo base_url(); ?>send-otp",
		 				type : "POST",
		 				data : { 'Mobile' : mob.val() },
		 				dataType : 'json',
		 				beforeSend : function() {
		 					$('#send_otp').text('Sending...');
		 				},
		 				success : function(resp) {
		 					$('#send_otp').text('Send OTP');
		 					var msg = '';
		 					if(!resp.data.data.IsError) {
		 						$('#Mobile, #send_otp').attr('readonly', 'readonly');
		 						msg = '<span class="alert alert-success">Success! OTP Sent!</span>';
		 						$('.otp-section-2').removeClass('hide');
		 						$('.otp-section-1').addClass('hide');
		 						$('#hidden_otp').val(resp.data.otp);
		 					} else {
		 						$('#Mobile, #send_otp').removeAttr('readonly');
		 						msg = '<span class="alert alert-danger">The number  is unverified. Trial accounts cannot send messages to unverified numbers.</span>';
		 						$('#hidden_otp').val('');
		 					}
		 					$('#msg').html(msg);
		 					setTimeout(function() { $('#msg').html(''); }, 5000);
		 				},
		 				error : function(err) {
		 					$('#send_otp').text('Send OTP');
		 					$('#Mobile, #send_otp').removeAttr('readonly');
		 					alert(err);
		 				}
		 			});
				}
			}
		});

		$('#verify_otp').on('click', function() {
			if( $('#hidden_otp').val() == $('#otp').val() ) {
				$('#msg').html('<span class="alert alert-success">OTP Verified...</span>');
			} else {
				$('#msg').html('<span class="alert alert-danger">Incorrect OTP...</span>');
			}
		});

		$('#resend_otp, #cancel_otp').click(function() {
			$('.otp-section-1').removeClass('hide');
			$('.otp-section-2').addClass('hide');
			$('#Mobile, #otp, #hidden_otp').val('');
			$('#verify_phone').find('input, button').each(function() {
				$(this).removeAttr('readonly');
			});
			$('#msg').html('');
		});

		setTimeout(function() { $('#msg').html(''); }, 5000);

	});
</script>
</body>
</html>