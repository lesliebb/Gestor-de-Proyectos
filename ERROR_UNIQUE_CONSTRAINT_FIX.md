# 🐛 Error Corregido: Unique Constraint Violation

## Problema Reportado

**Error:**
```
Illuminate\Database\UniqueConstraintViolationException
SQLSTATE[23505]: Unique violation: 7 ERROR: llave duplicada viola restricción de unicidad
«solicitudes_equipo_equipo_id_participante_id_unique»
```

**Cuando ocurría:**
Cuando un participante intentaba enviar una segunda solicitud a un equipo donde ya había enviado una solicitud (aceptada, rechazada o pendiente).

---

## Causa del Problema

### ❌ Validación Original:
```php
if (SolicitudEquipo::where('equipo_id', $equipo->id)
    ->where('participante_id', $participante->id)
    ->where('estado', 'pendiente')  // ← PROBLEMA: Solo verifica "pendiente"
    ->exists()) {
    return error;
}

// Crear solicitud
$solicitud = SolicitudEquipo::create([...]);
```

### El Problema:
1. Si una solicitud anterior fue **ACEPTADA** o **RECHAZADA**, no se detectaba
2. El sistema intentaba crear una **nueva** solicitud con el mismo `(equipo_id, participante_id)`
3. La BD tiene una restricción UNIQUE que lo previene → **Error de constraint**

---

## Solución Implementada

### ✅ Validación Mejorada:

```php
// 1. Buscar CUALQUIER solicitud (estado irrelevante)
$solicitudExistente = SolicitudEquipo::where('equipo_id', $equipo->id)
    ->where('participante_id', $participante->id)
    ->first();  // ← Obtiene el registro completo

// 2. Si existe, dar error según su estado
if ($solicitudExistente) {
    if ($solicitudExistente->estado === 'pendiente') {
        return error('Ya tienes una solicitud pendiente');
    } elseif ($solicitudExistente->estado === 'aceptada') {
        return error('Tu solicitud fue aceptada. Ya estás en el equipo.');
    } else {
        return error('Tu solicitud anterior fue rechazada. Espera antes de reintentar.');
    }
}

// 3. Si realmente no existe, crear
try {
    $solicitud = SolicitudEquipo::create([...]);
} catch (\Illuminate\Database\QueryException $e) {
    // Captura race conditions (2 requests simultáneos)
    if ($e->getCode() == '23505') {
        return error('Ya existe una solicitud para este equipo.');
    }
    throw $e;
}
```

---

## Cambios Realizados

### Archivo: `SolicitudEquipoController.php`

**Antes:**
```php
if (SolicitudEquipo::where(...)->where('estado', 'pendiente')->exists()) {
    return error;
}
$solicitud = SolicitudEquipo::create([...]);
```

**Después:**
```php
$solicitudExistente = SolicitudEquipo::where(...)->first();
if ($solicitudExistente) {
    if ($solicitudExistente->estado === 'pendiente') {...}
    elseif ($solicitudExistente->estado === 'aceptada') {...}
    else {...}
}

try {
    $solicitud = SolicitudEquipo::create([...]);
} catch (\Illuminate\Database\QueryException $e) {
    if ($e->getCode() == '23505') {...}
    throw $e;
}
```

### Archivo: `EquipoController.php`

**Antes:**
```php
$solicitudPendiente = SolicitudEquipo::where(...)->where('estado', 'pendiente')->exists();
if ($solicitudPendiente) {...}
```

**Después:**
```php
$solicitudExistente = SolicitudEquipo::where(...)->first();
if ($solicitudExistente) {
    if ($solicitudExistente->estado === 'pendiente') {...}
    else {...}
}
```

---

## Flujo de Validación Mejorado

```
Usuario intenta enviar solicitud al Equipo A
    ↓
¿Ya existe solicitud para (equipo_id=1, participante_id=55)?
    ├─ NO → Crear solicitud ✅
    │
    └─ SÍ
        ├─ ¿Estado = PENDIENTE?
        │  └─ Sí → "Ya tienes solicitud pendiente" ❌
        │
        ├─ ¿Estado = ACEPTADA?
        │  └─ Sí → "Ya estás en el equipo" ❌
        │
        └─ ¿Estado = RECHAZADA?
           └─ Sí → "Fue rechazada, espera" ❌
```

---

## Mensajes de Error Ahora

| Situación | Mensaje |
|-----------|---------|
| Solicitud pendiente | "Ya tienes una solicitud pendiente para este equipo" |
| Solicitud aceptada | "Tu solicitud fue aceptada. Ya deberías estar en este equipo." |
| Solicitud rechazada | "Tu solicitud anterior fue rechazada. Espera antes de intentar nuevamente." |
| Race condition | "Ya existe una solicitud para este equipo. Por favor, espera..." |

---

## Testing de la Corrección

### Caso 1: Enviar dos veces al mismo equipo
```
1. Participante envía solicitud a Equipo A → ✅ Se crea
2. Intenta enviar otra a Equipo A → ❌ Error: "Ya tienes solicitud pendiente"
3. NO hay duplicado en BD ✅
```

### Caso 2: Enviar después de aceptación
```
1. Participante envía solicitud a Equipo A → ✅ Aceptada
2. Participante ya está en Equipo A
3. Intenta enviar otra → ❌ Error: "Ya estás en el equipo" ✅
```

### Caso 3: Enviar después de rechazo
```
1. Participante envía solicitud a Equipo A → ❌ Rechazada
2. Intenta enviar otra inmediatamente → ❌ Error: "Fue rechazada, espera"
3. Sistema previene spam ✅
```

---

## Commits Relacionados

- `d916302` - Add pending requests widget to leader dashboard
- `a44557c` - Add multiple request prevention and auto-rejection logic
- **`592acbd`** - fix: Improve request validation ✅ (ESTE)

---

## 🚀 Resultado

✅ **No hay más unique constraint violations**  
✅ **Mensajes de error descriptivos**  
✅ **Prevención de race conditions**  
✅ **Mejor UX para el usuario**  
✅ **BD protegida**

