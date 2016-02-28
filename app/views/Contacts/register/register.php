<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-7">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<span class="glyphicon glyphicon-plus"></span>
						Novo contato
					</h4>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('contacts', 'saveRegister'); ?>">
					 	<div class="form-group">
					    	<label class="control-label col-sm-3" for="email">
					    		E-mail *
					    	</label>
					    	
					    	<div class="col-sm-7">
					      		<input type="text" class="form-control" id="email" placeholder="E-mail do usuário" name="contacts[email]" maxlenght="50" required>
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