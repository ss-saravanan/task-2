<?php $this->load->view('header'); ?>
</head>
<body>

<section class="container">

	<div class="container-fluid" style="box-shadow: inset 0px 1px lightgrey; padding: 20px;">
		
		<form id="myForm" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div id="msg"><?php echo ($this->session->flashdata('msg')) ? $this->session->flashdata('msg') : ''; ?></div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12 text-center"><h4 class="text-primary">LOGIN</h4></div>
						<div class="col-md-12 text-center">
							<div class="form-group">
								<input type="text" name="name" id="name" class="form-control" placeholder="User Name" value="" required>
							</div>
						</div>

						<div class="col-md-12 text-center">
							<div class="form-group">
								<input type="password" name="password" id="password" class="form-control" placeholder="Password" value="" required>
							</div>
						</div>

						<div class="col-md-12 text-center">
							<div class="form-group">
								<input type="submit" name="submit" id="submit" class="btn btn-md btn-success" value="Login">
							</div>
						</div>

						<div class="col-md-12">
							<span> <small>Username : <b>admin</b>  Password : <b>admin@123</b></small> </span>
						</div>
					</div>
				</div>
				<div class="col-md-3"></div>
			</div>
		</form>

	</div>

</section>

<?php $this->load->view('footer'); ?>
<script type="text/javascript">
	$(function() {
		$('form').validate();
	});
</script>
</body>
</html>