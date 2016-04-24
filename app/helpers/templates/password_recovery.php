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
				Ola, <?= $nameUser ?>
			</h2>
			
			<p>
				Você solicitou a recuperação de senha.
			</p>
			
			<p>
				Sua nova senha é 
				<strong>
					<?= $newPassword ?>
				</strong>
			</p>

			<br />
			
			<em>Att,</em>
			<br /> OrganizeME <br /> 
			<small>
				<em>Tudo sob controle</em>
			</small>
		</div>
	</body>
</html>
