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
				Ola, <?= $nameUser2 ?>
			</h2>
			
			<p>
				O usuário <strong><?= $nameUser1 ?></strong> gostaria de adiciona-lo aos contatos.
			</p>
			
			<p>
				Para aceita-lo basta clicar <a href="<?= $linkForAccept ?>" style="text-decoration:none;">aqui</a>.
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

<style type="text/css">
	.corpo {
		width: 50%;
		float: left;
	}
</style>