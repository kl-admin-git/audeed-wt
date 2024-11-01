<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
class ClientesPerfilesController extends Controller
{
    
    public function __construct(){
        
    }

    public function Index()
    {
        return view('Admin.clientes_perfiles');
    }
}
