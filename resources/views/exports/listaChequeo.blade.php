
{{-- <style>
    table, th, td {
  border: 1px solid black;
}
</style> --}}
{{-- @php
    dd($seccionTres);
@endphp --}}
<table>
  
    <tr>
        <td colspan="2" 
        style="
        height:30px;
        text-align: center;
        font-weight: bold;
        background-color: #44505c;
        color:#ffffff;
        "> INFORME DE LA LISTA DE CHEQUEO</td>
    </tr>
    <tr>
        <td style="font-weight: bold">Modelo</td>
        <td style="font-weight: bold">Lista de chequeo</td>
    </tr>
    <tr>
        <td>
            {{$seccionUno->NOMBRE_MODELO}}
        </td>
        <td>{{$seccionUno->NOMBRE_LISTA_CHEQUEO}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold">Publicado en</td>
        <td style="font-weight: bold">Fecha de realización</td>
    </tr>
    <tr>
        <td>
            {{$seccionUno->PUBLICADO_EN}}
        </td>
        <td>{{$seccionUno->FECHA_REALIZACION}}</td>
    </tr>
    <tr>
        <td style="font-weight: bold">Evaluado</td>
        <td style="font-weight: bold">Evaluador</td>
    </tr>
    <tr>
        <td>
            {{$seccionUno->EVALUADO_A}}
        </td>
        <td>{{$seccionUno->EVALUADOR}}</td>
    </tr>
    <tr>
        <td colspan="2"></td>
    </tr>

    <tr>
        <td colspan="2" style="
        height:30px;
        text-align: center; 
        font-weight: bold;
        background-color: #44505c;
        color:#ffffff;
        "> RESULTADO FINAL</td>
    </tr>

   {{--  <tr>
        <td colspan="2"></td>
    </tr> --}}

    {{-- //CATEGORIAS --}}
    @php
        $cantidadCategorias = count($seccionDos['Categorias']);
    @endphp
    {{-- <tr>
        <td style="
            
        text-align: center; 
    font-weight: bold;
    background-color: #44505c;
    color:#ffffff;
        ">
            Categorias
        </td>
        <td style="
            
        text-align: center; 
    font-weight: bold;
    background-color: #44505c;
    color:#ffffff;
        ">
            Porcentaje
        </td>
    </tr> --}}
    @foreach ($seccionDos['Categorias'] as $item)
        @php
            $numeroFormateado = number_format($item->porc_cat,2,'.','');
        @endphp
        <tr>
            <td>
                {{$item->categoria}}
            </td>
            
            <td>
                {{$numeroFormateado}}  %
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" 
        style="
        text-align: center; 
        font-weight: bold;
        "> Resultado final :  {{$seccionDos['ResultadoFinal']}} %</td>
    </tr>
    <tr>
        <td colspan="2"    style="
        height:30px;
        text-align: center;
        font-weight: bold;
        background-color: #44505c;
        color:#ffffff;">PREGUNTAS</td>
    </tr>

    @foreach ($seccionTres as $item)
        @php
            $categoriaPonderado = number_format($item['PONDERADO_CATEGORIA'],2,'.','');
        @endphp
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center;">{{$item['NOMBRE_CATEGORIA']}} : {{$categoriaPonderado}} % </td>
         
        </tr>
        {{-- //preguntas --}}
        @foreach ($item['PREGUNTAS'] as $preguntas)
            <tr>
                <td  colspan="2" style="text-align: center;">{{$preguntas->ORDEN_PREGUNTA }} ) {{$preguntas->NOMBRE_PREGUNTA}} : {{ number_format((float)$preguntas->porcentaje_pregunta,2) }} % </td>
            </tr>
            <tr>
                @if ($preguntas->ES_RESPUESTA_ABIERTA == 1)
                    {{-- RESPUESTA ABIERTA --}}
                    <td><p>{{ $preguntas->RESPUESTA_ABIERTA }}</p></td>
                    {{-- RESPUESTA ABIERTA - FIN--}}
                @else
                    @foreach ($preguntas->TIPOS_RESPUESTA as $tipoRespuesta)
                        <td style=" text-align: center;">{{$tipoRespuesta['valor_personalizado']}}</td>
                    @endforeach
                @endif
                
                @if ($preguntas->PERMITE_NO_APLICA == 1)
               
                    <td style=" text-align: center;">N/A</td>
                @endif
            </tr>
                
            <tr>
                @if ($preguntas->ES_RESPUESTA_ABIERTA != 1)
                    {{--  // tipos de respuesta --}}
                    @foreach ($preguntas->TIPOS_RESPUESTA as $tipoRespuesta)
                        @php
                            $marca='';
                            if($preguntas->RESPUESTA_ID == $tipoRespuesta['id']){
                                $marca = 'X';
                            }
                        @endphp
                        <td style=" text-align: center;">{{$marca}}</td>
                        
                    @endforeach
                @endif
               
                @if ($preguntas->PERMITE_NO_APLICA == 1)
                    @if ($preguntas->RESPUESTA_ID == 0)
                        <td style=" text-align: center;">X</td>
                    @endif
                @endif
            </tr>

            {{-- seccion de fotos --}}
            @if (isset($preguntas->FOTOS))
                <tr>
                    <td style="  text-align: center; 
                    font-weight: bold;">FOTOS</td>
                </tr>
            @endif
            
            @if (isset($preguntas->FOTOS))
                <tr>
                    @foreach ($preguntas->FOTOS as $fotos)
                        @php
                            $imagenes = $fotos['FOTO'];
                        @endphp
                        <td ><img  width="150" height="150"  src="{{$imagenes}}" alt="evidencia audiid"></td>
                    @endforeach
                        
                </tr>
            @endif
          
            
            <tr>
                <td colspan="2" style="height:50px;"><strong> Plan de accion :</strong> {{$preguntas->PLAN_ACCION}}</td>
            </tr>
            
            <tr>
                <td colspan="2" style="height:50px;"><strong>Observacion : </strong> {{$preguntas->COMENTARIO}}</td>  
            </tr>

            
        @endforeach
        <tr>
            <td colspan="2"></td>
        </tr>
    @endforeach
      
    
    
</table>