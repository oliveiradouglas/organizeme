<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-7">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<span class="glyphicon glyphicon-pencil"></span>
						Editar projeto
					</h4>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('project', 'edit', [$project['id']]); ?>">
					 	<div class="form-group">
					    	<label class="control-label col-sm-3" for="name">
					    		Nome *
					    	</label>
					    	
					    	<div class="col-sm-7">
					      		<input type="text" class="form-control" id="name" placeholder="Nome do projeto" name="project[name]" maxlenght="50" required value="<?= htmlspecialchars($project['name']); ?>" />
					    	</div>
					  	</div>
						
						<div class="form-group">
					    	<label class="control-label col-sm-3" for="users_project">
					    		Usuários
					    	</label>
					    	
					    	<div class="col-sm-7">
					      		<select class="form-control col-sm-7" id="users_project" name="project_users[]" multiple>
					      			<option value="" disabled selected style="display:none;">
						      			Selecione os usuários...
						      		</option>

						      		<?php foreach ($users as $user): ?>
						      			<option value="<?= $user['user_id']; ?>" <?php if (in_array($user['user_id'], $projectUsers)): ?>selected<?php endif; ?>>
						      				<?= $user['name']; ?>
						      			</option>
						      		<?php endforeach; ?>
					      		</select>
					    	</div>
					  	</div>

					  	<div class="form-group">
					    	<label class="control-label col-sm-3" for="description">
					    		Descrição
					    	</label>

					    	<div class="col-sm-7"> 
					    		<textarea class="form-control" id="description" name="project[description]" rows="5" placeholder="Informe a descrição" maxlenght="255"><?= htmlspecialchars($project['description']); ?></textarea>
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

<script type="text/javascript">
	activeMenu('list_projects');
</script>