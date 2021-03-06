<div class="row">
	<div class="col-sm-2"></div>

	<div class="col-sm-7">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>
						<span class="glyphicon glyphicon-pencil"></span>
						Editar tarefa
					</h4>
				</div>

				<div class="panel-body">
					<form class="form-horizontal" role="form" method="post" id="form-validate" action="<?= generateLink('task', 'edit', [$task['project_id'], $task['id']]); ?>" enctype="multipart/form-data">

						<input type="hidden" name="task[creator_id]" value="<?= $task['creator_id']; ?>" />

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
						      				<option value="<?= $performer['user_id']; ?>" <?php if ($performer['user_id'] == $task['performer_id']) echo "selected"; ?>>
						      					<?= $performer['user_name']; ?>
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
					      		<input type="text" class="form-control" id="name" placeholder="Nome da tarefa" name="task[name]" maxlength="100" value="<?= $task['name']; ?>" required />
					    	</div>
					  	</div>

					  	<div class="form-group">
					    	<label class="control-label col-sm-3" for="description">
					    		Descrição
					    	</label>

					    	<div class="col-sm-9"> 
					    		<textarea class="form-control" id="description" name="task[description]" rows="4" placeholder="Informe a descrição" maxlength="255"><?= $task['description']; ?></textarea>
					    	</div>
					  	</div>

						<div class="form-group">
					    	<label class="control-label col-sm-3" for="due_date">
					    		Vencimento *
					    	</label>
					    	
					    	<div class="col-sm-4">
					      		<input type="text" class="form-control date" id="due_date" name="task[date__due_date]" value="<?= formatDateToBR($task['due_date']); ?>" required />
					    	</div>
					  	</div>

					  	<div class="form-group"> 
					  		<label class="control-label col-sm-3">
					  			Prioridade *
					  		</label>

					    	<div class="col-sm-9">
					      		<div class="radio">
					        		<label>
					        			<input type="radio" name="task[priority]" value="baixa" <?php if ($task['priority'] == 'baixa') echo "checked"; ?>> Baixa
					        		</label>
					        		<label>
					        			<input type="radio" name="task[priority]" value="media" <?php if ($task['priority'] == 'media') echo "checked"; ?>> Média
					        		</label>
					        		<label>
					        			<input type="radio" name="task[priority]" value="alta" <?php if ($task['priority'] == 'alta') echo "checked"; ?>> Alta
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
						    			<input type="checkbox" id="completed" name="task[completed]" value="1" <?php if ($task['completed']) echo "checked"; ?>>
						    		</label>
						    	</div>
						    </div>
						</div>				  

						<div class="form-group" id="conclusion_date">
					    	<label class="control-label col-sm-3" for="input_conclusion_date">
					    		Data conclusão *
					    	</label>
					    	
					    	<div class="col-sm-4">
					      		<input type="text" class="form-control date" id="input_conclusion_date" name="task[date__conclusion_date]" value="<?= formatDateToBR($task['conclusion_date']); ?>" />
					    	</div>
					  	</div>

						<div class="form-group" id="div_remember">
					    	<label class="control-label col-sm-3" for="days_to_remember">
					    		Lembre-me com
					    	</label>
					    	
					    	<div class="col-sm-2">
					      		<input type="number" class="form-control" id="days_to_remember" name="task[days_to_remember]" maxlength="3" value="<?= (!empty($task['days_to_remember']) ? $task['days_to_remember'] : ''); ?>" min="1" max="60" />
					    	</div>

							<label class="control-label">
					    		dias antes do vencimento
					    	</label>
					  	</div>

					  	<div class="form-group">
					  		<label class="control-label col-sm-3" for="file">Arquivo</label>

					  		<div class="col-sm-9">
								<input id="file" type="file" class="file" name="file" />

								<?php if (!empty($file)): ?>
									<br />
									<div id="currentFile">
								  		<a href="<?= generateLink('file', 'download', [$file['id'], $file['name']]); ?>">
								  			<?= explode('-', $file['name'])[1]; ?>
								  		</a>
							  			
							  			<span id="removeCurrentFile" class="glyphicon glyphicon-trash" style="margin-left: 20px; cursor: pointer;"></span>
									</div>

									<input type="hidden" id="maintainCurrentFile" name="maintainCurrentFile" value="1" />
								<?php else: ?> 
									<input type="hidden" id="maintainCurrentFile" name="maintainCurrentFile" value="0" />
							  	<?php endif; ?>
					  		</div>
					  	</div>

				  		<div class="text-right">
				      		<button type="button" class="btn btn-danger" onclick="history.go(-1);">Cancelar</button>
				      		<button type="submit" class="btn btn-success" id="register">Salvar</button>
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

	$('#file').change(function(){
		if (validateFile(this.files[0])) {
			removeCurrentFile();
		}
	});

	var validExtensions = new Array('jpg', 'png', 'gif', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'odt', 'ppt', 'pptx', 'txt');
	function validateFile(file) {
		var extension = file.name.substr(file.name.lastIndexOf('.') +1);
		
		if ($.inArray(extension, validExtensions) == -1) {
			alert('O arquivo inserido é inválido insira apenas aquivos com extensões (' + validExtensions.join(', ') + ').');
			$('#file').val('');
			return false;
		}

		var maxUploadSize = (1024 * 1024 * 5);
		if (file.size > maxUploadSize) {
			alert('O arquivo inserido é muito grande, insira arquivos de até 5 MB.');
			$('#file').val('');
			return false;
		}

		return true;
	}

	activeMenu('list_projects');

	$('#removeCurrentFile').click(function(){
		removeCurrentFile();
	});

	function removeCurrentFile() {
		$('#currentFile').remove();
		$('#maintainCurrentFile').val(0);
	}
</script>