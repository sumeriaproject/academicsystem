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
                                <th class='small-columns' >S</th>
                                <th class='small-columns' >CS</th>
                                <th class='small-columns' >AV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c=0;
                            while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])):

                                if(isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"])) {
                                    $nota = $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"];
                                }else {
                                    $nota = '';
                                }

                            ?>
                                <tr>
                                    <td style="padding:5px"><b  style="font-weight: bold;">Competencia <?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR']; ?>: </b><?php echo $competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']; ?></td>
                                    <td class='small-columns'>
                                        <?php echo ($nota=='S')?'<i class="icon-star"></i>':'' ?>
                                    </td>
                                    <td class='small-columns'>
                                        <?php echo ($nota=='CS')?'<i class="icon-star"></i>':'' ?>
                                    </td>
                                    <td class='small-columns' >
                                        <?php echo ($nota=='AV')?'<i class="icon-star"></i>':'' ?>
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