# phpat
Projet de chat du cours P62 A15

## Etape pour le mardi 5 janvier 2016
Réaliser la validation du formulaire de la page d'inscription (inscription.php). Suggestion : Réaliser le formulaire et sa validation dans le fichier _inscription_form.php. 
1. Les critères de validation sont :
  1. nom : filtrage INPUT_SANITIZE_STRING, validation preg_match ou FILTER_VALIDATE_REGEXP, min 2 caractères alphabétiques
  2. firstname : filtrage INPUT_SANITIZE_STRING, validation preg_match ou FILTER_VALIDATE_REGEXP, min 2 caractères alphabétiques
  2. email :  filtrage FILTER_SANITIZE_EMAIL, validation FILTER_VALIDATE_EMAIL
  4. username : filtrage INPUT_SANITIZE_STRING, validation preg_match ou FILTER_VALIDATE_REGEXP, min 4 caractères alphabétiques et numériques
  5. password : filtrage INPUT_SANITIZE_STRING, validation preg_match ou FILTER_VALIDATE_REGEXP, min 4 caractères alphabétiques et numériques
2. Suivi de validation
  * Si certains champs ne sont pas valides, ré-afficher le formulaire avec les valeurs saisies par l'utilisateur. Idéalement, vous pourriez ajouter de l'information de validation dans le formulaire :
      * coloration css
      * information sur les critères à proximité des champs invalides
  * À l'inverse, si tous les  champs sont valides, rediriger vers la page d'accueil (index.php);


## Etape pour le mercredi 6 janvier 2016
Conclure la validation du formulaire de la page d'inscription. 
1. Mettre une classe css error sur tous les champs invalides (seulement en réception des données)
2. Pour chaque champ invalide, ajouter un span avec un message d'explication après le input invalide.