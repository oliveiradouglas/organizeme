			<?php if (isset($arquivosIncluir) && !empty($arquivosIncluir)): ?>
				<?php foreach($arquivosIncluir as $chave => $arquivo): ?>
					<?php include_once($arquivo); ?>
				<?php endforeach; ?>
			<?php endif; ?>
    	</div>
    	
		<footer class="footer" id="footer">
    		<p class="text-center"> &copy; Douglas Oliveira 2016</p>
  		</footer>
 	</body>
</html>
