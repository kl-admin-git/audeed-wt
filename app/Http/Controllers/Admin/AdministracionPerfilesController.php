<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
class AdministracionPerfilesController extends Controller
{
    
    public function __construct(){
        
    }

    public function Index()
    {
        return view('Admin.administracion_perfiles');
    }
}
