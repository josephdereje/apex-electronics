# APEX IMPORT AND EXPORT COMPANY LIMITED

Modern OEM/ODM website for APEX, with an animated homepage, a separate product catalog, and a modern admin for product upload. The same brand experience is available in **Django** and **PHP**.

Both stacks store products in a database. Images uploaded in admin appear on the public product list and product detail pages. A JSON product API is included on each stack.

## Shared look

- Orange `#FF6B35` brand system from the APEX brief
- Sticky translucent header, fade-up section animation, floating WhatsApp
- Pages: Home, Products, OEM / ODM, About, Contact
- Admin: overview, product list, upload/edit/delete, quote requests

Admin login for both sites:

- Username: `admin`
- Password: `ApexAdmin2026!`

Change the PHP password in production with `APEX_ADMIN_PASSWORD`.

## Django site

```sh
cd django_site
python3 -m pip install -r ../requirements.txt
python3 manage.py migrate
python3 manage.py seed_catalog
python3 manage.py runserver 8000
```

- Public site: http://127.0.0.1:8000/
- Product list: http://127.0.0.1:8000/products/
- Modern admin: http://127.0.0.1:8000/admin-panel/
- Product API: http://127.0.0.1:8000/api/products/
- Django built-in admin: http://127.0.0.1:8000/django-admin/

## PHP site

Requires PHP 8.1+ with PDO SQLite and Fileinfo.

```sh
cd php_site
php -S 127.0.0.1:8080 router.php
```

- Public site: http://127.0.0.1:8080/
- Product list: http://127.0.0.1:8080/products.php
- Modern admin: http://127.0.0.1:8080/admin/login.php
- Product API: http://127.0.0.1:8080/api/products.php

The PHP database is created automatically at `php_site/data/apex.sqlite`. Product images are stored in `php_site/uploads/products/`.

## Upload to cPanel

cPanel works best with the **PHP** site, not Django.

1. In cPanel, open **File Manager** and go to `public_html`.
2. Upload everything inside `php_site/` into `public_html`.
3. Also upload the project `assets/` folder to `public_html/assets` (CSS, images, catalog PDF).
4. Make `public_html/data` and `public_html/uploads/products` writable (755 or 775).
5. Set a strong admin password in cPanel → Environment or in `.htaccess`:

```
SetEnv APEX_ADMIN_PASSWORD "your-strong-password"
```

Site: `https://your-domain.com/`  
Admin: `https://your-domain.com/admin/login.php`  
Temporary default login is `admin` / `ApexAdmin2026!` — change it before going live.

The company catalog PDF is at `/assets/docs/APEX-Company-Catalog.pdf`.
