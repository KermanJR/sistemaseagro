<link href="./estilos/style.css" rel="stylesheet">
<?php
require_once ABSPATH.'model/programacao.php';
require_once ABSPATH.'model/participante.php';

$programacao = new Programacao();
$programacoes = $programacao->getProgramacao(2);
date_default_timezone_set ('America/Cuiaba');
$hojeDia = date("Y-m-d");
?>
<div class="container col-md-5" >
  <?php
    if (!empty($_SESSION['type'])) {
  ?>
  <div class="alert alert-<?php echo $_SESSION['type']; ?> alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php
      if(!empty($_SESSION['message']))
        echo $_SESSION['message'].'<br>';
    ?>
  </div>
  <?php
    unset($_SESSION['type']);
    unset($_SESSION['message']);
  }
  ?>
</div>

<h1 style="text-align: center; padding: 40px 0">Próximos eventos</h1>

<?php
$i = 0;
while(isset($programacoes[$i])){
  $palestrantes = $programacao->getPalestrantes($programacoes[$i]->id_atividade);
?>

<div class="bg-white">
  <div class="row align-items-center">
    <div class="col-11 programacao">

      <div class="programacao-item">
        <h4 class="p-1" style="text-align: center;background: #009c2f; border-radius:5px;padding:10px 0;color: white"><?php echo $programacoes[$i]->nomea;?></h4>
  
        
    <?php
    $inscritos = $programacao->numeroInscritos($programacoes[$i]->id_data_atividade);
    $vagas = $programacoes[$i]->limite-$inscritos[0]->total;
     if($hojeDia<=$programacoes[$i]->data){
      if(!empty($_SESSION['participante'])){
        $participante = unserialize($_SESSION['participante']);
        if(empty($programacao->verificaAtividade($programacoes[$i]->id_data_atividade,$participante->getIdParticipante()))){
            if($vagas>0){
    ?>
    <div class="justify-content-center ">
      <form action="?page=programacao_save" method="post">
    		<div class="row">
    			<div class="col-md-12">
            <input type="hidden" class="form-control" name="atividade" value="<?php echo $programacoes[$i]->id_data_atividade;?>">
            <input type="hidden" class="form-control" name="limite" value="<?php echo $programacoes[$i]->limite;?>">
    			<center><button type="submit" class="btn btn-success m-2">Participar</button></center>
    			</div>
    		</div>
    	</form>
    </div>
    <?php
      }else{
    ?>
        <div class="justify-content-center ">
        <p class="text-center m-2">Vagas preenchidas</p>
     </div>
    <?php
      }
      }else{
    ?>
    <div class="justify-content-center ">
      <form action="?page=programacao_save" method="post">
    		<div class="row">
    			<div class="col-md-12">
            <input type="hidden" class="form-control" name="desfazer" value="<?php echo $programacoes[$i]->id_data_atividade;?>">
    			<center>	<button type="submit" class="btn btn-secondary m-2">Cancelar Participação</button></center>
    			</div>
    		</div>
    	</form>
    </div>
    <?php
      }
    }else{
     ?>

     <div class="justify-content-center ">
        <p class="text-center m-2">Faça a inscrição para participar</p>
     </div>

     <?php
     }
   }
      ?>
        
        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="fas fa-calendar-alt" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>

          <div style="flex: 10">
            <h6 class="font-weight-bold"><?php echo "Data: ".date("d/m", strtotime($programacoes[$i]->data))." Hora: ".date("H:i", strtotime($programacoes[$i]->hora))." hs";?></h6>
          </div>
        </div><!--icon-item-->


        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="fas fa-thumbtack" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>
          <div style="flex: 10">
            <h6>Local: <?php echo $programacoes[$i]->nomes;?><br>Vagas: <?php echo $vagas;?></h6>
          </div>
        </div><!--icon-item-->

        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="far fa-user" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>

          <div style="flex: 10">
          <h5><?php if(count($palestrantes)>1){echo "Palestrantes: ";}else{echo "Palestrante: ";} ?></h5>
          </div>
        </div><!--icon-item-->
    
    
        <?php
          foreach($palestrantes as $indice => $palestrante){
        ?>

        
        <h6 class="font-italic"><?php echo $palestrante->nome; if(isset($palestrante->instituicao)){echo " (".$palestrante->instituicao.")";}?></h6>
        <?php
          }
        ?>
        <div class="row align-items-center" >
         <?php
            $total = count($palestrantes);
            foreach($palestrantes as $indice => $palestrante){
                if($total==1){
          ?>
          <img class="rounded-circle" style="width:40%; height: 40%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
          <?php
            }else if($total==2){
            ?>
            <img class="rounded-circle" style="width:37%; height: 37%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
          <?php
            }else{
            ?>
            <img class="rounded-circle" style="width:33%; height: 33%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
            <?php
            }
            }
          ?>
        </div>
      </div><!--programacao-item-->
        <?php 
            $i++;
            if(isset($programacoes[$i])){
        ?>
      <div class="programacao-item">
        <h4 class="p-1" style="text-align: center;background:#009c2f; border-radius:5px;padding:10px 0;color: white"><?php echo $programacoes[$i]->nomea;?></h4>
        
   <?php
    $palestrantes = $programacao->getPalestrantes($programacoes[$i]->id_atividade);
    $inscritos = $programacao->numeroInscritos($programacoes[$i]->id_data_atividade);
    $vagas = $programacoes[$i]->limite-$inscritos[0]->total;
     if($hojeDia<=$programacoes[$i]->data){
      if(!empty($_SESSION['participante'])){
        $participante = unserialize($_SESSION['participante']);
        if(empty($programacao->verificaAtividade($programacoes[$i]->id_data_atividade,$participante->getIdParticipante()))){
            if($vagas>0){
    ?>
    <div class="justify-content-center ">
      <form action="?page=programacao_save" method="post">
    		<div class="row">
    			<div class="col-md-12">
            <input type="hidden" class="form-control" name="atividade" value="<?php echo $programacoes[$i]->id_data_atividade;?>">
            <input type="hidden" class="form-control" name="limite" value="<?php echo $programacoes[$i]->limite;?>">
    			<center><button type="submit" class="btn btn-success m-2">Participar</button></center>
    			</div>
    		</div>
    	</form>
    </div>
    <?php
      }else{
    ?>
        <div class="justify-content-center ">
        <p class="text-center m-2">Vagas preenchidas</p>
     </div>
    <?php
      }
      }else{
    ?>
    <div class="justify-content-center ">
      <form action="?page=programacao_save" method="post">
    		<div class="row">
    			<div class="col-md-12">
            <input type="hidden" class="form-control" name="desfazer" value="<?php echo $programacoes[$i]->id_data_atividade;?>">
    			<center>	<button type="submit" class="btn btn-secondary m-2">Cancelar Participação</button></center>
    			</div>
    		</div>
    	</form>
    </div>
    <?php
      }
    }else{
     ?>

     <div class="justify-content-center ">
        <p class="text-center m-2">Faça a inscrição para participar</p>
     </div>

     <?php
     }
   }
      ?>
        
        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="fas fa-calendar-alt" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>

          <div style="flex: 10">
            <h6 class="font-weight-bold"><?php echo "Data: ".date("d/m", strtotime($programacoes[$i]->data))." Hora: ".date("H:i", strtotime($programacoes[$i]->hora))." hs";?></h6>
          </div>
        </div><!--icon-item-->


        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="fas fa-thumbtack" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>
          <div style="flex: 10">
            <h6>Local: <?php echo $programacoes[$i]->nomes;?><br>Vagas: <?php echo $vagas;?></h6>
          </div>
        </div><!--icon-item-->

        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="far fa-user" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>

          <div style="flex: 10">
          <h5><?php if(count($palestrantes)>1){echo "Palestrantes: ";}else{echo "Palestrante: ";} ?></h5>
          </div>
        </div><!--icon-item-->
    
    
         <?php
          foreach($palestrantes as $indice => $palestrante){
        ?>

        
        <h6 class="font-italic"><?php echo $palestrante->nome; if(isset($palestrante->instituicao)){echo " (".$palestrante->instituicao.")";}?></h6>
        <?php
          }
        ?>
         <div class="row align-items-center" >
         <?php
            $total = count($palestrantes);
            foreach($palestrantes as $indice => $palestrante){
                if($total==1){
          ?>
        <img class="rounded-circle" style="width:40%; height: 40%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
          <?php
            }else if($total==2){
            ?>
            <img class="rounded-circle" style="width:37%; height: 37%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
          <?php
            }else{
            ?>
            <img class="rounded-circle" style="width:33%; height: 33%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
            <?php
            }
            }
          ?>
        </div>
      </div><!--programacao-item-->
    <?php
            }
            $i++;
            if(isset($programacoes[$i])){
    ?>
      <div class="programacao-item">
        <h4 class="p-1" style="text-align: center;background: #009c2f; border-radius:5px;padding:10px 0;color: white"><?php echo $programacoes[$i]->nomea;?></h4>
            <?php
    
    $palestrantes = $programacao->getPalestrantes($programacoes[$i]->id_atividade);    
    $inscritos = $programacao->numeroInscritos($programacoes[$i]->id_data_atividade);
    $vagas = $programacoes[$i]->limite-$inscritos[0]->total;
     if($hojeDia<=$programacoes[$i]->data){
      if(!empty($_SESSION['participante'])){
        $participante = unserialize($_SESSION['participante']);
        if(empty($programacao->verificaAtividade($programacoes[$i]->id_data_atividade,$participante->getIdParticipante()))){
            if($vagas>0){
    ?>
    <div class="justify-content-center ">
      <form action="?page=programacao_save" method="post">
    		<div class="row">
    			<div class="col-md-12">
            <input type="hidden" class="form-control" name="atividade" value="<?php echo $programacoes[$i]->id_data_atividade;?>">
            <input type="hidden" class="form-control" name="limite" value="<?php echo $programacoes[$i]->limite;?>">
    			<center><button type="submit" class="btn btn-success m-2">Participar</button></center>
    			</div>
    		</div>
    	</form>
    </div>
    <?php
      }else{
    ?>
        <div class="justify-content-center ">
        <p class="text-center m-2">Vagas preenchidas</p>
     </div>
    <?php
      }
      }else{
    ?>
    <div class="justify-content-center ">
      <form action="?page=programacao_save" method="post">
    		<div class="row">
    			<div class="col-md-12">
            <input type="hidden" class="form-control" name="desfazer" value="<?php echo $programacoes[$i]->id_data_atividade;?>">
    			<center>	<button type="submit" class="btn btn-secondary m-2">Cancelar Participação</button></center>
    			</div>
    		</div>
    	</form>
    </div>
    <?php
      }
    }else{
     ?>

     <div class="justify-content-center ">
        <p class="text-center m-2">Faça a inscrição para participar</p>
     </div>

     <?php
     }
   }
      ?>
        
        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="fas fa-calendar-alt" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>

          <div style="flex: 10">
            <h6 class="font-weight-bold"><?php echo "Data: ".date("d/m", strtotime($programacoes[$i]->data))." Hora: ".date("H:i", strtotime($programacoes[$i]->hora))." hs";?></h6>
          </div>
        </div><!--icon-item-->


        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="fas fa-thumbtack" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>
          <div style="flex: 10">
            <h6>Local: <?php echo $programacoes[$i]->nomes;?><br>Vagas: <?php echo $vagas;?></h6>
          </div>
        </div><!--icon-item-->

        <div class="icon-item" style="display: flex; width: 100%">
          <div style="flex: 1;align-item: center;display: flex">
            <i class="far fa-user" style="height: 25px; align-self: center;color: #009c2f"></i>
          </div>

          <div style="flex: 10">
          <h5><?php if(count($palestrantes)>1){echo "Palestrantes: ";}else{echo "Palestrante: ";} ?></h5>
          </div>
        </div><!--icon-item-->
    
    
       <?php
          foreach($palestrantes as $indice => $palestrante){
        ?>

        
        <h6 class="font-italic"><?php echo $palestrante->nome; if(isset($palestrante->instituicao)){echo " (".$palestrante->instituicao.")";}?></h6>
        <?php
          }
        ?>
         <div class="row align-items-center">
         <?php
            $total = count($palestrantes);
            foreach($palestrantes as $indice => $palestrante){
                if($total==1){
          ?>
          <img class="rounded-circle" style="width:40%; height: 40%; border-radius: 100%;margin: 0 auto;"  src="<?php echo $palestrante->foto; ?>"><br>
          <?php
            }else if($total==2){
            ?>
            <img class="rounded-circle" style="width:37%; height: 37%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
          <?php
            }else{
            ?>
            <img class="rounded-circle" style="width:33%; height: 33%; border-radius: 100%;margin: 0 auto"  src="<?php echo $palestrante->foto; ?>"><br>
            <?php
            }
            }
            $i++;
          ?>
        </div>
      </div><!--programacao-item-->
    </div><!--programacao-->
    <?php
            }
    ?>

  </div>
</div>
<br>

<?php
}
?>
