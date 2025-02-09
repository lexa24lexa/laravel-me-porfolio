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

### Testing
To start this testing, I started by testing the connection with the database.

<img src="public/image/databaseConnection.jpg" alt="database connection">
<p style="font-style: italic">Figure 1: MariaDB connection.</p>

Then, I did migrate fresh and seed the previous created posts to test the database.

<img src="public/image/migrateSeed.jpg" alt="migrate fresh & seed">
<p style="font-style: italic">Figure 2: php artisan migrate:fresh & php artisan db:seed.</p>

# User Story 1: Post Creation and Update Management (Feature Test)
As a user, I want to create and update posts on the platform.

## System Test Scenarios:
### Happy Path:

* Creating a New Post:
<br>**Given** a user wants to create a new post,
<br>**When** they fill out the post creation form with valid data and submit it,
<br>**Then** a new post should be stored in the database.
* Updating an Existing Post:
<br>**Given** a user wants to update an existing post,
<br>**When** they edit the post details and save the changes,
<br>**Then** the post should reflect the updated information in the database.

### Unhappy Paths:

* Creating a Post with Invalid Data:
<br>**Given** a user tries to create a new post with incomplete or invalid data,
<br>**When** they submit the form,
<br>**Then** an error message should prevent the creation of the post.

* Updating a Post with Incorrect Information:
<br>**Given** a user attempts to update a post with incorrect information,
<br>**When** they save the changes,
<br>**Then** the post data should remain unchanged in the database.

## Unit Test Scenarios:
### Post Creation:

* **Given** a new Post model instance,
* **When** a new post is created using Post::create(),
* **Then** the post should be successfully stored in the database with the correct attributes.

<img src="public/image/unitcreate.jpg" alt="unit test create">
<p style="font-style: italic">Figure 3: unit test create a new post.</p>

<img src="public/image/featurestore.jpg" alt="feature test store">
<p style="font-style: italic">Figure 4: feature test stores a new post.</p>


### Post Update:

* **Given** an existing Post instance,
* **When** the post is updated using Post::update(),
* **Then** the post data should reflect the updated attributes.

<img src="public/image/unitupdate.jpg" alt="unit test update">
<p style="font-style: italic">Figure 5: unit test updates the post.</p>

<img src="public/image/featureupdate.jpg" alt="feature test update">
<p style="font-style: italic">Figure 6: feature test updates the post.</p>

### Tests Coverage Rationale:
* **System Tests:** Validate critical user-facing functionalities related to post creation and update.
* **Unit Tests:** Ensure core model functionalities related to data manipulation are correctly implemented.

### Test Execution:
* **System Tests:** Validate integrated features from the user interface to the database layer.
* **Unit Tests:** Validate individual model functionalities and database interactions.

### Evaluation of Test Quality:
* **Conclusion on Test Results:** Critical functionalities related to post creation and update are verified to function correctly.
* **Improvement Proposal:** Enhance test coverage by incorporating more edge cases and integrating automated testing with continuous integration tools.

# User Story 2: Post Deletion Management (Feature Test)
As a user, I want to delete posts on the platform.

## System Test Scenarios:
### Happy Path:
* Deleting an Existing Post:
<br>**Given** a user wants to delete an existing post,
<br>**When** they confirm the deletion action,
<br>**Then** the post should be permanently removed from the database.

### Unhappy Paths:
* Deleting a Non-Existent Post:
<br>**Given** a user tries to delete a post that does not exist,
<br>**When** they initiate the deletion process,
<br>**Then** no action should be taken, and the post should still exist.

## Unit Test Scenarios:
### Post Deletion:
* **Given** an existing Post instance,
* **When** the post is deleted using $post->delete(),
* **Then** the post should be removed from the database.

<img src="public/image/unitdelete.jpg" alt="unit test delete">
<p style="font-style: italic">Figure 7: unit test delete the post.</p>

<img src="public/image/featuredelete.jpg" alt="feature test deletes">
<p style="font-style: italic">Figure 8: feature test delete the post.</p>

### Tests Coverage Rationale:
* **System Tests:** Validate critical user-facing functionalities related to post deletion.
* **Unit Tests:** Ensure core model functionalities related to data manipulation are correctly implemented.

### Test Execution:
* **System Tests:** Validate integrated features from the user interface to the database layer.
* **Unit Tests:** Validate individual model functionalities and database interactions.

### Evaluation of Test Quality:
* **Conclusion on Test Results:** Critical functionalities related to post deletion are verified to function correctly.
* **Improvement Proposal:** Enhance test coverage by incorporating more edge cases and integrating automated testing with continuous integration tools.

# Final testing

<img src="public/image/testing.jpg" alt="unit and feature testing">
<p style="font-style: italic">Figure 9: php artisan test to test everything.</p>
