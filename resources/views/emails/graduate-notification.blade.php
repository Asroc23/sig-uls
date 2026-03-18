@component('mail::message')
# Mensaje de SIG - Sistema de Información de Graduados

Estimado/a {{ $graduate->first_name }} {{ $graduate->last_name }},

{{ str_replace("\n", "\n\n", $message) }}

---

**Información de Contacto:**
- Email: {{ $graduate->email }}
- Carrera: {{ $graduate->career->name ?? 'N/A' }}
- Año de Graduación: {{ $graduate->graduation_year }}

Agradecemos tu participación en nuestra comunidad de graduados.

@component('mail::button', ['url' => route('dashboard')])
Ir al Sistema
@endcomponent

Saludos cordiales,
**Sistema de Información de Graduados**
@endcomponent
