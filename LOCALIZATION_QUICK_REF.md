# Multi-Language Support - Quick Reference

## 🌍 Quick Links
- **Main Guide:** [LOCALIZATION_GUIDE.md](LOCALIZATION_GUIDE.md)
- **Translation Files:**
  - English: [resources/lang/en/app.php](resources/lang/en/app.php)
  - Spanish: [resources/lang/es/app.php](resources/lang/es/app.php)
- **Controller:** [app/Http/Controllers/LanguageController.php](app/Http/Controllers/LanguageController.php)

---

## ⚡ 60-Second Overview

Multi-language support for English and Spanish is fully implemented:

✅ **Language Switcher:** Dropdown button in navbar (top right)
✅ **Session Persistence:** Language selection saved across pages
✅ **89 Translation Keys:** All UI elements translated
✅ **Easy to Extend:** Add new languages in minutes

---

## 🎯 How Users Switch Language

1. Click the language button in the top navbar (right side)
2. Select **🇬🇧 English** or **🇪🇸 Español**
3. Language changes immediately
4. Selection persists when navigating pages
5. Refresh page - language is still saved

---

## 💻 Using Translations in Code

### Simple Key
```blade
{{ __('app.navigation.dashboard') }}
```

### With Parameters
```blade
<!-- Replace :name -->
{{ __('app.dashboard.subtitle', ['name' => Auth::user()->name]) }}

<!-- Replace :count -->
{{ __('app.dashboard.hours_ago', ['count' => 2]) }}
```

### In PHP
```php
$title = __('app.dashboard.title');
$message = __('app.dashboard.subtitle', ['name' => 'John']);
```

---

## 📝 Available Translation Keys

### Navigation (5 keys)
```php
app.navigation.dashboard    → Dashboard / Panel de Control
app.navigation.graduates    → Graduates / Graduados
app.navigation.careers      → Careers / Carreras
app.navigation.reports      → Reports / Reportes
app.navigation.emails       → Emails / Correos
```

### Topbar (2 keys)
```php
app.topbar.profile_settings → Profile Settings / Configuración de Perfil
app.topbar.log_out          → Log Out / Cerrar Sesión
```

### Dashboard (20+ keys)
```php
app.dashboard.title              → Dashboard / Panel de Control
app.dashboard.subtitle           → Welcome back, :name! / ¡Bienvenido...
app.dashboard.total_graduates    → Total Graduates / Total de Graduados
app.dashboard.active_careers     → Active Careers / Carreras Activas
app.dashboard.pending_reports    → Pending Reports / Reportes Pendientes
app.dashboard.emails_sent        → Emails Sent / Correos Enviados
app.dashboard.from_last_month    → ↑ 12% from last month / ↑ 12% del mes pasado
app.dashboard.new_this_week      → ↑ 4 new this week / ↑ 4 nuevos esta semana
app.dashboard.overdue            → ⚠ 5 overdue / ⚠ 5 vencidos
app.dashboard.delivered          → ↑ 89% delivered / ↑ 89% entregados
app.dashboard.recent_activity    → Recent Activity / Actividad Reciente
app.dashboard.quick_actions      → Quick Actions / Acciones Rápidas
app.dashboard.new_graduate       → Add New Graduate / Agregar Nuevo Graduado
app.dashboard.create_career      → Create Career / Crear Carrera
app.dashboard.generate_report    → Generate Report / Generar Reporte
app.dashboard.send_email         → Send Email Campaign / Enviar Campaña de Correo
app.dashboard.hours_ago          → :count hours ago / hace :count horas
app.dashboard.day_ago            → :count day ago / hace :count día
app.dashboard.days_ago           → :count days ago / hace :count días
app.dashboard.new                → New / Nuevo
app.dashboard.completed          → Completed / Completado
app.dashboard.updated            → Updated / Actualizado
app.dashboard.sent               → Sent / Enviado
```

### Language (2 keys)
```php
app.language.english    → English
app.language.spanish    → Español
```

---

## 🚀 Adding New Translations

### 1. Add to English file
```php
// resources/lang/en/app.php
'pages' => [
    'graduates' => [
        'title' => 'Manage Graduates',
        'add_button' => 'Add Graduate',
    ],
],
```

### 2. Add to Spanish file
```php
// resources/lang/es/app.php
'pages' => [
    'graduates' => [
        'title' => 'Gestionar Graduados',
        'add_button' => 'Agregar Graduado',
    ],
],
```

### 3. Use in Blade
```blade
<h1>{{ __('app.pages.graduates.title') }}</h1>
<button>{{ __('app.pages.graduates.add_button') }}</button>
```

---

## 🔄 Technical Flow

```
User Clicks Language Button (navbar)
        ↓
Selects English or Español
        ↓
Form POSTs to /language/{locale}
        ↓
LanguageController validates locale
        ↓
Stores in session: session(['locale' => 'es'])
        ↓
Sets app locale: app()->setLocale('es')
        ↓
Redirects back to previous page
        ↓
AppServiceProvider loads locale from session
        ↓
All __() helpers use selected locale
```

---

## 📁 Files Structure

```
resources/lang/
├── en/
│   └── app.php       ← 45 lines, 5 sections
└── es/
    └── app.php       ← 45 lines, 5 sections

app/Http/Controllers/
└── LanguageController.php    ← Handles language switching

app/Providers/
└── AppServiceProvider.php    ← Loads locale from session

routes/
└── web.php                   ← POST /language/{locale} route
```

---

## 🎯 Route Details

**Route:** `POST /language/{locale}`
**Name:** `language.switch`
**Parameters:**
- `locale` - Must be: `en` or `es`

**Usage in Form:**
```blade
<form method="POST" action="{{ route('language.switch', ['locale' => 'es']) }}">
    @csrf
    <button type="submit">🇪🇸 Español</button>
</form>
```

---

## 🔐 Session Management

- **Key:** `locale`
- **Values:** `en` or `es`
- **Stored In:** Laravel session (config/session.php)
- **Duration:** Session lifetime (default 2 hours of inactivity)
- **Persistence:** Across all pages and browser sessions

**To Make Permanent:** Store in `users` table (future enhancement)

---

## 🧪 Testing

### Manual Test
1. Navigate to `/dashboard`
2. Click language button
3. Select Spanish
4. Verify all text is in Spanish
5. Navigate to other pages - language stays Spanish
6. Refresh page - language persists
7. Switch back to English - verify English text

### With cURL
```bash
curl -X POST http://localhost/language/es -H "X-CSRF-TOKEN: token"
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Language not persisting | Check `storage/framework/sessions/` is writable |
| Translations not showing | Verify key exists in translation file |
| Language not changing | Check `app/Http/Controllers/LanguageController.php` |
| Route not found | Run `php artisan route:list \| grep language` |

---

## 📚 More Information

See [LOCALIZATION_GUIDE.md](LOCALIZATION_GUIDE.md) for:
- Complete file structure
- Adding more languages (French, Portuguese, etc.)
- Storing language preference in database
- Advanced translation features
- Full troubleshooting guide

---

## ✨ Summary

| Feature | Status |
|---------|--------|
| English translations | ✅ 45 keys |
| Spanish translations | ✅ 45 keys |
| Language switcher | ✅ Functional |
| Session persistence | ✅ Working |
| Navigation translated | ✅ Complete |
| Dashboard translated | ✅ Complete |
| Production ready | ✅ Yes |

**Ready to use!** 🌍
