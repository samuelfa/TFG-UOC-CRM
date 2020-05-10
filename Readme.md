
https://coderthemes.com/hyper/saas/pages-profile-2.html
https://github.com/eacevedof/prj_phptests/tree/master/vendor/DesignPatterns/SrtaDeveloper
https://github.com/CodelyTV/php-ddd-example
https://github.com/dddinphp/last-wishes

https://github.com/BlackrockDigital/startbootstrap-sb-admin-2

#frontend

yarn encore dev
yarn encore dev --watch
yarn encore production

#symfony

php bin/console lint:container


#ansible

ansible-galaxy install geerlingguy.mysql
ansible-galaxy install geerlingguy.redis

#google computer cloud

gcloud compute ssh frontal

#certs

openssl req -x509 -out localhost.crt -keyout localhost.key \
  -newkey rsa:2048 -nodes -sha256 \
  -subj '/CN=localhost' -extensions EXT -config <( \
   printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")
