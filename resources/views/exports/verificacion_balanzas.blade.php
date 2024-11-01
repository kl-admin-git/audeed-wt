    <table >
        <thead>
            <tr>
                <td colspan="3"><img width="210" src="imagenes/logos_empresariales/lucerna-logo.png" alt="Imagen"></td>
                <th colspan="3" style="height: 50px;text-align: center;font-weight: bold;vertical-align:center;font-size:8px;">VERIFICACIÓN DE BALANZAS</th>
                <th colspan="3" style="text-align: center;vertical-align:center;">
                    <p>Código: RG-CL-002</p>
                    <p>Fecha: Septiembre  de 2018</p>
                    <p>Versión: 002</p>
                </th>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 11px;height: 20px;vertical-align:center;">Fecha: {{ $fechaRealizacion }}</th>
                <th colspan="5" style="font-size: 11px;height: 20px;vertical-align:center;">Responsable: {{ $diligenciado }}</th>
            </tr>
            <tr>
                <th colspan="2" style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Balanza</th>
                <th colspan="2" style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Activo</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Masa patrón</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Desviación</th>
                <th colspan="3" style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td colspan="2" style="text-align: center;">{{ $item['EVALUADO'] }}</td>
                    <td colspan="2" style="text-align: center;">{{ $item['DESCRIPCION_EQUIPO'] }}</td>
                    @foreach ($item['RESPUESTA'] as $keys => $rta)
                        <td style="text-align: center;">{{ $rta }}</td>
                    @endforeach
                    <td colspan="3" style="text-align: center;">{{ $item['OBSERVACION_GENERAL'] }}</td>
                </tr>
            @endforeach
            {{-- CELDAS VACIAS --}}
            @for ($i = 0; $i < 4; $i++)
                <tr>
                    <td colspan="2" style="text-align: center;"></td>
                    <td colspan="2" style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td colspan="3" style="text-align: center;"></td>
                </tr>
            @endfor
            
        </tbody>
        <tfoot>
            <tr><th colspan="9"></th></tr>
            <tr>
                <th colspan="4" style="font-size: 11px;text-align:center;"><b>Elaborado por: Supervisor de calidad</b></th>
                <th colspan="5" style="font-size: 11px;text-align:center;"><b>Aprobó: Jefe de Calidad</b></th>
            </tr>
        </tfoot>
    </table>