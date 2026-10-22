# Deployment and recovery

## Local installation

Requirements: PHP 8.2+ with PDO MySQL, Mbstring, Fileinfo, OpenSSL and Curl; MySQL 8 or MariaDB 10.4+; Apache 2.4. XAMPP's bundled PHP 8.2 and MariaDB 10.4 are supported. No runtime dependency installation is required.

Place the project in `C:\xampp\htdocs\aquarosemanagementsystem`. Its URL is `http://localhost/aquarosemanagementsystem/`. The directory can be renamed; links derive their base path automatically. Do not expose a default XAMPP installation directly to the public internet.

This computer's Apache originally had `AllowOverride None`. A scoped configuration was added at:

```
C:\xampp\apache\conf\extra\aquarose-management.conf
```

and included from `httpd.conf`. A backup of the original main configuration is `C:\xampp\apache\conf\httpd.conf.before-aquarose`. The administrator restart was completed during local setup and this rule is now active. A fresh installation on another server must also restart Apache after adding the rule. No global override setting was changed for other projects.

On another Apache server, adapt `deployment/apache.conf`, include it in the server configuration, run `httpd -t`, and restart Apache. Verify all of these return **403** before use:

```
/aquarosemanagementsystem/config/local.php
/aquarosemanagementsystem/database/aqua_rose_db.sql
/aquarosemanagementsystem/app/Database.php
/aquarosemanagementsystem/storage/
/aquarosemanagementsystem/tests/integration.php
```

Directory listing must be disabled. Copy the supplied `.htaccess` files, including hidden files. Nginx deployments must deny `app`, `config`, `database`, `storage`, `tests`, `tools`, `deployment`, dotfiles and documentation explicitly; `.htaccess` does not apply to Nginx. The bundled `router.php` is a **development** server router, not a public production server.

## First-run setup

Open the root URL locally, create an empty database and choose the owner's credentials. Setup is limited to loopback (`127.0.0.1` / `::1`). On a remote server, access setup through an SSH tunnel or perform the setup on the target server's local console. Do not grant remote users access to the installer.

The schema file does not drop existing tables. For manual import, first create a new empty database using UTF8MB4, select it, then import `database/aqua_rose_db.sql`. To use the normal first-run installer instead, leave the target database empty; the installer performs the import and creates the owner account. A failed partial import requires a fresh empty database, not a destructive automatic retry.

After setup, `config/local.php` sets `installed=true` and setup is locked. There are no hard-coded initial passwords. Choose **Owner sign-in** and use the owner username/password. In **Branches & accounts**, create workers with a unique six-digit access code. Workers use only their code and have no temporary-password step. Share codes privately.

## Upgrade an existing installation to code-only worker login

This checkout is already upgraded. For another version-one installation, back up the database and private configuration, take the app offline during the upgrade, and run from the project directory:

```powershell
# Use schema-administrator credentials in these environment variables if root/blank is not appropriate.
# AR_UPGRADE_ADMIN_USER and AR_UPGRADE_ADMIN_PASS are used only by the upgrade command.
C:\xampp\php\php.exe tools\upgrade-access-codes.php
```

The command uses the database selected by the existing private configuration. It adds nullable code-hash and installation-keyed lookup fields, a unique index, and schema version 2. It preserves existing usernames, password hashes, branch memberships, transactions and owner credentials. The restricted runtime account needs no additional grants. Run the command once before reopening the updated login page; repeat runs are safe.

The owner can then issue codes in **Branches & accounts**. To generate codes for existing workers immediately, run the command with `--issue-existing`. Only workers without a code are provisioned; re-running never changes issued codes. Generated codes are written once to a randomly named `DELIVERY-ACCESS-CODES-*.txt` file in private storage outside the application directory, never to the terminal or release archive. Restrict that directory to the operating-system administrator and web-server account before provisioning. Distribute each code privately and delete the delivery file afterward. Old worker sessions are revoked when their first code is issued; old worker passwords no longer authenticate. The owner keeps the existing username and password.

Preserve the private `instance_id` when moving or restoring this installation: it keys worker-code lookups as well as binding sessions. A different installation identity requires the owner to issue new worker codes. Five failed code guesses per address block further worker attempts for 15 minutes; owner sign-in uses its separate existing rate limit.

## Enable fixed-price products on an existing installation

After deploying this release, run the following on the database host using the existing private configuration:

```powershell
C:\xampp\php\php.exe tools\upgrade-products.php
```

The command uses the same optional `AR_UPGRADE_ADMIN_USER` / `AR_UPGRADE_ADMIN_PASS` environment settings as the account-code upgrade. It appends bottled-water and filled-gallon product types, adds the three requested example products and their prices to both branches, and verifies that existing business records and credentials are preserved. It keeps existing catalog IDs and price history. Repeated runs preserve owner-edited prices and add no duplicate products. Fresh installations from the supplied SQL already contain these products. The runtime application still requires only PHP and the database.

## Reconcile old invoice statuses

This computer has already been reconciled. Older versions marked a walk-in or delivered invoice completed even when money remained unpaid. After updating the code, preview the correction against the existing configured database:

```powershell
C:\xampp\php\php.exe tools\repair-order-statuses.php
# Apply after reviewing the preview:
C:\xampp\php\php.exe tools\repair-order-statuses.php --apply
```

The tool changes only incorrect order status fields and appends an audit entry for each correction. It preserves invoice amounts, collections, refunds, customer gallon movements and delivery records. It uses the existing restricted database connection; no schema change or owner password reset is needed. Re-running is safe. Invoices whose paid amount plus balance does not equal their total are left unchanged and listed for review; the application blocks further collections against them.

## Production hardening

1. Use a maintained PHP / database / web-server release, a dedicated virtual host and **HTTPS**. Redirect HTTP to HTTPS in the host configuration. The app automatically marks session cookies Secure over HTTPS. Set `environment` to `production` in `config/local.php`.
2. Restrict database access to the app server. Replace the installation DB account with a dedicated runtime account. Use the grants in `database/least-privilege.sql` after creating that account with a unique strong password. The app needs no runtime schema changes; audit logs need INSERT and SELECT only.
3. Grant the web-server user read access to code and assets, write access only to private storage and (during installation) the configuration directory. Make configuration read-only after setup. Keep `config/local.php` out of version control and deployment archives.
4. Set `storage_path` in `config/local.php` or `AR_STORAGE_PATH` to a stable, writable directory **outside the document root**. The default on this XAMPP checkout is `C:\xampp\aquarose-private\aquarosemanagementsystem`. It contains uploaded expense receipts and `application.log`. Uploaded names are random `.dat` files, served only by the owner-authorized download endpoint.
5. Keep PHP `display_errors=Off` and log errors to private storage. Back up and rotate logs. Use filesystem and database permissions to protect audit records from direct tampering.
6. Remove the development tests/tools from a public production checkout if they are not needed. They are denied by Apache, and test scripts additionally refuse web execution / non-QA databases.
7. Validate the complete acceptance checklist with real printer settings, branch addresses, selling prices, expense categories and operator accounts.

Configuration can also read `AR_DB_HOST`, `AR_DB_PORT`, `AR_DB_NAME`, `AR_DB_USER`, `AR_DB_PASS`, `AR_ENV` and `AR_STORAGE_PATH`. Generated local configuration takes precedence for database settings. There are no API secrets required for core operation.

Setup generates a unique `instance_id` in private configuration. Preserve it when moving the same installation; generate a new ID when creating an independent installation. Sessions are bound to that ID so a test-installation session cannot authenticate into a later business installation.

## Backup and restore

Back up both the SQL database and private receipt storage. Keep an encrypted copy of `config/local.php` separately with access limited to the owner / administrator.

Example backup in PowerShell (the password prompt avoids embedding credentials):

```powershell
C:\xampp\mysql\bin\mysqldump.exe -u aqua_rose_app -p --single-transaction --routines --triggers --result-file=C:\backups\aqua-rose.sql aqua_rose_management
```

Use a separate backup account if the runtime account lacks required permissions. Copy private storage to a protected backup location. Do not put backups beneath `htdocs`. Schedule daily backups and test restoration on a different database; keep several generations.

To restore, create a **new** empty database, import the backup in phpMyAdmin (or the MySQL client), restore the private storage directory, and update private configuration to the restored database and storage path. Verify row counts, recent invoices, receivables, container balances, uploaded receipts and branch restrictions before switching traffic. Never overwrite the only good database copy during a recovery test.

## Go-live checklist

- [ ] Apache private-path checks return 403; the installer is locked after setup.
- [ ] HTTPS enabled for network / public use; secure cookies confirmed.
- [ ] Restricted runtime DB credentials configured; configuration not publicly readable.
- [ ] Both branches renamed, with correct addresses and contacts.
- [ ] Owner password chosen privately; unique worker access codes issued and shared privately.
- [ ] Delivery accounts created in the correct branches.
- [ ] Branch price cards checked; new transactions start with real data.
- [ ] Walk-in, partial collection, delivery, gallon return and expense workflow tested.
- [ ] Receipt paper size and actual printer tested by an operator.
- [ ] Backup and restore verified; private storage write permission checked.

Excel exports require PHP zlib (`gzdeflate`), included in this XAMPP installation. Deploy `app/report-workbook-styles.json` with the application; it is included in the release archive. No ZipArchive extension, Composer package, Node.js runtime or cloud spreadsheet service is required for exports.
