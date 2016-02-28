<nav class="navbar navbar-inverse">
	<div class="container-fluid">
  		<div class="navbar-header">
      		<a class="navbar-brand" href="<?= DOMAIN ?>" style="font-size:150%;">
      			<img src="/app/webroot/img/logo1.png" id="logo">
      		</a>
    	</div>
		
		<?php if (isset($_SESSION['user'])): ?>
	    	
	    	<div>
		  	    <ul class="nav navbar-nav navbar-right">
		  	    	<li>
		  	    		<p class="navbar-text">
		  	    			<span class="glyphicon glyphicon-user"></span>
		  	    			<?= $_SESSION['user']['name'] ?>
		  	    		</p>
		  	    	</li>

				    <li>
				    	<a class="navbar-link" href="<?= generateLink('user', 'logout'); ?>" title="Sair" id="logout">
				    		<span class="glyphicon glyphicon-log-out"></span>
				    	</a>
				    </li>
		    	</ul>
	    	</div>

		<?php else: ?>

			<div class="collapse navbar-collapse">
	            <ul class="nav navbar-nav navbar-right">
			        <li>
			        	<a href="<?= generateLink('user', 'passwordRecovery'); ?> "><span class="glyphicon glyphicon-ban-circle"></span> Esqueceu a senha? </a>
			        </li>
			        <li>
			        	<a href="<?= generateLink('user', 'register'); ?> "><span class="glyphicon glyphicon-plus"></span> Cadastre-se </a>
			        </li>
			    </ul>

		      	<form class="navbar-form navbar-right" role="form" method="post" action="<?= generateLink('user', 'login'); ?>">
	                <div class="input-group">
	                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	                    <input id="email" type="email" class="form-control" name="user[email]" placeholder="Email" required>                                        
	                </div>

	                <div class="input-group">
	                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	                    <input id="password" type="password" class="form-control" name="user[password__password]" placeholder="Senha" required>
	                </div>

	                <button type="submit" class="btn btn-success">Entrar</button>
	            </form>
			</div>
		<?php endif; ?>
	</div>
</nav>

<?php if (isset($_SESSION['user'])): ?>
	<div class="col-sm-3 col-md-2 sidebar" id="nav_esquerda">
    	<ul class="nav nav-sidebar">
        	<li id="li_list_projects">
	        	<a href="<?= generateLink('project', 'listProjects'); ?>" id="link_list_projects">
	        		<span class="glyphicon glyphicon-list"></span>
	        		Projetos
	        	</a>
	        </li>

        	<li id="li_contacts">
	        	<a href="<?= generateLink('contacts', 'listContacts'); ?>" id="link_contacts">
	        		<span class="glyphicon glyphicon-user"></span>
	        		Contatos
	        	</a>
	        </li>

	        <li id="li_edit_user">
	        	<a href="<?= generateLink('user', 'edit', [$_SESSION['user']['id']]); ?>" id="link_edit_user">
	        		<span class="glyphicon glyphicon-pencil"></span>
	        		Editar usuário
	        	</a>
	        </li>
      	</ul>
    </div>
<?php endif; ?>