Swiss tournament project
=================

Installation
------------
1) Clone repository to your local machine: 
  
```
git clone https://github.com/chabanenk0/swiss_tournament
```
2) Download the vendor files by running:

```
php composer.phar install
```
3) Configure your DB in .env:

```
DATABASE_URL=mysql://root:12345@127.0.0.1/swiss_tournament
```
4) Create database and load fixtures:

```
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
````

5) Download the node_modules files by running:

```
yarn install
```

6) Running Encore:

```
./node_modules/.bin/encore dev
```

7) Run server:

```
bin/console ser:run
```
7) Load up the app in your browser:

```
localhost:8000
```
   
       