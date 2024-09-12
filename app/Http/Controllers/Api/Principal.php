<?php
namespace App\Http\Controllers\Api;

use App\Models\usuario;
use App\Models\libro;
use App\Models\libro_x_usuario;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Principal extends Controller
{

    private $idUsuario;
    public function getAllUsers()
    {
        try {
            $users = usuario::all();
            return response()->json($users, Response::HTTP_OK);
        } catch (\Illuminate\Database\QueryException $e) {
            return response(["message" => "Error: " . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response(["message" => "Error: " . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllBooks()
    {
        try {
            $books = libro::all();
            return response()->json($books, Response::HTTP_OK);
        } catch (\Illuminate\Database\QueryException $e) {
            return response(["message" => "Error: " . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response(["message" => "Error: " . $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createNewUser(Request $request)
    {
        DB::beginTransaction();
        try {
            // Verificar si el usuario con el correo proporcionado ya existe
            $usuario = Usuario::where("correo", $request->correo)->first();
    
            if (!$usuario) {
                // Si el usuario no existe, crear un nuevo usuario
                $nuevoUsuario = Usuario::create([
                    'nombre' => $request->nombre,
                    'correo' => $request->correo,
                    'telefono' => $request->telefono,
                    'contrasena' => md5(123 . "Usuario"),
                    'fecha_inicio' => date("Y-m-d"),
                    'fecha_vencimiento' => date("Y-m-d", strtotime('+365 days')),
                    'sesiones_activas' => 0,
                    'estado' => null,
                    'primer_ingreso' => 0,
                ]);
    
                // Obtener el ID del nuevo usuario
                $idUsuario = $nuevoUsuario->id;
                $correoUsuario = $nuevoUsuario->correo;
            } else {
                $idUsuario = $usuario->id_usuario;
                $correoUsuario = $usuario->correo;
            }
    
            // Verificar si algún libro ya está registrado para el usuario
            $librosRegistrados = libro_x_usuario::whereIn('id_libro', $request->libros)
                ->where('id_usuario', $idUsuario)
                ->pluck('id_libro')
                ->toArray();
    
            if (!empty($librosRegistrados)) {
                // Al menos un libro ya está registrado
                DB::rollBack();
                return response(["message" => "Los siguientes libros ya están registrados: " . implode(', ', $librosRegistrados)], Response::HTTP_BAD_REQUEST);
            }
    
            // Obtener los libros que existen en la base de datos
            $librosExistentes = Libro::whereIn('id_libro', $request->libros)->pluck('id_libro')->toArray();
    
            // Filtrar los libros que no existen en la base de datos
            $librosAInsertar = array_intersect($request->libros, $librosExistentes);
    
            foreach ($librosAInsertar as $libroId) {
                libro_x_usuario::create([
                    'id_libro' => $libroId,
                    'id_usuario' => $idUsuario,
                ]);
            }
    
            if (!empty($librosAInsertar)) {
                DB::commit();
    
                if (!$usuario) {
                    $this->enviarCorreoBienvenida($correoUsuario);
                    return response(["message" => "Usuario creado exitosamente"], Response::HTTP_CREATED);
                } else {
                    $this->enviarCorreoNuevosLibros($request->correo);
                    return response(["message" => "Libros asociados al usuario existente"], Response::HTTP_OK);
                }
            } else {
                // Ningún libro válido para insertar
                DB::rollBack();
                return response(["message" => "Ningún libro válido para insertar"], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response(["message" => "Error: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(["message" => "Error: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    private function enviarCorreoBienvenida($correoUsuario)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "SSL";
            $mail->Host = "Aqui tu Host de SMTP";
            $mail->Port = 587;
            $mail->Username = "Aqui tu cuenta de SMTP";
            $mail->Password = 'Aqui tu contraseña de SMTP';
            $mail->CharSet = 'UTF-8';
            $mail->SetFrom('Aqui tu cuenta de SMTP', 'Plataforma Venta de Libros');
            $mail->isHTML(true);
            $mail->Subject = "¡Gracias por tu compra en Plataforma Venta de Libros!";
            $mail->Body = "<h4><strong>¡Enhorabuena! por la compra de tus libros.</strong></h4> 
                            <p>A continuación adjuntamos los datos para el acceso a la plataforma:</p>
                            <p><strong>Usuario: </strong>" . $correoUsuario . "</p>                            
                            <p><strong>Contraseña: </strong> 123Usuario</p>      
                            <p><strong>Link de Acceso: </strong> https://github.com/JPabloMendez2002 </p>
                            <small style='color: red;'>Por seguridad recomendamos que cambies tu contraseña al ingresar por primera vez.</small>       
                            ";
            $mail->addAddress($correoUsuario);
            $mail->send();
        } catch (Exception $e) {
            return back()->with('Error', 'Error al enviar los E-Mails.' + $e);
        }
    }

    private function enviarCorreoNuevosLibros($correoUsuario)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "SSL";
            $mail->Host = "Aqui tu Host de SMTP";
            $mail->Port = 587;
            $mail->Username = "Aqui tu cuenta de SMTP";
            $mail->Password = 'Aqui tu contraseña de SMTP';
            $mail->CharSet = 'UTF-8';
            $mail->SetFrom('Aqui tu cuenta de SMTP', 'Plataforma Venta de Libros');
            $mail->isHTML(true);
            $mail->Subject = "¡Nuevos libros disponibles!";
            $mail->Body = "<h4><strong>¡Enhorabuena! por tu nueva compra.</strong></h4>
                            <p>Te informamos que hemos agregado tus nuevos libros en Plataforma Venta de Libros.</p>
                            <p><strong>Link de Acceso: </strong> https://github.com/JPabloMendez2002 </p>
                            <small style='color: red;'>Este mensaje no contiene las credenciales de ingreso, si necesitas restablecer tu contraseña puedes hacerlo en la plataforma.</small>       
                            ";
            $mail->addAddress($correoUsuario);
            $mail->send();
        } catch (Exception $e) {
            return back()->with('Error', 'Error al enviar los E-Mails.' + $e);
        }
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
