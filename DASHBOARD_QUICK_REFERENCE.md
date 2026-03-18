# Dashboard Layout - Quick Reference Guide

## Quick Start

To use the new admin dashboard layout on any page:

```blade
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold">Page Title</h1>
        <p class="text-gray-600 dark:text-gray-400">Subtitle or description</p>
    </x-slot>

    <!-- Your page content here -->
</x-app-layout>
```

## Layout Structure

```
┌─────────────────────────────────────────┐
│           TOP NAVBAR                    │
│ (Dark Mode | Language | User Dropdown)  │
├──────────────┬──────────────────────────┤
│              │                          │
│   SIDEBAR    │    MAIN CONTENT          │
│   (Nav)      │    (Your Page Content)   │
│              │                          │
└──────────────┴──────────────────────────┘
```

## Key Components

### Header Slot
Place page title and actions here:
```blade
<x-slot name="header">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">Title</h1>
            <p class="text-gray-600 mt-1">Subtitle</p>
        </div>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            + Add Item
        </button>
    </div>
</x-slot>
```

### Content Section
Main page content automatically gets padding:
```blade
<!-- Automatically padded with py-6 px-4 sm:px-6 lg:px-8 -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Your content -->
</div>
```

## Dark Mode

### For Users
- Click the moon/sun icon in the top navbar to toggle dark mode
- Preference is saved in browser localStorage

### For Developers
Use dark mode classes in Tailwind:
```blade
<!-- Light mode styling / Dark mode styling -->
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
    Content
</div>
```

## Navigation Links

### To Add New Navigation Items
Edit `resources/views/layouts/sidebar.blade.php`:

```blade
<a href="{{ route('your-route') }}" 
   :class="request()->routeIs('your-route') ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- SVG path here -->
    </svg>
    <span class="font-medium">Your Link Name</span>
</a>
```

## Common UI Patterns

### Stats Card
```blade
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Label</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">1,234</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400"><!-- icon --></svg>
        </div>
    </div>
</div>
```

### Card with List
```blade
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Title</h3>
    <div class="space-y-4">
        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">Item</p>
            <span class="text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2.5 py-0.5 rounded-full">Badge</span>
        </div>
    </div>
</div>
```

### Form Input
```blade
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Label
    </label>
    <input type="text" 
           placeholder="Placeholder..."
           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
</div>
```

### Button Variations

**Primary Button:**
```blade
<button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
    Button Text
</button>
```

**Secondary Button:**
```blade
<button class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700">
    Button Text
</button>
```

**Danger Button:**
```blade
<button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
    Delete
</button>
```

## Color System

### Primary Colors
- Blue: `blue-500`, `blue-600`, `blue-700`

### Semantic Colors
- Success: `green-100/600`
- Warning: `amber-100/600`
- Error: `red-100/600`
- Info: `blue-100/600`

### Backgrounds
- Light: `bg-white dark:bg-gray-800`
- Light Alt: `bg-gray-50 dark:bg-gray-700`
- Dark: `bg-gray-900 dark:bg-black`

### Text
- Primary: `text-gray-900 dark:text-white`
- Secondary: `text-gray-600 dark:text-gray-400`
- Tertiary: `text-gray-500 dark:text-gray-500`

## Responsive Classes

- Mobile first: `sm:` (640px), `md:` (768px), `lg:` (1024px), `xl:` (1280px)
- Example: `grid-cols-1 md:grid-cols-2 lg:grid-cols-3`

## Alpine.js Features

### Dark Mode Toggle
```javascript
// Automatically handled in topbar
darkMode = !darkMode;
localStorage.setItem('darkMode', darkMode);
document.documentElement.classList.toggle('dark');
```

### Sidebar Toggle (Mobile)
```javascript
// Automatically handled in sidebar
sidebarOpen = !sidebarOpen;
```

### Dropdowns
```blade
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" @click.outside="open = false">Content</div>
</div>
```

## Browser Compatibility

- All modern browsers (Chrome, Firefox, Safari, Edge)
- Requires CSS Grid and Flexbox support
- Dark mode requires CSS class support
- Alpine.js 3.15.8+

## Performance Tips

1. **Use Blade Components** - Create reusable components for cards, buttons, etc.
2. **Lazy Load Images** - Use `loading="lazy"` on images
3. **Optimize Icons** - Use inline SVGs for better performance
4. **Minimize Requests** - Bundle assets with Vite
5. **Cache Dark Mode** - Already handled with localStorage

## Troubleshooting

### Dark Mode Not Working
1. Run `npm run build`
2. Hard refresh browser (Ctrl+Shift+R)
3. Check localStorage for `darkMode` key

### Sidebar Not Showing
1. Ensure you're viewing on desktop (lg: breakpoint)
2. Check that Alpine.js is loaded
3. Verify `sidebarOpen` state in Alpine

### Styles Not Applying
1. Run `npm run build` or `npm run dev`
2. Clear browser cache
3. Check file is in `content` array in `tailwind.config.js`

## File Structure
```
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php       ← Main layout
│   │   ├── sidebar.blade.php    ← Navigation sidebar
│   │   ├── topbar.blade.php     ← Top navbar
│   │   └── navigation.blade.php (legacy)
│   └── dashboard.blade.php       ← Example dashboard
├── css/
│   └── app.css
└── js/
    ├── app.js
    └── bootstrap.js
```

## Support

For issues or customization help:
1. Check the [DASHBOARD_LAYOUT.md](DASHBOARD_LAYOUT.md) file
2. Review example pages in `resources/views/examples/`
3. Check Tailwind docs: https://tailwindcss.com
4. Check Alpine docs: https://alpinejs.dev
