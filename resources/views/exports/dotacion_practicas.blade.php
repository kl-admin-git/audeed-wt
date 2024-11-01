    @php
        $diligenciado = "";
    @endphp
    <table >
        <thead>
            <tr>
                <td colspan="3"><img width="350" src="imagenes/logos_empresariales/lucerna-logo.png" alt="Imagen"></td>
                <th colspan="3" style="height: 80px;text-align: center;font-weight: bold;vertical-align:center;font-size:8px;">FORMATO DE VERIFICACIÓN DE <br> DOTACIÓN Y PRACTICAS HIGIÉNICAS </th>
                <th colspan="3" style="text-align: center;vertical-align:center;">
                    <p>Código: RG-PH-008</p>
                    <p>Fecha: Junio de 2018</p>
                    <p>Versión: 002</p>
                </th>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 11px;height: 30px;">Fecha: {{ $fechaRealizacion }}</th>
                <th colspan="5" style="font-size: 11px;height: 30px;">Calificación: Cumple - No Cumple</th>
            </tr>
            <tr>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Área</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Empleado</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Uniforme</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Zapatos</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Uñas</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Protector cabello</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Tapabocas</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Higiene personal</th>
                <th style="height:30px;text-align: center;font-weight: bold;background-color: #c00000;color:#ffffff;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            @php
                $diligenciado = $item['DILIGENCIADO'];
            @endphp
                <tr>
                    @foreach ($item['RESPUESTA'] as $keys => $rta)
                        @if ($keys == 1)
                            <td style="text-align: center;">{{ $item{'EVALUADO'} }}</td>
                            <td style="text-align: center;">{{ $rta }}</td>
                        @else
                            <td style="text-align: center;">{{ $rta }}</td>
                        @endif
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
            <tr></tr>
            <tr></tr>
            <tr></tr>
        </tbody>
        <tfoot>
            <tr><th colspan="9" style="font-size: 11px;">Responsable: {{$diligenciado}}</th></tr>
            <tr><th colspan="9"></th></tr>
            <tr>
                <th colspan="4" style="font-size: 11px;text-align:center;">Elaborado por: Supervisor de calidad</th>
                <th colspan="5" style="font-size: 11px;text-align:center;">Aprobó: Jefe de Calidad</th>
            </tr>
        </tfoot>
    </table>