# Aqua Rose Water Refilling Station

A PHP 8.2 / MySQL application for **two branches**, with a green cashier interface based on the supplied designs. Runtime assets are included locally. No Node.js, Composer, paid API, cloud database, or internet connection is needed for ordinary operation.

## Start on this computer

1. In the XAMPP Control Panel, **restart Apache as administrator** so the project-specific security configuration takes effect. MySQL must also be running.
2. Open **http://localhost/aquarosemanagementsystem/**.
3. Complete local setup. Choose a **new, empty database** and create your owner username and strong password. The suggested database is `aqua_rose_management` because this computer already has an `aqua_rose_db` containing records; that existing database is not changed.
4. Choose **Owner sign-in**, sign in, rename the two branches, create delivery worker accounts, review selling prices, and verify customer details.

If the login screen already appears, this checkout has been installed. Initial local credentials are stored privately at `C:\xampp\aquarose-private\aquarosemanagementsystem\FIRST-LOGIN.txt`; only the owner uses a username/password. Delivery workers now use unique six-digit codes; see `output/LOCAL_SETUP.md` for the private code file. See `output/LOCAL_SETUP.md` for this computer's installation status.

There are **no public default credentials**. Delivery workers enter only their six-digit code. The owner issues or resets codes in **Branches & accounts** and continues to use a username and strong password. No fictional sales, customers, delivery people, or revenue are installed.

Apache setup is deliberately blocked if private files are accessible. The app checks that `database/aqua_rose_db.sql` returns HTTP 403 before allowing installation.

## Included workflows

Search results update directly in the record table as staff type, with debounced requests, cancelled stale responses, accessible loading/error feedback and browser-history support. Customer merge and delivery-report account selection use bounded inline search cards instead of native dropdowns. Unsaved forms outside the results are preserved.

Record lists use database pagination with 10, 20, 50 or 100 rows per page, visible result totals and numbered navigation. Filters remain applied between pages. Deliveries combine search, delivery/payment status and booking dates. Customer, order, gallon, payment, account, expense, audit and owner report lists are paginated, including movement, installment and delivery histories. Full report printing and Excel/PDF exports include every matching record.

- Owner overview with combined / per-branch views, date filters, live sales charts, receivables and customer gallon balances.
- Responsive POS: walk-in / delivery, customer search with popup registration, staff-editable price per gallon, manual quantity controls, visible fixed-price products, manual payment entry, automatic change, partial or unpaid orders. Refill product selection is not required at checkout.
- Cash, GCash, bank and other collections with separate payment receipts. Electronic payments require a reference.
- Clickable customer, order, delivery, and recent order rows, with keyboard-accessible links on the record name or number.
- Clickable customer accounts with contact details, live-searchable and paginated orders/deliveries, payment/refund and gallon histories. Staff see all account activity within their branch; only the owner can generate per-customer reports. Profile editing, duplicate warnings and owner-controlled same-branch merging remain available.
- Station container borrowing, intact / damaged returns, outstanding balances, and owner write-offs / corrections.
- Delivery orders automatically belong to the signed-in creating account. Saving in POS immediately marks them Out for Delivery and records the booking time. Workers then confirm handover as Delivered, with failed attempts and a Retry delivery action. There is no separate dispatch, personnel registry or assignment step.
- POS-style delivery handover with customer/address, touch-friendly empty-gallon counts, a live custody balance, one amount-received entry, automatic cash change and unpaid-balance calculations, and collapsible notes/history. Validation errors retain entries on the same delivery; changed invoice balances require review before confirmation.
- Owner-only expenses, custom categories, edits / reversals with reasons, and private PDF / image attachments.
- Sales and payment summaries, expense reports, current receivables and payment refunds.
- Owner-only sales, delivery summary, payment ledger, receivables, gallon balances, operating expenses and individual customer reports with filtered HTML print, styled Excel workbooks, selectable-text PDF exports; individual sale, payment and gallon-return receipts.
- Sales, delivery and customer reports include saved prices, delivered/refilled gallons, paid/unpaid amounts, actual returns and current custody; totals cover all matches and count remaining gallons once per customer. Delivery date filtering, readable print records, landscape PDF summaries and full-detail Excel exports are included.
- Combined **Branches & accounts** management with branch details and searchable, paginated delivery accounts. Owner-managed six-digit delivery access codes, deactivation and session revocation, branch settings and an append-only application audit trail.

Exports use **Report name - YYYY-MM-DD** filenames. Excel and PDF follow the supplied template with a large centered station heading, prepared-by/branch/report-date details, centered title and period, bordered table, totals, and signature lines. Excel retains typed dates/currency, frozen headings and customer columns, and filters. The PHP/zlib exporter fills locally included styles authored with Artifact Tool; ordinary exports need no Node.js, Composer or external service.

Read [USER_GUIDE.md](USER_GUIDE.md) for everyday tasks, [DEPLOYMENT.md](DEPLOYMENT.md) for server installation and backups, and [ACCEPTANCE.md](ACCEPTANCE.md) for verification details.

[REQUEST_CHECKLIST.md](REQUEST_CHECKLIST.md) records the final behavior requested in the conversation, completed verification and the deferred iPad/operator checks.

## Code map

| Location | Purpose |
| --- | --- |
| `index.php`, `api.php` | Authenticated HTML controller and JSON mutation endpoint |
| `app/Auth.php` | Code-only worker login, owner passwords, throttling, sessions and branch authorization |
| `app/Sales.php`, `Delivery.php`, `Containers.php` | Transactional business services |
| `app/Admin.php` | Customer, delivery account, product, expense and settings management |
| `app/Reports.php`, `ReportPdf.php`, `ReportWorkbook.php` | Scoped reports, download names, landscape PDFs and styled Excel exports |
| `app/views/` | Responsive server-rendered screens |
| `assets/` | Local CSS, JavaScript, Inter font and vendored libraries |
| `database/aqua_rose_db.sql` | Import-ready schema and safe reference data |
| `config/local.php` | Generated private configuration; excluded from release archives |
| `tests/` | Disposable PHP integration / concurrency tests and POS interaction checks |

## Important operational behavior

- Invoice status follows actual payment: **Unpaid** for no collection, **Partially paid** for an outstanding installment balance, and **Completed** only after full payment and fulfillment. A prepaid delivery shows **Awaiting delivery** until handover. Cancelled invoices remain cancelled. Lists, receipts and order reports use the same labels. Existing status mistakes can be reconciled without changing money using `tools/repair-order-statuses.php`; see the deployment guide.

- Worker sign-in requires exactly six digits, with leading zeros preserved. Codes are unique across both branches, hashed, and never displayed after saving. Five failed code attempts from one address trigger a 15-minute cooldown. Worker passwords cannot sign in through the owner form. Owner password rules remain unchanged.
- Existing deployments need the additive version-two migration described in `DEPLOYMENT.md`; this computer has already been upgraded.

- Container deposit collection and refunds are removed. Historical transaction records are retained, and positive historical funds held remain visible as a read-only customer balance. Customer merging still preserves these records.
- Delivery fees are removed from checkout and rejected on new sale requests. New totals use product quantities and prices only. The old delivery-service product is unavailable for sale or repricing. Older invoices retain recorded fees for accurate receipts, reporting and payment balances; zero-fee rows are hidden.
- Currency is computed as integer centavos in PHP and stored as `DECIMAL`, never as floating point database amounts. Unit prices are snapshotted on invoices. Existing cost snapshots remain intact; new sales mark unrecorded costs as unknown.
- Delivery workers can customize a refill's unit price for the current sale. The server recalculates the invoice, preserves the actual rate on its receipt, and audits deviations from the branch price. Fixed-product prices come from the owner's branch price list. The first active, priced refill product supplies the POS default price.
- Sales are recorded from quantity and price without stock counts. The creating account is the delivery worker; customer gallon movements are posted only when delivery completes. Collections are attributed to the authenticated account. Workers manage their own deliveries; the owner can manage all deliveries.
- Walk-ins using station gallons automatically derive borrowing from the billed refill quantity. No separate borrowed count is displayed; the worker only enters actual returns. A customer account is required to record the loan. Server validation uses invoice quantities and rejects over-returns.
- Walk-ins using customer-owned gallons hide borrowed/returned controls and submit zero station-gallon movements. Switching from station to customer-owned containers clears both counts. Separate station-gallon returns remain available in the gallon ledger.
- Delivery checkout automatically derives outgoing station gallons from the billed refill quantity when **Borrow from station** is selected. Customer-owned containers create no station loan, and accessories are excluded from the automatic count. There is no separate delivery gallon panel or quantity entry. The arrival count in **Deliveries** starts at zero. Workers can confirm zero or partial returns; any unreturned station gallons remain on the customer account and are recorded in the handover audit. Later ledger returns reduce the outstanding balance without rewriting that delivery. The form previews the balance; the server validates against the current locked ledger and posts the movements once. Reports leave the uncounted return blank until handover. Existing saved delivery plans are preserved.
- A delivery can be marked Delivered only when the delivered gallons / new containers match its invoice. For short fulfillment, record a failed attempt; the owner can cancel with recovery/refund reasons and create the corrected invoice. Financial invoices are not silently rewritten.
- Cancellation reverses customer gallon movements and refunds collections in one transaction. Cancellation is blocked when its gallon reversal would produce a negative custody balance.
- Inventory, production, transfer controls and stock checks are removed. Historical stock tables are retained for compatibility and are not used by sales or customer gallon workflows. Profit excludes unrecorded product costs and is provisional.
- Current receivables and customer gallon balances cover **all history**, clearly marked as current. Other reports apply the selected reporting period. Shared expenses appear once in the all-branch expense report and are excluded from individual branch totals.
- Receipt printing uses the installed browser / operating-system printer dialog. Choose an 80 mm roll (or 58 mm with fit-to-width), or Save as PDF. No direct ESC/POS driver is bundled.

## Products and fixed prices

The owner manages fixed-price goods in **Products & pricing**, with searchable, paginated product and price-history lists. Add a name, product type, price, effective date and branch (or both branches). Active products with an effective branch price appear as visible clickable tiles above the POS quantity strip, with the price at the top and name below. All active product tiles are available directly without a product search field. Clicking a product loads its name, quantity and read-only catalog price into the main quantity strip and adds it to the bill automatically. Quantity changes update the total immediately. The untouched default refill is removed when choosing goods; explicitly entered refills are preserved for mixed orders. Selected tiles show their quantity, and price or availability changes appear in the visible review notice. Open cashier screens refresh the catalog every 15 seconds and when returning to the page, preserving their draft. Selected price changes require confirmation; unavailable selected products must be removed before checkout. The server enforces current prices and the selling branch.

The catalog includes **350 mL water - PHP 6**, **500 mL water - PHP 10** and **Gallon with water - PHP 150** in both branches. Water refill prices remain editable for each sale. Staff can choose **Sell products only (no refill)**. A new filled gallon is sold to the customer and creates no station-container loan; bottles do not count as delivered gallons. Deliveries show a packing list and validate the total physical item count. Saved invoices retain their original prices and product types. Existing installations must run `tools/upgrade-products.php` once; fresh installations include the catalog already.

## Animated sign-in

The login uses original water-drop, gallon and bubble characters inspired by the supplied animation reference. Local SVG/CSS and a small standalone script provide pointer-following eyes, private typing/reveal poses, authentication-error feedback and an immediate native-POST loading state. Staff keep six-digit code sign-in; the owner keeps username/password sign-in. The form works without JavaScript, supports password managers, includes an accessible show/hide control and starts character interactions automatically without a play/pause button. Cursor tracking stays available while background motion respects the system reduced-motion setting. No credentials are sent by the illustration or to an external service.

## Verification

Run these from the project directory. Each PHP suite creates and removes its own temporary database. The HTTP suite also creates and removes an isolated application copy; the installed configuration and business records are untouched.

```powershell
C:\xampp\php\php.exe tests\customer-search.php
C:\xampp\php\php.exe tests\refill-pricing.php
C:\xampp\php\php.exe tests\container-returns.php
C:\xampp\php\php.exe tests\product-migration.php

# Development dependencies, if not already installed
npm.cmd ci --prefix tests
node tests\products-ui.test.cjs
node tests\pos.test.cjs
node tests\pos-customers.test.cjs
node tests\access-code-ui.test.cjs
node tests\delivery-returns.test.cjs
node tests\pagination-ui.test.cjs
node tests\live-search.test.cjs
node tests\record-search.test.cjs
node tests\report-summary.test.cjs
node tests\customer-account-ui.test.cjs
node tests\login-animation.test.cjs
```

The HTTP suite generates the HTML fixtures used by the JavaScript suites. Current coverage is 779 checks, including code-only sign-in, leading zeros, duplicate rejection, owner password login, reset/session revocation, throttling, owner-only reports, payment-aware invoice statuses, concurrent payment validation and actual gallon returns at arrival. Customer account checks cover all-staff transaction visibility within the branch, record isolation, history filters/pagination, profile-save recovery and owner-only all-time customer Excel/PDF/full-print exports. Delivery handover checks cover automatic collections/change, electronic payment references, stale balances, duplicate confirmation, prepaid orders and retained entries after errors. Another 16 checks passed on the installed Apache host for both existing workers and private-path protection. The older integration and advanced suites document the previous inventory and assignment behavior and are historical references, not current acceptance suites.

Keep production backups and credentials outside the repository. Do not import this schema into a database containing existing tables.
