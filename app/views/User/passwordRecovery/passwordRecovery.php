<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<span class="glyphicon glyphicon-ban-circle"></span>
						Recuperar senha
					</h4>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('user', 'passwordRecovery'); ?>">
					  	<div class="form-group">
					    	<label class="control-label col-sm-3" for="email">
					    		Email *
					    	</label>

					    	<div class="col-sm-7"> 
					    		<input type="email" class="form-control" id="recovery_email" placeholder="Email" name="recovery_email" maxlenght="100" required />
					    	</div>
					  	</div>

				  		<div class="text-right">
				  			<div class="col-sm-10">
				      			<button type="button" class="btn btn-danger" onclick="history.go(-1);">Cancelar</button>
				      			<button type="submit" class="btn btn-success">Solicitar</button>
				      		</div>
				    	</div>
					</form>
				</div>
		  	</div>
		</div>
	</div>
</div>
