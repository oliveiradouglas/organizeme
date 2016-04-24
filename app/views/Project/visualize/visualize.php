<div class="row">
	<div class="col-sm-2"></div>
	
	<div class="col-sm-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="glyphicon glyphicon-info-sign">
                    Informações do projeto
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
                            <td class="content">
                            	<?= $project['name']; ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Usuários</td>
                            <td class="content">
                                <?= implode(' | ', array_column($projectUsers, 'user_name')); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Descricao</td>
                            <td class="content">
                            	<?= $project['description']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="panel-footer"> 
    		  		<div class="text-right">
    		      		<button type="button" class="btn btn-default" onclick="history.go(-1);">
    		      			Voltar
    		      		</button>

                        <?php if ($project['user_id'] == $_SESSION['user']['id']): ?>
        		      		<button type="submit" class="btn btn-success" id="editar">
        		      			<a href="<?= generateLink('project', 'edit', [$project['id']]) ?>" style="text-decoration: none; color: white;">
        		      				Editar
        		      			</a>
        		      		</button>
                        <?php endif; ?>
    		    	</div>
    		  	</div>
            </div>
    	</div>
    </div>
</div>

<script type="text/javascript">
    activeMenu('list_projects');
</script>