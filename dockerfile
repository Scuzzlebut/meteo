FROM php:7-apache

COPY . /var/www/html/

# Espone la porta 80 per consentire l'accesso all'applicazione via web.
EXPOSE 80

# Avvia il server Apache al momento dell'esecuzione del contenitore.
CMD ["apache2-foreground"]