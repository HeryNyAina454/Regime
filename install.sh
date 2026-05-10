#!/bin/bash
cd /home/ton_utilisateur/xampp/htdocs/fenosoa/S4-projet_trinome/regime || exit

# Télécharger composer.phar si absent
if [ ! -f composer.phar ]; then
    echo "Téléchargement de Composer..."
    curl -sS https://getcomposer.org/installer | php -- --filename=composer.phar
    if [ $? -ne 0 ]; then
        echo "Échec du téléchargement avec curl. Tentative avec wget..."
        wget https://getcomposer.org/composer.phar -O composer.phar
    fi
fi

# Installer les dépendances
echo "Installation des dépendances..."
/opt/lampp/bin/php composer.phar install --no-interaction

read -p "Appuyez sur Entrée pour continuer..."
