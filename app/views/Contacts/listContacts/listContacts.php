<div class="row">
	<div class="col-sm-1"></div>

	<div class="col-sm-9">
		<ol class="breadcrumb" style="background-color:#fff">
  			<li>
		  		<div class="btn-group" role="group">
		    		<a href="<?= generateLink('contacts', 'register'); ?>" type="button" class="btn btn-success">
		      			<span class="glyphicon glyphicon-plus"></span>
		      			Novo contato
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
					<?php if(isset($contacts) && !empty($contacts)): ?>
						<?php foreach ($contacts as $contact): ?>
							<tr>
								<td>
									<?= $contact['name']; ?>
								</td>
								<td>
									<?= ($contact['accepted'] ? 'Ativo' : 'Aguardando aprovação'); ?>
								</td>

								<td class="text-center">
									<a class='btn btn-danger' href='#' onclick='openDeleteModal(<?= $contact['id'] ?>, "contacts", $(this));return false;' title='Excluir contato'>
										<span class='glyphicon glyphicon-remove'></span>
									</a>

									<?php if (!$contact['accepted'] && $contact['user2'] == $_SESSION['user']['id']): ?>
										<a class='btn btn-success' href='/contacts/acceptContact/<?= $contact['id']; ?>/1' title='Aceitar contato'>
											<span class='glyphicon glyphicon-ok'></span>
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
	activeMenu('contacts');
</script>