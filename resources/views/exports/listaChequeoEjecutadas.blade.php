
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista chequeo ejecutadas</title>
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
                ">Lista de chequeo</th>
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
                ">Dirección</th>
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
                ">Entidad evaluada</th>
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
                ">Resultado final</th>
    
            </tr>
        </thead>
        <tbody>
            @foreach ($traerInformeEjecutadas as $item)
                <tr>
                    <td>{{$item->lista_chequeo}}</td>
                    <td>{{$item->FECHA_REALIZACION}}</td>
                    <td>{{$item->DIRECCION}}</td>
                    <td>{{$item->estado}}</td>
                    <td>{{$item->entidad_evaluada}}</td>
                    <td>{{$item->empresa}}</td>
                    <td>{{$item->evaluado}}</td>
                    <td>{{$item->evaluador}}</td>
                    <td>{{$item->resultado_final}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>