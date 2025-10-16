# My Little Softwarehouse

Backend: **Laravel** + **PostgreSQL**  
Frontend: **Vue.js**

### Clona il repository e crea file .env
Copia il file `.env.example` in un nuovo file `.env`

### Costruisci ed avvia i container  
```sh

docker compose up -d --build

```  

### Genera la chiave per Laravel  
```sh

docker compose exec app php artisan key:generate

```  

### Esegui le migrazioni  
```sh

docker compose exec app php artisan migrate

```  

### Test applicazione  
L'applicazione sarà accessibile ai seguenti indirizzi: 
Backend: `http://localhost:8000`  
Frontend: `http://localhost:5173` 