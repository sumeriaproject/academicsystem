<?php

      $pdf->AddPage();

      $y = $pdf->GetY();
      $pdf->Image($imageFolder.'/cerural.jpg',10,10,25,25);
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
      $a = 0;
      while(isset($areas[$a][0])){

    if($pdf->GetY()>230){
      $pdf->AddPage();
    }

    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(165,6,"AREA:".$areas[$a]['AREA'],1);
    $pdf->Cell(15,6,"(D)",1,0,'C');
    $pdf->Cell(15,6,"(V)",1,0,'C');
    $pdf->Ln();

    $c=0;
    while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])){

      $competencia = utf8_decode($competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']);

      $x = $pdf->GetX(); //x inicial
      $y = $pdf->GetY(); //y inicial

      $pdf->SetFont('Arial','',8);
      $pdf->MultiCell(165,4,"Competencia ".$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR'].": ".$competencia,1);
      $alto = ($pdf->GetY())-$y;

      $pdf->SetXY($x+165,$y);
      $pdf->Cell(15,$alto,@$notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"],1,0,'C');

      $nota_final = isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"]) ? $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"] : 0;

      if(($nota_final*1) < 3 ) {
        $nota_final = "";
      }

      $pdf->Cell(15,$alto,$nota_final,1,0,'C');
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

