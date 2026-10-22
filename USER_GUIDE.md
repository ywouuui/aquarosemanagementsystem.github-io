# Aqua Rose user guide

## Access from an iPad

Connect the iPad and the XAMPP computer to the same Wi-Fi. In Safari, open `http://<computer-IP>/aquarosemanagementsystem/`, using the computer's Wi-Fi IPv4 address. `localhost` only works on the computer hosting the site. Keep that computer on with Apache and MySQL running. The current address for this installation is recorded in `output/LOCAL_SETUP.md`.

## Sign in

Choose **Staff sign-in** for your six-digit code or **Owner sign-in** for the owner's username and password. The characters start automatically: their eyes follow your mouse, and they cover their eyes when you enter a code or password. There is no animation button to click. Use the eye button to show or hide your code/password; it starts hidden. The background animation still respects your device's reduced-motion setting. On a smaller screen, the illustration sits above the form. An unsuccessful sign-in shows the reason and lets you try again.

## Owner: prepare the station

1. Select **Owner sign-in** on the login page and use your existing username and password. In **Branches & accounts**, set both stations' names, addresses and phone numbers.
2. In **Branches & accounts**, create one login per delivery worker and select their branch. Enter a unique **6-digit access code**, or click **Generate**. Share the code privately before saving; saved codes are hidden. Workers enter only this code on the main sign-in screen. To reset a code, open **Manage**, enter a different six-digit code and save. Leave the field blank to keep the existing code. Codes must be unique across both branches; leading zeros are supported. Deactivating or editing an account revokes old sessions.
3. In **New sale / POS**, verify **Price per gallon (PHP)** for each sale. **Products & pricing** lets the owner add goods with fixed selling prices. Water refill prices remain editable for each sale.
4. Use the **View** selector in the header to work in one branch or inspect combined records. Select the selling branch explicitly on the POS when viewing all branches.

## Add and sell products

The owner opens **Products & pricing**, enters the product name, type and selling price, chooses one branch or **Both branches**, then saves. Use today's effective date to sell the product immediately. Products are shown as visible tiles above the main quantity area in the POS, with each name and fixed price displayed. An open POS updates within 15 seconds or when you return to it. Selected tiles show their quantity. Click a product tile and enter its quantity in the main quantity area. The POS product search field is removed. Its name and fixed price appear automatically, and the bill updates as you type. The product price is read-only. A product selection replaces the untouched default refill; a refill you already edited stays in the bill. Use **Include water refill** in the Products section when adding a refill to a product sale. Water refill prices remain editable for each sale.

The starting examples are **350 mL water - PHP 6**, **500 mL water - PHP 10** and **Gallon with water - PHP 150**. Choose **Sell products only (no refill)** if the customer is buying goods without a refill. A sold new gallon belongs to the customer; only station-container refills create borrowed-gallon balances. Delivery details include the products and quantities to bring. Bottles contribute to the invoice amount and do not count as gallons in reports.

An owner price change preserves the current sale draft and asks the cashier to confirm updated prices before saving. Remove a deactivated product by setting its quantity to zero. Saved invoices keep their original prices.

## Delivery workers: record a walk-in sale

1. Sign in using your six-digit delivery access code, then open **New sale / POS**. Type a name, phone number or address into **Customer** and select a matching account. Leave the search empty for an anonymous walk-in. If the customer is new, click **Add "name"** (or press Enter when there are no matches), complete the popup and choose **Save & select customer**. The new account is selected without clearing the sale. Delivery customers need a complete address.
2. Choose Walk-in, enter the agreed **Price per gallon (PHP)**, then enter the quantity or use the + and - buttons. You do not need to choose a water product. The invoice total and change update immediately when you edit the price. Add caps or a new customer-owned container if needed.
3. If the customer uses station containers, select **Borrow from station**. Borrowing automatically uses the refill quantity; there is no separate borrowed-count display. Select or add a named customer before saving so the station gallons are tracked. **Customer-owned gallon** hides the returned-gallons section and clears station counts; it creates no station-container loan.
4. The signed-in worker counts the actual station empties received and enters **Returned now**. This can differ from the refill quantity. For example, issuing 2 station gallons and receiving 1 empty increases the customer's station-gallon balance by 1. If a customer-owned refill also involves returning an older station loan, record that separate return in **Gallon ledger**. A refill unit is not automatically a new container sale.
5. Select the payment method and type the amount actually received. The app shows change and any unpaid amount. Enter a transaction reference for GCash, bank and other payments.
6. Save the transaction. The button changes to **Saved · start a new sale**; opening the receipt does not create another sale. Print the receipt or save it as PDF from the browser.

The price starts with the branch's default refill rate. Staff can set a different positive rate for each sale, with up to two decimal places. A custom rate is saved on that invoice and recorded in the audit trail; it does not change the branch price list or older invoices. Add-ons retain their configured prices.

The branch's first active refill item with a current price supplies the default rate. Staff enter the actual quantity and agreed price. Customer borrowing and return records track station gallons independently.

## Finding records and moving between pages

Type in **Search** to see matching records automatically after a short pause. There is no need to press Enter or select a search dropdown. Clear the field to restore the list. Changing a status, date or other list filter also updates the results; **Apply filters** remains available. Deliveries can filter by customer/order/worker, delivery status, payment status and booking dates. Leave dates blank to include all booking dates. **Reset** clears the list filters. A changed search or filter starts at page one.

Choose **10, 20, 50 or 100 rows per page**. Numbered pages and **Previous / Next** keep your current filters and work with live search. Searching preserves an unfinished profile or payment form elsewhere on the page. The footer shows the visible record range, total matching records and current page. An old bookmark beyond the last page opens the last available page.

Gallon movement, delivery update and invoice installment histories have their own page controls. **Payments** contains the searchable, filtered payment/refund history and receipt links. Select an order number there to open its invoice.

In **Gallon ledger**, search/filter customers and select **History** before recording a return. The return dialog uses that customer's account. The summary totals include every matching customer, regardless of the current page.

Customer matching in POS and duplicate-account merging uses a text search with clickable inline matches. In delivery reports, type an account name and select the matching account; **Clear** restores all accounts. No full customer/account directory is downloaded for these choices. Gallon returns use the customer already selected in the ledger.

Owner reports show one page at a time. **Print all** opens every matching record for printing. **Excel / PDF** also include all matches, regardless of the current page.

## Customer accounts

Open **Customers** and click a customer row to view their account. Both staff and the owner can see contact details, order totals, unpaid invoices and current station gallons. Staff see customer records within their own branch, including transactions entered by other staff accounts.

Use **Orders & deliveries**, **Payments & refunds** and **Gallon history** to switch histories. Each section has live search, date/status filters and pagination. Open **View items** to see the quantities and saved prices on an order, or click its row to open the invoice. Delivery rows show the delivery status and actual empties returned after handover. Viewing the history does not grant permission to update another worker's delivery.

Use **Edit profile** to change customer details. A successful save returns to the account; invalid entries stay in the editor for correction. Inactive customers retain their history, and merged profiles link to their destination account.

The owner also sees **Generate customer report**. It opens that customer's orders across **All time**, including the recorded staff account, refill quantities, delivery status and returned empties. Select a shorter reporting period when needed. **Print all**, **Excel** and **PDF** include every matching order, even when the screen shows one page. Staff cannot open or export these reports.

## Partial and later collections

An order with a remaining balance stays **Unpaid** or **Partially paid**. **Completed** means it is fully paid and fulfilled. A fully paid delivery stays **Awaiting delivery** until delivery is confirmed. Open **Sales & orders**, search for the invoice, and click its row. Under **Record a payment**, enter the amount to apply and the amount actually received, and select the payment method. Each collection has its own receipt. The amount applied cannot exceed the current remaining balance. In **Sales & orders**, click an invoice row to see its complete payment history.

## Deliveries

1. Sign in with your own delivery account. In POS, select **Delivery**, choose or add the customer and verify their complete address. Choose the container type, enter the refill quantity. Delivery has no additional fee. **Borrow from station** automatically uses the refill quantity as the outgoing station-gallon count; **Customer-owned gallon** creates no station loan. There is no separate outgoing quantity to enter. The POS does not ask for returned empties before the trip. Your account is automatically recorded as the delivery worker.
2. Save the delivery in POS. It is automatically marked **Out for Delivery** with your account and the booking time. There is no separate assignment or dispatch action. Open it in **Deliveries** when you reach the customer.
3. At the customer's house, check the customer and address. Enter **Empty station gallons received**, use the large **+ / -** buttons, or choose **No empties returned** to record zero. The count starts at **0**. Keep zero if none are returned, or enter the actual count if some are received. Zero or partial returns do not prevent delivery completion. Check **Unreturned station gallons**; anything still with the customer stays recorded on their account.
4. Type only the **Amount received**. Payment applied, cash change and the unpaid invoice balance calculate automatically. For an electronic payment, enter its reference; the amount cannot exceed the balance. Leave zero when nothing is collected. Already-paid deliveries do not ask for a second collection. Add a receiver or notes under the optional section, then select **Confirm delivered**. If another collection changed the balance, review the updated amounts before confirming again.
5. If delivery fails, expand **Delivery problem?** and record the reason. Open the same delivery and select **Retry delivery** when retrying. Earlier updates are under **Delivery history & details**.
6. Delivery status updates and collections record the signed-in account. Workers can manage their own deliveries; the owner can inspect and manage all deliveries. A successful handover can still have an unpaid or partially paid invoice; the confirmation screen shows the balance and a **Record a later payment** link.

The final station-gallon balance is **previously held + new station gallons delivered - empty station gallons received**. For example, with no prior balance, delivering 5 and receiving 3 empties leaves 2 station gallons held. Customer-owned containers do not create station-container loans. Customer gallon movements and collected empties are posted only on successful delivery. Completion records the actual returns and an audit snapshot of the unreturned balance, including zero-return deliveries. The completed delivery links to the gallon ledger for later returns. Do not record the same physical return twice in the delivery and standalone return forms. Record later installments inside the invoice in **Sales & orders**, using your signed-in account as the collector.

## Gallon returns

The main **Record return** button opens the **Gallon ledger** without selecting a customer or showing a popup. Open **History** beside the intended customer, then click **Record return** in their account to open the form. Enter the number physically returned. Both intact and damaged returns reduce customer custody, with their condition recorded. The server rejects quantities above the customer's current outstanding balance.

Returns are tracked by outstanding quantity without a due date. Use the customer movement timeline to print a specific return receipt.

The owner can reconcile a verified lost container or count correction with a reason.

## Owner expenses

Record expenses against a branch or **Shared / head office**. Choose a category, date, amount and payment method. Upload a PDF / PNG / JPEG receipt if available (maximum 5 MB). Only the owner can access attachments. Edits and reversals require reasons; old values stay in the audit log.

Use **Reports & exports** for sales and delivery totals, payments, unpaid invoices and operating expenses. Shared expenses appear once in the all-branch expense report. The Finance section is removed.

## Print or export sales and delivery summaries

1. Sign in as the owner. Open **Reports & exports**, then **Sales & orders** for all POS orders or **Delivery summary** for delivery orders.
2. Choose the branch and reporting period. For specific dates, select **Custom range**, enter **From** and **To**, then select **Apply**. In Delivery summary, choose **Delivery date (completed only)** to report what actually arrived during those dates, or **Order date** to include bookings from those dates. Delivery account and status filters are optional.
3. Search for a customer, phone, staff account or order if needed. Matching records update while you type.
4. Choose **Print all**, then **Print report** to open the printer dialog. Choose **Excel** to download a formatted `.xlsx` workbook with the station heading, reporting period, prepared-by details, table and totals. Choose **PDF** for a landscape report.

Every export is named **Report name - YYYY-MM-DD**, using the Philippine export date. Examples: **Sales & orders - 2026-10-20.xlsx**, **Delivery summary - 2026-10-20.pdf** and **Gallon balances - 2026-10-20.xlsx**. Customer-report filenames also include the customer name. The selected reporting period is printed inside the report. Use a new export to obtain the updated format.

Excel and PDF follow the supplied report template: a large centered Aqua Rose heading, prepared-by/branch/report-date details, a centered report title and date range, a bordered detail table, totals, and blank **Prepared by** and **Reviewed by** signature lines. Long PDFs continue across numbered pages with repeated headings.

The report shows the customer, dates, delivered gallons, saved price per gallon, total amount, amount paid, unpaid balance, returned gallons and station gallons remaining. Order numbers are omitted from report tables, printed reports, Excel and PDF exports. Different saved refill prices appear separately. Bottles and accessories contribute to the invoice amount but are excluded from delivered gallons; new filled gallons are included. Pending deliveries do not count as delivered gallons, and their unconfirmed returns appear as a dash; actual zero returns display **0**.

The Excel and PDF summary totals cover **every matching order across all pages**. The repeated summary panel is removed from the Reports screen; the screen shows the filtered detail records. Cancelled orders remain in the history but are excluded from totals. Paid and unpaid amounts reflect the current balance of the selected invoices, including later payments. Returned gallons are the returns recorded with those orders. Remaining gallons are current customer balances, including earlier loans and later returns; each customer is counted once in the summary, even when they have several orders.

For just one customer, open their account and choose **Generate customer report**. It includes the same totals and print/export options.

## Owner reports and end of shift

Only the admin/owner can access reports, printing and Excel/PDF report exports. Choose Sales & orders, Delivery summary, Payment ledger, Current receivables, Gallon balances or Operating expenses. Individual customer reports open from the customer account. Select the branch view and period, then choose Print all, Excel, Excel or PDF. Delivery performance, Staff activity and Branch comparison reports are removed. Delivery reports can filter delivery account / status; expense reports can filter category, payee and amount. Current receivables and gallon balances represent all-history balances as of now. The report header states the filters, generated time and user.

Review unpaid invoices, pending deliveries and gallon balances. Sign out from the header at the end of the shift. Sessions expire after 30 minutes of inactivity.
