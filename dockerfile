FROM 4.4-fpm-alpine

# Copia i file del progetto Git nella directory del documento di root di Apache
# possibilmente sostituire il token fornito con uno proprio
RUN apt-get update && apt-get install -y git
RUN git clone -b rouslan_kravtchouk https://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@github.com/ilmeteo/prova-tecnica-rouslan.git /var/www/html/

# Espone la porta 80 per consentire l'accesso all'applicazione via web.
EXPOSE 80

# Avvia il server Apache al momento dell'esecuzione del contenitore.
CMD ["apache2-foreground"]