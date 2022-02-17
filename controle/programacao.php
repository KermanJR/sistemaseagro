<link href="./estilos/style.css" rel="stylesheet">
<?php
require_once ABSPATH.'model/programacao.php';
require_once ABSPATH.'model/participante.php';

if(!empty($_POST)){
	if(!empty($_POST['atividade'])){
		add();
	}else if(!empty($_POST['desfazer'])){
		remover();
	}
}


function add() {
	$programacao = new Programacao();
  $participante = unserialize($_SESSION['participante']);
    $inscritos = $programacao->numeroInscritos($_POST['atividade']);
    if($_POST['limite']-$inscritos[0]->total > 0){
        $programacao->salvarParticipacao($_POST['atividade'],$participante->getIdParticipante());
    	$_SESSION['message'] = "Atividade adicionada com sucesso";
    	$_SESSION['type'] = 'success';
    }else{
        $_SESSION['message'] = "Limite excedido";
    	$_SESSION['type'] = 'danger';
    }
	
  header('Location: '.BASEURL.'?page=programacao');exit;
}

function remover(){
	$programacao = new Programacao();
  $participante = unserialize($_SESSION['participante']);
	$programacao->deletarParticipacao($_POST['desfazer'],$participante->getIdParticipante());
	$_SESSION['message'] = "Atividade removida com sucesso";
	$_SESSION['type'] = 'success';
  header('Location: '.BASEURL.'?page=programacao');exit;
}

?>
