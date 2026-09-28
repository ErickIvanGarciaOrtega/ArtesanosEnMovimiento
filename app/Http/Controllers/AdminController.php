<?php
//indica al sistema que este archivo es un controlador que vive dentro de app/Http/Controllers/.
namespace App\Http\Controllers;

//mporta la clase Request de Laravel, es una herramienta estándar para capturar peticiones HTTP (como datos enviados por POST o parámetros GET).
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Método para mostrar la vista principal del panel de administración
    public function index()
    {
        //Es la instrucción que le ordena a Laravel: "Busca dentro de la carpeta de vistas una subcarpeta llamada admin, y dentro de ella carga el archivo llamado dashboard.blade.php" para mostrárselo al usuario en la pantalla.
        return view('admin.dashboard');
    }

    // Método para mostrar la sección de gestión de emprendedores
    public function gestionarEmprendedores()
    {
        // Aquí posteriormente se agregara la lógica para consultar los datos de la BD
        return view('admin.emprendedores');
    }
}