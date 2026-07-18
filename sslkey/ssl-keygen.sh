openssl req -x509 -nodes -days 365 -newkey rsa:4096 -keyout waph-team10.key -out waph-team10.crt
sudo cp waph-team10.crt /etc/ssl/certs
sudo cp waph-team10.key /etc/ssl/private