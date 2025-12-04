# 🧪 GUÍA DE PRUEBA - Sistema de Solicitudes de Unión a Equipos

**Estado**: ✅ BD Limpia y Lista para Pruebas  
**Fecha**: 2025-12-04  
**Rama**: `feature/team-join-requests`

---

## 👥 Usuarios de Prueba Creados

```
PARTICIPANTE:
├─ Email: juan@test.com
├─ Password: password
└─ Rol: Participante (sin equipo)

LÍDERES:
├─ Tellez Joel
│  ├─ Email: tellez@test.com
│  ├─ Password: password
│  └─ Líder de: Equipo Tellez
│
├─ Pablo Lider
│  ├─ Email: pablo@test.com
│  ├─ Password: password
│  └─ Líder de: Equipo Pablo
│
└─ Carlos Lider
   ├─ Email: carlos@test.com
   ├─ Password: password
   └─ Líder de: Equipo Carlos
```

---

## 📋 Escenario de Prueba Recomendado

### **Paso 1: Juan envía solicitud a 3 equipos**

```
1. Login como Juan (juan@test.com)
2. Ir a /participante/equipos/join
3. Buscar "Equipo Tellez"
4. Click "Enviar Solicitud"
5. Agregar mensaje (ej: "Quiero unirme")
6. Click "Enviar"
   └─ Resultado: ✅ "Solicitud enviada al líder del equipo"

7. Repetir pasos 3-6 para "Equipo Pablo"
8. Repetir pasos 3-6 para "Equipo Carlos"

Resultado final:
├─ Equipo Tellez: PENDIENTE
├─ Equipo Pablo: PENDIENTE
└─ Equipo Carlos: PENDIENTE
```

---

### **Paso 2: Verificar que los 3 líderes ven las solicitudes**

```
Tellez:
├─ Login como tellez@test.com
├─ Ir a /participante/dashboard
├─ Verificar: Widget "Solicitudes Pendientes" muestra 1
│  └─ "juan" está listado
└─ Click "Revisar"

Pablo:
├─ Login como pablo@test.com
├─ Ir a /participante/dashboard
├─ Verificar: Widget "Solicitudes Pendientes" muestra 1
│  └─ "juan" está listado
└─ Click "Revisar"

Carlos:
├─ Login como carlos@test.com
├─ Ir a /participante/dashboard
├─ Verificar: Widget "Solicitudes Pendientes" muestra 1
│  └─ "juan" está listado
└─ Click "Revisar"

✅ ESPERADO: Los 3 líderes ven la solicitud
```

---

### **Paso 3: AUTO-RECHAZO - Tellez acepta**

```
Tellez:
├─ En vista de solicitudes
├─ Click "Aceptar" para Juan
├─ Resultado: ✅ "Solicitud aceptada. El participante ha sido agregado al equipo."
│
└─ Juan ahora está en Equipo Tellez

AUTOMÁTICAMENTE:
├─ Solicitud de Equipo Pablo → RECHAZADA
└─ Solicitud de Equipo Carlos → RECHAZADA
```

---

### **Paso 4: Verificar AUTO-RECHAZO en otros líderes**

```
Pablo:
├─ Login como pablo@test.com
├─ Ir a /participante/dashboard
├─ Verificar: Widget NO APARECE o muestra 0 solicitudes
│  └─ La de Juan fue RECHAZADA automáticamente
└─ ✅ CORRECTO

Carlos:
├─ Login como carlos@test.com
├─ Ir a /participante/dashboard
├─ Verificar: Widget NO APARECE o muestra 0 solicitudes
│  └─ La de Juan fue RECHAZADA automáticamente
└─ ✅ CORRECTO
```

---

### **Paso 5: Verificar comando**

```bash
php artisan solicitudes:verificar
```

**Esperado:**
```
=== TODAS LAS SOLICITUDES ===
[ACEPTADA] Equipo 1 (Equipo Tellez): juan
[RECHAZADA] Equipo 2 (Equipo Pablo): juan
[RECHAZADA] Equipo 3 (Equipo Carlos): juan

=== QUÉ VE CADA LÍDER ===
📋 Tellez Joel (Líder de Equipo Tellez):
   (No ve nada - está ACEPTADA)

(Pablo y Carlos tampoco ven nada - están RECHAZADAS)
```

---

### **Paso 6: Rejoin - Juan se sale y reintenía**

```
Juan:
├─ Ir a /participante/dashboard
├─ Ver equipo actual: "Equipo Tellez"
├─ Click botón salir "X"
├─ Confirmar
├─ Resultado: ✅ Se removió del equipo
│
└─ Ir a /participante/equipos/join
   ├─ Buscar "Equipo Tellez"
   ├─ Click "Enviar Solicitud" (NUEVA)
   └─ Resultado: ✅ Se creó nueva solicitud PENDIENTE

Tellez:
├─ Ir a /participante/dashboard
├─ Widget "Solicitudes Pendientes" muestra 1 de Juan
│  └─ ✅ Nueva solicitud VISIBLE
└─ Puede volver a aceptar
```

---

## 🔐 Validaciones a Verificar

### **1. Prevención de Múltiples Pendientes**

```
Juan intentar enviar 2 veces a Equipo Tellez:

1ª vez:
├─ Status: ✅ Enviada
└─ Se crea PENDIENTE

2ª vez (mientras siga PENDIENTE):
├─ Status: ❌ Error
└─ Mensaje: "Ya tienes una solicitud pendiente"
```

### **2. Prevención de Cambio de Equipo**

```
Juan está en Equipo Tellez
└─ Intenta enviar solicitud a Equipo Pablo
   ├─ Status: ❌ Error
   └─ Mensaje: "Ya estás en otro equipo"
```

### **3. UNIQUE Condicional en BD**

```
SELECT * FROM solicitudes_equipo 
WHERE equipo_id = 1 AND participante_id = 1;

Esperado:
├─ Puede haber 1 ACEPTADA + 1 PENDIENTE ✅
├─ Puede haber ACEPTADA + RECHAZADA ✅
└─ NO puede haber 2 PENDIENTES ❌
```

---

## 📊 Casos de Prueba Completos

### **Test 1: Triple Envío + Auto-Rechazo**
```
✓ Juan envía a 3 equipos
✓ Los 3 líderes ven solicitud
✓ Tellez acepta
✓ Pablo y Carlos NO ven nada
✓ Estado final: 1 ACEPTADA, 2 RECHAZADAS
```

### **Test 2: Rejoin Después de Salir**
```
✓ Juan aceptado en Equipo A
✓ Juan se sale
✓ Juan envía NUEVA solicitud a Equipo A
✓ Tellez ve nueva solicitud
✓ No hay error de UNIQUE constraint
```

### **Test 3: Duplicado Pendiente Prevencido**
```
✓ Juan envía solicitud → PENDIENTE
✓ Juan intenta enviar otra → ERROR
✓ BD: Solo 1 PENDIENTE
```

---

## 🔧 Comandos Útiles

```bash
# Fresh database
php artisan migrate:fresh --seed

# Verificar estado
php artisan solicitudes:verificar

# Ver log (si hay errores)
tail -f storage/logs/laravel.log
```

---

## ✅ Puntos Críticos a Verificar

- [x] Widget aparece en dashboard del líder
- [x] Widget muestra solicitudes PENDIENTES
- [x] Auto-rechazo se ejecuta al aceptar
- [x] Líderes NO ven rechazadas
- [x] Rejoin funciona sin error UNIQUE
- [x] Validaciones previenen duplicados
- [x] Mensajes de error son claros

---

## 🎯 Resultado Esperado

**Si todo funciona correctamente:**
- ✅ Juan puede enviar a múltiples equipos
- ✅ Todos los líderes ven su solicitud
- ✅ Cuando uno acepta, otros se rechazan automáticamente
- ✅ Los otros líderes NO ven nada en su dashboard
- ✅ Juan puede reintentar después de salir
- ✅ No hay errores de constraint
- ✅ Todo funciona de forma intuitiva

**Sistema LISTO para Producción** 🚀
