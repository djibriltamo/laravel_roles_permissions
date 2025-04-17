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

🔁 Attribuer des rôles et permissions

Dans un seeder, un controlleur ou via Tinker :
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$admin = Role::create(['name' => 'admin']);

$permission = Permission::create(['name' => 'edit articles']);

$admin->givePermissionTo($permission);

$user = User::find(1);
$user->assignRole('admin');

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
    // code...
}
if ($user->hasRole('admin')) {
    // code...
}


📢 Auteur : Djibril Tamo

📅 Date : 12 Avril 2025

🌟 Licence : Publique

