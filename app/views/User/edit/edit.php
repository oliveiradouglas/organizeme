<div class="container">
	<div class="row">
		<div class="col-sm-2"></div>

		<div class="col-sm-7">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>
							<span class="glyphicon glyphicon-pencil"></span>
							Editar usuário
						</h4>
					</div>

					<div class="panel-body">
						<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('user', 'edit', [$user['id']]); ?>">
						 	<div class="form-group">
						    	<label class="control-label col-sm-3" for="name">
						    		Nome *
						    	</label>
						    	
						    	<div class="col-sm-7">
						      		<input type="text" class="form-control" id="name" placeholder="Nome do usuário" name="user[name]" maxlenght="50" value="<?= htmlspecialchars($user['name']) ?>" required>
						    	</div>
						  	</div>

						  	<div class="form-group">
						    	<label class="control-label col-sm-3" for="email">
						    		Email *
						    	</label>

						    	<div class="col-sm-7"> 
						    		<input type="email" class="form-control" id="email" placeholder="Email" maxlenght="100" value="<?= htmlspecialchars($user['email']) ?>" required disabled />
						    	</div>
						  	</div>

							<div class="form-group">
						    	<label class="control-label col-sm-3" for="password">
						    		Nova senha
						    	</label>
						    	
						    	<div class="col-sm-7">
						      		<input type="password" class="form-control" id="password" name="user[password__password]" placeholder="Nova senha" maxlength="30">
						    	</div>
						  	</div>

					  		<div class="text-right">
					  			<div class="col-sm-10">
					      			<button type="button" class="btn btn-danger" onclick="history.go(-1);">Cancelar</button>
					      			<button type="submit" class="btn btn-success">Salvar</button>
					      		</div>
					    	</div>
						</form>
					</div>
			  	</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	activeMenu('edit_user');
</script>