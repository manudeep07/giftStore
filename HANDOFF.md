# GiftStore — payments & mail setup

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Demo accounts (after seed):

| Role     | Email                   | Password  |
|----------|-------------------------|-----------|
| Admin    | admin@customgift.com    | password  |
| Customer | customer@customgift.com | password |

---

## Mail (forgot password, order emails)

### Option A — Log driver (easiest local)

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=orders@customgift.test
MAIL_FROM_NAME="${APP_NAME}"
```

Emails are written to `storage/logs/laravel.log`.

### Option B — Mailtrap (see real HTML in inbox)

1. Create a free inbox at [mailtrap.io](https://mailtrap.io)
2. Set in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=orders@customgift.test
MAIL_FROM_NAME="${APP_NAME}"
```

### Test forgot password

1. Visit `/forgot-password`
2. Submit a seeded user email
3. Check log or Mailtrap for the reset link

### Order emails (HTML in `resources/views/emails/`)

| Email | When |
|-------|------|
| **OrderPlacedMail** | Razorpay payment succeeds → order `placed`, payment `paid` |
| **OrderCancelledMail** | Customer cancels order |
| **RefundProcessedMail** | Admin processes refund on cancelled paid order |
| **OrderStatusChangedMail** | Admin changes fulfillment status (shipped, etc.) |

### Cancel & refund flow (localhost)

1. Customer pays → order **placed**, payment **paid**, confirmation email
2. Customer opens order → **Cancel order** (only before shipment)
3. Order → **cancelled**, payment stays **paid**, cancellation email sent
4. Admin → `/admin/orders/{id}` → **Process refund**
5. Payment → **refunded**, refund email sent

---

## Razorpay

1. Create account at [dashboard.razorpay.com](https://dashboard.razorpay.com)
2. Use **Test mode** keys
3. Add to `.env`:

```env
RAZORPAY_KEY_ID=rzp_test_xxxxxxxx
RAZORPAY_KEY_SECRET=your_secret
RAZORPAY_WEBHOOK_SECRET=whsec_xxxxxxxx
```

### Checkout flow

1. Customer submits checkout → **pending** order + **pending** payment (cart cleared)
2. Redirect to `/checkout/orders/{id}/pay` → Razorpay modal
3. On success → signature verified → payment **paid**, order **processing**, stock decremented, confirmation email sent
4. On failure/dismiss → no charge; order stays unpaid; **Pay now** on order page

### Webhook (recommended for production)

1. Razorpay Dashboard → Webhooks → Add `https://your-domain.com/razorpay/webhook`
2. Event: `payment.captured`
3. Copy webhook secret to `RAZORPAY_WEBHOOK_SECRET`

Local testing with ngrok:

```bash
ngrok http 8000
# Use https://xxxx.ngrok.io/razorpay/webhook in Razorpay dashboard
```

---

## Payment design note

Orders are created **before** payment with status `pending` so shipping data is saved. They are **not** fulfilled (stock, coupon use, confirmation email) until Razorpay confirms payment. Unpaid orders can be paid later via **Pay now** on the order page.
