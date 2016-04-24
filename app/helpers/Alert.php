<?php

namespace Helpers;

class Alert {
	public static function displayAlert($controllerName, $messageIndex, $success){
		$type    = (($success) ? 'success' : 'danger');

		try {
			if(isset(self::$messages[$type][$controllerName][$messageIndex])) {
				$message = self::$messages[$type][$controllerName][$messageIndex];
			} else {
				$message = $messageIndex;
			}
		} catch (\Exception $e){
			$message = 'Ocorreu algum erro ao processar a operação, contate o administrador.';
		}

		$_SESSION['alert'] = self::mountHtmlAlert($type, $message);
	}

	private static function mountHtmlAlert($type, $message){
		$htmlAlert  = '<div class="alert alert-' . $type . ' text-center" id="message">';
		$htmlAlert .= '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
		$htmlAlert .= $message . '</div>';

		return $htmlAlert;
	}

	private static $messages = [
		'danger' => [
			'system' => [
				'QUERY_ERROR'          => 'Ocorreu algum erro ao buscar os dados!',
				'ERROR_OPERATION'      => 'Você não pode realizar esta operação!',
				'FILL_REQUIRED_FIELDS' => 'Você precisa preencher todos os campos obrigatórios!',
				'OPERATION_UNKNOW'     => 'Operação não informada!',
			],
			'user' => [
				'USER_POST_EMPTY'           => 'Você deve informar os dados do usuário!',
				'RECOVERY_EMAIL_POST_EMPTY' => 'Você precisa informar o e-mail do usuário!',
				'INVALID_LOGIN'             => 'Usuário ou senha inválido, verifique os dados inseridos!',
				'ERROR_REGISTER'            => 'Ocorreu algum erro ao realizar o cadastro!',
				'NOT_FOUND'                 => 'Usuário não encontrado no sistema!',
				'EDIT'                      => 'Ocorreu algum erro ao editar o usuário!',
				'PASSWORD_RECOVERY'         => 'Ocorreu algum erro ao solicitar a recuperação de senha do usuário!',
				'USER_ID'                   => 'Usuário não informado!',
				'USER_NOT_LOGGED'           => 'Você precisa realizar o login para realizar esta operação!',
			],
			'project' => [
				'PROJECT_POST_EMPTY' => 'Você deve informar os dados do projeto!',
				'REGISTER'           => 'Ocorreu algum erro ao cadastrar o projeto!',
				'PROJECT_ID'         => 'Projeto não informado!',
				'EDIT'               => 'Ocorreu algum erro ao editar o projeto!',
				'DELETE'             => 'Ocorreu algum erro ao excluir o projeto!',
				'PROJECT_NOT_FOUND'  => 'Projeto não encontrado!',
			],
			'task' => [
				'TASK_POST_EMPTY' 		 => 'Você deve informar os dados da tarefa!',
				'REGISTER'        		 => 'Ocorreu algum erro ao cadastrar a tarefa!',
				'TASK_ID'         		 => 'Tarefa não informada!',
				'EDIT'            		 => 'Ocorreu algum erro ao editar a tarefa!',
				'DELETE'          		 => 'Ocorreu algum erro ao excluir a tarefa!',
				'TASK_NOT_FOUND'         => 'Tarefa não encontrada!',
				'TASK_NOT_ALLOWED_EDIT'  => 'Apenas o criador da tarefa pode edita-la!',
				'USER_UNRELATED_TO_TASK' => 'Usuário não relacionado a tarefa!',
				'TASK_COMPLETE'          => 'Ocorreu algum erro ao concluir a tarefa!',
			],
			'contacts' => [
				'REGISTER'         => 'Ocorreu algum erro ao cadastrar o contato!',
				'NOT_FOUND'        => 'Contato não encontrado!',
				'DELETE'           => 'Ocorreu algum erro ao excluir o contato!',
				'WAITING_APPROVAL' => 'Ja existe uma solicitação de amizade para este contato!',
				'PARAMETERS_WRONG' => 'Os parametros passados para função estão incorretos!', 
				'ACCEPT_CONTACT'   => 'Ocorreu algum erro ao aceitar o contato!',
				'REJECT_CONTACT'   => 'Ocorreu algum erro ao rejeitar o contato!',
				'CURRENT_USER'     => 'Você não pode solocitar amizade do próprio usuário!',
				'EXISTING_CONTACT' => 'O usuário informado ja é seu amigo!',
				'LOAD_CONTACTS'    => 'Ocorreu algum erro ao carregar os contatos!',
				'CONTACTS_ID'      => 'Contato não informado!',
			],
			'file' => [
				'ERROR_UPLOAD_FILE'   => 'Ocorreu algum erro ao fazer o upload do arquivo!',
				'EXCEEDED_MAX_SIZE'   => 'Você inseriu um arquivo muito grande, o tamanho máximo de upload é de 5MB!',
				'ERROR_VALIDATE_FILE' => 'Ocorreu algum erro ao validar o arquivo!',
				'ERROR_DOWNLOAD'      => 'Ocorreu algum erro ao fazer o download do arquivo!',
			],
		],
		'success' => [
			'user'=> [
				'LOGOUT'            => 'Logout efetuado com sucesso!',
				'EDIT'              => 'Usuário editado com sucesso!',
				'PASSWORD_RECOVERY' => 'A nova senha foi enviada para o seu e-mail!',
				'REGISTER'          => 'Usuário cadastrado com sucesso!',
			],
			'project' => [
				'REGISTER' => 'Projeto cadastrado com sucesso!',
				'EDIT'     => 'Projeto editado com sucesso!',
				'DELETE'   => 'Projeto excluido com sucesso!',
			],
			'task' => [
				'REGISTER'      => 'Tarefa cadastrada com sucesso!',
				'EDIT'          => 'Tarefa editada com sucesso!',
				'DELETE'        => 'Tarefa excluida com sucesso!',
				'TASK_COMPLETE' => 'Tarefa concluida com sucesso!',
			],
			'contacts' => [
				'REGISTER'         => 'Contato adicionado com sucesso!',
				'WAITING_APPROVAL' => 'Solicitação enviada com sucesso!',
				'DELETE'           => 'Contato excluido com sucesso!',
				'ACCEPT_CONTACT'   => 'Parabéns vocês agora são amigos!',
			],
		],
	];
}

?>