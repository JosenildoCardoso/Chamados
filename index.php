<?php
session_start();
include 'conexao.php';
include 'autentica.php';
$matricula = $_SESSION['matricula'];
$sql = "SELECT id, sla_hr, prb.descricao as problema, sb.descricao as subsetor, str.descricao as setor, descricao_problema, IFNULL(user_ab.nome, '-') as uab, IFNULL(user_at.nome, '-') as uat, IFNULL(user_enc.nome, '-') as uen, IFNULL(user_resp.nome, '-') as uresp, titulo, data_atendimento, IFNULL(DATE_FORMAT(data_encerramento, '%d/%m/%Y %H:%i'), '-') as dtfim,  DATE_FORMAT(data_abertura, '%d/%m/%Y %H:%i')as dtv, IFNULL(DATE_FORMAT(data_atendimento, '%d/%m/%Y %H:%i'), '-') as dtat, data_abertura, st.descricao as std, status FROM chamados ch LEFT JOIN status_chamados st ON st.cod = ch.status LEFT JOIN dados user_ab ON user_ab.matricula = ch.matricula LEFT JOIN dados user_at ON user_at.matricula = ch.operador_atendimento LEFT JOIN dados user_enc ON user_enc.matricula = ch.operador_encerramento LEFT JOIN dados user_resp ON user_resp.matricula = ch.responsavel LEFT JOIN problemas_chamados prb ON prb.cod = ch.tipo_problema LEFT JOIN setor str ON str.cod = ch.setor LEFT JOIN subsetor sb ON sb.cod = ch.subsetor  ORDER BY data_abertura DESC";
$result = $conn->query($sql);


function horasUteisDecorridas($dataInicio, $dataFim = 'now') {
    $inicio = new DateTime($dataInicio);
    $fim = new DateTime($dataFim);
$diffDias = (int)$inicio->diff($fim)->format('%a');

    if($diffDias > 14){

    return $diffDias * 8 * 3600;
}
    $segundosUteis = 0;

    $atual = clone $inicio;


    while ($atual < $fim) {
        // Ignora finais de semana
        if (in_array($atual->format('N'), [6, 7])) {
            $atual->modify('+1 day')->setTime(0, 0, 0);
            continue;
        }

        $inicioDia = (clone $atual)->setTime(8, 0, 0);
        $fimDia = (clone $atual)->setTime(18, 0, 0);

        if ($atual < $inicioDia) {
            $inicioContar = $inicioDia;
        } elseif ($atual > $fimDia) {
            $atual->modify('+1 day')->setTime(0, 0, 0);
            continue;
        } else {
            $inicioContar = clone $atual;
        }

        $fimContar = min($fim, $fimDia);

        if ($inicioContar < $fimContar) {
            $segundosUteis += $fimContar->getTimestamp() - $inicioContar->getTimestamp();
        }

        $atual->modify('+1 day')->setTime(0, 0, 0);
    }
 
    return $segundosUteis;
}

function formatarTempoUteis($segundosUteis) {
    $totalMin = floor($segundosUteis / 60);
    $dias = floor($totalMin / (10 * 60)); // 10h por dia útil
    $minRestantes = $totalMin % (10 * 60);
    $horas = floor($minRestantes / 60);
    $minutos = $minRestantes % 60;
if($dias > 0){
    return sprintf('%dd %02dh %02dm', $dias, $horas, $minutos);
}else{
    return sprintf('%02dh %02dm', $horas, $minutos);
}
}
function slaVencido($dataAbertura, $slaHoras = 12) {
    $segundosUteis = horasUteisDecorridas($dataAbertura);
    $horas = $segundosUteis / 3600;
    return $horas >= $slaHoras;
}

// Exemplo de uso

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">
    <?php include("header.php"); ?>
<div class="container mt-5">
    <h2>Meus Chamados</h2>
    <a href="abrir_chamado.php" class="btn btn-success mb-3">Abrir Novo Chamado</a>
    <table class="table table-bordered table-striped">
      
            <?php while($row = $result->fetch_assoc()) {
                 $tmpsla = 4;
                $id = $row['id'];
                $titulo = $row['titulo'];
                $dtv = $row['dtv'];
                $std = $row['std'];
                $dataAt = $row['dtat'];
                $desc = $row['descricao_problema'];
                $datafim = $row['dtfim'];
                $sts = $row['status'];
                $uat = $row['uat'];
                $uen = $row['uen'];
                $st = $row['setor'];
                $sb = $row['subsetor'];
                $uresp = $row['uresp'];
                $uab = $row['uab'];
                $slahr = $row['sla_hr'];
               $problema = $row['problema'];
                if($sts == 0){
                $dataChamado = $row['data_abertura'];
                $abert = $dtv;
                    if (slaVencido($dataChamado, $tmpsla)) {
                    $stab = "⚠️";
                    } else {
                    $stab = "✅";
                    }
                $atend = formatarTempoUteis(horasUteisDecorridas($dataChamado)) . $stab;


                $fechamento = "-";
                }else if($sts == 1){
                $dataChamado = $row['data_atendimento'];
                $abert = $dtv;
                $atend = $dataAt;

                                if (slaVencido($dataChamado, $tmpsla)) {
                                 $stat= "⚠️";
                                } else {
                                  $stat= "✅";
                               }
                $fechamento = formatarTempoUteis(horasUteisDecorridas($dataChamado)) . $stat;


                }else if($sts == 2){
                $abert = $dtv;
                $atend = $dataAt;
                $fechamento = $datafim;
                }else if($sts == 3){
                $abert = $dtv;
                $atend = $dataAt;
                $fechamento = $datafim;
                }

               



          
                echo " <div class='row bg-secondary mt-3'><div class='col-1 p-1 border-end'>#$id</div><div class='col-9 p-1 border-end'>$titulo</div><div class='col-2 p-1 text-right'>$std</div></div>
                 
                 <div class='row bg-body-secondary p-2'>$desc</div>
                 <div style='font-size: 10px;' class='row bg-secondary-subtle fw-bold' ><div class='col-4 border-end'>Abertura</div><div class='col-4 border-end'>Atendimento</div><div class='col-4'>Encerramento</div></div>
                <div class='row bg-body-secondary border' ><div class='col-4 border-end'>$abert</div><div class='col-4 border-end'>$atend</div><div class='col-4'>$fechamento</div></div>
                <div class='row bg-body-secondary' ><div class='col-4 border-end'>$uab</div><div class='col-4 border-end'>$uat</div><div class='col-4'>$uen</div></div>

                <div style='font-size: 10px;' class='row bg-secondary-subtle' ><div class='col-4 border-end'>Local</div><div class='col-4 border-end'>Problema</div><div class='col-3'>Responsável</div><div class='col-1'>Prazo</div></div>
                <div class='row bg-body-secondary fw-bold border' ><div class='col-4 pt-1 border-end'>$st > $sb</div><div class='col-4 pt-1 border-end'>$problema</div><div class='col-3 pt-1'>$uresp</div><div class='col-1 pt-1'>$slahr Horas</div></div>
                     
               
               ";
                  ?>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
