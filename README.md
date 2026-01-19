Docker Compose – Documentación y creación

•¿Qué son los contenedores de docker?
Són unidades ligeras y portátiles que encapsulas una aplicación junto a sus dependencias en un entorno aislado, siendo fácil de instalar y manejas, siendo capaces de crear la misma imagen con su código fuente y dependencias, siendo esta inmutable no importa las veces que se ejecute, altamente ligero, siendo capaces de manejar gran cantidad de contenedores sin problemas de rendimiento, sin olvidar su seguridad, dado que son compatibles con los principales antivirus o cualquier seguridad a nivel de kernel, también ofreciendo grupos de control de acceso, restringiendo cualquier tipo de cambio

• ¿Qué diferencias hay entre los contenedores de docker y los lxc?
Docker	LXC
Imágenes en capas reutilizables	VMs completas con sistema
Daemon centralizado	Proceso por contenedor
~MBs de RAM	~GBs de RAM
Aplicaciones específicas	Sistemas operativos completos
	

• ¿Cuál es la diferencia entre una imagen y un contenedor en docker?
La imagen es la plantilla misma, igual que un cd, mientras que el contenedor es la misma plantilla ejecutándose

• ¿Qué sucede con los datos cuando un contenedor se elimina?
Se eliminan por completo a no ser que se cree una copia de seguridad

• ¿Cuáles son las ventajas de utilizar contenedores de docker?
Como mencioné anteriormente, es altamente portable, seguro, consume pocos recursos, fácil de entender tanto para cliente como servidor y rápido de ejecución

• ¿Qué tipo de aplicaciones y servicios se pueden desplegar con docker?
Entre los servicios que se pueden son los normales como Gitlab, Apache, PHP, Nginx, Python, Mysql a otros como AWS y Kubernetes, pero siendo mucho más, tanto de código libre de github como plantillas pre creadas por otros usuarios

• ¿Qué otros tipos de contenedores existen además de docker?
Kubernetes, LXC, Podman, 
• Escribe una guía de usuario con los pasos claves para desplegar una aplicación web en contenedores.

Guía de usuario
Como crear una guía de usuario de 0 implicaría rehacer el proyecto optaré por mostrar el proceso de manera breve incluyendo el árbol de archivos

Requisitos previos
# 1. Instalar Docker + Compose
sudo apt update && sudo apt install docker.io docker-compose -y
# 2. Permisos usuario (sin sudo)
sudo usermod -aG docker $USER
newgrp docker

Despliegue
1.      Obtener proyecto
git clone https://github.com/disonanciavirtual/DockerCompose.git
cd DockerCompose

2.      Verificar estructura
tree . -L 2
ls -la web/

3.      Desplegar servicios
docker compose up -d --build
# Esperar inicialización MySQL (~15s)
sleep 15

4.      Verificar estado
docker compose ps
Salida esperada:
NAME           IMAGE                    STATUS    PORTS
pr1_nginx      nginx:alpine             Up        0.0.0.0:8082->80/tcp
pr1_php        mi-pr1-docker-php        Up        9000/tcp
pr1_mysql      mysql:8.0                Up        3306/tcp
pr1_phpmyadmin phpmyadmin/phpmyadmin    Up        0.0.0.0:8081->80/tcp

5.      URLS
App completa:          http://localhost:8082/
Login:                 http://localhost:8082/acceso.php
Registro:              http://localhost:8082/registro.php
Dashboard:             http://localhost:8082/dashboard.php
phpMyAdmin:            http://localhost:8081/ (root/rootpass)
MySQL directo:         localhost:3306

6.      Flujo de uso
Abrir http://localhost:8082/ → Index (plantilla HTML5 UP)
Botón rojo "Sign Up" → http://localhost:8082/registro.php → Rellenar → Usuario guardado en BD
Login → http://localhost:8082/acceso.php → Credenciales → Redirige dashboard.php
Logout → http://localhost:8082/logout.php → Sesión cerrada

7.      BBDD
Usuario: root
Pass: rootpass
BD: login_app
Tabla: users (id, username, email, password, created_at)

8.      Comandos de gestión
# Estado servicios
docker compose ps
# Logs específicos
docker compose logs php        # Errores PHP
docker compose logs mysql      # Base datos
docker compose logs nginx      # Web server
# Reiniciar servicio
docker compose restart
# Parar todo
docker compose down
# Parar + limpiar BD
docker compose down -v

Árbol de directorios, como debe quedar
mi-pr1-docker/
├── docker-compose.yml
├── Dockerfile
├── .gitignore
├── nginx/
│   └── nginx.conf
└── web/
    ├── acceso.php
    ├── assets/
    │   ├── css/
    │   │   ├── fontawesome-all.min.css
    │   │   ├── main.css
    │   │   └── noscript.css
    │   ├── js/
    │   │   ├── jquery.min.js
    │   │   ├── main.js
    │   │   └── util.js
    │   ├── sass/
    │   └── webfonts/
    ├── auth-modal.css
    ├── auth-modal.html
    ├── auth-modal.js
    ├── auth-modal.php
    ├── conexion.php
    ├── dashboard.php
    ├── estilos.css
    ├── images/
    │   ├── banner.jpg
    │   ├── pic01.jpg
    │   ├── pic02.jpg
    │   ├── pic03.jpg
    │   ├── pic04.jpg
    │   ├── pic05.jpg
    │   ├── pic06.jpg
    │   ├── pic07.jpg
    │   └── pic08.jpg
    ├── includes/
    │   ├── footer.php
    │   └── header.php
    ├── index.html
    ├── index.php
    ├── logout.php
    ├── pagina.php
    ├── registro.php
    └── test_registro.php

Incidencias técnicas y soluciones 
1.	Incidencia: Permiso denegado Docker Socket
Solución: Dar permisos a mi usuario con el siguiente comando:
sudo usermod -aG Docker $USER
newgrp docker

2.	Incidencia: Mysql no instalado
Solución: Ejecutar una imagen mysql personalizada con:
FROM php:8.2-fpm 
RUN Docker-php-ext-install mysqli pdo pdo_mysql

3.	Incidencia: Tabla usuarios no creada automáticamente
Solución: Crear la tabla manualmente

4.	Incidencia: Docker-compose.yml corrupto
Solución: Recrear archivo completo con verificación de saltos, comillas y cierres

5.	Incidencia: Conexión MySQL no conectaba
Solución: Reiniciar

Detalles interesantes
Lo más interesante (y remarcable) es ver como algo que me costó DEMASIADO hacer que funcione durante el año pasado, conexiones base de datos, PHP registre, archivos se conecten sin errores, errores por XAMPP, se pudo hacer en un tiempo increíblemente menor y con problemas mínimos, los mencionados anteriormente y que son una tontería a comparación de las numerosas incidencias que tuve en mi anterior proyecto.

<img width="1840" height="777" alt="imagen" src="https://github.com/user-attachments/assets/1966b5ef-cc02-4c95-8ffa-e97ca6495a73" />
<img width="1388" height="852" alt="imagen" src="https://github.com/user-attachments/assets/3de78ee5-eb8a-450f-bf12-8b370f2640ae" />
<img width="514" height="575" alt="imagen" src="https://github.com/user-attachments/assets/facd3fd1-0a10-4fe1-a262-8cab27dd2ef5" />
<img width="502" height="407" alt="imagen" src="https://github.com/user-attachments/assets/308e4c85-b9be-47e6-81a9-af3ac2fb5110" />
<img width="1143" height="340" alt="imagen" src="https://github.com/user-attachments/assets/f8108b28-6425-4899-9ba4-8e5e6c7aa523" />
<img width="1274" height="509" alt="imagen" src="https://github.com/user-attachments/assets/7878b2fe-b90a-4c99-ab67-dc0ce5280cf4" />



