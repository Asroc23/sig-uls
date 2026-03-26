# SweetAlert2 Visual Guide

## 🎨 Alert Appearance

### Success Toast (Auto-dismissing)
```
┌─────────────────────────────────────┐
│  ✓ ¡Éxito!                          │
│  Graduado creado exitosamente.      │
│  ══════════════════════════════════ │ (Progress bar)
└─────────────────────────────────────┘
   (Top-right corner)
   Auto-dismisses: 3 seconds
```

**When Appears:**
- After creating a graduate
- After updating a graduate
- After successfully deleting a graduate
- After creating a career
- After updating a career
- After successfully deleting a career

**Behavior:**
- Non-blocking (doesn't cover content)
- Can be clicked to dismiss instantly
- Shows countdown progress bar
- Automatically disappears

---

### Error Modal (Requires Confirmation)
```
╔═══════════════════════════════════╗
║            ⚠ Error               ║
║                                   ║
║  Error al procesar               ║
║  la solicitud.                   ║
║                                   ║
║         [  OK  ]                 ║
╚═══════════════════════════════════╝
   (Centered on screen)
```

**When Appears:**
- Validation errors
- Server processing errors
- Database constraint violations
- Authorization errors

**Behavior:**
- Blocks interaction (modal)
- Requires user confirmation
- Shows error icon and message
- Redirects on dismiss

---

### Delete Confirmation Dialog
```
╔═════════════════════════════════╗
║     ⚠ ¿Estás seguro?            ║
║                                 ║
║  Esta acción no se puede        ║
║  deshacer.                      ║
║                                 ║
║  [Sí, eliminar]  [Cancelar]     ║
╚═════════════════════════════════╝
   (Centered on screen)
```

**Triggers:**
- Click "Eliminar" button
- Click "Delete" button
- Delete form with `data-confirm-delete`

**Actions:**
- **Confirm:** Submits delete form → Record deleted → Success toast
- **Cancel:** Closes dialog → Nothing happens

**Styling:**
- Red confirm button (indicates danger)
- Gray cancel button (safe action)
- Warning icon
- Clear message in Spanish

---

## 📍 Layout Diagram

```
┌────────────────────────────────────────────────┐
│  Browser Window                                │
├────────────────────────────────────────────────┤
│                                                │
│  Success Toast (Top-Right)     ┌────────────┐ │
│                                │ ✓ ¡Éxito!  │ │
│                                └────────────┘ │
│  ┌────────────────────────────────────────┐  │
│  │  Graduate Management Page              │  │
│  │  ┌────────────────────────────────────┐│  │
│  │  │ [Name] [Email] [Career]  [Actions]││  │
│  │  │ Juan    juan@... Engineering  View ││  │
│  │  │ María   maria@.. Medicine     Edit ││  │
│  │  │                          Delete    ││  │
│  │  │                                    ││  │
│  │  │  Error Modal (Centered)           ││  │
│  │  │  ╔══════════════════════════════╗ ││  │
│  │  │  ║ ✗ Error                      ║ ││  │
│  │  │  ║ Could not delete             ║ ││  │
│  │  │  ║         [OK]                 ║ ││  │
│  │  │  ╚══════════════════════════════╝ ││  │
│  │  │                                    ││  │
│  │  └────────────────────────────────────┘│  │
│  │  Pagination ...                        │  │
│  └────────────────────────────────────────┘  │
│                                                │
└────────────────────────────────────────────────┘
```

---

## 🎬 User Interaction Flows

### Create Graduate Flow
```
┌─ User Action ──────────────────────────┐
│ 1. Click "Nuevo Graduado" button       │
└────────────────────────────────────────┘
                ↓
┌─ Form Interaction ─────────────────────┐
│ 2. Fill in graduate details            │
│ 3. Click "Guardar" button              │
└────────────────────────────────────────┘
                ↓
┌─ Server Processing ────────────────────┐
│ 4. Validate data                       │
│ 5. Create in database                  │
│ 6. Redirect with success message       │
└────────────────────────────────────────┘
                ↓
┌─ Client Response ──────────────────────┐
│ 7. Page loads                          │
│ 8. Layout detects session('success')   │
│ 9. JavaScript triggers SweetAlert      │
│ 10. Toast appears (top-right)          │
└────────────────────────────────────────┘
                ↓
┌─ User Sees Toast ──────────────────────┐
│ ┌──────────────────────────┐            │
│ │ ✓ ¡Éxito!               │            │
│ │ Graduado creado         │            │
│ │ exitosamente.           │            │
│ │ ══════════════════ (3s) │            │
│ └──────────────────────────┘            │
│ (auto-dismisses)                       │
└────────────────────────────────────────┘
```

---

### Delete Graduate Flow
```
┌─ User Action ──────────────────────────┐
│ 1. Click "Eliminar" button             │
└────────────────────────────────────────┘
                ↓
┌─ JavaScript Interception ──────────────┐
│ 2. Form submission prevented           │
│ 3. SweetAlert confirmation created     │
└────────────────────────────────────────┘
                ↓
┌─ Confirmation Dialog ──────────────────┐
│       ┌──────────────────┐             │
│       │ ⚠ ¿Estás seguro? │             │
│       │                  │             │
│       │ Esta acción...   │             │
│       │                  │             │
│       │ [Sí] [No]        │             │
│       └──────────────────┘             │
└────────────────────────────────────────┘
                ↓
        ┌───────┴────────┐
        ↓                ↓
   [Confirm]         [Cancel]
        ↓                ↓
   ┌─────┴──────┐    Returns
   │            │    to Page
   │ Processing │
   │            │
   └─────┬──────┘
        ↓
┌─ Server Processes ─────────────────────┐
│ 4. Validate authorization              │
│ 5. Delete from database                │
│ 6. Delete associated files (photo)     │
│ 7. Redirect with success message       │
└────────────────────────────────────────┘
        ↓
┌─ Success Toast ────────────────────────┐
│ ┌─────────────────────────────┐        │
│ │ ✓ Graduado eliminado        │        │
│ │ exitosamente.               │        │
│ │ ═══════════════════ (3s)    │        │
│ └─────────────────────────────┘        │
└────────────────────────────────────────┘
```

---

## 🌙 Dark Mode Comparison

### Light Mode
```
Toast                          Modal
┌──────────────────────┐      ╔═════════════════╗
│ ✓ ¡Éxito!            │      ║ ✗ Error         ║
│ Message here         │      ║                 ║
│ ────────────         │      ║ Error message   ║
└──────────────────────┘      ║                 ║
White bg, dark text           ║   [OK]          ║
                              ╚═════════════════╝
                              White bg, dark text
```

### Dark Mode
```
Toast                          Modal
┌──────────────────────┐      ╔═════════════════╗
│ ✓ ¡Éxito!            │      ║ ✗ Error         ║
│ Message here         │      ║                 ║
│ ────────────         │      ║ Error message   ║
└──────────────────────┘      ║                 ║
Dark bg, light text           ║   [OK]          ║
                              ╚═════════════════╝
                              Dark bg, light text
```

---

## 🎨 Color Reference

| Element | Light Mode | Dark Mode |
|---------|-----------|-----------|
| Background | #FFFFFF (white) | #1f2937 (dark gray) |
| Text | #111827 (dark gray) | #f3f4f6 (light gray) |
| Success Icon | Green (#10b981) | Green (#10b981) |
| Error Icon | Red (#ef4444) | Red (#ef4444) |
| Warning Icon | Orange (#f59e0b) | Orange (#f59e0b) |
| Confirm Button | Red (#ef4444) | Red (#ef4444) |
| Cancel Button | Gray (#6b7280) | Gray (#6b7280) |

---

## 📱 Responsive Behavior

### Desktop (≥1024px)
```
┌──────────────────────────────────────┐
│ Top Bar                              │
├──────────────────────────────────────┤
│           Success Toast              │
│           (Top-Right)                │
│                                      │
│ ┌────────────────────────────────┐  │
│ │ Content Area                   │  │
│ │                                │  │
│ │ Error Modal (Centered)         │  │
│ │ ╔════════════════════════════╗ │  │
│ │ ║ Error Message              ║ │  │
│ │ ║        [OK]                ║ │  │
│ │ ╚════════════════════════════╝ │  │
│ │                                │  │
│ └────────────────────────────────┘  │
└──────────────────────────────────────┘
```

### Mobile (< 768px)
```
┌──────────────────┐
│ Top Bar          │
├──────────────────┤
│                  │
│ Success Toast    │
│ (Smaller size)   │
│                  │
│ ┌──────────────┐ │
│ │ Content      │ │
│ │              │ │
│ │ Error Modal  │ │
│ │ ╔══════════╗ │ │
│ │ ║ Error    ║ │ │
│ │ ║ [OK]     ║ │ │
│ │ ╚══════════╝ │ │
│ │              │ │
│ └──────────────┘ │
└──────────────────┘
```

---

## ⏱️ Timing Reference

| Action | Duration | Effect |
|--------|----------|--------|
| Toast appears | Instant | Smooth fade-in |
| Toast visible | 3 seconds | Countdown shows |
| Toast dismisses | Smooth | Fade-out animation |
| Modal appears | Instant | Smooth popup |
| Modal stays | Until action | Persistent |
| Modal closes | Instant | Smooth fade-out |

---

## 🎯 Button Styling

### Confirm Button (Red - Dangerous)
```
Light Mode          Dark Mode
┌──────────┐       ┌──────────┐
│Sí, elim. │       │Sí, elim. │
│#ef4444   │       │#ef4444   │
└──────────┘       └──────────┘
Hover: Darker red
```

### Cancel Button (Gray - Safe)
```
Light Mode          Dark Mode
┌──────────┐       ┌──────────┐
│ Cancelar │       │ Cancelar │
│#6b7280   │       │#6b7280   │
└──────────┘       └──────────┘
Hover: Slightly darker gray
```

---

## 📊 Alert Statistics

**Alerts Implemented:**
- ✅ Success toasts: 3 seconds, auto-dismiss
- ✅ Error modals: Until dismissed
- ✅ Delete confirmations: Until user action
- ✅ Warning dialogs: Until user action

**Coverage:**
- ✅ Graduates CRUD: 100%
- ✅ Careers CRUD: 100%
- ✅ Dark mode: 100%
- ✅ Mobile: 100%

---

This visual guide helps understand how SweetAlert2 appears and behaves throughout the application.
