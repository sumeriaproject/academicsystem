<?php
    $a=0;
    while(isset($areas[$a][0])):
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box box-color box-bordered">
                <div class="box-content nopadding">
                    <table class="table table-nomargin table-bordered">
                        <thead>
                            <tr class='thefilter'>
                                <th>AREA: <?=$areas[$a]['AREA']?></th>
                                <th class='small-columns' >(D)</th>
                                <th class='small-columns' >(V)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c=0;
                            while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])): ?>
                                <tr>
                                    <td style="padding:5px"><b  style="font-weight: bold;">Competencia <?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR']; ?>: </b><?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']; ?></td>
                                    <td class='small-columns'  style="width:50px; padding:5px">
                                        <?php echo isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"])?$notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"]:''; ?>
                                    </td>
                                     <td class='small-columns'  style="width:50px; padding:5px">
                                        <?php echo isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"])?$notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"]:''; ?>
                                    </td>
                                </tr>
                            <?php
                            $c++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
$a++;
endwhile;
?>