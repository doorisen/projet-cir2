#               Projet de fin d'année CIR2 - IRVE Bretagne


Auteur : Valentin TANG-PATUREL



# Pour l'initialisation du site :
- Dans le terminal linux, se connecter à la VM avec ``ssh user1@10.30.51.39``, mot de passe ``Pastek2006``
- Lancer apache2 avec ``sudo service apache2 start``, puis lancer mariadb avec ``sudo service mariadb start`` (même mot de passe)
- Exécuter le fichier irve_bretagne.sql avec ``sudo mysql < /var/www/html/projet-cir2/config/irve_bretagne.sql``



# Pour accéder au site :
- Accéder au site sur http://projet-cir2-39/projet-cir2
- A la première visite du site après initialisation, un écran de chargement (gif de pikachu) s'affiche le temps que la database soit automatiquement peuplée. Cela prend plusieurs secondes. Une fois ce peuplement fait, cet écran de chargement n'est plus jamais disponible.





