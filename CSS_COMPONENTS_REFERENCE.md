# Admin Panel CSS Components Reference

## 📊 Dashboard Components

### Stat Cards
```html
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3>42</h3>
            <p>Toplam Başvuru</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check"></i>
        </div>
        <div class="stat-info">
            <h3>12</h3>
            <p>Onaylanan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3>8</h3>
            <p>Bekleyen</p>
        </div>
    </div>
</div>
```

**Icon Variants:**
- Default (primary blue gradient)
- `.success` (green gradient)
- `.warning` (orange gradient)
- `.info` (blue gradient)

## 🗂️ Cards

### Basic Card
```html
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
        <button class="btn btn-primary btn-sm">Action</button>
    </div>
    <div class="card-body">
        <!-- Content -->
    </div>
    <div class="card-footer">
        <!-- Footer content -->
    </div>
</div>
```

## 📋 Tables

### Standard Table
```html
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <td>
                    <button class="btn btn-sm btn-info">Edit</button>
                    <button class="btn btn-sm btn-danger">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### Small Table
```html
<table class="table table-sm">
    <!-- Same structure, smaller padding -->
</table>
```

## 🔘 Buttons

### All Button Variants
```html
<!-- Primary (gradient blue) -->
<button class="btn btn-primary">Primary</button>

<!-- Secondary (gray) -->
<button class="btn btn-secondary">Secondary</button>

<!-- Success (green) -->
<button class="btn btn-success">Success</button>

<!-- Danger (red) -->
<button class="btn btn-danger">Danger</button>

<!-- Warning (orange) -->
<button class="btn btn-warning">Warning</button>

<!-- Info (blue) -->
<button class="btn btn-info">Info</button>

<!-- Sizes -->
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Default</button>
<button class="btn btn-primary btn-lg">Large</button>

<!-- With Icons -->
<button class="btn btn-primary">
    <i class="fas fa-plus"></i> Add New
</button>
```

### Filter Buttons
```html
<div class="filter-buttons">
    <a href="?filter=all" class="btn btn-sm btn-primary">All</a>
    <a href="?filter=active" class="btn btn-sm btn-secondary">Active</a>
    <a href="?filter=inactive" class="btn btn-sm btn-secondary">Inactive</a>
</div>
```

## 🏷️ Badges

```html
<!-- All badge colors -->
<span class="badge badge-primary">Primary</span>
<span class="badge badge-secondary">Secondary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-info">Info</span>

<!-- In tables -->
<td>
    <span class="badge badge-success">Active</span>
</td>
```

## 📝 Forms

### Complete Form Example
```html
<form>
    <!-- Text Input -->
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter name">
        <small class="form-text text-muted">Helper text here</small>
    </div>
    
    <!-- Textarea -->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
    </div>
    
    <!-- Select -->
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="">Select...</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    
    <!-- Small Select -->
    <select class="select-sm">
        <option>Option 1</option>
        <option>Option 2</option>
    </select>
    
    <!-- Two-column layout -->
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="first">First Column</label>
            <input type="text" id="first" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="second">Second Column</label>
            <input type="text" id="second" class="form-control">
        </div>
    </div>
    
    <!-- Checkbox -->
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="active" name="active">
        <label class="custom-control-label" for="active">Is Active</label>
    </div>
    
    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

### Filter Form (Date Range)
```html
<form method="GET" class="filter-form">
    <input type="date" name="start_date" value="2025-01-01">
    <input type="date" name="end_date" value="2025-12-31">
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
</form>
```

## 🪟 Modals

### Complete Modal Example
```html
<!-- Modal Trigger -->
<button class="btn btn-primary" onclick="showModal()">
    <i class="fas fa-plus"></i> Add New
</button>

<!-- Modal Structure -->
<div class="modal" id="myModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Modal Title</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        
        <form>
            <div class="form-group">
                <label for="field">Field</label>
                <input type="text" id="field" class="form-control">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
function showModal() {
    document.getElementById('myModal').classList.add('active');
}

function closeModal() {
    document.getElementById('myModal').classList.remove('active');
}
</script>
```

### Large Modal
```html
<div class="modal" id="largeModal">
    <div class="modal-content modal-lg">
        <!-- Content -->
    </div>
</div>
```

## 🚨 Alerts

```html
<!-- Success Alert -->
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    Operation completed successfully!
</div>

<!-- Danger Alert -->
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    An error occurred!
</div>

<!-- Info Alert -->
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    Here's some information.
</div>
```

## 📐 Layout Components

### Admin Header
```html
<div class="admin-header">
    <h1><i class="fas fa-dashboard"></i> Page Title</h1>
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New
    </button>
</div>
```

### Admin Content Wrapper
```html
<div class="admin-content">
    <!-- Your content here -->
</div>
```

### Dashboard Two-Column Layout
```html
<div class="dashboard-row">
    <div class="dashboard-col">
        <!-- Left column -->
        <div class="card">
            <!-- Content -->
        </div>
    </div>
    
    <div class="dashboard-col">
        <!-- Right column -->
        <div class="card">
            <!-- Content -->
        </div>
    </div>
</div>
```

## 🛠️ Utility Classes

### Spacing
```html
<!-- Margin Top -->
<div class="mt-1">Margin top 0.25rem</div>
<div class="mt-2">Margin top 0.5rem</div>
<div class="mt-3">Margin top 1rem</div>
<div class="mt-4">Margin top 1.5rem</div>
<div class="mt-5">Margin top 3rem</div>

<!-- Same for mb, ml, mr, pt, pb -->
```

### Text Utilities
```html
<p class="text-left">Left aligned</p>
<p class="text-center">Center aligned</p>
<p class="text-right">Right aligned</p>

<p class="text-primary">Primary color</p>
<p class="text-success">Success color</p>
<p class="text-danger">Danger color</p>
<p class="text-muted">Muted text</p>

<p class="font-weight-bold">Bold text</p>
```

### Display & Flex
```html
<div class="d-flex justify-content-between align-items-center">
    <span>Left</span>
    <span>Right</span>
</div>

<div class="d-flex gap-3">
    <button class="btn btn-primary">Button 1</button>
    <button class="btn btn-secondary">Button 2</button>
</div>
```

## 🎨 Color Variables

Use these in your custom CSS:

```css
/* Primary Colors */
var(--primary)        /* #6366f1 */
var(--primary-dark)   /* #4f46e5 */
var(--primary-light)  /* #818cf8 */

/* Status Colors */
var(--success)        /* #10b981 */
var(--danger)         /* #ef4444 */
var(--warning)        /* #f59e0b */
var(--info)           /* #3b82f6 */

/* Grays */
var(--gray-50)        /* #f9fafb */
var(--gray-100)       /* #f3f4f6 */
var(--gray-200)       /* #e5e7eb */
var(--gray-300)       /* #d1d5db */
var(--gray-400)       /* #9ca3af */
var(--gray-500)       /* #6b7280 */
var(--gray-600)       /* #4b5563 */
var(--gray-700)       /* #374151 */
var(--gray-800)       /* #1f2937 */
var(--gray-900)       /* #111827 */
```

## 📱 Responsive Breakpoints

```css
/* Tablet and below (1024px) */
@media (max-width: 1024px) {
    /* Styles */
}

/* Mobile (768px) */
@media (max-width: 768px) {
    /* Styles */
}

/* Small mobile (480px) */
@media (max-width: 480px) {
    /* Styles */
}
```

## 💡 Best Practices

1. **Always use semantic HTML**
   ```html
   <main>, <section>, <article>, <header>, <footer>
   ```

2. **Combine utility classes**
   ```html
   <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
   ```

3. **Use consistent spacing**
   - Use `-1` to `-5` scale for consistent spacing
   - Prefer flexbox gap over margins when possible

4. **Icon consistency**
   ```html
   <i class="fas fa-icon-name"></i>
   ```

5. **Button groups**
   ```html
   <div class="d-flex gap-2">
       <button class="btn btn-primary">Save</button>
       <button class="btn btn-secondary">Cancel</button>
   </div>
   ```

---

**Note:** All components are fully responsive and work on mobile, tablet, and desktop screens.

