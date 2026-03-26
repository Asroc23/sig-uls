# SweetAlert2 - Quick Reference for Developers

## 🎯 Quick Start

SweetAlert2 is globally available in all views. No additional imports needed.

## 📋 Common Use Cases

### 1. Show a Simple Alert (in JavaScript)
```javascript
Swal.fire('Title', 'Message', 'success');
```

### 2. Delete Confirmation (in Blade)
```blade
<form action="{{ route('model.destroy', $id) }}" method="POST" data-confirm-delete>
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

### 3. Success Message After Redirect (in Controller)
```php
return redirect()->route('list')
    ->with('success', 'Action completed successfully');
```

### 4. Error Message (in Controller)
```php
return redirect()->back()
    ->with('error', 'Something went wrong');
```

## 🎨 Alert Types

| Type | Icon | Use Case |
|------|------|----------|
| success | ✓ | Create/Update/Delete completed |
| error | ✗ | Validation/Processing errors |
| warning | ⚠️ | Dangerous actions (delete) |
| info | ℹ️ | Informational messages |
| question | ❓ | Yes/No questions |

## 🔧 Configuration Options

### Success Toast (Auto-dismiss)
```javascript
Swal.fire({
    background: isDarkMode() ? '#1f2937' : '#ffffff',
    color: isDarkMode() ? '#f3f4f6' : '#111827',
    title: 'Success',
    text: 'Message',
    icon: 'success',
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});
```

### Error Modal (Requires Confirmation)
```javascript
Swal.fire({
    background: isDarkMode() ? '#1f2937' : '#ffffff',
    color: isDarkMode() ? '#f3f4f6' : '#111827',
    title: 'Error',
    text: 'Error message',
    icon: 'error',
    position: 'center',
    confirmButtonColor: '#ef4444',
});
```

### Confirmation Dialog
```javascript
Swal.fire({
    background: isDarkMode() ? '#1f2937' : '#ffffff',
    color: isDarkMode() ? '#f3f4f6' : '#111827',
    title: 'Confirm?',
    text: 'Are you sure?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel',
}).then((result) => {
    if (result.isConfirmed) {
        // Do something
    }
});
```

## 📍 Positions

- `top-end`: Top-right (default for toasts)
- `center`: Center screen (default for modals)
- `top`: Top center
- `bottom`: Bottom center

## 🎯 Implementation Patterns

### Pattern 1: Session-Based Success
```php
// Controller
return redirect()->route('list')
    ->with('success', 'Item created successfully');

// Layout automatically handles display
```

### Pattern 2: Form Deletion
```blade
<!-- View -->
<form action="{{ route('item.destroy', $item) }}" method="POST" data-confirm-delete>
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>

<!-- Layout script automatically handles -->
```

### Pattern 3: Custom JavaScript Alert
```blade
<script>
    // When you need custom behavior
    Swal.fire({
        ...getAlertConfig(), // Include this for dark mode support
        title: 'Title',
        text: 'Message',
        icon: 'info',
    });
</script>
```

## 🔍 Dark Mode

Always use `getAlertConfig()` helper to get proper colors:

```javascript
const isDarkMode = () => document.documentElement.classList.contains('dark');

const getAlertConfig = () => ({
    background: isDarkMode() ? '#1f2937' : '#ffffff',
    color: isDarkMode() ? '#f3f4f6' : '#111827',
});

// Use it:
Swal.fire({
    ...getAlertConfig(),
    title: 'Title',
    text: 'Message',
    icon: 'success',
});
```

## 📱 Mobile Best Practices

- Use `toast: true` for notifications (doesn't block content)
- Use `position: 'center'` for important confirmations
- Ensure text is clear and concise
- Button text should be action-oriented

## ✅ Testing Tips

Alerts don't appear in automated tests by default since they're JavaScript-based.

For frontend testing with Cypress:
```javascript
// Wait for alert to appear
cy.get('.swal2-title').should('contain', 'Success');
cy.get('.swal2-confirm').click(); // Click confirm button
```

## 🚫 Avoid

- Don't use alerts for every action (too noisy)
- Don't use complex HTML in messages (keep it simple)
- Don't forget dark mode support
- Don't use old `confirm()` or `alert()` methods

## 📚 Examples in Codebase

### Delete Confirmation
- `resources/views/graduates/index.blade.php`
- `resources/views/graduates/show.blade.php`
- `resources/views/careers/index.blade.php`

### Success Messages
- `app/Http/Controllers/GraduateController.php`
- `app/Http/Controllers/CareerController.php`

## 🔗 Resources

- [SweetAlert2 Docs](https://sweetalert2.github.io/)
- [Live Demo](https://sweetalert2.github.io/#examples)
- [GitHub Issues](https://github.com/sweetalert2/sweetalert2/issues)

## 📝 Checklist for New Features

When implementing delete for a new model:

- [ ] Add `data-confirm-delete` to delete form
- [ ] Remove old `onsubmit="return confirm()"` 
- [ ] Test form submission with confirmation
- [ ] Test cancel action (doesn't delete)
- [ ] Verify success message appears
- [ ] Test in dark mode
- [ ] Test on mobile

---

**Last Updated:** 2026-03-23
**Version:** 1.0
