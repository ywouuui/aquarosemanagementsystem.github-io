# Aqua Rose request review

Reviewed on October 22, 2026. Later corrections in the conversation take precedence over the original development PDF. Removed modules are intentionally unavailable.

| Area | Current behavior |
| --- | --- |
| Design and language | Aqua Rose green design, English interface, responsive page layouts and local assets. |
| Local setup | Existing XAMPP installation runs with automatic Apache/MySQL services. Deployment source, SQL schema and release archive are included. |
| Login | Delivery workers use unique six-digit codes; the owner uses a username and password. Account activation, code resets, validation and session revocation are enforced. |
| Login animation | Eyes follow the cursor automatically. No Play/Pause button is required; characters react to private typing and authentication errors. |
| Products | Owner adds fixed-price goods per branch or both branches. Products appear as visible clickable price-and-name tiles above the quantity controls and refresh automatically. The POS product search field is removed. Examples: 350 mL PHP 6, 500 mL PHP 10, new filled gallon PHP 150. Selecting a product loads its name and read-only price into the main quantity strip; quantity entry updates the bill automatically. Product-only and mixed sales are supported. |
| POS pricing | Staff enter the price and refill quantity. Refill product selection and gallon preset buttons are removed. Price and subtotal sit beside quantity. |
| Removed sale controls | No stock validation, discount, delivery fee or cash denomination shortcuts. The saved-receipts button and its leftover payment-help text are removed. |
| Customer selection | Live search by name, phone or address, with popup registration and automatic selection of the new customer. |
| Customer accounts | Clicking a customer opens orders, payments and gallon history recorded by all accounts within the authorized branch. Order line details show quantity, unit price and total without item names. Only the owner can generate customer reports. |
| Walk-in containers | Customer-owned gallons hide borrowing/return controls. Station borrowing automatically equals refill quantity; there is no separate borrowed-count field. |
| Delivery booking | The signed-in account is recorded as the delivery worker. Booking in POS immediately marks the order Out for Delivery, with no assignment or dispatch step. |
| Delivery arrival | The worker enters actual returned empties. Zero and partial returns are valid; outstanding station gallons stay on the customer ledger. |
| Delivery screen | POS-style handover, payment/change validation and retained entries after errors. The Call customer button and redundant worker/container instructions are removed. |
| Payment status | Unpaid and partially paid invoices cannot display Completed. Fulfillment and payment are tracked separately. |
| Gallon ledger | Actual movements, later returns and customer balances are retained. Return due dates, reminders, SMS and deposit/refund entry are removed. |
| Record lists | Filters, live search, database pagination and clickable customer/order/delivery rows remain available. The POS Failed tile and redundant Payments collection form are removed. |
| Owner management | One Branches & accounts page contains branch details and searchable, paginated delivery-account management. |
| Reports | Owner-only sales, delivery, payment, receivables, gallon, expense and individual-customer reports. Delivery performance, staff activity and branch comparison reports are removed. |
| Report screen | Filtered detail records remain. The repeated overview-style summary panel is removed. |
| Downloads | Excel and PDF only. CSV URLs are retired. Full matching data and reconciled totals remain in downloadable reports. |
| Export layout | Centered Aqua Rose heading, prepared-by/branch/date details, report title, period, bordered table, totals and signature lines. Order-number columns are omitted. |
| Export names | Report name - YYYY-MM-DD, with the customer name included for customer reports. |
| Simplified navigation | Finance is removed. Products & pricing is restored for owner-managed goods with fixed prices. Settings omits technical system information. |
| Validation and permissions | Server-calculated amounts, duplicate-submit protection, payment/return validation, owner-only reports/expenses and branch isolation are enforced. |

## Verification

- 493 authenticated HTTP checks, 26 pricing checks, 25 gallon-return checks, four catalog-migration checks and 231 interface checks passed: **779 checks** in the current regression suites.
- Syntax checks passed for 73 PHP source and test files, excluding private configuration.
- A read-only installed-database review checked all 14 existing orders: zero payment/status mismatches and zero amount-validation errors. Both configured branches and active worker codes were present.
- The sign-in page returned HTTP 200 through localhost and the computer's current LAN address. Private configuration, SQL and tool URLs returned HTTP 403 through both addresses.
- Existing Apache TCP access is allowed by Windows Firewall. No firewall, router, credentials or business records were changed during this review.
- The Excel/PDF layout review from the preceding update remains applicable; this review changed no workbook or PDF layout code.

## Checks left to the operator

The iPad access test was deferred at the user's request. On the same Wi-Fi, use **http://192.168.1.3/aquarosemanagementsystem/** while this computer is on. The address may change if the router assigns a new IP. Computer-side checks passed; successful access from the iPad has not been confirmed.

No browser or native app was attached to the computer-use tool during this review. Actual browser/iPad layout and physical receipt-printer checks therefore remain operator checks; automated interface checks are not a substitute for those observations.

The main Record return button opens the gallon ledger without selecting the first customer or automatically opening the form. Choose a customer, then use their account's Record return button.
