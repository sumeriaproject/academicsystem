<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("class/controlAcceso.class.php");
include_once("class/Array.class.php");

class Viewpromocion
{
    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $enlace;
    var $context;
    
    function __construct()
    {
        $this->context                = Context::singleton();
        $this->session                = Sesion::singleton();
        $this->resource               = $this->context->fabricaConexiones->getRecursoDB("aplicativo");
        $this->enlace                 = $this->context->getVariable("host") . $this->context->getVariable("site") . "?" . $this->context->getVariable("enlace");
        $this->sessionId              = $this->session->getValue('idUsuario');
        $this->controlAcceso          = new controlAcceso();
        $this->controlAcceso->usuario = $this->sessionId;
        $this->controlAcceso->rol     = $this->session->getValue('rol');
        $this->sorter                 = orderArray::singleton();
    }
    
    public function setRuta($unaRuta)
    {
        $this->ruta = $unaRuta;
    }
    
    public function setLenguaje($lenguaje)
    {
        $this->lenguaje = $lenguaje;
    }
    
    public function setFormulario($formulario)
    {
        $this->formulario = $formulario;
    }
    
    function setSql($a)
    {
        $this->sql = $a;
    }
    
    function setFuncion($funcion)
    {
        $this->funcion = $funcion;
    }
    
    function html()
    {
        
        $this->ruta = $this->context->getVariable("rutaBloque");
        $option     = isset($_REQUEST['option']) ? $_REQUEST['option'] : "";
        
        switch ($option) {
            case "panel":
                //$this->showMainReport();
                //$this->conflictStudents();
                break;
            default:
                $this->loadSIMAT();
                $this->generarCierreEstudiantes(2015);
                //$this->conflictStudents();
                break;
        }
        
    }
    
    function loadSIMAT()
    {
        $cadenaSql   = $this->sql->get("countSIMAT", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        echo "<br/>Número actual de Estudiantes en SIMAT: " . $estudiantes[0][0];
        
        $cadenaSql   = $this->sql->get("countEstudiantes", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        echo "<br/>Número actual de Estudiantes en el sistema: " . $estudiantes[0][0];
        
        $cadenaSql   = $this->sql->get("conteoEstudiantesSinSistema", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        echo "<br/>Número actual de Estudiantes de SIMAT fuera del sistema: " . $estudiantes[0][0];
        
        $cadenaSql   = $this->sql->get("conteoEstudiantesSinSIMAT", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        echo "<br/>Número actual de Estudiantes en el sistema fuera del SIMAT: " . $estudiantes[0][0];
        
    }
    
    /**
     * Función que completa la tabla del cierre de estudiantes donde se registra en que estado
     * quedo el estudiante al cierre del año.
     * Pendiente de trasladar al Controlador de Cierre Total y cambio de Periodo
     */
    function generarCierreEstudiantes($anio)
    {
        
        //El Orden de cierre debe ser el que esta no debe ser alterado
        
        //1.Estudiantes en estado Desertor o Retirado o cualquier estado diferente de 1 = Activo
        
        $cadenaSql   = $this->sql->get("estudiantesInactivos", $anio);
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        foreach ($estudiantes as $estudiante => $data) {
            $data = array(
                "anio" => $anio,
                "id_estudiante" => $data["ID"],
                "id_curso" => $data["CURSO"],
                "estado_academico" => $data["ESTADO"],
                "observacion" => "ESTADO NO ACTIVO",
                "adjunto" => "",
                "estado_cierre" => "INACTIVO"
            );
            //$this->registrarHistoricoEstudiante($data);
        }
        
        //2.Estudiantes que no se encuentran en el SIMAT por NUI
        
        $cadenaSql   = $this->sql->get("estudiantesSinSIMATPorNUI", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        foreach ($estudiantes as $estudiante => $data) {
            $data = array(
                "anio" => $anio,
                "id_estudiante" => $data["ID"],
                "id_curso" => $data["CURSO"],
                "estado_academico" => $data["ESTADO"],
                "observacion" => "ACTIVO EN EL SISTEMA PERO NO ESTA EN SIMAT",
                "adjunto" => "",
                "estado_cierre" => "PENDIENTE"
            );
            
            //$this->registrarHistoricoEstudiante($data);  
        }
        
        
        //3. Consultar Estudiantes con Notas al Dia
        //Numero de Notas Cerradas debe ser igual Al numero de Areas del GRado Actual
        
        $cadenaSql   = $this->sql->get("estudiantesNotasPendientes", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        foreach ($estudiantes as $estudiante => $data) {
            $data = array(
                "anio" => $anio,
                "id_estudiante" => $data["ID"],
                "id_curso" => $data["CURSO"],
                "estado_academico" => $data["ESTADO"],
                "observacion" => ($data["NUM_AREAS"] - $data["NUM_NOTAS"]) . " AREA(S) PENDIENTE(S)",
                "adjunto" => "",
                "estado_cierre" => "PENDIENTE"
            );
            
            //$this->registrarHistoricoEstudiante($data);  
        }
        
        //Estudiantes con el mismo curso en el sistema y en el simat
        //Nota: Esto se hace para el año 2016 debido a q se actualizaron en primera instancia los
        //cursos en el SIMAT
        //4. Estudiantes cuyo curso es igual al SIMAT
        $cadenaSql   = $this->sql->get("estudiantesGradoIgual", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        foreach ($estudiantes as $estudiante => $data) {
            $data = array(
                "anio" => $anio,
                "id_estudiante" => $data["ID"],
                "id_curso" => $data["CURSO"],
                "estado_academico" => $data["ESTADO"],
                "observacion" => " GRADO SIN ACTUALIZAR EN SIMAT. ACTUAL:" . $data["GRADO"],
                "adjunto" => "",
                "estado_cierre" => "PENDIENTE"
            );
            
            //$this->registrarHistoricoEstudiante($data);  
        }
        
        //5. Estudiantes notas al dia 
        $cadenaSql   = $this->sql->get("estudiantesNotasCompletas", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        foreach ($estudiantes as $estudiante => $data) {
            $data = array(
                "anio" => $anio,
                "id_estudiante" => $data["ID"],
                "id_curso" => $data["CURSO"],
                "estado_academico" => $data["ESTADO"],
                "observacion" => "NOTAS COMPLETAS",
                "adjunto" => "",
                "estado_cierre" => "PROMOVIDO"
            );
            
            //$this->registrarHistoricoEstudiante($data);  
        }
        
        //6. Al finalizar el numero de estudiantes debe corresponder con el numero de estudiantes cerrados
        //para proceder a enviar las notas de los estudiantes PROMOVIDOS e INACTIVOS a los respectivos historicos  
        
        $cadenaSql   = $this->sql->get("estudiantesParaHistoricos", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        foreach ($estudiantes as $estudiante => $data) {
            //$this->enviarNotasAHistoricos($data["ID"],$anio); 
        }
        
        //7. Solo los que estan en estado PROMOVIDO se les cambia el curso
        //y la sede si es el caso para el 2016 se realiza de acuerdo al SIMAT
        //pero la idea es que este cambio se realice desde el sistema
        
        $cadenaSql   = $this->sql->get("estudiantesPromovidos", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        /*  SELECT
        uc.id_usuario,
        uc.id_curso,
        c.id_curso
        FROM notas_usuario_curso uc
        INNER JOIN notas_usuario u ON uc.id_usuario = u.id_usuario
        INNER JOIN notas_csv_simat_tmp cst ON cst.NUI = u.nui
        INNER JOIN notas_curso c ON c.id_grado = cst.grado AND c.id_sede = cst.sede
        INNER JOIN notas_estudiante_cierre ec ON uc.id_usuario = ec.id_estudiante
        WHERE ec.estado_cierre = 'PROMOVIDO'
        */
        /*UPDATE notas_usuario_curso uc
        INNER JOIN notas_usuario u ON uc.id_usuario = u.id_usuario
        INNER JOIN notas_csv_simat_tmp cst ON cst.NUI = u.nui
        INNER JOIN notas_curso c ON c.id_grado = cst.grado AND c.id_sede = cst.sede
        INNER JOIN notas_estudiante_cierre ec ON uc.id_usuario = ec.id_estudiante
        SET uc.id_curso = c.id_curso
        WHERE ec.estado_cierre = 'PROMOVIDO'*/
        
        //8. Codificar Estudiantes
        
        $cadenaSql   = $this->sql->get("estudiantesSinSistema", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        
        foreach ($estudiantes as $estudiante => $data) {
            
            $cadena_sql = $this->sql->get("lastIdByCourse", $data['SEDE']);
            $result     = $this->resource->execute($cadena_sql, "busqueda");
            $lastID     = $result[0][0];
            
            $cadena_sql = $this->sql->get("basicUserByID", $lastID);
            $exist      = $this->resource->execute($cadena_sql, "busqueda");
            
            while (is_array($exist)) {
                $lastID++;
                $cadena_sql = $this->sql->get("basicUserByID", $lastID);
                $exist      = $this->resource->execute($cadena_sql, "busqueda");
            }
            
            $data['LASTID'] = $lastID;
            
            echo "<hr/><br/>*" . $cadenaSql = $this->sql->get("insertarEstudiante", $data);
            $estudiantes = $this->resource->execute($cadenaSql, "");
            echo "<br/>*" . $cadenaSql = $this->sql->get("insertarCurso", $data);
            $estudiantes = $this->resource->execute($cadenaSql, "");
            echo "<br/>*" . $cadenaSql = $this->sql->get("insertarSubsistema", $data);
            $estudiantes = $this->resource->execute($cadenaSql, "");
            
        }
        
        //8. Esto es lo unico que deberia estar aca de aqui para arriba deberia estar en el controlador 
        $cadenaSql   = $this->sql->get("resultadoPendientes", $data);
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        echo "<br/><br/><h2>ESTUDIANTES PENDIENTES</h2><br/><table>";
        foreach ($estudiantes as $estudiante => $data) {
            echo "<tr><td>" . $data[0] . " </td><td> " . $data[1] . " </td><td>" . $data[2] . "</td><td>" . $data[3] . "</td><td>" . $data[4] . "</td><td>" . $data[5] . "</td><td>" . $data[6] . "</td></tr>";
        }
        echo "</table>";
    }
    
    private function registrarHistoricoEstudiante($data)
    {
        echo "<br/>" . $cadenaSql = $this->sql->get("registrarHistoricoEstudiante", $data);
        $result = $this->resource->execute($cadenaSql, "");
    }
    
    
    private function enviarNotasAHistoricos($id_estudiante, $anio)
    {
        
        $parametros = array(
            "estudiante" => $id_estudiante,
            "anio" => $anio
        );
        //Notas Parcial
        //Insertar historico
        echo "<hr/><br/>" . $cadenaSql = $this->sql->get("insertarHistoricoParcial", $parametros);
        $result = $this->resource->execute($cadenaSql, "");
        
        //Eliminar actual
        $cadenaSql = $this->sql->get("eliminarActualParcial", $parametros);
        $result    = $this->resource->execute($cadenaSql, "");
        
        //Notas Final
        //Insertar historico
        $cadenaSql = $this->sql->get("insertarHistoricoFinal", $parametros);
        $result    = $this->resource->execute($cadenaSql, "");
        
        //Eliminar actual
        $cadenaSql = $this->sql->get("eliminarActualFinal", $parametros);
        $result    = $this->resource->execute($cadenaSql, "");
        
        
        //Notas Cerrada
        //Insertar historico
        $cadenaSql = $this->sql->get("insertarHistoricoCerrada", $parametros);
        $result    = $this->resource->execute($cadenaSql, "");
        
        //Eliminar actual
        $cadenaSql = $this->sql->get("eliminarActualCerrada", $parametros);
        $result    = $this->resource->execute($cadenaSql, "");
        
    }
    
    /**
     * Estudiantes con notas de competencias diferentes al grado actual
     */
    function conflictStudents()
    {
        
        //traer listado de todos los estudiantes con el respectivo grado
        $cadenaSql   = $this->sql->get("estudiantes", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        $e           = 0;
        while (isset($estudiantes[$e][0])) {
            
            $cadenaSql    = $this->sql->get("notasFinalesFueradelGrado", $estudiantes[$e]);
            $notasFinales = $this->resource->execute($cadenaSql, "busqueda");
            
            if (is_array($notasFinales)) {
                
                $n = 0;
                while (isset($notasFinales[$n][0])) {
                    $cadenaSql = $this->sql->get("inactivarNotaFinal", $notasFinales[$n]['IDNOTA']);
                    // $result = $this->resource->execute($cadenaSql,"");
                    $n++;
                }
                echo "<br>" . $e . ":" . $estudiantes[$e]['ID'] . " idgrado:" . $estudiantes[$e]['IDGRADO'];
                echo " conflictos:" . count($notasFinales);
                
            }
            $e++;
        }
        // var_dump($estudiantes);
        
    }
    
    function showMainReport()
    {
        
        //Consulto el listado de sedes y grados permitidos para el usuario
        $courses = $this->controlAcceso->getAccesoCompleto();
        $sedes   = $this->controlAcceso->getSedes();
        
        //Consulto el listado de estudiantes organizados por curso
        $cadenaSql   = $this->sql->get("estudiantes", "");
        $estudiantes = $this->resource->execute($cadenaSql, "busqueda");
        $estudiantes = $this->sorter->orderMultiKeyBy($estudiantes, "IDCURSO");
        
        //Consulto el listado de competencias organizados por grado
        $cadenaSql    = $this->sql->get("competencias", "");
        $competencias = $this->resource->execute($cadenaSql, "busqueda");
        $competencias = $this->sorter->orderMultiKeyBy($competencias, "IDGRADO");
        
        //Traer listado de cursos sin cerrar
        $cadenaSql      = $this->sql->get("cursosCerrados", "2015");
        $cursosCerrados = $this->resource->execute($cadenaSql, "busqueda");
        $cursosCerrados = $this->sorter->orderMultiKeyBy($cursosCerrados, "IDCURSO");
        
        //Se asume que un curso esta asociado a un unico grado y una unica sede
        // Listado de notas finales por curso y estudiante
        
        foreach ($courses as $idsede => $course) {
            
            foreach ($course as $idcourse => $value) {
                
                $numeroDeCompetencias = count($competencias[$value["GRADO_ID"]]);
                
                $estudiantesPendientes = 0;
                $estudiantesAlDia      = 0;
                
                $cadenaSql = $this->sql->get("notasFinalesPorCurso", $idcourse);
                $notas     = $this->resource->execute($cadenaSql, "busqueda");
                
                if (is_array($notas)) {
                    
                    $notas = $this->sorter->orderMultiTwoKeyBy($notas, "IDCURSO", "ESTUDIANTE");
                    
                    foreach ($notas[$idcourse] as $idestudiante => $notasestudiante) {
                        
                        $numeroDeNotas = count($notasestudiante);
                        
                        if ($numeroDeNotas < $numeroDeCompetencias) {
                            $courses[$idsede][$idcourse]["ESTUDIANTES_PENDIENTES"][] = $idestudiante;
                        } else {
                            $courses[$idsede][$idcourse]["ESTUDIANTES_ALDIA"][] = $idestudiante;
                        }
                    }
                    
                }
                
                $formSaraData = "action=promocion";
                $formSaraData .= "&bloque=promocion";
                $formSaraData .= "&bloqueGrupo=instrumentos";
                $formSaraData .= "&option=imprimirBoletines";
                $formSaraData .= "&curso=" . $idcourse;
                $formSaraData .= "&sede=" . $idsede;
                $formSaraData .= "&grado=" . $courses[$idsede][$idcourse]["GRADO_ID"];
                $courses[$idsede][$idcourse]["LINK_BOLETIN"] = $this->context->fabricaConexiones->crypto->codificar_url($formSaraData, $this->enlace);
                
                $formSaraData = "action=controlEvaluacion";
                $formSaraData .= "&bloque=controlEvaluacion";
                $formSaraData .= "&bloqueGrupo=instrumentos";
                $formSaraData .= "&option=showPDFCompetencias";
                $formSaraData .= "&curso=" . $idcourse;
                $formSaraData .= "&sede=" . $idsede;
                $formSaraData .= "&grado=" . $courses[$idsede][$idcourse]["GRADO_ID"];
                $courses[$idsede][$idcourse]["LINK_NOTAS"] = $this->context->fabricaConexiones->crypto->codificar_url($formSaraData, $this->enlace);
                
                if (!isset($cursosCerrados[$idcourse])) {
                    $formSaraData = "action=promocion";
                    $formSaraData .= "&bloque=promocion";
                    $formSaraData .= "&bloqueGrupo=instrumentos";
                    $formSaraData .= "&option=cierreNotas";
                    $formSaraData .= "&curso=" . $idcourse;
                    $formSaraData .= "&sede=" . $idsede;
                    $formSaraData .= "&grado=" . $courses[$idsede][$idcourse]["GRADO_ID"];
                    $courses[$idsede][$idcourse]["LINK_CIERRE"] = $this->context->fabricaConexiones->crypto->codificar_url($formSaraData, $this->enlace);
                } else {
                    $courses[$idsede][$idcourse]["LINK_CIERRE"] = "";
                }
            }
        }
        
        
        include_once($this->ruta . "/html/mainReport.php");
    }
    
}