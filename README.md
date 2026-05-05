# To-Do
## Template
- [x] Catálogo de productos
- [x] Info de producto
- [x] Página de información de usuario
- [x] Creación de cuenta de usuario
- [x] Carrito de compras
- [x] Admin
- [x] Información de contacto
- [ ] About us

## Funcionamiento
### Products
- [x] Mostrar la información de producto seleccionado
### Carrito
- [x] Mostrar los artículos que el usuario seleccionó del catálogo.
- [x] Permitir modificar su selección 
- [x] Finalizar compra
### Admin
- [x] Ver reporte de productos en inventario
- [x] Agregar nuevos productos
- [x] Modificar productos existentes
- [x] Mostrar historial de transacciones
### Usuarios
- [x] Sistema de inicio de sesión validando correo electrónico y contraseña
- [x] Debe permanecer iniciada la sesión hasta que el usuario la finalice

# To-Do técnico
### Gestión de usuarios
- [x] Terminar `php/signin.php`: No se insertan datos
- [x] Crear lógica en `login.php` de inicio de sesión
- [x] Hacer que el header `layout/header.php` cambie cuando está iniciada la sesión
	- [x] Mostrar "Cerrar sesión" si hay sesión activa
---
### Checkout
- [x] Crear formulario para capturar o seleccionar dirección de envío
- [x] Implementar la inserción en las tablas `orders` y `order_details` al conformar la compra
--- 
### Admin
- [x] Gestión de productos (*CRUD*):
	- [x] Formulario para subir nuevos productos
	- [x] Leer
	- [x] Actualizar: Crear `edit_product.php` para modificar productos
	- [x] Borrar
---
#### Gestión de categorías y proveedores
- [x] Crear interfaces para añadir o editar categorías y proveedores
