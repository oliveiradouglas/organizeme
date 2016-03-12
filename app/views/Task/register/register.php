<div class="row">
	<div class="col-sm-2"></div>

	<div class="col-sm-7">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<span class="glyphicon glyphicon-plus"></span>
						Nova tarefa
					</h4>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('task', 'register', [$projectId]); ?>">

						<input type="hidden" name="task[project_id]" value="<?= $projectId; ?>" />

					    <div class="form-group">
					    	<label class="control-label col-sm-3" for="performer">
					    		Executante *
					    	</label>

					      	<div class="col-sm-6">
						      	<select class="form-control" id="performer" name="task[performer_id]" required>
						      		<option value="" disabled selected style="display:none;">
						      			Selecione o executante...
						      		</option>

						      		<?php if (!empty($performers)): ?>
						      			<?php foreach ($performers as $performer): ?>
						      				<option value="<?= $performer['user_id']; ?>">
						      					<?= $performer['name']; ?>
						      				</option>
						      			<?php endforeach; ?>
						      		<?php endif; ?>
						      	</select>
					      	</div>
					    </div>

					 	<div class="form-group">
					    	<label class="control-label col-sm-3" for="name">
					    		Nome *
					    	</label>
					    	
					    	<div class="col-sm-9">
					      		<input type="text" class="form-control" id="name" placeholder="Nome da tarefa" name="task[name]" maxlenght="50" required>
					    	</div>
					  	</div>

					  	<div class="form-group">
					    	<label class="control-label col-sm-3" for="description">
					    		Descrição *
					    	</label>

					    	<div class="col-sm-9"> 
					    		<textarea class="form-control" id="description" name="task[description]" rows="4" placeholder="Informe a descrição" required maxlenght="255"></textarea>
					    	</div>
					  	</div>

						<div class="form-group">
					    	<label class="control-label col-sm-3" for="due_date">
					    		Vencimento *
					    	</label>
					    	
					    	<div class="col-sm-4">
					      		<input type="text" class="form-control date" id="due_date" name="task[date__due_date]" required />
					    	</div>
					  	</div>

					  	<div class="form-group"> 
					  		<label class="control-label col-sm-3">
					  			Prioridade *
					  		</label>

					    	<div class="col-sm-9">
					      		<div class="radio">
					        		<label>
					        			<input type="radio" name="task[priority]" value="baixa" checked> Baixa
					        		</label>
					        		<label>
					        			<input type="radio" name="task[priority]" value="media"> Média
					        		</label>
					        		<label>
					        			<input type="radio" name="task[priority]" value="alta"> Alta
					        		</label>
					      		</div>
					    	</div>
					  	</div>
						
						<div class="form-group"> 
							<label class="control-label col-sm-3" for="completed">
								Concluida 
							</label>

						    <div class="col-sm-9">
							    <div class="checkbox">
						    		<label>
						    			<input type="checkbox" id="completed" name="task[completed]" value="1">
						    		</label>
						    	</div>
						    </div>
						</div>				  

						<div class="form-group" id="conclusion_date">
					    	<label class="control-label col-sm-3" for="input_conclusion_date">
					    		Data conclusão *
					    	</label>
					    	
					    	<div class="col-sm-4">
					      		<input type="text" class="form-control date" id="input_conclusion_date" name="task[date__conclusion_date]">
					    	</div>
					  	</div>

						<div class="form-group" id="div_remember">
					    	<label class="control-label col-sm-3" for="days_to_remember">
					    		Lembre-me com
					    	</label>
					    	
					    	<div class="col-sm-2">
					      		<input type="number" class="form-control" id="days_to_remember" name="task[days_to_remember]" maxlength="3" min="1" max="60" />
					    	</div>

							<label class="control-label">
					    		dias antes do vencimento
					    	</label>
					  	</div>

				  		<div class="text-right">
				      		<button type="button" class="btn btn-danger" onclick="history.go(-1);">Cancelar</button>
				      		<button type="submit" class="btn btn-success" id="register">Cadastrar</button>
				    	</div>
					</form>
				</div>
		  	</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		verifyConclusion();
	});

	function verifyConclusion(){
		if ($('#completed').is(':checked')) {
			$('#conclusion_date').show();
			$('#input_conclusion_date').attr('required', true);
			$('#div_remember').hide();
			$('#days_to_remember').val('');
		} else {
			$('#conclusion_date').hide();		
			$('#input_conclusion_date').removeAttr('required');
			$('#input_conclusion_date').val('');
			$('#div_remember').show();
		}
	}

	$('#completed').click(function(){
		verifyConclusion();
	});	

	activeMenu('list_projects');
</script>