# Product Listing Refactor Strategy

## 1. Architecture Overview: Before → After

### BEFORE (Current - Problematic)
```
Custom Page Template (page-products.php)
├── Uses WP_Query with custom pagination
├── Manual query var handling (page vs paged)
├── Custom rewrite rules needed
├── Unstable pagination (404 errors)
└── Complex URL handling
```

### AFTER (Proposed - Native WordPress)
```
Taxonomy Archive Template (taxonomy-product_cat.php)
├── Uses WordPress main query
├── Native pagination support (/category/page/2/)
├── Automatic rewrite rules
├── Stable, reliable pagination
└── SEO-friendly URLs
```

## 2. Custom Post Type & Taxonomy Structure

### Current Setup (Already Exists)
```php
// Post Type: 'product'
// Taxonomy: 'product_cat' (hierarchical)
// Slug: 'danh-muc-san-pham'
```

### Template Hierarchy
```
taxonomy-product_cat.php          → Category archive (PRIMARY)
archive-product.php               → All products archive (if needed)
single-product.php                 → Single product (already exists)
```

## 3. How WordPress Handles Pagination Natively

### For Taxonomy Archives:
1. **URL Structure**: `/danh-muc-san-pham/category-slug/page/2/`
2. **Query Var**: `paged` (automatically set by WordPress)
3. **Main Query**: WordPress automatically queries posts for the term
4. **Rewrite Rules**: Built-in, no custom rules needed
5. **Pagination**: `get_pagenum_link()` works out of the box

### Benefits:
- ✅ No 404 errors
- ✅ No custom rewrite rules
- ✅ No query var conflicts
- ✅ SEO-friendly URLs
- ✅ Works with all WordPress plugins

## 4. Integration with wp_bootstrap_pagination

### In taxonomy-product_cat.php:
```php
<?php
// WordPress main query is already set up automatically
// No custom WP_Query needed!

if (have_posts()) :
    while (have_posts()) : the_post();
        // Display product card
    endwhile;
    
    // Pagination: Simple one-liner!
    // WordPress handles URL generation automatically
    wp_bootstrap_pagination();
endif;
?>
```

### Key Points:
- ✅ No `custom_query` needed (uses main query automatically)
- ✅ No `is_page_template` parameter (removed from function)
- ✅ No `page_id` parameter (removed from function)
- ✅ No manual query var handling
- ✅ Just call `wp_bootstrap_pagination()` - it works!

### How It Works:
1. WordPress main query automatically:
   - Detects current taxonomy term
   - Sets `paged` query var from URL
   - Queries posts for that term
   - Handles pagination URLs

2. `wp_bootstrap_pagination()`:
   - Uses `$GLOBALS['wp_query']` by default
   - Reads `paged` from query vars
   - Uses `get_pagenum_link()` for URLs (works perfectly!)
   - Preserves `sort` parameter if present

## 5. Migration Steps

1. **Verify taxonomy-product_cat.php exists** ✅ (already exists)
2. **Remove page-products.php** (or keep as fallback)
3. **Update navigation links** to use term links instead of page links
4. **Test pagination** on taxonomy archive pages
5. **Clean up function-pagination.php** (remove page template code)

## 6. URL Changes

### Before:
- `/san-pham/` (page template)
- `/san-pham/page/2/` (custom rewrite, unstable)

### After:
- `/danh-muc-san-pham/category-name/` (taxonomy archive)
- `/danh-muc-san-pham/category-name/page/2/` (native, stable)

## 7. Benefits Summary

✅ **Stability**: Native WordPress pagination is battle-tested
✅ **SEO**: Better URL structure for search engines
✅ **Maintainability**: Less custom code to maintain
✅ **Compatibility**: Works with all WordPress plugins
✅ **Performance**: Optimized by WordPress core

