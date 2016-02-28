$(document).ready(function(){
	$('#listagem').DataTable({
	    "language": {
	        "lengthMenu": "Exibir _MENU_",
	        "zeroRecords": "Nenhum registro encontrado.",
	        "info": "Exibindo _END_ de _TOTAL_ registros",
	        "infoEmpty": "Nennhum registro disponível",
	        "loadingRecords": "Carregando...",
			"processing":     "Processando...",
			"search":         "Pesquisar", 
			"paginate": {
				"first":      "Primeira",
				"last":       "Ultima",
				"next":       "Próxima",
				"previous":   "Anterior"
			},
	    },
	    "iDisplayStart": 0,
	});

	$('input[type=search]').addClass('form-control');
	$('input[type=search]').addClass('pesquisa');
	$('[name="listagem_length"]').addClass('form-control');
	$('[name="listagem_length"]').addClass('exibir');
	
	$('.date').datepicker({
		format: 'dd/mm/yyyy',
		language: 'pt-BR',
		autoclose: true
	});
});


function openDeleteModal(id, controller, element){
	var href = $(location).attr('origin') + "/" + controller + "/delete/" + id;

	$('#btn_confirm_delete').attr('href', href);

	$('#title_delete_modal').html($(element).attr('title'));

	$('#delete_modal').modal({
		show: true,
	});
}

function activeMenu(nameItem) {
	$('#li_' + nameItem).addClass('active');
    $('#link_' + nameItem).attr('id', 'active');
}