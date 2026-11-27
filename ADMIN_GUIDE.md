# BaryoHub administrator guide

Use Laravel 12 and PHP 8.2 or later. Run migrations before using the new modules. Register and verify an administrator account, then run `php artisan baryohub:grant-admin admin@example.test` with the actual account email.

Open the dashboard and select Admin management dashboard. Review resident records, approve or reject new accounts, classify indigent residents after independent eligibility verification, and manage officials. Senior counts are calculated from recorded dates of birth. Open the office queue to process requests; select the appropriate status and confirm with the resident before marking a document released. Review submitted incidents and update their status. Export document reports using Download CSV. Use Manage staff access for staff permissions and Publish barangay updates for announcements.

Never use real resident information in public demos. Limit staff access, configure HTTPS, back up the database securely, and establish authorized retention and incident handling procedures before production. The printable card is a prototype record, not an official barangay ID.
