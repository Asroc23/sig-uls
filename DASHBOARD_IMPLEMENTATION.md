# Graduate Dashboard with Chart.js - Implementation Guide

## Overview

A fully functional, dynamic dashboard for the Graduate system featuring real-time statistics with interactive Chart.js visualizations, filterable data, and responsive design.

**Status:** ✅ Complete & Tested (40/40 tests passing)

---

## Features Implemented

### 1. Backend API Endpoints

Three RESTful API endpoints providing graduate statistics with GROUP BY queries:

#### `/api/graduates/by-year`
- **Method:** GET
- **Query Parameters:** `gender`, `career_id`
- **Response:** `{ labels: [2023, 2024, ...], data: [count, count, ...] }`
- **Logic:** Groups graduates by graduation_year, counts per year

#### `/api/graduates/by-gender`
- **Method:** GET
- **Query Parameters:** `graduation_year`, `career_id`
- **Response:** `{ labels: ["Masculino", "Femenino"], data: [count, count] }`
- **Logic:** Groups graduates by gender with Spanish labels

#### `/api/graduates/by-career`
- **Method:** GET
- **Query Parameters:** `graduation_year`, `gender`
- **Response:** `{ labels: [career_name, ...], data: [count, ...] }`
- **Logic:** Groups graduates by career name, joins with careers table

**Authentication:** All endpoints require session authentication (`auth` middleware)

---

## File Structure

### Backend Files

**Controller:** [app/Http/Controllers/Api/GraduateStatisticsController.php](app/Http/Controllers/Api/GraduateStatisticsController.php)
- `byYear()` - Returns graduates grouped by graduation year
- `byGender()` - Returns graduates grouped by gender
- `byCareer()` - Returns graduates grouped by career

**Dashboard Controller:** [app/Http/Controllers/GraduateDashboardController.php](app/Http/Controllers/GraduateDashboardController.php)
- Invokable controller that loads all careers for filter dropdowns
- Returns dashboard view with $careers data

**Routes:**
- [routes/api.php](routes/api.php) - API route definitions
- [routes/web.php](routes/web.php) - Dashboard route at `/graduates-dashboard`

### Frontend Files

**View:** [resources/views/graduates/dashboard.blade.php](resources/views/graduates/dashboard.blade.php)
- Responsive grid layout with filters and charts
- Chart.js library loaded from CDN (v4.4.0)
- Real-time filtering with AJAX
- Dark mode support

### Test Files

**API Tests:** [tests/Feature/Api/GraduateStatisticsTest.php](tests/Feature/Api/GraduateStatisticsTest.php)
- 7 comprehensive tests covering all endpoints
- Filter validation tests
- Authentication requirement test
- All tests passing ✅

---

## Dashboard UI Components

### Filters Section
Three select dropdowns allowing dynamic data filtering:
1. **Graduation Year** - All years from 2000 to current year
2. **Career** - All available careers (loaded from database)
3. **Gender** - Masculino / Femenino

### Charts
Three interactive Chart.js visualizations:

1. **Graduates by Year (Bar Chart)**
   - X-axis: Graduation years
   - Y-axis: Number of graduates
   - Color: Primary blue (#3b82f6)
   - Responsive with hover effects

2. **Graduates by Gender (Pie Chart)**
   - Segments for Masculino/Femenino
   - Color-coded (blue/pink)
   - Legend with percentages
   - Centered layout

3. **Graduates by Career (Horizontal Bar Chart)**
   - Y-axis: Career names
   - X-axis: Number of graduates
   - Multi-colored bars
   - Sorted by count (descending)

---

## Frontend Technology

### Chart.js Integration

```javascript
// Loaded from CDN
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

// Features Used:
- Bar Charts (vertical & horizontal)
- Pie Charts
- Responsive containers
- Dark mode color adaptation
- Real-time chart updates/destruction
```

### AJAX Data Fetching

```javascript
// Uses native Fetch API
async function fetchChartData(url, filters) {
    const queryString = new URLSearchParams(filters).toString();
    const response = await fetch(`${url}?${queryString}`);
    return response.json();
}
```

### Event Listeners

- Filter change events trigger chart updates
- Dark mode observer watches for class changes
- All updates happen without page reload

---

## API Query Examples

### Get graduates by year (all data)
```
GET /api/graduates/by-year
Response: { labels: ["2023", "2024"], data: [5, 3] }
```

### Get graduates by year, filtered by gender
```
GET /api/graduates/by-year?gender=male
Response: { labels: ["2023", "2024"], data: [3, 2] }
```

### Get graduates by career, filtered by year and gender
```
GET /api/graduates/by-career?graduation_year=2023&gender=female
Response: { labels: ["Carrera A", "Carrera B"], data: [2, 1] }
```

---

## Database Queries

### By Year Query
```php
$query = Graduate::query()
    ->select('graduation_year')
    ->selectRaw('count(*) as total')
    ->groupBy('graduation_year')
    ->orderBy('graduation_year');
// Optional filters applied with where()
```

### By Gender Query
```php
$query = Graduate::query()
    ->select('gender')
    ->selectRaw('count(*) as total')
    ->groupBy('gender');
// Gender labels translated (male → Masculino, female → Femenino)
```

### By Career Query
```php
$query = Graduate::query()
    ->join('careers', 'graduates.career_id', '=', 'careers.id')
    ->select('careers.name')
    ->selectRaw('count(graduates.id) as total')
    ->groupBy('careers.id', 'careers.name')
    ->orderBy('total', 'desc');
```

---

## Responsive Design

### Grid Layout
- **Mobile (< 768px):** Single column
- **Tablet/Desktop (≥ 768px):** 2-column grid for year & gender charts
- **Full Width:** Career chart spans all columns

### Tailwind CSS Classes
- Dark mode support with `dark:` variants
- Responsive spacing and padding
- Rounded corners and shadows
- Smooth transitions and hover effects

---

## Navigation Integration

**Sidebar Update:** Added link to dashboard in [resources/views/layouts/sidebar.blade.php](resources/views/layouts/sidebar.blade.php)
- Route: `graduates.dashboard` → `/graduates-dashboard`
- Icon: Bar chart icon (Chart/Statistics)
- Label: "Dashboard"
- Active state highlighting

---

## Test Coverage

### API Statistics Tests (7 tests, 29 assertions)

```
✓ it returns graduates by year
✓ it returns graduates by gender
✓ it returns graduates by career
✓ it filters graduates by year
✓ it filters graduates by gender
✓ it filters graduates by career
✓ it requires authentication to access statistics
```

### All Tests Status
```
Tests:    40 passed (107 assertions)
Duration: 1.75s
```

---

## Security Features

1. **Authentication Required:**
   - All API endpoints protected with `auth` middleware
   - Session-based authentication
   - Redirects unauthenticated users to login

2. **Authorization:**
   - Users must be logged in to access dashboard
   - Data returned reflects all graduates (no user-specific filtering)

3. **Input Validation:**
   - Query parameters validated using `filled()` method
   - Database query injection prevented by Eloquent

---

## Dark Mode Support

The dashboard automatically adapts to dark mode:

```javascript
function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

// Observer watches for dark mode changes
observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
});

// Charts re-render when dark mode toggles
```

---

## Usage Instructions

### Accessing the Dashboard
1. Navigate to `/graduates-dashboard` (requires authentication)
2. Or click "Dashboard" in sidebar under Graduates section

### Filtering Data
1. Select filters from dropdown menus
2. Charts update automatically via AJAX
3. Multiple filters work together (AND logic)

### Interpreting Charts
- **By Year:** Trend of graduates over time
- **By Gender:** Gender distribution of graduates
- **By Career:** Which careers have most graduates

---

## Performance Considerations

### Query Optimization
- Uses `selectRaw()` for efficient aggregation
- `groupBy()` performed at database level
- Joins only when necessary (by-career query)

### Frontend Optimization
- Chart.js library lazy loaded from CDN
- Destroy & recreate charts on filter change (lightweight)
- Minimal DOM manipulation
- Efficient event delegation

### Data Transfer
- JSON responses are lightweight (labels + data arrays)
- No unnecessary fields returned
- Response size typically < 1KB per request

---

## Troubleshooting

### Charts Not Displaying
1. Verify `/api/graduates/*` endpoints return JSON
2. Check browser console for fetch errors
3. Ensure user is authenticated (check login status)

### Filters Not Working
1. Verify query parameters are being passed correctly
2. Check API response format: `{ labels: [], data: [] }`
3. Inspect network tab for API requests

### Dark Mode Not Working
1. Check that `dark` class is applied to `<html>` element
2. Verify CSS is loaded correctly
3. Try refreshing page

---

## Future Enhancements

Possible improvements for future iterations:

1. **Additional Charts:**
   - Graduates over time (line chart)
   - Age distribution (histogram)
   - Education level analysis

2. **Data Export:**
   - Download charts as images
   - Export data to CSV/Excel
   - Generate PDF reports

3. **Advanced Filtering:**
   - Date range picker
   - Multiple career selection
   - Custom metrics

4. **Performance:**
   - Cache frequently accessed data
   - Implement pagination for large datasets
   - Add loading indicators for slow connections

---

## Deployment Checklist

- ✅ API routes configured with auth middleware
- ✅ Dashboard controller created
- ✅ Dashboard view created and styled
- ✅ Chart.js library loaded
- ✅ Sidebar navigation updated
- ✅ All 40 tests passing
- ✅ Code formatted with Pint (PSR-12)
- ✅ Dark mode support implemented
- ✅ Responsive design verified
- ✅ Database queries optimized

---

## Code Quality Metrics

- **Test Coverage:** 7 new API tests
- **Code Style:** PSR-12 compliant (Pint formatted)
- **Type Hints:** All methods properly typed
- **Documentation:** Comprehensive inline comments
- **Error Handling:** Robust query filtering

---

**Documentation Created:** March 18, 2026
**Last Updated:** March 18, 2026
**Status:** Production Ready ✅
