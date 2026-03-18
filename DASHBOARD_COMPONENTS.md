# Dashboard Layout - Components & Files

## 📁 File Organization

### Main Layout Files
- **[resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)** (47 lines)
  - Main layout wrapper with flexbox structure
  - Includes sidebar and topbar
  - Dark mode initialization
  - Main content area with padding

- **[resources/views/layouts/sidebar.blade.php](resources/views/layouts/sidebar.blade.php)** (120 lines)
  - Responsive sidebar navigation
  - 5 main navigation items (Dashboard, Graduates, Careers, Reports, Emails)
  - Logo/branding section
  - User profile footer
  - Mobile hamburger toggle
  - Dark mode support

- **[resources/views/layouts/topbar.blade.php](resources/views/layouts/topbar.blade.php)** (105 lines)
  - Top navigation bar
  - Dark mode toggle with localStorage persistence
  - Language switcher (English/Spanish)
  - User dropdown menu
  - Profile link and logout
  - Dark mode styling

- **[resources/views/dashboard.blade.php](resources/views/dashboard.blade.php)** (180 lines)
  - Example dashboard with stats cards
  - Quick action buttons
  - Activity feed
  - Responsive grid layouts

## 🎨 Component Breakdown

### Sidebar Components

#### Logo Section
```html
<div class="h-16 flex items-center px-6 border-b">
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
        <!-- Branded logo with gradient background -->
    </a>
</div>
```

#### Navigation Links
Each link includes:
- SVG icon (w-5 h-5)
- Link text
- Active state styling with `request()->routeIs()`
- Hover effects with dark mode variants
- Transition animations

#### User Footer
```html
<div class="flex items-center space-x-3">
    <div class="w-10 h-10 bg-gradient-to-br rounded-full">
        {{ substr(Auth::user()->name, 0, 1) }}
    </div>
    <div class="flex-1 min-w-0">
        <!-- User name and email (truncated) -->
    </div>
</div>
```

### Topbar Components

#### Dark Mode Toggle
- Moon icon for light mode
- Sun icon for dark mode
- Uses Alpine.js `x-show` for conditional rendering
- Persists to localStorage
- Updates document class for Tailwind dark mode

#### Language Switcher
- Dropdown menu component
- English (🇬🇧) and Spanish (🇪🇸) options
- Click-outside handling with Alpine
- Animated transitions

#### User Dropdown
- Avatar with user initial
- Chevron down indicator
- Profile link
- Logout form
- Styled with borders and separators

### Dashboard Components

#### Stats Cards (4 variants)
```html
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-[color]">
    <!-- Title -->
    <!-- Large number -->
    <!-- Icon in colored circle -->
    <!-- Trend indicator -->
</div>
```

Colors: Blue, Purple, Amber, Green

#### Activity Card
- Vertical list with borders
- Status badges (New, Completed, Updated, Sent)
- Time stamps
- Hover effects

#### Quick Actions Card
- 4 colored button variations
- Icons and labels
- Responsive layout
- Hover state transitions

## 🎯 Responsive Breakpoints

### Mobile (default)
- Single column layouts
- Full-width components
- Visible hamburger menu for sidebar
- Stacked navigation

### Tablet (md: 768px)
- 2-column grids for cards
- Sidebar still hidden
- Improved spacing

### Desktop (lg: 1024px)
- 4-column grids for stats
- Persistent sidebar visible
- Hamburger menu hidden
- Full navigation layout

## 🌙 Dark Mode Classes

### Consistent Pattern
```blade
class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
```

### Color Mappings
- Background: `bg-white → dark:bg-gray-800`
- Secondary BG: `bg-gray-50 → dark:bg-gray-700`
- Text Primary: `text-gray-900 → dark:text-white`
- Text Secondary: `text-gray-600 → dark:text-gray-400`
- Borders: `border-gray-200 → dark:border-gray-700`

## ⚙️ Alpine.js Features

### 1. Dark Mode (topbar.blade.php)
```javascript
x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
@click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode);"
```

### 2. Language Dropdown (topbar.blade.php)
```javascript
x-data="{ open: false }"
@click="open = !open"
@click.outside="open = false"
```

### 3. User Dropdown (topbar.blade.php)
```javascript
x-data="{ open: false }"
@click="open = !open"
@click.outside="open = false"
```

### 4. Sidebar Toggle (sidebar.blade.php)
```javascript
x-data="{ sidebarOpen: true }"
:class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
@click="sidebarOpen = !sidebarOpen"
```

## 🎨 Tailwind Classes Used

### Layout
- `flex`, `flex-col`, `h-screen`, `overflow-hidden`
- `grid`, `grid-cols-1`, `md:grid-cols-2`, `lg:grid-cols-4`
- `gap-6`, `space-y-2`, `space-x-3`

### Sizing
- `w-5 h-5` (icons)
- `w-64` (sidebar width)
- `h-16` (navbar height)
- `px-4 py-2`, `px-6 py-3` (padding)

### Colors
- `bg-white`, `dark:bg-gray-800`
- `text-gray-900`, `dark:text-white`
- `border-gray-200`, `dark:border-gray-700`

### Effects
- `shadow`, `rounded-lg`
- `hover:bg-gray-50`, `hover:text-blue-700`
- `transition-colors`, `duration-300`
- `ease-in-out`

### Responsive
- `hidden lg:block`, `lg:hidden`
- `md:grid-cols-2`, `lg:grid-cols-4`
- `sm:px-6 lg:px-8`

## 📊 CSS Bundle Size

After build:
- `app-*.css`: 57.36 kB (9.76 kB gzipped)
- `app-*.js`: 83.51 kB (31.01 kB gzipped)

## 🔄 State Management

### Persisted State
- Dark mode preference (localStorage)

### Session State
- Sidebar open/closed (component level)
- Dropdown menus open/closed (component level)

### User Data
- Auth user name and email from `Auth::user()`
- Route checking with `request()->routeIs()`

## 📱 Mobile Behavior

### Sidebar
- Hidden on mobile by default
- Toggleable with hamburger button
- Overlay backdrop when open
- Smooth slide animation
- Closes on link click

### Navigation
- Responsive menu items
- Stacked on mobile
- Full width on desktop
- Touch-friendly tap targets

### Layout
- Single column on mobile
- Multi-column on desktop
- Proper padding at all sizes

## 🚀 Performance Optimizations

1. **CSS-in-Utility** - Tailwind purges unused classes
2. **SVG Icons** - Inline, no additional requests
3. **Local Storage** - Dark mode preference cached
4. **Minimal JavaScript** - Alpine.js lightweight
5. **Image Optimization** - Use `loading="lazy"` on images

## 🔧 Customization Points

### Easy to Change
- Navigation links in sidebar
- Logo/branding
- Color scheme (border-l-4 colors)
- User name/email display
- Dashboard stats cards

### Requires More Work
- Layout structure (sidebar position)
- Navbar position (top/bottom)
- Responsive breakpoints
- Component spacing

## 📖 Related Files

- [DASHBOARD_LAYOUT.md](DASHBOARD_LAYOUT.md) - Implementation summary
- [DASHBOARD_QUICK_REFERENCE.md](DASHBOARD_QUICK_REFERENCE.md) - Usage guide
- [tailwind.config.js](tailwind.config.js) - Tailwind configuration
- [resources/js/app.js](resources/js/app.js) - JavaScript entry point
- [resources/css/app.css](resources/css/app.css) - CSS entry point
