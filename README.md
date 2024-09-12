# API con Integración a WooCommerce

Este proyecto está diseñado para leer las compras realizadas en WooCommerce y crear usuarios con acceso a una plataforma externa. Se ejecuta como un "cron job" en el servidor. 

El API se creó específicamente para una tienda en línea de libros. Cuando un usuario realiza una compra, el API crea un usuario con los datos ingresados en la tienda, y envía un correo electrónico con la información de acceso a la plataforma externa. Además, cada vez que se realiza una nueva compra, se envía un correo adicional notificando que hay más libros disponibles en la plataforma.

> **Nota:** Ya existe un desarrollo completo de la plataforma externa. Si estás interesado en adquirirlo, por favor consulta la sección de contacto.

## Construido con

- **Laravel**
- **PHP**
- **MySQL**

---

## Requisitos Previos

- Tener instalado **WampServer**, **Composer** y un servidor en la nube para ejecutar los "cron jobs" del API.
- Contar con credenciales de servidor SMTP para el envío correcto de correos electrónicos.

### Adicional

Puedes probar los endpoints utilizando la siguiente colección de Postman: [API WooCommerce Collection](https://elements.getpostman.com/redirect?entityId=24073540-ef553ee2-c6a7-4765-9177-94fcce6d229c&entityType=collection).

### Importante

- Asegúrate de ingresar direcciones de correo válidas para que los correos se envíen correctamente.
- Para añadir más libros a un usuario, utiliza el mismo endpoint de `CreateNewUser` y ajusta los IDs de los libros según sea necesario.
- Si el API está alojada y en uso con la tienda en línea, configura la tienda para evitar que los usuarios compren el mismo libro más de una vez.

---

## Instalación

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/JPabloMendez2002/api-woocommerce.git
   ```

2. **Configura la Base de Datos:**
   - Accede a phpMyAdmin y crea una nueva base de datos llamada `api_woocommerce`.
   - Importa el archivo `api_woocommerce.sql` que se encuentra en la raíz del proyecto.

3. **Navega a la carpeta del proyecto e inicia el servidor:**
   ```bash
   cd ./api-woocommerce
   php artisan serve
   ```

4. **Configura el archivo `.env`:**
   - Asegúrate de completar todas las variables de entorno necesarias, como las credenciales de la base de datos y las configuraciones SMTP.

---

## Contribuir

¡Las contribuciones son bienvenidas! Sigue estos pasos para contribuir al proyecto:

1. Haz un fork del repositorio.
2. Crea una nueva rama para tu feature: 
   ```bash
   git checkout -b feature/nueva-feature
   ```
3. Realiza tus cambios y haz un commit:
   ```bash
   git commit -m 'Agrega nueva feature'
   ```
4. Sube tu rama al repositorio remoto:
   ```bash
   git push origin feature/nueva-feature
   ```
5. Abre un pull request en GitHub.

---

## Contacto

- **GitHub:** [JPabloMendez2002](https://github.com/JPabloMendez2002)  
- **LinkedIn:** [Jose Pablo Mendez Poveda](https://www.linkedin.com/in/jose-pablo-mendez-poveda)  
- **Instagram:** [@pablomendezpoveda](https://www.instagram.com/pablomendezpoveda)  

---

![GitHub license](https://img.shields.io/github/license/JPabloMendez2002/api-woocommerce)
