<div class="row">
	<div class="col-sm-2"></div>
	
	<div class="col-sm-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="glyphicon glyphicon-info-sign">
                    Informações da tarefa
                </h4>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr class="active">
                            <th>Atributo</th>
                            <th>Informação</th>
                        </tr>
                    </thead>
                    
                    <tbody>                        
                        <tr>
                            <td>Nome</td>
                            <td class="conteudo">
                            	<?= $task['name']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Descricao</td>
                            <td class="conteudo">
                            	<?= $task['description']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Vencimento</td>
                            <td class="conteudo">
                            	<?= formatDateToBR($task['due_date']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Prioridade</td>
                            <td class="conteudo">
                            	<?= str_replace('e', 'é', ucfirst($task['priority'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Concluída</td>
                            <td class="conteudo">
                            	<?= ($task['completed']) ? 'Sim' : 'Não'; ?>
                            </td>
                        </tr>

                        <?php if($task['completed']): ?>
                            <tr>
                                <td>Data conclusão</td>
                                <td class="conteudo">
                                	<?= formatDateToBR($task['conclusion_date']) ?>
                                </td>
                            </tr>
                        <?php elseif (!empty($task['days_to_remember'])): ?>
                            <tr>
                                <td>Lembrar com</td>

                                <td class="conteudo">
                                    <?= $task['days_to_remember'] ?>

                                    dias antes do vencimento
                                </td>

                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>

                <div class="panel-footer"> 
    		  		<div class="text-right">
    		      		<button type="button" class="btn btn-default" onclick="history.go(-1);">
    		      			Voltar
    		      		</button>

    		      		<button type="submit" class="btn btn-success" id="editar">
    		      			<a href="<?= generateLink('task', 'edit', [$task['project_id'], $task['id']]) ?>" style="text-decoration: none; color: white;">
    		      				Editar
    		      			</a>
    		      		</button>
    		    	</div>
    		  	</div>
            </div>
    	</div>
    </div>
</div>

<script type="text/javascript">
    activeMenu('list_projects');
</script>