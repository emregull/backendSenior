## Proje Adı

Emre Gül - Senior Backend Projesi

## Başlangıç

Projeyi çalıştırmak için aşağıdaki adımları takip edin.

## Gereksinimler

- Docker
- Docker Compose
- PHP
- Composer
- Postman

## Kurulum

Bu depoyu indirin veya klonlayın:

```bash
git clone https://github.com/emregull/mukellef.git
```

Gerekli composer dosyalarını indirin:

```bash
composer install
```

Docker container'larını başlatmak için:

```bash
cd docker
docker-compose up -d
```

Laravel projesinin ana dizinine gidin ve migration'ları çalıştırın:

```bash
cd ..
php artisan migrate
```

Ardından, veritabanını örnek verilerle doldurmak için seed işlemi yapın:

```bash
php artisan db:seed
```

Laravel sunucusunu başlatın:

```bash
php artisan serve
```

Tarayıcıda http://localhost:8000 adresine gidip uygulamayı görüntüleyebilirsiniz.

yada

Tarayıcıda http://localhost:8080 adresine gidip phpmyadmin'e ulaşabilirsiniz.

Kullanıcı Adı: `root`

Parola: `mukellef`

## Postman
Postman koleksiyonuna proje dizininden erişebilirsiniz:

```http
Mukellef.postman_collection.json
```

## Testler
PHPUnit testlerini bu komut ile çalıştırabilirsiniz:

```http
vendor/bin/phpunit
```
