(I deleted vendor folder for this ZIP folder)
### DevOps 3

### Security 3

This Laravel application follows security best practices to protect against unauthorized access and attacks, particularly Insecure Direct Object References (IDOR) and Session Hijacking.

**1. Protection Against IDOR**

To prevent users from accessing or modifying resources they do not own, the following security measures have been implemented:

**Middleware Role-Based Access Control (Located in routes/web.php)**

Routes for creating, updating, and deleting posts are restricted to admin users using RoleMiddleware.

This ensures that only users with the 'admin' role can perform these actions.

**Direct Authorization Checks in Controllers (Located in app/Http/Controllers/PostController.php)**

The PostController.php includes explicit permission checks before editing, updating, or deleting a post:
```php
if (auth()->user()->id !== $post->user_id && auth()->user()->role !== 'admin') {
    abort(403, 'Access denied');
}
```
- Impact: This prevents users from accessing posts that do not belong to them via direct URL manipulation.

**Blade View Restrictions (Located in resources/views/posts/show.blade.php)**

Buttons for editing or deleting posts only appear for admin users:
```php
@if(auth()->check() && auth()->user()->role === 'admin')
    <a href="{{ route('posts.edit', $post) }}" class="edit-button">Edit</a>
    <a href="{{ route('posts.delete', $post) }}" class="delete-button">Delete</a>
@endif
```
- Impact: This hides privileged actions from unauthorized users.

**Prevention of Unauthorized Post Manipulation (Located in app/Http/Controllers/PostController.php)**

Users cannot modify or delete posts they do not own.

Implemented in the PostController.php:
```php
public function update(Request $request, Post $post)
{
    if (auth()->user()->id !== $post->user_id && auth()->user()->role !== 'admin') {
        abort(403, 'Access denied');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'date' => 'required|date',
        'description' => 'required|string',
    ]);

    $post->update($validated);

    return redirect()->route('work')->with('success', 'Post updated successfully!');
}
```
- Impact: Prevents unauthorized users from editing posts that do not belong to them.

**2. Protection Against Session Hijacking**

The application is configured to secure user sessions and prevent hijacking attacks.

**Secure Session Storage in Database (Configured in .env)**

The session driver is set to database in .env, ensuring that session data is not exposed in browser cookies.
```php
SESSION_DRIVER=database
```
**Secure Cookie Settings (Configured in config/session.php & .env)**

The session configuration enforces secure cookies that cannot be accessed via JavaScript (HttpOnly) and prevents session fixation attacks.
```php
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'lax',
```
- Impact: This prevents session hijacking by ensuring cookies are only transmitted over HTTPS.

**Forcing HTTPS in Deployment (Located in app/Providers/AppServiceProvider.php)**

In a production environment, Laravel is configured to force HTTPS.
```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}
```
- Impact: This prevents man-in-the-middle attacks when sending authentication cookies.

**Session Fixation Prevention (Located in app/Http/Controllers/Auth/LoginController.php)**

Whenever a user logs in, all previous sessions are invalidated to prevent session reuse.
```php
Auth::logoutOtherDevices($request->password);
```
Impact: Ensures that only one session per user is active at a time, preventing attackers from hijacking an active session.

**3. Protection Against Unauthorized API Access**

**Sanctum Authentication for Protected Routes (Located in routes/web.php)**

All routes requiring authentication use Sanctum middleware.
```php
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/work/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/work', [PostController::class, 'store'])->name('posts.store');
    Route::get('/work/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/work/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/work/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});
```
- Impact: Prevents unauthorized users from performing privileged actions.

**Stateful Domain Configuration for Sanctum (Configured in config/sanctum.php)**

To prevent CSRF attacks on API authentication, the application configures trusted stateful domains in config/sanctum.php:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'yourdomain.com')),
```
- Impact: Ensures that authentication is only valid within a trusted domain.
