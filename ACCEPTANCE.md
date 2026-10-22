# Verification record and acceptance checklist

## Automated coverage

Validated against PHP 8.2.12 and MariaDB 10.4.32 on this XAMPP computer. Tests use generated disposable databases, not the existing `aqua_rose_db`.

| Current suite | Checks | Coverage |
| --- | ---: | --- |
| `tests/customer-search.php` | 367 | Authenticated HTTP requests: customer search/creation, branch isolation, sales without inventory, receipts, deliveries, returns, cancellations, worker ownership, dispatch/retry, account-attributed collections, removed reminder routes, code login/migration, owner-only reports, invoice/payment validation, 27 arrival-return and automatic outgoing-quantity checks and 22 POS-style handover/payment checks |
| `tests/refill-pricing.php` | 26 | Staff pricing, exact centavos, zero-stock quantities, unknown product costs, invalid-price rollback, branch isolation, blocked discounts and delivery collections |
| `tests/container-returns.php` | 25 | Borrowing and returns with zero stock, duplicate and excessive returns, intact/damaged condition, delivery completion, removed return dates, blocked reminder actions, merges and cancellation |
| `tests/pos.test.cjs` | 49 | Quantity and price entry, manual payment/change, removed delivery fees, removed controls, checkout payloads, gallon custody and accessories-only orders |
| `tests/pos-customers.test.cjs` | 24 | Customer search, keyboard navigation, popup registration, error recovery, cart preservation, automatic selection and safe customer text rendering |
| `tests/access-code-ui.test.cjs` | 10 | Code generation, leading zeros, numeric validation, focus and account form behavior |
| `tests/delivery-returns.test.cjs` | 39 | Required actual counts, touch controls, return limits, live custody, cash change, partial/prepaid collections, electronic references, invalid money, submission guard and browser-history recovery |
| `tests/pagination-ui.test.cjs` | 9 | Native filter forms, retained search/status/dates, page-size changes, filter resets, numbered navigation and accessible disabled/current-page states |
| `tests/live-search.test.cjs` | 16 | Search without Enter, direct row replacement, preserved drafts/focus, empty/error recovery, paging, stale-response rejection, history and complete report export links |
| `tests/record-search.test.cjs` | 16 | Inline customer/account cards, required selection, safe text, lookup races, owner report selection/clearing and synchronized history restoration |
| `tests/customer-account-ui.test.cjs` | 8 | Customer history navigation, invoice row clicks, safe item expansion, live account searches, retained section/account filters and owner-only report action |
| `tests/login-animation.test.cjs` | 32 | Masked typing and reveal poses, pointer/touch behavior, automatic startup, reduced background motion, no-JavaScript fallback, real native submission, duplicate protection and browser-back recovery |
| **Total** | **621** | All current suites passed, including animated sign-in, customer account histories and per-customer owner reports |

The reported seven-gallon order was reproduced through the real API at PHP 30.00 per gallon, with PHP 500.00 received. It completed for PHP 210.00 with all inventory entries removed from the disposable database. Stock counts, product-to-stock links, stock movements, production, transfers, stock alerts and inventory routes are not required or available in the active application. Customer borrowed/returned gallon balances remain transactional.

Delivery orders use the creating login account as their worker. Separate personnel registration and assignment controls/API are removed. POS delivery bookings immediately start Out for Delivery, and failed deliveries can retry directly. Status updates, payment collections and performance reports use user accounts. Workers cannot open or update another worker’s delivery, including within the same branch; the owner retains oversight. Older Pending/Assigned bookings open the handover form directly and appear as Out for Delivery without changing existing records. The delivery workflow uses existing user identities. Code-only worker login additionally uses schema version 2, installed on this computer.

No business transactions were changed. Existing delivery accounts were issued private six-digit codes and their old sessions were revoked; owner credentials were preserved. The version-one migration and repeat upgrades were verified in a disposable database. Both existing worker codes were then checked through the installed Apache host (16 additional checks, including sign-out and private-path protection). Historical database tables and old invoice cost snapshots are preserved for compatibility. New sales mark product costs as unknown so finance identifies the provisional profit figure. The older 132-check integration and 38-check advanced suites retain pre-removal inventory scenarios as historical references and are excluded from the current total.

To reproduce, run `C:\xampp\php\php.exe tests\customer-search.php`, `C:\xampp\php\php.exe tests\refill-pricing.php`, `C:\xampp\php\php.exe tests\container-returns.php`, `node tests\pos.test.cjs`, `node tests\pos-customers.test.cjs` and `node tests\access-code-ui.test.cjs` and `node tests\delivery-returns.test.cjs`. Temporary databases and the HTTP application copy are removed automatically. The HTTP suite supplies the customer HTML fixture for the JavaScript suites. The installed configuration is never replaced.

All 67 PHP source files were syntax-checked; all application JavaScript files passed syntax checks. Actual exported PDFs were previously parsed, rendered and inspected for legibility and pagination; the exporter retains complete records on a page when they fit. Dependencies are vendored locally, with license files included.

The HTTP integration suite ran through the protected PHP development router and a real MariaDB database. Concurrency tests used separate PHP processes with separate PDO connections to exercise database row locks. POS tests use JSDOM; they are not real-browser screenshots.

Invoice statuses were checked through real authenticated HTTP requests, report views, receipts and two concurrent PHP/PDO collection processes. Unpaid and partial balances cannot complete an order; prepaid deliveries await fulfillment. Tests cover centavo remainders, cash change, duplicate payments, conflicting request keys, excessive collections, cancellation, delivery rollback and legacy status repair. One incorrect installed status was corrected. Before/after fingerprints confirmed invoice amounts, payments, deliveries, customers, gallon movements and user credentials were preserved.

Delivery return counts are entered at arrival, rather than during checkout. Tests cover unknown counts, explicit zero, unequal delivered/returned quantities, old planned-return values, duplicate completion and a return posted after the form opened. A separate PHP/PDO process commits a return while the first transaction holds an older read snapshot; the locked ledger read correctly prevents a negative balance. Walk-in returns remain available. No installed orders, customer gallon balances or credentials were changed by this update.

The delivery workspace follows the POS with three steps, touch controls, a single received amount and automatic payment/change calculations. Real authenticated POST requests confirm that invalid submissions return to the same delivery with the entered count, collection, reference, receiver and notes preserved. Successful handovers show their actual invoice balance; partial payments do not turn the invoice into Completed. Stale invoice balances, excessive electronic collections and extra cash on prepaid orders are rejected atomically. Apache serves the new CSS/JavaScript with byte-for-byte source matches. Interface interactions were checked with JSDOM; desktop/mobile rendering still requires a connected browser.

## Hosting protection activated

Apache was configured with `AllowOverride None`. This ignored the application access rules. A **project-specific** Apache include now enables `.htaccess` for this checkout, and `httpd -t` reports valid syntax. During local setup, an administrator restart was completed through the Windows elevation prompt. Private config, schema, application, storage, test and tool URLs now return HTTP 403 through Apache on port 80. The installer is locked after installation.

Expense receipt files and application error logs additionally live outside `htdocs`, and internal PHP files include direct-access guards. The pre-existing business database was not modified. QA configuration and QA databases are removed before handoff.

## Checks still requiring an operator

No connected browser surface or physical printer was available. These items are deliberately not claimed as verified:

- [ ] Open the application in current Chrome / Edge / Firefox and confirm desktop POS layout, keyboard focus and mobile navigation.
- [ ] Test at approximately 390 px, 768 px, 1366 px and 1920 px widths. Forms and tables must remain usable; tables may scroll horizontally.
- [ ] Print sale, installment and return receipts on the actual thermal printer. Confirm paper size, margins and fit-to-width.
- [x] Administrator Apache restart applied and private paths return HTTP 403 on this computer; repeat on another deployment host.
- [ ] Review selling prices, customer addresses and staff access before live operation.
- [ ] Enable HTTPS and restricted database credentials if deployed for network / public use.
- [ ] Run a backup and restore drill on a separate database.

## Business acceptance cases

1. **Walk-in:** Branch 1 staff sells three refills, records cash, verifies change and prints a receipt. Owner sees the same invoice; Branch 2 staff cannot retrieve it by ID, receipt or export.
2. **Partial collection:** A PHP 175 invoice receives PHP 50, then PHP 125 in a separate receipt. The invoice stays Partially paid until its balance reaches exactly zero, then becomes Completed if fulfilled. A further collection is rejected.
3. **Delivery:** A Branch 2 delivery worker records five refills, saves it immediately as Out for Delivery, opens the delivery on arrival, and records actual empties and payment. Their login is the delivery identity; status updates and collections retain the actual account and timestamps.
4. **Gallon equation:** Previous custody 5 + borrowed 3 − returned 2 = 6. A later return of 2 leaves 4. An over-return fails and changes nothing.
5. **Damage:** A damaged return reduces customer custody and retains the reported condition. A verified loss / correction requires the owner and a reason.
6. **Expenses:** Owner records an electricity expense against Branch 1. It appears once in Branch 1 / consolidated figures and never in Branch 2. A shared cost appears once, separately, in consolidated finance. Staff cannot access expense pages, mutation endpoints, exports or attachments.
7. **Delivery ownership:** Another worker in the same branch cannot open or change the delivery. The owner can inspect it. A failed attempt can be retried directly without assignment.
8. **Cancellation:** The owner supplies a reason and cancels an order. Original financial records remain, refunds are explicit and customer gallon movements are reversed. Unsafe negative gallon reversals are blocked.
9. **Security:** Staff manipulates `branch_id`, order IDs and export URLs; all reads and writes remain scoped. The owner uses username/password; workers use only a unique six-digit code. Leading zeros work. Duplicate codes across branches are blocked. Reissuing a code invalidates its predecessor and active sessions. Five failed code guesses from one address trigger a 15-minute cooldown. Deactivation invalidates active sessions.
10. **Reporting:** Only the admin/owner can view report pages and export PDF/CSV. Staff direct URLs return HTTP 403, including forged owner fields; staff pages contain no report or export links. Branch, period and applicable delivery / expense filters carry through HTML, print, PDF and CSV. Receivables and customer gallon balances are clearly labeled as current, all-history balances.

Delivery POS no longer shows the separate outgoing station-gallon panel. Six new HTTP checks verify that station ownership derives the outgoing plan from billed refill quantities, customer-owned containers create no loan, accessories are excluded, invalid ownership is rejected and actual arrival returns produce the correct ledger. Six additional POS checks verify the removed panel, automatic updates after quantity changes, switching ownership and accessory-only deliveries. The client sends ownership and invoice items without a duplicate borrowed count. Walk-in borrowing and saved historical delivery plans retain their existing behavior.

Delivery fees are removed from POS, checkout payloads and new-sale calculations. Seven additional authenticated HTTP checks verify missing fee controls, positive-fee rejection for delivery/walk-in requests without partial writes, retirement of the legacy delivery-service product, zero-fee compatibility, product-only totals/change, hidden zero-fee receipt rows and preservation of older fee-bearing invoices. Historical fees remain included in reports, balances and old receipts. No installed invoices or credentials were changed.

The delivery-worker box and duplicate address block were removed from POS. The selected customer summary retains the address, including immediately after popup registration. Automatic worker attribution, ownership and branch validation remain on the server. This display change passed 220 authenticated HTTP checks and 65 POS/customer interaction checks (285 focused checks); the unchanged suites retain their previous results.

Customer-owned walk-ins now hide the borrowed/returned section from initial page load, clear old counts when ownership changes and submit zero station-gallon movements. Station walk-ins restore the section without stale returns. Three new HTTP checks validate that customer-owned sales reject nonzero borrowing/returns atomically and preserve existing station custody. Three additional POS checks cover the initial state, ownership switching and checkout with stale hidden values. All 389 current checks passed; business records and credentials were untouched.

Station walk-ins now derive borrowed gallons directly from the billed refill quantity; the outgoing controls are read-only without adjustment buttons. Workers enter actual returns independently. The quantity is previewed before customer selection, and saving a station loan requires a customer. Four additional HTTP checks cover automatic quantity plus actual returns, stale loan values, excessive returns and missing customer rejection. Five new POS checks cover automatic updates, non-editable borrowing, independent return entry, customer-required preview and a checkout payload without a duplicate outgoing count. All 398 current checks passed. Older explicit API plans remain compatible; saved business records and credentials were untouched.

The duplicate Borrowed now card was subsequently removed. The POS section is now Returned gallons with one full-width return control. Borrowing still derives from invoice quantities internally, and customer-owned orders keep the section hidden. The change passed 227 authenticated HTTP checks and 73 POS/customer interaction checks (300 focused checks); unchanged suites retain their prior results.

POS delivery bookings now start Out for Delivery atomically with the order and a booking timestamp. Created/Out for Delivery history and audit entries use the signed-in account; request-key retries preserve the original delivery, timestamp and history. Ready queues, filters and the separate dispatch button are removed. Failed deliveries retain a Retry delivery action. Older Pending/Assigned rows are shown and filtered as Out for Delivery and may complete directly; missing dispatch timestamps use the original order booking time on handover. Six additional HTTP checks cover booking idempotency, active filtering and both legacy states. Main workflow checks now complete deliveries without a dispatch API call. Installed delivery states were four Out for Delivery and one Delivered, so no data migration was needed or performed.

Arrival returns now start at zero. Zero and partial returns leave Confirm delivered enabled when the other fields are valid. Unreturned station gallons remain on the customer ledger, are shown on the completed delivery and are snapshotted with actual returns in the audit. Three additional HTTP checks confirm partial/zero completion with the recorded balance and later returns that preserve the original handover record. Two additional interface checks confirm completion remains available with zero/partial returns. Missing or cleared counts and excessive returns remain invalid; opening a form does not post a return. All 409 current checks passed using disposable QA data.

Container deposit collection/refunds are retired: the ledger form, mutation service/action and collection instructions were removed. Zero deposit rows no longer appear on customer profiles. Existing positive historical balances remain read-only, with transactions and merge support retained. Eight new authenticated HTTP checks cover both roles, rejected collection/refund requests, a forged legacy form, unchanged historical funds/custody, normal gallon returns and read-only legacy balances. All 417 current checks passed without modifying installed financial records or credentials.

## Filters and pagination

Forty-one new real HTTP checks use 238 added customer fixtures, 139 delivery fixtures, 55-entry histories and multiple management lists in the disposable database. They verify independent histories, filtered counts, stable order, invalid/out-of-range page handling, the 100-row maximum, grouped gallon balances, records beyond the old 200-account cutoff, full report printing/exports and owner/worker/branch restrictions. Nine DOM interaction checks verify forms and page-size behavior. No business records, login credentials or production schema were changed. An attached browser was unavailable for visual review; desktop/iPad layout remains an operator check.

Payments now shows only the filtered, paginated payment/refund ledger and receipt/invoice links. The repeated outstanding-invoice list and collection form were removed. Collections remain available inside invoice details and delivery handover; payment records and validation are unchanged. The existing payment pagination checks now cover the simplified page and discard obsolete unpaid-list query parameters.

## Live search and inline selections

Twenty-seven additional authenticated HTTP checks verify bounded owner record lookups, strict permissions, branch scope, literal wildcard handling, private-field exclusion, inactive/merged exclusions, empty input, CSRF, all list search regions, return entry without a redundant customer dropdown and historical owner-created deliveries. Sixteen live-table interaction checks verify debouncing, direct results, preserved edits, row actions, page sizes, zero results, retry recovery, stale requests, history and current export/print links. Sixteen inline-lookup checks cover customer and worker choices, validation, XSS-safe text, request races, clearing, and history synchronization. All 526 current checks passed using disposable QA data. No installed transactions, credentials or schema were changed. A connected browser was unavailable, so actual desktop/iPad visual review remains an operator check.

## Customer account detail views

Clicking a customer now opens a dedicated account view for staff and the owner. Its summaries cover the full recorded history; cancelled invoices are excluded from purchase and unpaid totals. Orders/deliveries, payment/refund receipts and gallon movements have separate searchable, filtered database pages. The view includes transactions recorded by every account for that customer, while staff remain restricted to their own branch. Another worker's delivery update route remains protected.

The authenticated HTTP suite adds 43 checks for customer isolation, dates, saved item prices, correct payment/delivery/return status, all-account history, refunds, literal searches, pagination, profile-edit success/error redirects, inactive/merged accounts and owner-only customer reports. The report defaults to all time; its CSV, PDF and full print contain all matching customer orders regardless of the displayed page. Eight JSDOM checks cover the customer-facing interaction flow. Real-browser visual review remains unavailable in this session. Installed business records, credentials and schema were not changed.

## Animated login

Eleven additional authenticated HTTP checks verify both new login views, local versioned assets, staff-only code fields, owner password-manager fields, escaped username/error handling, secret non-reflection, CSRF, leading-zero worker authentication, owner report access and signed-in redirects. Thirty-two JSDOM interaction checks cover private typing, reveal/hide, pointer limits, touch behavior, pause, reduced motion, validation, native POST, duplicate submits and retry/browser-back recovery. Real Apache requests returned HTTP 200 for both login modes and their CSS/JavaScript. The stylesheet parsed successfully, and the checked form/help/scene/tab/button text contrasts exceed 4.5:1.

The reference was inspected through its public preview; the full reel was unavailable through the browsing tool. The implementation uses original Aqua Rose SVG/CSS water characters, local assets only and no credential transmission by the animation. No application roles, codes, passwords, business records or schema were changed. Browser surfaces were unavailable, so no real-browser screenshot or visual certification is claimed.

Cursor following now aims each pupil from its own eye center, with movement bounded to the eye size. Six regression checks cover all directions, cursor movement across the distant form, individual eye targets, leaving the page, mouse-event fallback and failed-login screens. Thirty focused animation checks passed; Apache serves the updated JavaScript with HTTP 200. Browser visual review remains unavailable.

The pupil renderer now applies explicit transforms to absolutely positioned pupils, and the regression fixture loads both real stylesheets. Reduced motion starts paused but no longer hides the Play control: a user can opt into cursor following while background motion remains reduced. Thirty-two focused checks passed, including concrete computed transforms and enabling cursor following from reduced-motion mode. The reported browser remains unavailable to the automation tool; no visual confirmation on that browser is claimed.

The gross-sales Finance card, sales-report column and branch-comparison column were removed, including PDF/CSV/full-print output. The unused gross-sales aggregate was removed. The authenticated HTTP suite passed 366 checks, and a read-only check against the installed database confirmed net sales, payments and receivables still load. No invoice amounts or other business records were changed.

## Products & Pricing temporarily disabled

The management menu and page are unavailable while `product_management_enabled` is false. Authenticated API product creation and price updates, forged native forms, direct URLs, pagination and search parameters cannot reopen or change the disabled module. Existing catalog rows and price history remain intact, and ordinary/custom-price POS sales continue to work. The module source is retained for later restoration. No installed products, prices, invoices, credentials or schema were changed.

## Automatic login animation

The Play/Pause animation control and activation gate are removed. Cursor following and character reactions start as soon as the script loads, including when the device requests reduced motion; background CSS animations still honor that preference. Both login views omit the control, and tests cover starting/resuming cursor following without a click. This supersedes the previous manual play/pause behavior described above.

## Simplified report catalog

Delivery performance, Staff activity and Branch comparison are removed from Reports & Exports. Their direct report pages, full-print pages and PDF/CSV exports return HTTP 404 to the owner; staff report restrictions remain enforced. The matching comparison table in Finance and audit-export shortcut were removed. Finance summaries, ordinary audit history and the remaining paginated reports continue to load.

The authenticated HTTP suite passed 384 checks, including 20 checks for this removal and the remaining finance/audit views. Another 41 pagination and live-search interaction checks passed (425 focused checks). PHP syntax checks passed for all seven changed PHP files. Tests used a disposable database and isolated server; installed business records, credentials and schema were unchanged.

## Sales, delivery and gallon summaries

Sales & orders, Delivery summary and individual customer reports show saved refill prices, confirmed delivered/refilled gallons, invoice totals, current paid/unpaid amounts, returns at handover and current station-gallon custody. Delivery summary can use order date or actual completion date. HTML, full print, CSV and landscape PDF use the same filters. A database snapshot keeps each report's detail rows and totals consistent.

Totals cover all matching orders across pages; cancelled orders are excluded. Remaining custody counts each matching customer once and includes earlier loans and later returns. Delivered water quantities exclude accessories; pending deliveries contribute no confirmed gallons or assumed returns. Currency totals use the database decimal aggregate directly, including totals above the single-payment input limit.

The authenticated HTTP suite passed 407 checks, including 23 summary checks for centavos, partial/full/unpaid orders, multiple saved rates, accessories, zero and unknown returns, later returns, repeated customers, cancellation, dates, branch scope, per-customer totals, empty results, pagination, full exports, formula-safe buyer names, visible PDF zero values, large totals and owner-only access. Six new summary interaction checks and the existing 41 pagination/lookup/live-search checks passed (454 focused checks). Syntax checks passed for ten affected PHP files. The generated 18-order PDF was rendered and all three landscape pages visually reviewed; every order and summary value was present, with no text outside page margins. Native browser/printer review remains unavailable. Installed records, credentials and schema were unchanged.

## Finance section removed

The Finance navigation item, route and view are removed. Direct owner and staff URLs return HTTP 404. Expenses, sales/delivery/customer report summaries, invoice history and PDF/CSV exports continue through their existing pages. Historical invoice amounts are preserved in receipts and sales reports. This supersedes earlier references to the separate Finance screen.

All 407 authenticated HTTP checks passed using the disposable test application and database, including the retired-route/menu check and existing report/export checks. PHP syntax checks passed for the four changed PHP files. Installed business records, credentials and schema were unchanged.

## Order numbers omitted from reports

The Order column is removed from shared sales/delivery/customer, payment and receivables report queries. Report previews, full print, CSV and both PDF layouts omit order references. Customer names, dates, saved prices, quantities, payment/gallon balances and full-report totals remain present. Existing order identifiers still support internal filtering and saved operational records.

All 414 authenticated HTTP checks passed, including seven checks covering CSV/PDF exports for every available report type. Six summary interface checks passed (420 focused checks), and syntax checks passed for seven affected PHP files. The regenerated 18-record summary PDF was rendered and both landscape pages visually reviewed: no order numbers, correct totals and zero returns, and no text outside the page margins. Installed records, credentials and schema were unchanged. Earlier verification entries that identify exported records by order number describe the previous format.

## Named downloads and formatted Excel reports

All seven available report types use `Report name - YYYY-MM-DD` download names for Excel, PDF and CSV, using the Philippine export date. Customer reports include the customer name, with unsafe filename characters removed and Unicode names retained. Full-print document titles suggest the same name when saving as PDF. The reporting period remains inside each report.

The new Excel button exports a genuine `.xlsx` workbook with Aqua Rose branding, prepared-by and period details, a formatted table, frozen headings and identifying columns, filters, typed dates and amounts, and totals. The included styles were authored with Artifact Tool; runtime generation requires only PHP/zlib. Native formulas recalculate eligible totals; current gallon custody remains deduplicated by customer. Phone numbers and names stay literal text, including leading zeros and formula-like names. Landscape PDFs now include matching totals for every report type. Order numbers remain omitted.

All 455 authenticated HTTP checks passed, including 37 export-format checks across seven report types and three formats. Another 38 report-summary, live-search and record-search interaction checks passed (493 focused checks); ten affected PHP files passed syntax checks. All seven Excel reports were imported, recalculated, checked for formula errors and rendered using Artifact Tool. A changed sample amount correctly updated its total and headline summary, then restored. Sample PDF pages were rendered and reviewed, and all text on all 50 generated pages stayed inside the page margins. These checks used disposable QA data; installed business records, credentials and schema were unchanged. Native Excel, browser and physical-printer validation were unavailable in this session.

## Report template matched to the supplied sample

Excel and PDF now use a large centered Aqua Rose banner and station subtitle, separate prepared-by/branch/report-date lines, a centered report title and date range, bordered detail cells, highlighted totals, and blank prepared-by/reviewed-by signature lines. Excel preserves its complete dataset, native dates/currency, formula totals, filters and frozen headings/identifying columns. PDF pagination reserves space for the final record, totals, notes and signatures, avoiding a separate signature-only page. Download names and CSV data remain unchanged.

All 455 authenticated HTTP checks passed again, including the 37 format checks for all seven report types. The seven redesigned Excel workbooks were imported, recalculated, scanned without formula errors, rendered and visually reviewed. A sample amount change updated its footer and headline totals correctly. PDF samples were rendered and reviewed; all text on all 70 generated pages stayed inside the page margins, and each final page retained totals with its signatures. The two changed PHP runtime files passed syntax checks. Installed business records, credentials and schema were unchanged; native Excel/browser/printer validation remains unavailable.

## Simplified reports and combined branch/account management

CSV downloads and their report controls are removed. Owner CSV URLs return HTTP 404 with guidance to use Excel or PDF; staff and anonymous requests retain their existing authorization failures. All seven report types retain complete Excel/PDF datasets, reconciled totals, filename conventions and owner restrictions. The large repeated summary panel is removed from report screens and full-print pages. Filtered detail records, live search, pagination and readable print records remain available; Excel/PDF summaries and totals are preserved.

One **Branches & accounts** navigation entry opens the branch-detail forms and searchable, paginated delivery-account list/editor on the same page. Legacy account-management URLs render the combined owner-only screen. Account forms save back to that screen; failed edits restore the existing account and allow its saved access code to remain unchanged. Branch assignments, six-digit code validation, duplicate protection and session revocation are unchanged.

All 458 authenticated HTTP checks passed, including 37 Excel/PDF/retired-CSV format checks and three combined-management checks. Another 57 focused interface checks passed for live/inline search, report detail views, code generation/validation and pagination (515 focused checks). Seven changed PHP runtime files passed syntax checks. Tests used disposable QA data; installed business records, credentials and schema were unchanged. Real-browser visual checks were unavailable. Earlier CSV and on-screen-summary verification entries describe the previous behavior.

## Full conversation request review

The application was reviewed against the supplied development specification and subsequent corrections. `REQUEST_CHECKLIST.md` records the resulting behavior; removed modules remain intentionally unavailable. The leftover POS payment-help sentence beneath checkout was removed, completing the earlier saved-receipts/footer removal request. Setup notes now use the combined management label and include the current LAN address for the deferred iPad test.

All current regression suites were run together: 458 authenticated HTTP checks, 26 pricing checks, 25 container-return checks and 209 interface checks passed, for 718 checks. Syntax checks passed for 73 PHP source/test files, excluding private configuration. A read-only check of all 14 installed orders found zero payment/status mismatches and zero amount-validation errors. Both branches and active worker codes passed the installed checks.

Localhost and LAN HTTP checks returned 200 for sign-in and 403 for private configuration, SQL and tool paths. Apache and MySQL are running as automatic services; the existing Apache TCP firewall allow rule is active. No network settings, business records or credentials were changed. The iPad test was explicitly deferred by the user. No browser/native-app surface was available for real-device or physical-printer visual verification.

## Customer-history item names removed

Expanded customer order lines now show `Quantity: 2` with the saved unit price and line total beneath, without the item name or an orphan multiplication sign. All 458 authenticated HTTP checks passed, including the updated customer-history quantity/price/payment/return check. Eight customer-account interaction checks and PHP syntax validation passed (466 focused checks). Stored transaction records and credentials were unchanged.

## Fixed-price products and visible POS catalog

The owner can create, search, edit, activate and price bottled water, new filled gallons and other fixed-price goods for one or both branches. Water refill prices stay editable per sale. Product cards are visible directly below the refill controls, with live name/size search and quantity entry. An open POS refreshes every 15 seconds and on returning to the page, preserving customer, quantity, custom refill price, tender and notes. Price changes on selected goods require confirmation; unavailable selected goods block checkout until removed. Stale owner branch contexts also block checkout. The server enforces current fixed prices, branch scope and owner-only catalog management.

The installed catalog upgrade appended product types and added 350 mL water at PHP 6, 500 mL water at PHP 10 and Gallon with water at PHP 150 in both branches. The upgrade fingerprint confirmed existing accounts, branches, customers, invoices, items, payments, deliveries, gallon movements and expenses were preserved. Legacy migration and repeated upgrades were checked in disposable databases. No network settings changed.

Product-only and mixed refill/product sales were verified through the authenticated HTTP API. Bottles and sold new gallons create no station loan; only station refills do. Mixed deliveries show their item list and validate all physical quantities. Reports include new filled gallons in gallon totals and exclude bottles, while retaining invoice totals and payment balances.

All current suites passed: 489 authenticated HTTP checks, 26 pricing checks, 25 gallon-return checks, four product-migration checks and 223 interface checks, totaling 767. Product-specific coverage includes 30 HTTP and 14 interaction checks. Twelve affected PHP runtime/migration/upgrade files and five test files passed syntax checks. The installed restricted runtime connection returned all three product prices for both branches, and local Apache sign-in returned HTTP 200. Real-browser visual and physical-printer checks were unavailable.

## Compact POS product dropdown

Products are now in a closed-by-default dropdown on the right of the refill heading. Search, fixed prices, quantity fields, product-only checkout and automatic catalog refresh remain available inside it. The summary shows the selected quantity. Escape and an outside click close the dropdown; a selected-price or availability issue opens it and marks the summary for review. The dropdown fits narrow screens and scrolls independently for large catalogs.

All 489 authenticated HTTP checks passed, together with 14 product, 49 POS and 24 customer-search interaction checks (576 focused checks). PHP and JavaScript syntax checks passed. Existing business data, catalog prices and credentials were unchanged. Real-browser visual verification was unavailable.

## Selected product quantity and locked price

Clicking a product in the POS dropdown now loads its name, saved price and quantity into the main quantity strip. The product price is read-only and its label changes to Product price; only water refill rates remain editable. Quantity typing, steppers and Enter update the item and bill automatically. Selecting goods removes the untouched default refill, while an explicitly entered refill is retained in mixed sales. Returning to the refill restores its separate quantity and custom rate. Station borrowing derives only from refill gallons, including while a bottled product is being edited. Fractional product quantities cannot be submitted. Existing server validation continues to reject fixed-price overrides.

All 489 authenticated HTTP checks passed, with 22 product interaction, 49 POS and 24 customer-search checks (584 focused checks). Coverage includes choosing a product without an unintended refill, locked prices, quantity updates, price-tampering attempts, invalid and zero quantities, restoring editable refills, mixed checkout prices and correct station borrowing. PHP and JavaScript syntax checks passed. Tests used disposable data; installed business records, prices, credentials and schema were unchanged. Real-browser visual verification was unavailable.

## Visible product tiles

The product dropdown is replaced by a visible grid of clickable tiles above the POS quantity strip. Each tile shows a prominent fixed price and product name; selected tiles show their quantity. Live search and automatic catalog refresh remain available. Clicking a tile selects it immediately and focuses the main quantity control. Product prices stay locked, refill rates stay editable, and quantities update the bill automatically. Selected unavailable products remain selectable so their quantity can be set to zero and checkout can continue.

All 489 authenticated HTTP checks passed, with 22 product, 49 POS and 24 customer-search interaction checks (584 focused checks). Price locking, product-only and mixed checkout, quantity validation, automatic refresh and unavailable-item removal were verified. PHP and JavaScript syntax checks passed. Installed business data, catalog prices, credentials and schema were unchanged. Real-browser visual verification was unavailable.

## POS product search removed

The product search label, input and filtering logic are removed from the POS. All active compact product tiles are directly selectable, and new admin products appear automatically. The Include water refill control, fixed-price locking, main quantity entry and payment calculations remain available. An empty catalog shows No products available.

All 489 authenticated HTTP checks, 22 product interaction checks, 49 POS checks and 24 customer-search checks passed (584 focused checks). PHP and JavaScript syntax checks passed. Installed business records, prices, credentials and schema were unchanged. Real-browser visual verification was unavailable.

## Return form opens only after customer selection

The main Record return navigation opens the ledger without a selected customer. The ledger no longer chooses its first customer automatically. Customer-account and POS return links open the intended ledger account without opening a popup; the customer-specific Record return button explicitly opens the form. Legacy new_return links also leave the form closed. Cleared accounts show no return action. Branch authorization, return validation, receipts and movement history remain available.

All 493 authenticated HTTP checks passed, including four new checks for staff/owner selection, explicit modal opening and branch isolation. Twenty-five return service checks and four dialog interaction checks passed (522 focused checks). All four changed PHP views passed syntax validation. Installed business records, prices, credentials and schema were unchanged. Real-browser visual verification was unavailable.
