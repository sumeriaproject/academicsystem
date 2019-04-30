<?php

      $check = $imageFolder.'/star.png';

      $pdf->AddPage();
      $pdf->Image($imageFolder.'/bg-boletin-1.jpg', 0, 0, 215.9, 279.4);

      $y = $pdf->GetY();
      $pdf->Image($imageFolder.'/cerural.png',10,10,30,30);
      $pdf->SetY($y);

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(190,5,"DEPARTAMENTO DEL META - RESTREPO",0,0,'C');
      $pdf->Ln();
      $pdf->Cell(190,5,utf8_decode("SECRETARÍA DE EDUCACIÓN"),0,0,'C');
      $pdf->Ln();
      $pdf->SetFont('Arial','B',13);
      $pdf->Cell(190,5,"CENTRO EDUCATIVO RURAL DE RESTREPO",0,0,'C');
      $pdf->Ln();
      $pdf->SetFont('Arial','I',10);
      $pdf->Cell(190,5,"DANE 150606000393",0,0,'C');
      $pdf->Ln(8);
      $pdf->SetFont('Arial','B',13);
      $pdf->Cell(190,5,utf8_decode("Boletín Académico Institucional ".$this->activeYear." Sede ".$sedeByID['NOMBRE']),0,0,'C');
      $pdf->Ln(8);
      $pdf->SetFont('Arial','B',13);
      $estudiante = $listadoEstudiantes[$e]['APELLIDO'].' '.$listadoEstudiantes[$e]['APELLIDO2'].' '.$listadoEstudiantes[$e]['NOMBRE'].' '.@$estudianteByID[$e]['NOMBRE2'];
      $pdf->Cell(40,8,"Estudiante ".utf8_decode($estudiante)."    Grado ".$cursoByID['NOMBRE']);

      $pdf->Ln(8);
      $pdf->SetFont('Arial','B',9);
      

      $colorCell = [];
      $colorCell[] = [255,145,53];
      $colorCell[] = [31,193,195];
      $colorCell[] = [255,73,156];
      $colorCell[] = [61,161,63];
      $colorCell[] = [255,189,46];
      $colorCell[] = [0,150,136];
      $colorCell[] = [156,39,176];

      $a = 0;
      while(isset($areas[$a][0])){
          if($pdf->GetY()>230){
            $pdf->AddPage();
          }
          
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor($colorCell[$a][0],$colorCell[$a][1],$colorCell[$a][2]);

          $pdf->Cell(150,6,"AREA: ".$areas[$a]['AREA'],1,0,'L',1);
          $pdf->Cell(15,6,"S",1,0,'C',1);
          $pdf->Cell(15,6,"CS",1,0,'C',1);
          $pdf->Cell(15,6,"AV",1,0,'C',1);
          $pdf->Ln();

          $c=0;
          while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0]))
          {
              $competencia = utf8_decode($competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']);

              $x = $pdf->GetX(); //x inicial
              $y = $pdf->GetY(); //y inicial

              $pdf->SetFillColor(255,255,255);


              $pdf->SetFont('Arial','B',9);
              $pdf->MultiCell(150,5,"Competencia ".$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR'].": ".$competencia,1,'J',0);
              $alto = ($pdf->GetY()) - $y;

              $pdf->SetXY($x + 150,$y);
              
              if(isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"])) {
                  $nota_final = $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"];
              }else {
                  $nota_final = '';
              }

              //$pdf->Image($check,15,$alto);

              $pdf->Cell(15,$alto,($nota_final=='S')?$pdf->Image($check, $pdf->GetX()+5, $pdf->GetY(),4.5):'',1,0,'C');
              $pdf->Cell(15,$alto,($nota_final=='CS')?$pdf->Image($check, $pdf->GetX()+5, $pdf->GetY(),4.5):'',1,0,'C');
              $pdf->Cell(15,$alto,($nota_final=='AV')?$pdf->Image($check, $pdf->GetX()+5, $pdf->GetY(),4.5):'',1,0,'C');


              $pdf->Ln();
              $c++;
          }
          $pdf->Ln(3);
          $a++;
     }

    $pdf->Ln(0);
    $pdf->Cell(40,3,"OBSERVACIONES:",0,0,'L');
    $pdf->Ln(5);
    $pdf->Cell(295,2,utf8_decode("_____________________________"),0,0,'C');
    $pdf->Ln(4);
    $pdf->Cell(295,3,"FIRMA DOCENTE",0,0,'C');

