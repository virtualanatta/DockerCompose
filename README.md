# PHP Login con Docker Compose

Aplicación web de registro e inicio de sesión en PHP, desplegada con Docker Compose en cuatro contenedores: nginx, PHP-FPM, MySQL y phpMyAdmin.

```
Navegador ──:8082──▶ nginx ──FastCGI :9000──▶ PHP-FPM ──▶ MySQL :3306
                                                            ▲
                     localhost:8081 ──▶ phpMyAdmin ─────────┘
```

| Contenedor | Imagen | Puerto | Función |
|---|---|---|---|
| `pr1_nginx` | nginx | 8082 | Servidor web; envía los `.php` a PHP-FPM |
| `pr1_php` | php:8.2-fpm + mysqli | interno | Ejecuta la aplicación |
| `pr1_mysql` | mysql:8.0 | interno | Base de datos `login_app` |
| `pr1_phpmyadmin` | phpmyadmin | 8081, solo localhost | Administración de la base de datos |

La interfaz usa la plantilla *Landed* de [HTML5 UP](https://html5up.net) (licencia CC BY 3.0).

## Despliegue

```bash
git clone https://github.com/virtualanatta/DockerCompose.git
cd DockerCompose
cp .env.example .env        # definir las contraseñas
docker compose up -d --build
docker compose ps
```

| URL | Contenido |
|---|---|
| `http://localhost:8082` | Página principal |
| `http://localhost:8082/registro.php` | Registro |
| `http://localhost:8082/acceso.php` | Inicio de sesión |
| `http://localhost:8081` | phpMyAdmin |

La tabla `users` se crea automáticamente en el primer arranque a partir de [`db/init.sql`](db/init.sql).

## Estructura

```
├── docker-compose.yml
├── Dockerfile            PHP-FPM con la extensión mysqli
├── .env.example
├── db/init.sql           esquema de la base de datos
├── nginx/nginx.conf
└── web/
    ├── index.html        página principal (plantilla)
    ├── registro.php      alta de usuarios
    ├── acceso.php        inicio de sesión
    ├── dashboard.php     zona privada
    ├── logout.php
    └── conexion.php      conexión a MySQL con las variables de entorno
```

## Funcionamiento

1. **Registro.** Valida los campos y la confirmación de contraseña, comprueba que el email no exista y guarda la contraseña con `password_hash` (bcrypt).
2. **Acceso.** Busca el usuario por email y verifica la contraseña con `password_verify`. Si es correcta, regenera el identificador de sesión y redirige al panel.
3. **Panel.** Solo accesible con sesión iniciada; en otro caso redirige al login.
4. **Salida.** Destruye la sesión.

### Seguridad aplicada

- Consultas preparadas en todos los accesos a la base de datos, contra inyección SQL.
- Contraseñas con bcrypt, nunca en texto plano.
- `session_regenerate_id` tras el login, contra la fijación de sesión.
- Mismo mensaje de error para email inexistente y contraseña incorrecta, para no revelar qué cuentas existen.
- Salida escapada con `htmlspecialchars`, contra XSS.
- La aplicación conecta con un usuario de MySQL propio, no con root.
- Credenciales fuera del código, en `.env`.
- phpMyAdmin publicado solo en `127.0.0.1` y sin inicio de sesión automático.

## Operación

```bash
docker compose logs -f php      # errores de PHP
docker compose logs -f mysql
docker compose restart
docker compose down             # conserva los datos en ./mysql_data
docker compose down && rm -rf mysql_data   # empezar de cero
```

## Incidencias

| Problema | Causa | Solución |
|---|---|---|
| `permission denied` en el socket de Docker | Usuario fuera del grupo `docker` | `sudo usermod -aG docker $USER` y `newgrp docker` |
| PHP no podía conectar con MySQL | La imagen oficial de PHP no incluye `mysqli` | Imagen propia con `docker-php-ext-install mysqli` |
| La tabla `users` no existía | La imagen de MySQL solo crea la base de datos vacía | Script de esquema en `/docker-entrypoint-initdb.d` |
| Fallos de conexión en el primer arranque | MySQL tarda unos segundos en inicializarse y PHP arrancaba antes | *Healthcheck* en MySQL y `depends_on` con `condition: service_healthy` |
| `docker-compose.yml` inválido | Errores de indentación y comillas | Reescritura y validación con `docker compose config` |

Comparado con montar el mismo entorno con XAMPP, Compose deja el stack definido en un único fichero y reproducible en cualquier máquina.

## Capturas

<img width="1840" height="777" alt="Página principal" src="https://github.com/user-attachments/assets/1966b5ef-cc02-4c95-8ffa-e97ca6495a73" />
<img width="1388" height="852" alt="Captura 2" src="https://github.com/user-attachments/assets/3de78ee5-eb8a-450f-bf12-8b370f2640ae" />
<img width="514" height="575" alt="Captura 3" src="https://github.com/user-attachments/assets/facd3fd1-0a10-4fe1-a262-8cab27dd2ef5" />
<img width="502" height="407" alt="Captura 4" src="https://github.com/user-attachments/assets/308e4c85-b9be-47e6-81a9-af3ac2fb5110" />
<img width="1143" height="340" alt="Captura 5" src="https://github.com/user-attachments/assets/f8108b28-6425-4899-9ba4-8e5e6c7aa523" />
<img width="1274" height="509" alt="Captura 6" src="https://github.com/user-attachments/assets/7878b2fe-b90a-4c99-ab67-dc0ce5280cf4" />

## Docker: conceptos

| | Docker | LXC |
|---|---|---|
| Tipo | Contenedores de aplicación: un proceso principal por contenedor | Contenedores de sistema: un sistema Linux completo |
| Imágenes | Por capas, reutilizables y versionadas | Plantillas de sistema |
| Uso típico | Desplegar servicios y aplicaciones | Entornos tipo máquina virtual, pero ligeros |

Ambos comparten el kernel del anfitrión y se aíslan con *namespaces* y *cgroups*, así que ninguno es una máquina virtual.

- **Imagen y contenedor.** La imagen es una plantilla inmutable; el contenedor es una instancia en ejecución de esa imagen.
- **Datos.** Lo que se escribe dentro de un contenedor se pierde al eliminarlo. Para persistir se usan volúmenes o *bind mounts*, como `./mysql_data` en este proyecto.
- **Alternativas.** Podman (compatible con Docker y sin demonio) o LXC/LXD. Kubernetes no es un tipo de contenedor, sino un orquestador que gestiona contenedores a escala.
