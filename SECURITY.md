# Deployment configuration

Set CAREERS_DB_DSN (PDO, including charset=utf8mb4), CAREERS_DB_HOST, CAREERS_DB_NAME, CAREERS_DB_USER and CAREERS_DB_PASSWORD outside source. Use a limited application account.

Revoke the previously committed remote database password at the provider, check access logs and rotate any reuse. Deletion from source does not revoke historical credentials. Do not copy its value into issues or pull requests.

All administration pages require the existing validated k_username session. POST forms use a session-bound CSRF token. Deletion links now display an authenticated confirmation page and only mutate after a verified POST.
