FROM dev-docker-registry0.km.rst.com.pl/php-libraries/php71:latest

COPY . /var/www/card-battle-game

WORKDIR /var/www/card-battle-game

EXPOSE 80

CMD ["sh", "create.sh"]
