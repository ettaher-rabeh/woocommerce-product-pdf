# WooCommerce Product PDF (Dompdf, no plugin)

Smart, lightweight way to add a **“Télécharger PDF”** button on WooCommerce product pages.  
It renders a clean PDF using **Dompdf** — no plugin packaging needed.

> **Setup in 2 minutes:** put Dompdf in `wp-content/dompdf/` and paste the snippet into Code Snippets or your theme’s `functions.php`.

---

## ✅ What this does

- Adds a **Download PDF** button right **under the Add to Cart** button
- Renders a PDF with **title, price, image, short/long description**
- Works with **Arabic/RTL** if you add a proper Unicode font
- No composer, no plugin build — **simple folder + snippet**

---

## 📦 Requirements

- WordPress 6.x, WooCommerce 8+
- PHP 8.0+
- [Dompdf](https://github.com/dompdf/dompdf) 2.x (manual install)

---
## 🗂️ Folder structure
wp-content/
├─ dompdf/ ← put Dompdf here (the full library)
│ ├─ autoload.inc.php
│ └─ src/...
└─ themes/
└─ your-theme/
└─ functions.php ← (or use the Code Snippets plugin)

> If you use **Code Snippets**, create a new snippet (run **on front-end**), paste the code below, and save/activate.

---
