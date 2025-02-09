(I deleted vendor folder for this ZIP folder)
# DevOps 3

# Security 3

This Laravel application follows security best practices to protect against unauthorized access and attacks, particularly Insecure Direct Object References (IDOR) and Session Hijacking.

## 1. Protection Against IDOR

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

## 2. Protection Against Session Hijacking

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
- Impact: Ensures that only one session per user is active at a time, preventing attackers from hijacking an active session.

## 3. Protection Against Unauthorized API Access

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

# Testing

## Test Plan

This document describes the test plan to ensure the quality and security of the system. The tests will be conducted at different levels, following the V-Model, and will include **unit tests, system tests, and evaluation of results**.

### The test plan covers the following functionalities:
- **Authentication (Login)**
- **CRUD operations for posts** (create, edit, delete)
- **Permission system** (action restriction based on roles)
- **Security measures**
  - Protection against IDOR (Insecure Direct Object Reference)
  - Prevention of Session Hijacking
  - Secure API access

### What will NOT be tested
- UI end-to-end tests (focus is on API and backend testing)
- Performance/stress testing (out of scope for this project)

### Tools
- **Testing framework:** PHPUnit (Laravel)
- **Test data generator:** Laravel Factories
- **Environment:** Docker or Homestead for isolated environments
- **CI/CD:** GitHub Actions for test automation

### Link to the V-Model
The test plan is structured according to the V-Model:
- **Unit Tests** - Validate individual components (e.g., functions and controllers).
- **Integration Tests** - Ensure modules communicate correctly.
- **System Tests** - Test the complete system with real workflows.
- **Acceptance Tests** - Validate that the system meets business requirements.

### Testing Strategy
The tests will follow the **Given-When-Then** or **AAA (Arrange-Act-Assert)** principles.

## Unit Testing
- **Test Structure:** Given-When-Then or AAA (Arrange-Act-Assert).
- **Coverage of Happy and Unhappy Paths:**
  - 🟢 **Happy Path:** Ensuring core functionalities like login and CRUD work correctly.
  - 🔴 **Unhappy Path:** Handling invalid inputs, failed authentication, and unauthorized access.
- **Use of Factories:** Generate test data efficiently to ensure reproducibility.

**_User Stories with Happy and Unhappy Paths_**
<br>
<ins>1. Login User Stories</ins>
<br>
**Happy Path:**
- **As a user**, I want to log in with my correct credentials **so that** I can access my dashboard.
  - **Given** I have a valid account,
  - **When** I submit the correct email and password,
  - **Then** I should be authenticated and redirected to the dashboard.

**Unhappy Paths:**
- **As a user**, I should not be able to log in with incorrect credentials **so that** my account remains secure.
  - **Given** I have an account,
  - **When** I enter an incorrect password,
  - **Then** I should see an error message and remain on the login page.

- **As a user**, I should not be able to log in without providing a password **so that** empty submissions are not allowed.
  - **Given** I am on the login page,
  - **When** I attempt to log in without a password,
  - **Then** I should receive a validation error.

<ins>2. CRUD Permissions Tests</ins>
<br>
**Happy Path:**

- **As an admin**, I want to create, edit, and delete posts **so that** I can manage the platform's content.
  - **Given** I am authenticated as an admin,
  - **When** I create, edit, or delete a post,
  - **Then** the action should be successfully completed.

**Unhappy Paths:**
- **As a normal user**, I should not be able to create posts **to prevent** unauthorized content management.
  - **Given** I am logged in as a normal user,
  - **When** I attempt to create a post,
  - **Then** I should receive an error message.

- **As a user**, I should not be able to delete a post that does not belong to me **to prevent** unauthorized data manipulation (IDOR protection).
  - **Given** I am logged in as a normal user,
  - **When** I attempt to delete another user’s post,
  - **Then** I should receive a permission error.
<br>
<ins>3.Security Tests (IDOR & Session Hijacking)</ins>
<br>
**Happy Paths:**

- **As a user**, I want to access only my own data so that my personal information remains private.
  - **Given** I am authenticated,
  - **When** I request my profile data,
  - **Then** I should receive my own information and a 200 OK response.

**Unhappy Paths:**
- **As a user**, I should not be able to access another user's data to prevent unauthorized access (IDOR).
  - **Given** I am authenticated,
  - **When** I try to access another user's profile via URL manipulation,
  - **Then** I should receive a 403 Forbidden or 401 Unauthorized response.

- **As an attacker**, I should not be able to hijack another user's session so that accounts remain secure.
  - **Given** a valid user is logged in,
  - **When** an unauthorized party tries to reuse their session ID,
  - **Then** the system should detect and block the attempt, requiring reauthentication.

**_Unit Test Examples_**
<br>
<ins>1. Login Tests</ins>
<br>
- ✅ Login with correct credentials (happy path).
- ✅ Login with incorrect credentials (unhappy path).
- ✅ Login attempt without providing a password (unhappy path).
![alt text](image.png)

<ins>2. CRUD Permissions Tests</ins>
<br>
✅ Normal user CANNOT create posts (unhappy path).
✅ Normal user CANNOT edit posts (unhappy path).
✅ Normal user CANNOT delete posts (unhappy path).
❌ Admin can create, edit, update and delete posts (happy path), test FAILS – issue unknown.

<ins>3.Security Tests (IDOR & Session Hijacking)</ins>
<br>
✅ Utilizador autenticado acessa os seus próprios dados (happy path).
❌ Utilizador tenta acessar dados de outro utilizador diretamente via URL (IDOR test).
❌ Simulação de Session Hijacking para validar proteção.

## System Testing
Seguir Given-When-Then ou AAA
Abranger Happy e Unhappy Paths
Uso de Factories para Dados de Teste
Cobertura de Edge Cases
Testes Automatizados no Source Control
📌 Exemplos de System Tests
1️⃣ Login Flow Test
Given um utilizador registado,
When ele introduz credenciais corretas e submete o formulário,
Then ele deve ser autenticado e redirecionado para a dashboard.

2️⃣ IDOR Protection Test
Given dois utilizadores autenticados (User A e User B),
When o User A tenta acessar um recurso do User B alterando o ID na URL,
Then o sistema deve retornar um erro 403 (Forbidden).

3️⃣ API Security Test
Testar se endpoints exigem autenticação.
Testar rate limiting (proteção contra brute force).
Testar acessos não autorizados.

## Test effectivity

## Evaluation
Conclusão dos Resultados
O que os testes indicam sobre a qualidade do projeto?
Há problemas críticos identificados?
Reflexão Crítica
O plano de testes cobriu tudo necessário?
Houve dificuldades na implementação dos testes?
Propostas de Melhorias
Melhor cobertura de edge cases?
Otimização de tempo de execução dos testes?
Melhor integração com CI/CD?
