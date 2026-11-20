# Photos et vidéos de la page « Photo / Video » avec Cloudinary

La page Photo / Video (`/moments`) affiche automatiquement tout ce qui est déposé dans les dossiers
`Photo` et `Video` du compte Cloudinary. Pas besoin de toucher au code ni de redéployer : un fichier ajouté
apparaît sur le site en **10 minutes maximum** (le site garde la liste en cache 10 minutes).

Les photos et vidéos déjà présentes sur le serveur restent affichées en plus.

---

## 1. Mise en route (une seule fois)

### a) Créer le compte (le propriétaire du site)

1. Créer un compte gratuit sur [cloudinary.com](https://cloudinary.com).
2. Dans la console : roue crantée **Settings** → **API Keys**. Noter les trois valeurs :
   - **Cloud name**
   - **API Key**
   - **API Secret** (à garder secret, ne jamais le publier)

### b) Brancher le site

**En local** (`.env`) :

```
CLOUDINARY_CLOUD_NAME=xxxx
CLOUDINARY_API_KEY=xxxx
CLOUDINARY_API_SECRET=xxxx
CLOUDINARY_FOLDER=Photo,Video
```

puis `php artisan config:clear`.

**En production**, ajouter les trois secrets dans GitHub :
dépôt → **Settings** → **Secrets and variables** → **Actions** → **New repository secret** :
`CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`.
Le prochain déploiement les transmet au serveur.

Si le déploiement automatique est en panne, les ajouter à la main sur le VPS dans le fichier `.env`
du dossier `/docker/saaheem`, puis relancer :

```
cd /docker/saaheem && docker compose up -d
```

Tant que ces variables sont vides, la page fonctionne comme avant (fichiers du serveur uniquement).

---

## 2. Ajouter du contenu (au quotidien)

### Les dossiers

Dans **Media Library** → **Folders**, deux dossiers à la racine : **`Photo`** et **`Video`**.

- Un fichier posé directement dans `Photo` (ou `Video`) apparaît dans **« Tout »** du menu Photo
  (ou Vidéo) et en « Pinterest mode ».
- Pour le ranger dans une catégorie, créer un sous-dossier portant son nom :

```
Photo/
├── Fashion week
├── Concert festival
├── Brand
├── Soirée
└── Shoot
Video/
├── Artist
├── Clip
├── Aftermovie
└── Production
```

Majuscules, accents et espaces ne posent pas de problème (« Soirée » = soiree). Il faut juste que le
nom corresponde à une catégorie du site ; sinon le fichier n'apparaît que dans « Tout ».
Les autres dossiers du compte (par exemple `samples`, fourni par Cloudinary) sont ignorés.

### Ajouter

Ouvrir le bon dossier → **Upload** (ou glisser-déposer les fichiers). Ça marche aussi depuis le
navigateur du téléphone. C'est tout : le site s'en occupe.

Cloudinary prépare automatiquement des versions légères (miniatures pour la grille, grand format au
clic, vidéos adaptées au mobile). Inutile de réduire les photos avant de les envoyer.

### Retirer

Supprimer le fichier dans Cloudinary : il disparaît du site dans les 10 minutes.

---

## 3. Bonnes pratiques

- **Vidéos** : privilégier des extraits courts (moins de 1 à 2 minutes). Les clips longs vont mieux
  sur YouTube.
- **Plan gratuit** : il a une limite mensuelle (« credits »), visible sur le **Dashboard** de
  Cloudinary. Les photos en consomment peu, les vidéos beaucoup plus.
- **Ne pas renommer les dossiers** : un dossier renommé n'est plus reconnu comme catégorie.

---

## Pour les développeurs

- Code : `app/Support/CloudinaryMoments.php` (Search API, cache 10 min, URLs de transformation).
- Config : `config/services.php` → `cloudinary` (`CLOUDINARY_FOLDER` : dossiers racine séparés par des virgules,
  `CLOUDINARY_CACHE_TTL` en secondes).
- Tests : `tests/Feature/CloudinaryMomentsTest.php`.
- Vider le cache tout de suite : `php artisan cache:forget cloudinary.moments`.
