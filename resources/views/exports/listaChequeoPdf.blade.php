
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    {{-- main_general css --}}
    <link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
    <!-- C3 charts css -->
    <link href="{{ assets_version('/vertical/assets/plugins/c3/c3.min.css') }}" rel="stylesheet" type="text/css" />
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ assets_version('/vertical/assets/plugins/morris/morris.css') }}">
    <!--Animate CSS -->
    <link href="{{ assets_version('/vertical/assets/plugins/animate/animate.min.css') }}" rel="stylesheet" type="text/css">
    {{-- Select2 --}}
    <link href="{{ assets_version('/vertical/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets_version('/vertical/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ assets_version('/vertical/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link href="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ assets_version('/vertical/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    
    <link href="{{    assets_version('/vertical/assets/js/tour/shepherd.css') }}" rel="stylesheet">
    <link href="{{    assets_version('/vertical/assets/js/tour/tour.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/detalle/main.css') }}">
</head>
<body>
 <style>
     .titulo-pdf{
       
        text-align: center;
        font-weight: bold;
        width: 1000px;
        background-color: #44505c!important;
        color: white;
        height: 50px;
        text-align: center;
     }
     .td-seccionuno{
         width: 480px;
         height: 50px;
         background-color: #44505c!important;
         font-weight: bold;
         text-align: center;
         color: white;
         /* vertical-align: bottom; */

     }

     .td-secciondos{
        padding: 22px;
        border-radius: 20px;
        width: 80px;
        height: 50px;
        background-color: #44505c!important;
        color: white;
        margin-bottom: 15px !important; 
     }
     .titulo-resultado-final{
        background-color: #44505c!important;
        color: white;
        margin-top: 15px !important; 
        width: 1000px !important;
        height: 50px;
        color: white;
        text-align: center;
       
     }
   /*   .contenedoresCategoriasPDF
        {
            background: #F3F3F3;
            width: 120px;
            height: 100px;
            color: #4A4A4A;
            font-weight: bold;
            text-align: center;
            padding-top: 1rem;
            border-radius: 1.4rem;
            margin-left: 5px;
            display: inline-block;
            font-size: 12px;
        }

        .contenedorCategorias:not(:last-child){
            margin-bottom: 13px !important;
        } */
    .resultado-cal{
        width: 30px !important;
        padding: 20px !important;
        border: 1px solid #d6d6d6;
        border-radius: 7px;
        color: #4FB648 !important;
        text-align: center;
    }
    .seccion { page-break-inside:avoid; page-break-after:always; }
 </style>
    
    <div class="row datosLista" idListaChequeoEjecutada="{{ Request::segment(3) }}">
        <div class="col-lg-12">
            <div class="row m-b-10">
                <div class="col-lg-12">
                    {{-- PRIMERA SECCION --}}
                    <div class="contenedorEncabezado">
                        <div id="accordion">
                            <div class="card">
    
                                <table>
                                    <tr>
                                        <td class="titulo-pdf"> INFORME DE LA LISTA DE CHEQUEO</td>
                                    </tr>
                                </table>
    
                                <div id="" class="collapse show contenedorTextosEncabezado">
    
                                    <div class="card-header subtituloEncabezado">
                                        <p class="m-0">
                                            <a class="">
                                                INFORMACIÓN GENERAL
                                            </a>
                                        </p>
                                    </div>
                                    <hr>
    
                                    
                                        <table>
                                            <tr>
                                                <td class="td-seccionuno">Modelo</td>
                                                <td class="td-seccionuno">Lista de chequeo</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{$seccionUno->NOMBRE_MODELO}}
                                                </td>
                                                <td>{{$seccionUno->NOMBRE_LISTA_CHEQUEO}}</td>
                                            </tr>
                                            <tr>
                                                <td class="td-seccionuno">Publicado en</td>
                                                <td class="td-seccionuno">Fecha de realización</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{$seccionUno->PUBLICADO_EN}}
                                                </td>
                                                <td>{{$seccionUno->FECHA_REALIZACION}}</td>
                                            </tr>
                                            <tr>
                                                <td class="td-seccionuno">Evaluado</td>
                                                <td class="td-seccionuno">Evaluador</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{$seccionUno->EVALUADO_A}}
                                                </td>
                                                <td>{{$seccionUno->EVALUADOR}}</td>
                                            </tr>

                                        </table>
                                        
    
                                   
    
                                </div>
    
                            </div>
    
                        </div>
                    </div>
                    
                   
                    {{-- FIN PRIMERA SECCION --}}
    
                    {{-- SEGUNDA SECCION --}}
                    <div class="segundaSeccion">
                        <div id="accordion">
                            <div class="card">
    
                                <div id="" class="collapse show contenedorTextosEncabezado ">
    
                                    <div class="card-header subtituloEncabezado">
                                        <p class="m-0">
                                            <a class="">
                                                RESULTADO FINAL
                                            </a>
                                        </p>
                                    </div>
                                    <hr>

                                    
    
                                 {{--    <br />
                                    <br />
                                    @php
                                        $contador=0;
                                        $divisor = 6;
                                        $estilo = '';
                                    @endphp

                                    @foreach ($seccionDos['Categorias'] as $categoria)
                                        @php
                                            $numeroFormateado = number_format($categoria->porc_cat,2,'.','');
                                        @endphp
                                        <div class="contenedoresCategoriasPDF">
                                            <div class="textosCategoria">
                                                <p style="padding:0;margin:0;margin-top:15px;">{{$categoria->categoria}}</p>
                                                <p style="padding:0;margin:0;">{{number_format($numeroFormateado, 2, '.', '')}} %</p>
                                            </div>
                                        </div>
                                        @if (($contador != 0 && (($contador + 1) % $divisor == 0) || $contador == COUNT($seccionDos['Categorias'])))
                                            <br />
                                            <br />
                                            <br />
                                        @endif

                                        @php $contador++; @endphp
                                    @endforeach --}}
                                   
                                    <table>
                                        <tr>
                                            <th class="td-seccionuno">Categoria</th>
                                            <th class="td-seccionuno">Resultado</th>
                                        </tr>
                                        @foreach ($seccionDos['Categorias'] as $categoria)
                                            @php
                                                $numeroFormateado = $categoria->porc_cat;
                                            @endphp

                                            <tr>
                                                <td style="text-align: center;">{{$categoria->categoria}}</td>
                                                <td style="text-align: center;">{{$numeroFormateado}} %</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                        
                                    <table>
                                        <tr>
                                            <td class="titulo-resultado-final"> Resultado final : % {{$seccionDos['ResultadoFinal']}}</td>
                                        </tr>
                                    </table>
                                    
    
                                </div>
    
                            </div>
    
                        </div>
                    </div>
                    <br>
                    <br>
                    
                    {{-- FIN SEGUNDA SECCION --}}
                    <div class="seccion"></div>
                    {{-- TERCERA SECCION --}}
                    <div class="terceraSeccion">
                        <div id="accordion">
                            <div class="card">
    
                                <div id="" class="collapse show contenedorTextosEncabezado ">
    
                                    <div class="card-header subtituloEncabezado">
                                        <p class="m-0">
                                            <a class="">
                                                PREGUNTAS
                                            </a>
                                        </p>
                                    </div>
                                    <hr>
    
                                   
                                 
                                        @foreach ($seccionTres as $item)
                                            @php
                                                $categoriaPonderado = $item['PONDERADO_CATEGORIA'];
                                            @endphp
                                            <table>
                                                <tr>
                                                    <td class="titulo-pdf">{{$item['NOMBRE_CATEGORIA']}} : {{$categoriaPonderado}} % </td>
                                                    {{-- <td></td> --}}
                                                </tr>
                                            </table>
                                            {{-- //preguntas --}}
                                            @foreach ($item['PREGUNTAS'] as $preguntas)
                                                @php
                                                    $preguntaPonderado = $preguntas->porcentaje_pregunta;
                                                @endphp
                                                <table>
                                                    <tr>
                                                        <td>{{$preguntas->ORDEN_PREGUNTA }}. {{$preguntas->NOMBRE_PREGUNTA}} : {{$preguntaPonderado}} % </td>
                                                        {{-- <td></td> --}}
                                                    </tr>
                                                    <table>
                                                        <tr>
                                                            @if ($preguntas->ES_RESPUESTA_ABIERTA == 1)
                                                                {{-- RESPUESTA ABIERTA --}}
                                                                <td><p>{{ $preguntas->RESPUESTA_ABIERTA }}</p></td>
                                                                {{-- RESPUESTA ABIERTA - FIN--}}
                                                            @else
                                                                @foreach ($preguntas->TIPOS_RESPUESTA as $tipoRespuesta)
                                                                    <td>{{$tipoRespuesta['valor_personalizado']}}</td>
                                                                @endforeach
                                                            @endif

                                                            @if ($preguntas->PERMITE_NO_APLICA == 1)
                                                        
                                                                <td>N/A</td>
                                                            @endif
                                                        </tr>
                                                            
                                                        <tr>
                                                            {{--  // tipos de respuesta --}}
                                                                @if ($preguntas->ES_RESPUESTA_ABIERTA != 1)
                                                                    @foreach ($preguntas->TIPOS_RESPUESTA as $tipoRespuesta)
                                                                        @php
                                                                            $marca=' ';
                                                                            if($preguntas->RESPUESTA_ID == $tipoRespuesta['id']){
                                                                                $marca = 'X';
                                                                            }
                                                                        @endphp
                                                                        <td class="resultado-cal">{{$marca}}</td>
                                                                        
                                                                    @endforeach
                                                                @endif
                                                                
                                                                @if ($preguntas->PERMITE_NO_APLICA == 1)
                                                                    @if ($preguntas->RESPUESTA_ID == 0)
                                                                        <td class="resultado-cal">X</td>
                                                                    @endif
                                                                @endif
                                                        </tr>

                                                    </table>
                                                </table>
                                              
                                    
                                                {{-- seccion de fotos --}}
                                                <table>
                                                    @if (isset($preguntas->FOTOS))
                                                        <tr>
                                                            <td style="text-align: center;font-weight: bold;">FOTOS</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        @if (isset($preguntas->FOTOS))
                                                            @foreach ($preguntas->FOTOS as $fotos)
                                                                @php
                                                                
                                                                    // $imagenes = $_SERVER["DOCUMENT_ROOT"].'/'.$fotos['FOTO'];
                                                                    // $imagenes = 'https://'.$_SERVER['SERVER_NAME'].'/'.$fotos['FOTO'];
                                                                    // $imagenes = public_path().'/'.$fotos['FOTO'];
                                                                    // $imagenes = public_path($fotos['FOTO']);
                                                                        $imagenes = $fotos['FOTO'];
                                                                //    dd($imagenes.'----'.$fotos['FOTO']);
                                                                @endphp
                                                              
                                                                <td ><img  width="150" height="150"  src="{{public_path($imagenes)}}" alt="evidencia audiid"></td>
                                                            @endforeach
                                                                
                                                        @endif
                                                    </tr>

                                                </table>
                                                <table>
                                                    <tr>
                                                        <td style="text-align: center;font-weight: bold;">Plan de accion</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$preguntas->PLAN_ACCION}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;">Observacion : {{$preguntas->COMENTARIO}}</td>  
                                                    </tr>

                                                </table>
                                                
                                    
                                                
                                            @endforeach
                                        <div class="seccion"></div>
                                        @endforeach

                                  
                                </div>
    
                            </div>
    
                        </div>
                    </div>
                   
                    {{-- FIN TERCERA SECCION --}}
    
               
    
    
                 
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>






