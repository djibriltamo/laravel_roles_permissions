🎯 Gestion des rôles et permissions avec Spatie dans Laravel

Ce projet utilise le package [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) pour gérer les **rôles** et **permissions** des utilisateurs dans une application Laravel.

---

✅ Installation du package

composer require spatie/laravel-permission

📦 Publier les fichiers de configuration et de migration

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

🧠 Configuration du modèle User
Ajoute le trait HasRoles dans ton modèle User :

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}

📌 Ajoute ceci dans app.php dans le dossier bootstrap qui sont les alias des middlewares

->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    
📚 Importer ceci dans votre contrôleur

use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

ensuite ajouter cet interface sur ce contrôleur : class UserController extends Controller implements HasMiddleware

et ensuite ajouter cette méthode avec les permissions que vous aurez crées :
public static function middleware(): array
    {
        return [
            new Middleware('permission:voir users', only: ['index']),
            new Middleware('permission:ajouter users', only: ['create']),
            new Middleware('permission:editer users', only: ['edit']),
            new Middleware('permission:supprimer users', only: ['destroy']),
        ];
    }

🔁 Attribuer des rôles et permissions

Dans un seeder, un controlleur ou via Tinker :
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Créer un rôle
$admin = Role::create(['name' => 'admin']);

// Créer une permission
$permission = Permission::create(['name' => 'edit articles']);

// Associer la permission au rôle
$admin->givePermissionTo($permission);

// Associer un rôle à un utilisateur
$user = User::find(1);
$user->assignRole('admin');

// Donner une permission directement à un utilisateur
$user->givePermissionTo('edit articles');

🔒 Bloquer des accès avec @can dans les vues Blade
 Si vous voulez bloquer en utilisant les permissions
    @can('edit articles')
      <a href="/edit">Modifier l'article</a>
    @endcan
  Si voulez bloquez en utilisant les rôles
    @role('admin')
      <a href="/admin">Admin Panel</a>
    @endrole
    @hasanyrole('admin|moderator')
      <a href="/moderation">Espace Modération</a>
    @endhasanyrole

🧪 Vérification dans le code (contrôleur, service...)
if ($user->can('delete articles')) {
    // faire quelque chose
}
if ($user->hasRole('admin')) {
        // faire autre chose
}


📢 Auteur : Djibril Tamo
📅 Date : 12 Avril 2025
🌟 Licence : Publique

