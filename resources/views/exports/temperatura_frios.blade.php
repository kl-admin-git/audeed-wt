    <table >
        <thead>
            <tr>
                <td colspan="3"><img width="210" src="imagenes/logos_empresariales/lucerna-logo.png" alt="Imagen"></td>
                <th colspan="3" style="height: 50px;text-align: center;font-weight: bold;vertical-align:center;font-size:8px;">FORMATO TEMPERATURAS <br> EQUIPOS DE FRIO</th>
                <th colspan="4" style="text-align: center;vertical-align:center;">
                    <p>Código: RG-CL-002</p>
                    <p>Fecha: Septiembre  de 2018</p>
                    <p>Versión: 002</p>
                </th>
            </tr>
            <tr>
                <th colspan="10" style="background-color: #003366; color:#ffffff text-align: center;vertical-align:center;">PROGRAMA: MUESTREO</th>
            </tr>
            <tr>
                <th colspan="5" style="text-align: initial;"><b>Semana:</b> <span class="semanaInforme">{{ $semana }}</span></th>
                <th colspan="5" style="text-align: initial;"><b>Diligenciado:</b><span class="Diligenciado">{{ $diligenciado }}</span></th>
            </tr>

            <tr>
                <th colspan="6"></th>
            </tr>

            @foreach ($data as $category => $value)
                <tr>
                    <th style="background-color: #003366; color:#ffffff">{{ $category }}</th>
                    <th style="background-color: #003366; color:#ffffff">Código</th>
                    <th style="background-color: #003366; color:#ffffff">Lunes</th>
                    <th style="background-color: #003366; color:#ffffff">Martes</th>
                    <th style="background-color: #003366; color:#ffffff">Miércoles</th>
                    <th style="background-color: #003366; color:#ffffff">Jueves</th>
                    <th style="background-color: #003366; color:#ffffff">Viernes</th>
                    <th style="background-color: #003366; color:#ffffff">Sábado</th>
                    <th style="background-color: #003366; color:#ffffff">Domingo</th>
                    <th style="background-color: #003366; color:#ffffff">Observaciones</th>
                </tr>

                @foreach ($data[$category] as $question => $value)
                    <tr>
                        <th style="text-align: center;vertical-align:center;">{{ explode('-',$question)[1] }}</th>
                        <th style="text-align: center;vertical-align:center;"> </th>
                        
                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['lunes']) ? "" : $data[$category][$question]['lunes'][0]['respuesta']) }}
                        </th>

                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['martes']) ? "" : $data[$category][$question]['martes'][0]['respuesta']) }}
                        </th>

                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['miércoles']) ? "" : $data[$category][$question]['miércoles'][0]['respuesta']) }}
                        </th>

                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['jueves']) ? "" : $data[$category][$question]['jueves'][0]['respuesta']) }}
                        </th>

                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['viernes']) ? "" : $data[$category][$question]['viernes'][0]['respuesta']) }}
                        </th>

                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['sábado']) ? "" : $data[$category][$question]['sábado'][0]['respuesta']) }}
                        </th>

                        <th style="text-align: center;vertical-align:center;">
                            {{ (!ISSET($data[$category][$question]['domingo']) ? "" : $data[$category][$question]['domingo'][0]['respuesta']) }}
                        </th>

                        <th style=""></th>
                    </tr>
                    
                @endforeach

            @endforeach
        </thead>
        <tfoot>
            <tr>
                <th colspan="5" style="font-size: 11px;text-align:center;"><b>Elaborado por: Supervisor de calidad</b></th>
                <th colspan="5" style="font-size: 11px;text-align:center;"><b>Aprobó: Jefe de Calidad</b></th>
            </tr>
        </tfoot>
    </table>