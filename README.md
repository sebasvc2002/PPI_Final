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
- [ ] Mostrar los artículos que el usuario seleccionó del catálogo.
- [ ] Permitir modificar su selección 
- [ ] Finalizar compra
### Admin
- [ ] Ver reporte de productos en inventario
- [ ] Agregar nuevos productos
- [ ] Modificar productos existentes
- [ ] Mostrar historial de transacciones
### Usuarios
- [ ] Sistema de inicio de sesión validando correo electrónico y contraseña
- [ ] Debe permanecer iniciada la sesión hasta que el usuario la finalice

# To-Do técnico
### Gestión de usuarios
- [x] Terminar `php/signin.php`: No se insertan datos
- [x] Crear lógica en `login.php` de inicio de sesión
- [ ] Hacer que el header `layout/header.php` cambie cuando está iniciada la sesión
	- [ ] Mostrar "Cerrar sesión" si hay sesión activa
---
### Checkout
- [ ] Crear formulario para capturar o seleccionar dirección de envío
- [ ] Implementar la inserción en las tablas `orders` y `order_details` al conformar la compra
--- 
### Admin
- [ ] Gestión de productos (*CRUD*):
	- [ ] Formulario para subir nuevos productos
	- [ ] Leer
	- [ ] Actualizar: Crear `edit_product.php` para modificar productos
	- [ ] Borrar
---
#### Gestión de categorías y proveedores
- [ ] Crear interfaces para añadir o editar categorías y proveedores
