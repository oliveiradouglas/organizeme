<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>
			<meta charset="utf-8" />
		</title>
	</head>

	<body>
		<div class="corpo">
			<h2>
				Ola!
			</h2>
			
			<p>
				A seguinte tarefa esta próxima do vencimento:
			</p>
			
			<strong>Nome:       </strong> <?= $task['name']; ?> <br />
			<strong>Descrição:  </strong> <?= $task['description']; ?> <br />
			<strong>Prioridade: </strong> <?= str_replace('e', 'é', ucfirst($task['priority'])); ?> <br />
			<strong>Vencimento: </strong> <?= formatDateToBR($task['due_date']); ?> <br /><br />
			
			<em>Att,</em>
			<br /> OrganizeME <br /> 
			<small>
				<em>Tudo sob controle</em>
			</small>
		</div>
	</body>
</html>