
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Plan de acción</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Código</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Fecha de realización</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Lista de chequeo</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Empresa</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Evaluado</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Evaluador</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Pregunta</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Respuesta</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Observación del evaluador</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Estado</th>

                <th style="
                height:30px;
                text-align: center;
                font-weight: bold;
                background-color: #44505c;
                color:#ffffff;
                ">Tipo de plan de acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($planAccion as $item)
                <tr>
                    <td>{{$item->CODIGO_PLAN_ACCION}}</td>
                    <td>{{$item->FECHA_REALIZACION}}</td>
                    <td>{{$item->nombre}}</td>
                    <td>{{$item->EMPRESA}}</td>
                    <td>{{$item->evaluado}}</td>
                    <td>{{$item->evaluador}}</td>
                    <td>{{$item->pregunta}}</td>
                    <td>{{($item->ES_RESPUESTA_ABIERTA == 1 ? $item->RESPUESTA_ABIERTA : $item->respuesta)}}</td>
                    <td>{{$item->OBSERVACION}}</td>
                    <td>{{$item->ESTADO}}</td>
                    <td>{{$item->TIPO_PLAN_ACCION}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>