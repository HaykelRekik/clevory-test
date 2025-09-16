# Projet de Test Clevory

Ceci est un projet basé sur Laravel pour le test Clevory.

## Démarrage avec Docker

Pour mettre l'application en service, vous pouvez utiliser la configuration Docker fournie.

1.  **Construire et démarrer les conteneurs:**

    ```bash
    make build && make up
    ```

2.  **Générer la clé secrète JWT:**

    ```bash
    docker-compose exec app php artisan jwt:secret
    ```

3. **Exécuter les migrations de la base de données:**

    ```bash
    docker-compose exec app php artisan migrate
    ```

L'application devrait maintenant être accessible à [http://localhost:8001](http://localhost:8001).

## Utilisation de l'API avec Postman

Une collection Postman est incluse à la racine de ce projet (`Clevory Test.postman_collection.json`).

1.  **Importer la collection:** Importez le fichier `Clevory Test.postman_collection.json` dans votre application Postman.

2.  **Définir l'URL de base:**
    *   Dans Postman, accédez à la racine de la collection importée ("Clevory Test").
    *   Allez dans l'onglet "Variables".
    *   Définissez la variable `baseUrl` à `http://localhost:8001/api/v1`.

3.  **Créer un compte:** Utilisez la requête "Register" dans le dossier "Auth" de la collection pour créer un nouveau compte utilisateur.

4.  **Définir le Bearer Token:**
    *   Après vous être inscrit ou connecté, vous recevrez un bearer token dans la réponse.
    *   Dans Postman, accédez à la racine de la collection importée ("Clevory Test").
    *   Allez dans l'onglet "Authorization".
    *   Assurez-vous que le "Type" est défini sur "Bearer Token".
    *   Collez le token que vous avez reçu dans le champ "Token". Cela appliquera automatiquement le token à toutes les requêtes de la collection.

## Exécution des tests

Pour exécuter la suite de tests de l'application, utilisez la commande suivante:

```bash
```bash
php artisan test
```

## Visualisation de la base de données

Pour visualiser la base de données via Adminer, vous pouvez y accéder à [http://localhost:8081/](http://localhost:8081/).

Utilisez les coordonnées présentes dans le fichier `.env` pour vous connecter 
