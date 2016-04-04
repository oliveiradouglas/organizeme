<div class="row">
	<div class="col-sm-1"></div>

	<div class="col-sm-9">
		<ol class="breadcrumb" style="background-color:#fff">
  			<li>
		  		<div class="btn-group" role="group">
		    		<a href="<?= generateLink('project', 'register'); ?>" type="button" class="btn btn-success">
		      			<span class="glyphicon glyphicon-plus"></span>
		      			Novo projeto
		    		</a>
		  		</div>
  			</li>
		</ol>

		<div class="table-responsive" style="margin:30px 0px;">
			<table class="table table-bordered display" id="listagem">
				<thead>
					<tr>
						<?php if(isset($tableHeader) && !empty($tableHeader)): ?>
							<?php foreach ($tableHeader as $header): ?>
								<th>
									<?= $header; ?>
								</th>
							<?php endforeach; ?>
						<?php endif; ?>

						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					<?php if(isset($projects) && !empty($projects)): ?>
						<?php foreach ($projects as $project): ?>
							<tr>
								<?php foreach ($tableHeader as $key => $header): ?>
									<td <?php if ($key == 'name') echo "style='width:65%;'"; ?>>
										<?= $project[$key]; ?>
									</td>
								<?php endforeach; ?>

								<td class="text-center">
									<a class='btn btn-default' href='<?= generateLink('task', 'listTasks', [$project['id']]); ?>' title='Tarefas'>
										<span class="glyphicon glyphicon-list-alt"></span>
									</a>
									<a class='btn btn-default' href='<?= generateLink('project', 'visualize', [$project['id']]); ?>' title='Visualizar projeto'>
										<span class='glyphicon glyphicon-eye-open'></span>
									</a>
									
									<?php if ($project['user_id'] == $_SESSION['user']['id']): ?>
										<a class='btn btn-default' href='<?= generateLink('project', 'edit', [$project['id']]); ?>' title='Editar projeto'>
											<span class='glyphicon glyphicon-pencil'></span>
										</a>

										<a class='btn btn-danger' href='#' onclick='openDeleteModal(<?= $project['id'] ?>, "project", $(this));return false;' title='Excluir projeto'>
											<span class='glyphicon glyphicon-remove'></span>
										</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include_once(PATH_ROOT . '/app/views/Container/deleteModal.php') ?>

<script type="text/javascript">
	activeMenu('list_projects');
</script>