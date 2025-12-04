<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores Generales
use App\Http\Controllers\ProfileController;

// Controladores Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\CriterioController as AdminCriterioController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EquipoController as AdminEquipoController;
use App\Http\Controllers\Admin\ProyectoController;
use App\Http\Controllers\Admin\ResultadosController;

// Controladores Juez
use App\Http\Controllers\Juez\JuezController;
use App\Http\Controllers\Juez\CriterioController as JuezCriterioController;
use App\Http\Controllers\Juez\EvaluacionController;
use App\Http\Controllers\Juez\EquipoController as JuezEquipoController;

// Controladores Participante
use App\Http\Controllers\Participante\ParticipanteController;
use App\Http\Controllers\Participante\PerfilController;
use App\Http\Controllers\Participante\EquipoController as ParticipanteEquipoController;
use App\Http\Controllers\Participante\AvanceController;
use App\Http\Controllers\Participante\SolicitudEquipoController;

// Middlewares
use App\Http\Middleware\EnsureParticipantProfileExists;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- RUTAS COMUNES Y REDIRECCIÓN INTELIGENTE ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Perfil de Usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirección Centralizada (Usa tu lógica del Modelo User)
    Route::get('/dashboard', function () {
        // Cargamos el modelo User explícitamente con roles para evitar advertencias de analizadores
        $user = \App\Models\User::with('roles')->find(\Illuminate\Support\Facades\Auth::id());

        if (!$user) {
            return redirect()->route('login');
        }

        return redirect()->route($user->getDashboardRouteName());
    })->name('dashboard');
});

// ==========================================
// MÓDULO ADMINISTRADOR
// ==========================================
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/preferences', [AdminController::class, 'savePreferences'])->name('dashboard.preferences');
    Route::get('/dashboard/report', [AdminController::class, 'generateReport'])->name('dashboard.report');

    // Gestión Principal
    Route::resource('eventos', EventoController::class);
    
    // Exportación de usuarios 
    Route::get('/usuarios/exportar', [UsuarioController::class, 'exportar'])->name('usuarios.exportar');
    
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('equipos', AdminEquipoController::class);
    Route::resource('proyectos', ProyectoController::class);

    // Gestión de Criterios (Admin)
    Route::post('/eventos/{evento}/criterios', [AdminCriterioController::class, 'store'])->name('eventos.criterios.store');
    Route::put('/criterios/{criterio}', [AdminCriterioController::class, 'update'])->name('criterios.update');
    Route::delete('/criterios/{criterio}', [AdminCriterioController::class, 'destroy'])->name('criterios.destroy');
    Route::get('/criterios/{criterio}/editar', [AdminCriterioController::class, 'edit'])->name('criterios.edit');

    // Gestión de Miembros de Equipo (Admin)
    Route::post('/equipos/{equipo}/miembros', [AdminEquipoController::class, 'addMember'])->name('equipos.miembros.store');
    Route::delete('/equipos/{equipo}/miembros/{participante}', [AdminEquipoController::class, 'removeMember'])->name('equipos.miembros.destroy');

    // Resultados y Constancias
    Route::get('/resultados', [ResultadosController::class, 'index'])->name('resultados.index');
    Route::get('/resultados/constancia/{proyecto}/{posicion}', [ResultadosController::class, 'descargarConstancia'])->name('constancia.descargar');

    // Gestión de Carreras
    Route::resource('carreras', \App\Http\Controllers\Admin\CarreraController::class);

    // Gestión de Perfiles
    Route::resource('perfiles', \App\Http\Controllers\Admin\PerfilController::class);

    // Reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('index');
        Route::get('/usuarios/pdf', [\App\Http\Controllers\Admin\ReporteController::class, 'usuariosPdf'])->name('usuarios.pdf');
        Route::get('/equipos/pdf', [\App\Http\Controllers\Admin\ReporteController::class, 'equiposPdf'])->name('equipos.pdf');
        Route::get('/eventos/pdf', [\App\Http\Controllers\Admin\ReporteController::class, 'eventosPdf'])->name('eventos.pdf');
        Route::get('/proyectos/pdf', [\App\Http\Controllers\Admin\ReporteController::class, 'proyectosPdf'])->name('proyectos.pdf');
    });
});

// ==========================================
// MÓDULO JUEZ
// ==========================================
Route::middleware(['auth', 'role:Juez'])->prefix('juez')->name('juez.')->group(function () {

    // Dashboard y Vista de Evento
    Route::get('/dashboard', [JuezController::class, 'index'])->name('dashboard');
    Route::get('/evento/{evento}', [JuezController::class, 'showEvento'])->name('evento.show');

    // Gestión de Criterios (Juez)
    Route::resource('criterios', JuezCriterioController::class)->except(['index', 'show', 'create', 'edit']);

    // Evaluación de Proyectos
    Route::controller(EvaluacionController::class)->group(function () {
        Route::get('/evaluar/{proyecto}', 'edit')->name('evaluaciones.edit');
        Route::post('/evaluar/{proyecto}', 'store')->name('evaluaciones.store');
    });

    // Gestión de Equipos (Juez - Permisos extendidos)
    Route::controller(JuezEquipoController::class)->group(function () {
        Route::get('/equipo/{equipo}/editar', 'edit')->name('equipos.edit');
        Route::put('/equipo/{equipo}', 'update')->name('equipos.update');
        Route::post('/equipo/{equipo}/miembro', 'addMember')->name('equipos.addMember');
        Route::delete('/equipo/{equipo}/miembro/{participante}', 'removeMember')->name('equipos.removeMember');
    });
});

// ==========================================
// MÓDULO PARTICIPANTE
// ==========================================
Route::middleware(['auth', 'role:Participante'])->prefix('participante')->name('participante.')->group(function () {

    // 1. Registro Inicial (Accesible SIN perfil completo)
    Route::controller(PerfilController::class)->group(function () {
        Route::get('/registro-inicial', 'create')->name('registro.inicial');
        Route::post('/registro-inicial', 'store')->name('registro.store');
    });

    // 2. Área Protegida (Requiere Perfil Completo: Teléfono, Carrera, etc.)
    Route::middleware([EnsureParticipantProfileExists::class])->group(function () {

        Route::get('/dashboard', [ParticipanteController::class, 'index'])->name('dashboard');

        // Gestión de Equipos (CRUD Básico y Acciones Específicas)
        Route::resource('equipos', ParticipanteEquipoController::class)->only(['create', 'store', 'show', 'edit', 'update']);
        Route::delete('/equipos/salir', [\App\Http\Controllers\Participante\EquipoController::class, 'leave'])->name('equipos.leave');

        Route::controller(ParticipanteEquipoController::class)->group(function () {
            // Unirse a equipo existente
            Route::get('/unirse-equipo', 'showJoinForm')->name('equipos.join');
            Route::post('/unirse-equipo', 'join')->name('equipos.join.store');

            // Gestión de miembros por el líder
            Route::post('/equipo/agregar-miembro', 'addMember')->name('equipos.addMember');
            Route::delete('/equipo/eliminar-miembro/{id}', 'removeMember')->name('equipos.removeMember');
        });

        // Solicitudes de Unión a Equipos
        Route::controller(SolicitudEquipoController::class)->prefix('solicitudes')->name('solicitudes.')->group(function () {
            Route::get('/mis-solicitudes', 'misSolicitudes')->name('mis');
            Route::get('/equipo/{equipo}', 'verSolicitudesEquipo')->name('equipo');
            Route::get('/{equipo}/crear', 'showCrearSolicitud')->name('crear.form');
            Route::post('/{equipo}/crear', 'crearSolicitud')->name('crear');
            Route::post('/{solicitud}/aceptar', 'aceptar')->name('aceptar');
            Route::post('/{solicitud}/rechazar', 'rechazar')->name('rechazar');
        });

        // Bitácora de Avances
        Route::controller(AvanceController::class)->group(function () {
            Route::get('/proyecto/bitacora', 'index')->name('avances.index');
            Route::post('/proyecto/bitacora', 'store')->name('avances.store');
            Route::delete('/proyecto/bitacora/{id}', 'destroy')->name('avances.destroy');
        });

        // Descarga de Constancias
        Route::get('/constancia/imprimir/{tipo}', [ParticipanteController::class, 'generarConstancia'])
            ->name('constancia.imprimir');
    });
});

require __DIR__ . '/auth.php';