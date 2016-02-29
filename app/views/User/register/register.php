<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<span class="glyphicon glyphicon-plus"></span>
						Novo usuário
					</h4>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('user', 'register'); ?>">
					 	<div class="form-group">
					    	<label class="control-label col-sm-3" for="name">
					    		Nome *
					    	</label>
					    	
					    	<div class="col-sm-7">
					      		<input type="text" class="form-control" id="name" placeholder="Nome do usuário" name="user[name]" maxlenght="50" required>
					    	</div>
					  	</div>

					  	<div class="form-group">
					    	<label class="control-label col-sm-3" for="email">
					    		Email *
					    	</label>

					    	<div class="col-sm-7"> 
					    		<input type="email" class="form-control" id="email_user" placeholder="Email" name="user[email]" maxlenght="100" required>
					    	</div>
					  	</div>

						<div class="form-group">
					    	<label class="control-label col-sm-3" for="password">
					    		Senha *
					    	</label>
							    	
					    	<div class="col-sm-7">
					      		<input type="password" class="form-control" id="password" name="user[password__password]" placeholder="Senha" maxlength="30" required>
					    	</div>
					  	</div>

				  		<div class="text-right">
				  			<div class="col-sm-10">
				      			<button type="button" class="btn btn-danger" onclick="history.go(-1);">Cancelar</button>
				      			<button type="submit" class="btn btn-success">Cadastrar</button>
				      		</div>
				    	</div>
					</form>
				</div>
		  	</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#form-validate').submit(function(){
		if (!validateEmail()) {
			return false;
		}

		return true;
	});

	function validateEmail(){
		var validEmail;

		$.ajax({
			url: "<?= generateLink('user', 'validateEmail') ?>",
			type: 'POST',
			data: {email: $('#email_user').val()},
			dataType: 'json',
			async: false,
			success: function(returnFunction){
				if (returnFunction == 0) {
					alert('Este e-mail ja esta sendo utilizado no sistema, favor inserir outro.');					
				}

				validEmail = returnFunction;
			},
			error: function(error){
				alert('Ocorreu algum erro ao validar seu e-mail');
				validEmail = false;
			}
		});

		return validEmail;
	}
</script>