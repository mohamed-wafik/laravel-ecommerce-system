# Admin Panel — Migrations, Models & Services (Laravel)

Generated from your ERD. Covers all 14 entities: Users, Otps, Addresses,
Categories, Brands, Products, ProductImages, ProductVariants, Carts,
CartItems, Orders, OrderItems, Payments, Reviews.

## What's included

```
database/migrations/   20 migrations (14 from the ERD + webhook_events, category image_public_id,
                        guest cart support, payments.stripe_checkout_session_id, settings table, users.google_id)
app/Models/             16 Eloquent models (uuid PKs, relationships, casts, scopes)
config/auth.php         `sanctum` guard (customers) + dedicated `admin` guard
app/Contracts/          PaymentGateway interface
app/DataTransferObjects/  CheckoutSessionResult (provider-agnostic checkout result)
app/Services/Payment/   StripePaymentGateway, PaymentGatewayFactory, PaymentGatewayException
app/Services/Checkout/  CartPricer, AddressResolver, DeliveryFeeCalculator
app/Services/Concerns/  ReplacesCloudinaryImage, HashesPassword — shared logic, used across services
app/Http/Middleware/    EnsureUserIsAdmin, EnsureGuestToken
app/Providers/          AppServiceProvider — Gate::define('admin', ...) + rate limiters
app/Http/Controllers/Controller.php   Base controller — success()/error()/paginatedSuccess() envelope
app/Services/Admin/     Admin service layer — one service per entity + BaseCrudService
app/Services/Guest/     CatalogService (browsing), GuestCartService (pre-login cart)
app/Services/Customer/  CartService, ProfileService, AddressService (post-login, self-scoped)
app/Services/           AuthService, OtpService, CheckoutService, SettingService, StripeWebhookService, CloudinaryService
app/Http/Controllers/Admin/     Admin controllers (incl. Auth, Media, Setting)
app/Http/Controllers/Auth/      Customer AuthController + SocialAuthController (Google OAuth)
app/Http/Controllers/Guest/     CatalogController, CartController
app/Http/Controllers/Customer/  ProfileController, AddressController, CartController, CheckoutController
app/Http/Controllers/Webhooks/  StripeWebhookController (public, signature-verified)
app/Http/Requests/Admin/        Form Requests for product/brand/category/user/login
app/Http/Requests/Auth/         Form Requests for register/login/otp/reset-password/google
app/Http/Requests/Customer/     CheckoutRequest, UpdateProfileRequest, ChangePasswordRequest, AddressRequest
routes/admin.php        Admin API routes (admin guard + role middleware)
routes/auth.php         Customer auth routes incl. Google OAuth (rate-limited)
routes/guest.php        Public storefront routes (rate-limited)
routes/customer.php     Profile, addresses, post-login cart + checkout (auth:sanctum, rate-limited)
routes/webhooks.php     Public webhook route
database/factories/     A factory per model, used by the test suite
tests/Fakes/             FakeCloudinaryService, FakePaymentGateway — no real external calls in tests
tests/Concerns/          FakesExternalServices, InteractsWithAuthGuards
tests/Feature/           Auth/, Admin/, Guest/, Customer/, Webhooks/ — one suite per controller area
```

## How to use in an existing Laravel app

1. Copy `database/migrations/*` into your project's `database/migrations/`.
2. Copy `app/Models/*` into `app/Models/`.
3. Copy `app/Contracts/*` into `app/Contracts/` and `app/DataTransferObjects/*`
   into `app/DataTransferObjects/`.
4. Copy `app/Services/**` in full — `Admin/`, `Guest/`, `Customer/`,
   `Checkout/`, `Payment/`, `Concerns/`, plus the top-level `AuthService.php`,
   `OtpService.php`, `CheckoutService.php`, `SettingService.php`,
   `CloudinaryService.php`, `StripeWebhookService.php` — into `app/Services/`.
5. Copy `app/Http/Controllers/**` (`Admin/`, `Auth/`, `Guest/`, `Customer/`,
   `Webhooks/`) into `app/Http/Controllers/`.
6. Copy `app/Http/Requests/**` (`Admin/`, `Auth/`, `Customer/`) into `app/Http/Requests/`.
7. Copy `app/Http/Middleware/*` into `app/Http/Middleware/`.
8. Copy `config/auth.php` into `config/` (merge with your existing one if
   you've customized it — the important part is the `sanctum` and `admin`
   guard entries).
9. Copy `app/Providers/AppServiceProvider.php` in (or merge the
   `Gate::define` + `configureRateLimiters()` calls into your existing one).
10. Copy `routes/admin.php`, `routes/auth.php`, `routes/guest.php`,
   `routes/customer.php`, `routes/webhooks.php` into `routes/`, then
   register all five in `routes/api.php`:
   ```php
   Route::prefix('admin')->group(base_path('routes/admin.php'));   // guard applied inside the file
   Route::prefix('auth')->group(base_path('routes/auth.php'));     // throttles applied inside the file
   Route::prefix('guest')->middleware('throttle:guest')->group(base_path('routes/guest.php'));
   Route::prefix('customer')->middleware('auth:sanctum')->group(base_path('routes/customer.php'));
   require base_path('routes/webhooks.php');                        // no prefix/guard — public
   ```
11. Register the `admin` middleware alias in `bootstrap/app.php`:
    ```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['admin' => \App\Http\Middleware\EnsureUserIsAdmin::class]);
        $middleware->validateCsrfTokens(except: ['webhooks/stripe']);
    })
    ```
12. Since primary keys are UUIDs, `User` uses `HasUuids` + `HasApiTokens`
    (Sanctum) and extends `Authenticatable` with `password_hash` (not the
    default `password` column) — `getAuthPassword()` is overridden accordingly.
13. Run:
    ```
    composer require laravel/sanctum laravel/socialite stripe/stripe-php cloudinary/cloudinary_php doctrine/dbal
    php artisan install:api   # publishes Sanctum's config/migration if not already present
    php artisan migrate
    ```
    `doctrine/dbal` is needed specifically for migration `2024_01_01_000017`,
    which uses `Schema::table(...)->change()` to make `carts.user_id` nullable.
14. Set in `.env`:
    ```
    CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
    STRIPE_SECRET=sk_test_...
    STRIPE_WEBHOOK_SECRET=whsec_...
    STRIPE_CHECKOUT_SUCCESS_URL=https://your-frontend.com/checkout/success
    STRIPE_CHECKOUT_CANCEL_URL=https://your-frontend.com/checkout/cancelled
    PAYMENT_GATEWAY=stripe
    GOOGLE_CLIENT_ID=...
    GOOGLE_CLIENT_SECRET=...
    GOOGLE_REDIRECT_URI=https://your-api.com/auth/google/callback
    ```
    and add to `config/services.php`:
    ```php
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'checkout_success_url' => env('STRIPE_CHECKOUT_SUCCESS_URL'),
        'checkout_cancel_url' => env('STRIPE_CHECKOUT_CANCEL_URL'),
    ],
    'payment' => ['default' => env('PAYMENT_GATEWAY', 'stripe')],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
    ```

## Response envelope (base Controller)

Every admin controller extends `App\Http\Controllers\Controller`, which
gives them:

```php
$this->success($data, 'message', 200);       // { success: true,  message, data }
$this->created($data, 'message');            // 201 shortcut
$this->error('message', 400, $errors);       // { success: false, message, errors }
$this->notFound() / unauthorized() / forbidden() / validationError($errors);
$this->paginatedSuccess($paginator);         // wraps items + pagination meta
$this->noContentSuccess('message');          // 200 with data: null, for deletes/toggles
```

So every endpoint returns the same shape whether it succeeded or failed —
one response interceptor on the frontend/mobile app handles all of them.
`StripeWebhookController` deliberately does **not** use this envelope,
since Stripe just needs a bare 200/4xx, not a JSON body.

To catch framework-level exceptions (404 route-model-binding misses,
`ValidationException`, auth failures) in the same envelope, hook Laravel's
exception handler in `bootstrap/app.php`:

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
        if ($request->is('api/*')) {
            return response()->json([
                'success' => false, 'message' => $e->getMessage(), 'errors' => $e->errors(),
            ], 422);
        }
    });
    $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
        if ($request->is('api/*')) {
            return response()->json(['success' => false, 'message' => 'Resource not found', 'errors' => null], 404);
        }
    });
})
```

## Cloudinary (image uploads)

1. `composer require cloudinary/cloudinary_php`
2. In `.env`:
   ```
   CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
   ```
   The SDK reads this automatically — no `config/cloudinary.php` needed.
3. **Flow**: the admin UI uploads a file to `POST /admin/media` (or
   `/admin/media/bulk` for several) first, gets back `{url, public_id}`,
   then sends that pair as part of the product/brand/category/user payload.
   `DELETE /admin/media` removes an asset by `public_id` directly (e.g. a
   gallery image the admin removed before saving).
4. **Supported formats**:
   - `POST /admin/media` / `/admin/media/bulk` — images: png, jpg/jpeg,
     gif, webp, bmp, svg, heic/heif, avif, tiff, ico (10MB max). Validated
     explicitly with `mimes:...` rather than Laravel's built-in `image`
     rule, which misses heic/avif/tiff — common from phone cameras and
     design tools.
   - `POST /admin/media/document` — non-image files (pdf, doc/docx,
     xls/xlsx, csv, txt), stored on Cloudinary under `resource_type: raw`
     (20MB max). Use this for things like return-request attachments or
     invoices, not product/brand imagery.
   - `DELETE /admin/media` accepts an optional `resource_type` (`image` by
     default, or `raw`/`video`) so it can delete whichever kind of asset
     you uploaded.
   - If your PHP `upload_max_filesize` / `post_max_size` in `php.ini` are
     below these limits, requests will fail before validation even runs —
     raise both to match (e.g. `20M`).
5. `CloudinaryService` methods: `upload()`, `uploadMany()`, `replace()`
   (upload-then-delete-old, for avatar/logo-style single-image fields),
   `destroy()`, `destroyMany()`. `upload()` takes an `$options` array
   that's merged over the defaults, so you can pass `resource_type`,
   `public_id`, transformations, etc. straight through to Cloudinary.
6. Already wired into the services that own an image field, so stale
   Cloudinary assets don't pile up:
   - `ProductService` deletes the old gallery images from Cloudinary when
     `images` is replaced on update, and when a product is deleted.
   - `BrandService` deletes the old `logo_public_id` when a new one is
     saved, and on brand delete.
   - `UserService` deletes the old `avatar_public_id` when a new one is
     saved.
   - `CategoryService`/`Category.image_url` and `ProductVariant` don't
     track a `public_id` in the ERD, so they aren't cleaned up
     automatically — add a `public_id` column if you want the same
     treatment there.

## Direct file uploads — brand logo, category image, user avatar

Same one-request pattern as products: `logo` / `image` / `avatar` are sent
as real files inside the create/update request itself, not pre-uploaded via
`/admin/media` first. Each service uploads to Cloudinary internally and
swaps the file for `{x}_url` / `{x}_public_id` before saving:

| Resource | Request field | Service method | Stored as |
|---|---|---|---|
| Brand | `logo` | `BrandService::handleLogo()` | `logo_url`, `logo_public_id` |
| Category | `image` | `CategoryService::handleImage()` | `image_url`, `image_public_id`* |
| User | `avatar` | `UserService::handleAvatar()` | `avatar_url`, `avatar_public_id` |
| Product | `images[]` | `ProductService::syncImages()` | `product_images` rows |

\* `image_public_id` isn't in the original ERD (it only had `image_url`
for categories) — migration `2024_01_01_000016` adds it so category images
get the same cleanup-on-replace/delete treatment as brands/users/products.

Example — create a brand with a logo (multipart/form-data):
```
name    Nike
logo    (file) nike-logo.png
is_active  1
```

Updating without touching the file — just omit it:
```
PUT (spoofed via POST + _method=PUT)
name    Nike Inc.
```
The existing logo stays untouched since `handleLogo()` only acts when a
new file is actually present. Sending a new `logo` file deletes the old
Cloudinary asset and stores the new one; deleting the brand/category/user
itself also cleans up its image on Cloudinary.

All four `Store*Request` / `Update*Request` classes validate the file the
same way as products:
`mimes:jpg,jpeg,png,gif,webp,bmp,svg,heic,heif,avif,tiff,ico`, max 10MB.

**Reminder**: browsers/PHP can't send a real `PUT` with multipart file
data, so update requests with a file must be sent as `POST` with a spoofed
`_method=PUT` field — Laravel handles the rest automatically.

## Admin guard & permissions

Access to every admin route is gated by a dedicated `admin` Sanctum guard,
kept separate from any customer-facing `web`/`sanctum` guard even though
both currently read the same `users` table.

**Files**:
- `config/auth.php` — defines the `admin` guard (`driver: sanctum`).
- `app/Http/Middleware/EnsureUserIsAdmin.php` — runs after `auth:admin`;
  checks `role === 'admin'` **and** `is_active === true`, so deactivating
  an admin invalidates their access immediately even if their token is
  still technically valid.
- `app/Providers/AppServiceProvider.php` — also defines `Gate::define('admin', ...)`
  as a lighter-weight equivalent, for spot-checks (`Gate::authorize('admin')`,
  `@can('admin')`) outside route middleware.
- `app/Http/Controllers/Admin/AuthController.php` — `login`/`logout`/`me`.
  Login only succeeds for `role === 'admin'`, issues a Sanctum token scoped
  to the `admin` ability (`$user->createToken('admin-panel', ['admin'])`),
  and every other admin route requires that token.

**Setup**:
1. `composer require laravel/sanctum` if not already installed, then
   `php artisan install:api` (or manually publish Sanctum's config/migration).
2. Register the middleware alias in `bootstrap/app.php`:
   ```php
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
           'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
       ]);
   })
   ```
3. Register the routes in `routes/api.php`:
   ```php
   Route::prefix('admin')->group(base_path('routes/admin.php'));
   ```
   (The guard + role middleware are applied *inside* `routes/admin.php`
   itself, wrapping every route except `POST /admin/login`.)
4. Copy `app/Providers/AppServiceProvider.php` in (or merge the `Gate::define`
   call into your existing one).

**Usage**:
```
POST /admin/login          { email, password }        -> { token, user }
Authorization: Bearer <token>   on every other /admin/* request
POST /admin/logout                                     -> revokes that token
GET  /admin/me                                          -> current admin user
```

A request with no token, an expired token, a valid token for a
`role: customer` user, or a valid token for a deactivated admin all get
rejected the same way — 401 for "not authenticated at all", 403 for
"authenticated but not authorized" — using the same success/error envelope
as the rest of the API.

## Customer auth (register → OTP → tokens, login, forgot password)

`routes/auth.php` + `app/Http/Controllers/Auth/AuthController.php` +
`app/Services/AuthService.php` + `app/Services/OtpService.php`.

**Register → verify → tokens**
```
POST /auth/register          { name, email, password, password_confirmation }
                              -> account created (unverified), OTP emailed
POST /auth/register/verify    { email, code }
                              -> { access_token, refresh_token, user }
```

**Login — OTP-gated until the account is verified**
```
POST /auth/login              { email, password }
  - if email_verified_at is set  -> { verified: true, access_token, refresh_token, user }
  - if NOT verified              -> { verified: false } and a fresh OTP is sent
POST /auth/login/verify       { email, code }
                              -> { access_token, refresh_token, user }
```

**Forgot / reset password**
```
POST /auth/forgot-password    { email }   -> always the same response,
                                              whether or not the email
                                              exists (no account enumeration)
POST /auth/reset-password     { email, code, password, password_confirmation }
                              -> password changed, every existing session
                                 (all devices) is signed out
```

**Tokens — access/refresh pair, refresh token rotates**
- `access_token`: 30 minutes, ability `access-api` — send as `Authorization: Bearer <token>` on every authenticated request.
- `refresh_token`: 30 days, ability `issue-access-token` — send only to `/auth/refresh`.
- `POST /auth/refresh` (needs `Authorization: Bearer <refresh_token>`) deletes
  that refresh token and issues a brand-new pair — a stolen refresh token
  only works once before the real client's next refresh invalidates it.
- `POST /auth/logout` revokes the current token; `POST /auth/logout-all`
  revokes every token for the account (all devices).

**Setup**: same Sanctum install as the admin guard section below; these
routes run on the plain `sanctum` guard (`config/auth.php`), not `admin`.
Add `HasApiTokens` is already applied to `User` — no changes needed there.

## Rate limiting

Defined in `app/Providers/AppServiceProvider.php::configureRateLimiters()`,
applied per-route via `throttle:<name>`:

| Limiter | Rule | Applied to |
|---|---|---|
| `auth` | 10/min per IP | `/auth/register`, `/auth/login` |
| `otp` | 3/min per email **and** 20/min per IP | verify/forgot/reset-password endpoints |
| `refresh` | 10/min per user (or IP if somehow unauthenticated) | `/auth/refresh` |
| `guest` | 120/min per guest token (or IP) | all of `routes/guest.php` |

`otp` uses two limits at once (per-identifier AND per-IP) — per-identifier
stops someone spamming one victim's inbox, per-IP stops one attacker
spraying codes across many emails. This is on top of, not instead of, the
5-wrong-attempts-per-code cap already enforced inside `OtpService::verify()`.

## Guest services (public storefront, no login required)

`routes/guest.php` + `app/Http/Controllers/Guest/` +
`app/Services/Guest/CatalogService.php` + `GuestCartService.php`.

This is the anonymous-visitor side big e-commerce apps all have: you can
browse the whole catalog and build a cart before creating an account —
Amazon, Noon, most Shopify storefronts all work this way. Everything here
is scoped to active/approved data only (no draft products, disabled
brands, or unmoderated reviews leak through).

**Browsing**
```
GET /guest/categories                  -> nested category tree
GET /guest/brands                      -> active brands
GET /guest/products?search=&category_id=&brand_id=&min_price=&max_price=&sort=
                                        -> paginated, sort: newest|price_asc|price_desc|name_asc|best_selling
GET /guest/products/{slug}             -> product + images + variants + rating summary
                                           + first page of approved reviews + related products
GET /guest/products/{id}/reviews       -> paginated approved reviews
GET /guest/search?q=                   -> lightweight autocomplete (products + categories)
```

**Guest cart** — keyed by an opaque `X-Guest-Token` header instead of a
user id, so it works with zero auth:
```
POST   /guest/cart/token               -> { guest_token } — call once, store it client-side
GET    /guest/cart                     (needs X-Guest-Token)
POST   /guest/cart/items               { product_id, variant_id?, quantity }
PATCH  /guest/cart/items/{itemId}      { quantity }   (0 removes the item)
DELETE /guest/cart/items/{itemId}
```

**Merging into the account on login/register** — send the same
`X-Guest-Token` header on `/auth/login`, `/auth/login/verify`,
`/auth/register/verify`. `AuthController` calls
`GuestCartService::mergeIntoUser()` right after tokens are issued: if the
user already has a cart, guest items are folded in (quantities combined);
otherwise the guest cart just becomes theirs. Nothing on either side is lost.

**Schema note**: the ERD's `carts.user_id` was `NOT NULL UNIQUE`, which
can't represent a guest cart. Migration `2024_01_01_000017` makes it
nullable and adds `guest_token` — every cart has exactly one of the two
set (enforced in `GuestCartService`, not a DB constraint, since a
mutually-exclusive-columns CHECK isn't portable across MySQL/Postgres/SQLite
the same way). **This migration uses `->change()`, which needs
`composer require doctrine/dbal`.**

## Checkout (cart → order → Stripe Checkout Session)

`routes/customer.php` + `app/Http/Controllers/Customer/` +
`app/Services/CheckoutService.php` + `app/Services/Customer/CartService.php`.

```
GET    /customer/cart                      -> the logged-in user's cart
POST   /customer/cart/items                { product_id, variant_id?, quantity }
PATCH  /customer/cart/items/{itemId}       { quantity }
DELETE /customer/cart/items/{itemId}
POST   /customer/checkout                  { shipping_address_id, billing_address_id?, idempotency_key }
                                            -> { order, checkout_url, checkout_session_id }
```

**What `POST /customer/checkout` does, all inside one DB transaction**:
1. If `idempotency_key` matches an existing order, returns that order's
   existing checkout URL as-is — no new order, no new session, no stock
   touched again. Generate this key once per checkout attempt on the
   client and reuse it on retry (dropped connection, double-tapped button).
2. Loads the user's active cart; 422s if it's empty.
3. Loads `shipping_address_id` (and `billing_address_id` if given) from
   the user's **own** saved addresses — every address field on the order
   comes from there, not from free-text in the request.
4. Locks each product/variant row (`lockForUpdate`) and re-checks stock
   against the live row — not the cart's stale snapshot — so two
   customers can't both check out with the last unit of something.
5. Builds one `OrderItem` per cart item (name/price snapshotted at
   checkout time, per the ERD) and decrements stock immediately —
   inventory is reserved as soon as the order exists, not only once paid.
6. Creates a Stripe **Checkout Session** (`mode: payment`) — Stripe's own
   hosted payment page — with one line item per order item plus separate
   tax/shipping lines if non-zero, and a `Payment` row (`status: pending`)
   storing the session id.
7. Marks the cart `converted` and returns `{ order, checkout_url, checkout_session_id }`.

**Frontend**: redirect the browser to `checkout_url` immediately — that's
Stripe's hosted page, not something to render locally:
```js
const res = await fetch('/customer/checkout', { method: 'POST', body: ... });
const { data } = await res.json();
window.location.href = data.checkout_url;
```
Stripe sends the customer back to `success_url` (`?order_id=...&session_id=...`)
or `cancel_url` (`?order_id=...`) depending on outcome — both are plain
frontend routes you build; they only need to show status, since payment
confirmation itself happens server-side via webhook, not by the presence
of a query param.

**Nothing here marks the order `paid` directly.** `StripeWebhookService`
handles that:
- `checkout.session.completed` — the main signal. Only flips the order to
  `paid` when the session's `payment_status` is `paid` (some payment
  methods complete the session before the payment itself settles; those
  wait for `payment_intent.succeeded` instead, already handled from the
  earlier PaymentIntent-based flow, kept as a fallback).
- `checkout.session.expired` — the customer abandoned Stripe's page. Since
  stock was reserved at step 5 above, this **cancels the order and puts
  the stock back**, so an abandoned checkout doesn't hold inventory hostage
  indefinitely.
- `charge.refunded` — unchanged from before.

**Setup**: `config/services.php`:
```php
'stripe' => [
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'checkout_success_url' => env('STRIPE_CHECKOUT_SUCCESS_URL'),
    'checkout_cancel_url' => env('STRIPE_CHECKOUT_CANCEL_URL'),
],
```
and in `.env`:
```
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
STRIPE_CHECKOUT_SUCCESS_URL=https://your-frontend.com/checkout/success
STRIPE_CHECKOUT_CANCEL_URL=https://your-frontend.com/checkout/cancelled
```
Remember to also enable `checkout.session.completed` and
`checkout.session.expired` (alongside the existing `payment_intent.*` /
`charge.refunded`) in the Stripe Dashboard webhook configuration, or the
CLI (`stripe listen --events checkout.session.completed,checkout.session.expired,...`)
while testing locally.

**Tax/shipping**: `CheckoutService::TAX_RATE` / `FLAT_SHIPPING_FEE` are
flat placeholders (both `0.0`) — swap in a real per-region tax table or
shipping-rate lookup when you have one; everything downstream (order
totals, the extra Checkout Session line items) already flows from those
two constants.

## Payment gateway — Factory pattern

`app/Contracts/PaymentGateway.php` + `app/Services/Payment/` +
`app/DataTransferObjects/CheckoutSessionResult.php`.

`CheckoutService` never touches the Stripe SDK directly — it depends on
the `PaymentGateway` interface, obtained from `PaymentGatewayFactory::make()`:

```php
interface PaymentGateway
{
    public function createCheckoutSession(Order $order, array $lineItems, string $currency, string $successUrl, string $cancelUrl, array $metadata = []): CheckoutSessionResult;
    public function expireCheckoutSession(string $sessionId): void;
    public function retrieveCheckoutSessionUrl(string $sessionId): ?string;
    public function name(): string;
}
```

`StripePaymentGateway` is the only class that imports the Stripe SDK for
checkout. Adding PayPal/Paymob/another provider later is:
1. Write `PayPalPaymentGateway implements PaymentGateway`.
2. Add one `match` arm in `PaymentGatewayFactory::build()`.

Nothing in `CheckoutService` changes — that's the point of the pattern
(Open/Closed Principle: open to new providers, closed to modifying
checkout logic). Which provider is active is `config('services.payment.default')`,
defaulting to `stripe`:
```php
// config/services.php
'payment' => ['default' => env('PAYMENT_GATEWAY', 'stripe')],
```

## Google OAuth (register/login)

`routes/auth.php` + `app/Http/Controllers/Auth/SocialAuthController.php` +
`AuthService::loginWithGoogle()`.

Two entry points, same result:
```
POST /auth/google             { id_token }   -> { access_token, refresh_token, user }
GET  /auth/google/redirect                    -> redirects to Google (classic OAuth)
GET  /auth/google/callback                     -> Google redirects back here -> tokens
```
`POST /auth/google` is the one an SPA/mobile app actually wants: the
client uses Google's own Sign-In SDK (Google Identity Services on web,
GoogleSignIn on iOS/Android) to get an ID token, then posts it here — no
redirect through this backend at all. `redirect`/`callback` exist for a
classic server-driven OAuth flow if you need it.

A Google login skips the OTP step entirely (Google already verified the
email) and issues tokens immediately. Accounts are linked by `google_id`,
falling back to matching `email` — so someone who registered the normal
way and later taps "Sign in with Google" on the same address lands in
their existing account (with `google_id` backfilled) instead of a
duplicate one. Same `X-Guest-Token` merge-on-login behavior as every other
auth entry point.

**Setup**:
```
composer require laravel/socialite
```
```php
// config/services.php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```
```
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=https://your-api.com/auth/google/callback
```
Migration `2024_01_01_000020` adds `users.google_id` (nullable, unique).

## App settings (admin-configurable, single source of truth)

`app/Models/Setting.php` + `app/Services/SettingService.php` +
`app/Http/Controllers/Admin/SettingController.php`.

Key-value store (`settings` table, cached) for values that shouldn't be
hardcoded constants scattered across services — `tax_rate`,
`delivery_fee_standard`, `delivery_fee_express`, `free_delivery_threshold`,
`currency`, `store_name`. `DeliveryFeeCalculator` and `CheckoutService`
both read from here, so there's exactly one place that defines "what's the
standard delivery fee" — change it in the admin panel, checkout picks it
up on the next request (cache is invalidated on write).

```
GET   /admin/settings          -> all settings (defaults merged with stored overrides)
PATCH /admin/settings          { tax_rate?, delivery_fee_standard?, delivery_fee_express?, free_delivery_threshold?, currency?, store_name? }
```

## Customer profile & address book

`routes/customer.php` + `app/Http/Controllers/Customer/ProfileController.php` /
`AddressController.php` + `app/Services/Customer/ProfileService.php` / `AddressService.php`.

```
GET    /customer/profile                   -> the logged-in user
PATCH  /customer/profile                   { name?, phone?, avatar? }  (avatar is a real file, same pattern as brand/category/user elsewhere)
POST   /customer/profile/change-password   { current_password, password, password_confirmation }
                                            -> every OTHER session signed out, this one stays valid
DELETE /customer/profile                   -> deactivates the account (is_active=false), signs out everywhere

GET    /customer/addresses
POST   /customer/addresses                 { label?, full_name, phone, country, city, street, postal_code?, is_default? }
PATCH  /customer/addresses/{id}
DELETE /customer/addresses/{id}
```
`AddressService` is intentionally separate from `Admin\AddressService` —
every method is implicitly scoped to the `$user` passed in, so there's no
"list all addresses" shape here a customer could ever misuse to reach
someone else's data, unlike the admin version which manages everyone's.

## Checkout — updated flow (address entered at checkout, delivery fee, create/update order)

The full pipeline now matches this exactly:

```
authenticate -> load cart -> price items (locks stock) -> resolve address
-> calculate delivery fee -> calculate total -> create/update order
-> create payment session -> return checkout URL
```

Refactored into single-purpose collaborators (`app/Services/Checkout/`) so
`CheckoutService` is now a ~15-line-per-method orchestrator, not one long
method — each piece has one reason to change:

| Class | Responsibility |
|---|---|
| `CartPricer` | locks stock rows, re-validates quantity, prices line items |
| `AddressResolver` | saved address by id, OR a freshly typed one — either way returns a real `Address` row |
| `DeliveryFeeCalculator` | `standard`/`express` fee from `SettingService`, free above threshold |
| `PaymentGatewayFactory` → `PaymentGateway` | opens/expires the hosted checkout session |

```
POST /customer/checkout
{
  "shipping_address_id": "...",        // OR:
  "shipping_address": { "full_name", "phone", "country", "city", "street", "postal_code?" },
  "billing_address_id": "...",         // optional
  "delivery_method": "standard|express",
  "idempotency_key": "..."             // client-generated UUID, reused on retry
}
-> { order, checkout_url, checkout_session_id }
```

**Create vs. update, both keyed by `idempotency_key`**:
- No existing order for that key → **create**: price cart, resolve
  address (saving it to the address book if typed fresh), calculate fee +
  total, create the order + order_items, decrement stock, open a payment session.
- An existing order for that key that's still `pending` → **update**: the
  customer changed address/delivery method before paying. Re-prices the
  (still-live) cart, releases the previous order_items' stock reservation,
  replaces them, recalculates totals, **expires the old payment session**
  and opens a new one — so there's never a stale, wrong-amount payment
  link left valid.
- An existing order for that key that's NOT `pending` (already paid/
  cancelled/etc.) → returned as-is, untouched. Nothing about a completed
  checkout is still editable.

## Code quality — SOLID & DRY, concretely

Rather than claim this abstractly, here's exactly where each principle
shows up in this codebase:

- **Single Responsibility** — `CheckoutService` used to lock stock, build
  Stripe payloads, and manage totals all in one class; now each is its own
  collaborator (`CartPricer`, `AddressResolver`, `DeliveryFeeCalculator`,
  `PaymentGateway`) and `CheckoutService` only orchestrates them.
- **Open/Closed** — adding a payment provider means writing one new class
  against `PaymentGateway` and one `match` arm in `PaymentGatewayFactory`;
  `CheckoutService` is never modified. Same idea for `BaseCrudService` —
  every admin service extends it and only overrides what's actually
  different (filters, side effects), instead of each reimplementing
  paginate/find/create/update/delete.
- **Liskov substitution** — anywhere a `PaymentGateway` is expected,
  `StripePaymentGateway` (or a future `PayPalPaymentGateway`) can be
  swapped in without the caller knowing or caring.
- **Interface segregation** — `PaymentGateway` only has the four methods
  checkout actually needs (create/expire/retrieve a session, report its
  own name), not a bloated "everything Stripe can do" surface.
- **Dependency inversion** — `CheckoutService` depends on the
  `PaymentGateway` interface and gets a concrete instance injected via the
  factory, not `new StripeClient(...)` inline.
- **DRY** — three services (`BrandService`, `CategoryService`,
  `UserService`) each had a near-identical "upload a replacement image,
  swap url/public_id, delete the old asset" method; that's now
  `ReplacesCloudinaryImage::replaceImage()`, one implementation, used by
  all three (and `ProfileService`). Password hashing (`Hash::make` +
  `unset($data['password'])`) was duplicated across `AuthService::register()`,
  `Admin\UserService`, and `AuthService::resetPassword()`; now
  `HashesPassword::hashPassword()`. Delivery fees and tax rate used to be
  hardcoded constants that could drift from what an admin sees in
  settings; now both read from `SettingService`, one source of truth.

## Tests

`tests/Feature/` covers every controller in the app end-to-end (HTTP in,
JSON out), plus the webhook service directly. `database/factories/` has a
factory for every model tests need to create.

```
tests/Feature/Auth/          RegisterTest, LoginTest, PasswordResetTest, TokenTest
tests/Feature/Admin/         AdminGuardTest, User/Category/Brand/Product/Order/Payment/ReviewManagementTest, SettingsTest
tests/Feature/Guest/         CatalogTest, CartTest
tests/Feature/Customer/      ProfileTest, AddressTest, CartTest, CheckoutTest
tests/Feature/Webhooks/      StripeWebhookServiceTest
```

**No real external calls, ever.** `tests/Fakes/FakeCloudinaryService.php`
and `tests/Fakes/FakePaymentGateway.php` are drop-in stand-ins for the real
Cloudinary/Stripe integrations — bound into the container via
`tests/Concerns/FakesExternalServices::fakeExternalServices()`. This is
only a one-line container swap *because* the app depends on
`CloudinaryService`/`PaymentGateway` rather than instantiating SDK clients
wherever they're needed — the dependency-inversion work pays for itself
directly here. Both fakes record what was called (`->uploaded`,
`->destroyed`, `->createdSessions`, `->expiredSessions`) so tests can
assert *that* an upload/session happened without needing a real API key.

`tests/Concerns/InteractsWithAuthGuards.php` gives every test
`$this->actingAsAdmin()` / `$this->actingAsCustomer()` — thin wrappers
around `Sanctum::actingAs()` scoped to this app's two guards, so tests
read as "as an admin..." instead of repeating guard names and ability
arrays everywhere.

**What's covered, beyond basic CRUD**: the admin guard actually rejects
customer tokens and deactivated admins (not just "some auth is required");
Cloudinary cleanup is asserted (old asset actually gets destroyed on
replace/delete, not just that a new one was uploaded); the order status
state machine rejects illegal transitions; cancelling/expiring/refunding
an order actually restocks; checkout's stock-locking, idempotency
(same key twice ≠ two orders), inline-address-gets-saved, and
free-delivery-threshold behavior all have dedicated tests; the webhook
service's idempotency (duplicate event ID processed once) is tested
directly.

**Setup**:
1. Copy `database/factories/*` into `database/factories/`.
2. Copy `tests/Fakes/`, `tests/Concerns/`, and `tests/Feature/**` into
   `tests/`. Skip `tests/TestCase.php` / `tests/CreatesApplication.php` if
   your project already has them (it almost certainly does).
3. These tests assume the routes are registered exactly as step 10 above
   describes — the admin guard/role middleware applied *inside*
   `routes/admin.php`, but `auth:sanctum` applied to `routes/customer.php`
   *at the registration call site* in `routes/api.php`. If you wire
   `customer.php` up differently, `AddressTest`/`CartTest`/`CheckoutTest`'s
   "requires authentication" assertions won't hold.
4. Use the array cache driver in testing (Laravel's default `phpunit.xml`
   already sets `CACHE_STORE=array`) — `SettingsTest`/`CheckoutTest`'s
   free-delivery-threshold test both write then immediately read a
   setting through `SettingService`'s cache within the same test.
5. Run:
   ```
   composer require --dev phpunit/phpunit fakerphp/faker mockery/mockery
   php artisan test
   ```
   (`fakerphp/faker` and `mockery` are already dev dependencies on a
   default Laravel install — only needed if your project removed them.)

## Design notes

- **BaseCrudService** centralizes list/paginate/find/create/update/delete;
  every entity service extends it and only overrides what's special
  (filters, side effects, business rules).
- **ProductService** transactionally syncs `images` and `variants` as
  nested arrays on create/update, so the admin can save a product with its
  gallery and variants in one request.
- **OrderService** enforces a status state machine
  (`pending → processing → paid → shipped → delivered`, with
  `cancelled`/`refunded` branches) and restocks inventory on cancellation.
- **PaymentService::markRefunded()** only updates local state — for
  admin-initiated refunds, issue the actual Stripe refund call first, then
  call this (or better, let the `charge.refunded` webhook be the one
  source of truth and have this method just trigger the Stripe refund).
- **StripeWebhookController / StripeWebhookService** — handles
  `payment_intent.succeeded`, `payment_intent.payment_failed`, and
  `charge.refunded`. Signature verification happens in the controller via
  `Stripe\Webhook::constructEvent()`; the service is idempotent (each
  Stripe event ID is recorded in `webhook_events` and only processed once),
  which matters because Stripe retries delivery on any non-2xx response or
  timeout and can send the same event more than once.
- **CategoryService::tree()** builds the self-referencing parent/child tree
  in memory (single query) rather than N+1 recursive queries.
- Money columns use `decimal:2` casts; `Category`/`Brand`/`ProductImage`/
  `ProductVariant`/`OrderItem` have no `updated_at` (matches the ERD, which
  only lists `created_at` or no timestamps for these).
- `Otp`, `CartItem` use `created_at` only (or none) per the ERD; `OtpService`
  is intentionally read/cleanup-only since OTPs are generated by the auth
  flow, not hand-authored by admins.

## Not included (tell me if you want these too)

- Auth/Sanctum setup, admin middleware/policy (`can:admin`)
- Form Request classes (validation currently inline in controllers)
- API Resource (JSON transformer) classes for response shaping
- Seeders/factories, tests
- Frontend for the admin panel (Vue/React/Blade)
- Outbound Stripe calls (creating PaymentIntents at checkout, issuing
  refunds) — only the inbound webhook side is covered, since that's what
  the admin panel/backend needs to stay in sync with Stripe
