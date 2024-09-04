<?php

namespace Database\Seeders;

use App\Models\Acceso;
use App\Models\Empresa;
use App\Models\Formapago;
use App\Models\Othercosto;
use App\Models\Serie;
use App\Models\Tribute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Acceso::create([
            'access' => 0,
            'dominio' => 0,
        ]);

        Empresa::create([
            'ruc' => '99999999999',
            'name' => 'MI EMPRESA S.A.C',
            'direccion' => 'CALLE DESCONOCIDA SIN NUMERO',
            'zona' => '',
            'urbanizacion' => 'JAEN',
            'ubigeo' => '060801',
            'distrito' => 'JAEN',
            'provincia' => 'JAEN',
            'departamento' => 'CAJAMARCA',
            'estado' => 'ACTIVO',
            'condicion' => 'HABIDO',
            'logo' => null,
            'icono' => null,
            'publickey' => null,
            'privatekey' => null,
            'usuariosol' => null,
            'clavesol' => null,
            'moneda' => 'PEN',
            'default' => 1,
        ]);

        Serie::create([
            'serie' => 'F001',
            'name' => 'FACTURA ELECTRÓNICA',
            'code' => '01',
            'default' => 1
        ]);

        Serie::create([
            'serie' => 'B001',
            'name' => 'BOLETA DE VENTA',
            'code' => '03',
            'default' => 1
        ]);

        Serie::create([
            'serie' => 'FC01',
            'name' => 'NOTA DE CREDITO',
            'code' => '07',
            'default' => 1
        ]);

        Othercosto::create([
            'name' => 'DELIVERY A DOMICILIO',
            'price' => 5.00,
            'code' => 'DLVDM'
        ]);

        Othercosto::create([
            'name' => 'TAPER DE PLASTICO',
            'price' => 2.00,
            'code' => 'TPDPB'
        ]);

        Formapago::create([
            'name' => 'EFECTIVO',
            'default' => 1,
        ]);

        Formapago::create([
            'name' => 'YAPE',
        ]);

        Formapago::create([
            'name' => 'TRANSFERENCIA',
        ]);


        $admin = Role::create(['name' => 'admin']);
        $master = Role::create(['name' => 'master']);

        Permission::create(['name' => 'dashboard.home', 'descripcion' => 'Panel Administrativo', 'tabla' => 'Dashboard'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.cupones', 'descripcion' => 'Ver/Administrar Cupones', 'tabla' => 'Cupones'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.cupones.create', 'descripcion' => 'Crear Cupón', 'tabla' => 'Cupones'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.cupones.edit', 'descripcion' => 'Editar Cupón', 'tabla' => 'Cupones'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.cupones.delete', 'descripcion' => 'Eliminar Cupón', 'tabla' => 'Cupones'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.orders', 'descripcion' => 'Ver Ordenes', 'tabla' => 'Orders'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.orders.create', 'descripcion' => 'Crear Orden', 'tabla' => 'Orders'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.orders.edit', 'descripcion' => 'Editar Orden', 'tabla' => 'Orders'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.orders.delete', 'descripcion' => 'Eliminar Orden', 'tabla' => 'Orders'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.pedidos', 'descripcion' => 'Ver Pedidos', 'tabla' => 'Pedidos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.pedidos.create', 'descripcion' => 'Crear Pedido', 'tabla' => 'Pedidos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.pedidos.edit', 'descripcion' => 'Editar Pedido', 'tabla' => 'Pedidos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.pedidos.delete', 'descripcion' => 'Eliminar Pedido', 'tabla' => 'Pedidos'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.productos', 'descripcion' => 'Ver/Administrar Productos', 'tabla' => 'Productos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.productos.create', 'descripcion' => 'Crear Producto', 'tabla' => 'Productos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.productos.edit', 'descripcion' => 'Editar Producto', 'tabla' => 'Productos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.productos.delete', 'descripcion' => 'Eliminar Producto', 'tabla' => 'Productos'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.agregados', 'descripcion' => 'Ver/Administrar Agregados', 'tabla' => 'Agregados'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.agregados.create', 'descripcion' => 'Crear Agregado', 'tabla' => 'Agregados'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.agregados.edit', 'descripcion' => 'Editar Agregado', 'tabla' => 'Agregados'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.agregados.delete', 'descripcion' => 'Eliminar Agregado', 'tabla' => 'Agregados'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.categories', 'descripcion' => 'Ver/Administrar Categorías', 'tabla' => 'Categorias'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.categories.create', 'descripcion' => 'Crear Categoría', 'tabla' => 'Categorias'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.categories.edit', 'descripcion' => 'Editar Categoría', 'tabla' => 'Categorias'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.categories.delete', 'descripcion' => 'Eliminar Categoría', 'tabla' => 'Categorias'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.clients', 'descripcion' => 'Ver/Administrar Clientes', 'tabla' => 'Clientes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.clients.create', 'descripcion' => 'Crear Cliente', 'tabla' => 'Clientes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.clients.edit', 'descripcion' => 'Editar Cliente', 'tabla' => 'Clientes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.clients.delete', 'descripcion' => 'Eliminar Cliente', 'tabla' => 'Clientes'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.users', 'descripcion' => 'Ver/Administrar Usuarios', 'tabla' => 'Usuarios'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.users.create', 'descripcion' => 'Crear Usuario', 'tabla' => 'Usuarios'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.users.edit', 'descripcion' => 'Editar Usuario', 'tabla' => 'Usuarios'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.users.delete', 'descripcion' => 'Eliminar Usuario', 'tabla' => 'Usuarios'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.roles', 'descripcion' => 'Ver/Administrar Roles', 'tabla' => 'Roles'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.roles.create', 'descripcion' => 'Crear Rol', 'tabla' => 'Roles'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.roles.edit', 'descripcion' => 'Editar Rol', 'tabla' => 'Roles'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.roles.delete', 'descripcion' => 'Eliminar Rol', 'tabla' => 'Roles'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.permisos', 'descripcion' => 'Ver/Administrar Permiso', 'tabla' => 'Permisos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.permisos.edit', 'descripcion' => 'Editar Permiso', 'tabla' => 'Permisos'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.locations', 'descripcion' => 'Ver/Administrar Ubicaciones', 'tabla' => 'Ubicación Mesas'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.locations.create', 'descripcion' => 'Crear Ubicacion', 'tabla' => 'Ubicación Mesas'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.locations.edit', 'descripcion' => 'Editar Ubicacion', 'tabla' => 'Ubicación Mesas'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.locations.delete', 'descripcion' => 'Eliminar Ubicacion', 'tabla' => 'Ubicación Mesas'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.mesas', 'descripcion' => 'Ver/Administrar Mesas', 'tabla' => 'Mesas'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.mesas.create', 'descripcion' => 'Crear Mesa', 'tabla' => 'Mesas'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.mesas.edit', 'descripcion' => 'Editar Mesa', 'tabla' => 'Mesas'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.mesas.delete', 'descripcion' => 'Eliminar Mesa', 'tabla' => 'Mesas'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.comprobantes', 'descripcion' => 'Ver/Administrar Comprobantes', 'tabla' => 'Comprobantes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.comprobantes.create', 'descripcion' => 'Crear Comprobante', 'tabla' => 'Comprobantes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.comprobantes.edit', 'descripcion' => 'Editar Comprobante', 'tabla' => 'Comprobantes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.comprobantes.delete', 'descripcion' => 'Eliminar Comprobante', 'tabla' => 'Comprobantes'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.series', 'descripcion' => 'Ver/Administrar Tipo Comprobantes', 'tabla' => 'Tipo Comprobantes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.series.create', 'descripcion' => 'Crear Tipo Comprobante', 'tabla' => 'Tipo Comprobantes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.series.edit', 'descripcion' => 'Editar Tipo Comprobante', 'tabla' => 'Tipo Comprobantes'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.series.delete', 'descripcion' => 'Eliminar Tipo Comprobante', 'tabla' => 'Tipo Comprobantes'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.empresa', 'descripcion' => 'Ver/Administrar Empresa', 'tabla' => 'Empresa'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.empresa.create', 'descripcion' => 'Crear Empresa', 'tabla' => 'Empresa'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.empresa.edit', 'descripcion' => 'Editar Empresa', 'tabla' => 'Empresa'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.empresa.delete', 'descripcion' => 'Eliminar Empresa', 'tabla' => 'Empresa'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.cocina', 'descripcion' => 'Ver/Administrar Cocina', 'tabla' => 'Cocina'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.othercostos', 'descripcion' => 'Ver/Administrar Otros Costos', 'tabla' => 'Otros Costos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.othercostos.create', 'descripcion' => 'Crear Otro Costo', 'tabla' => 'Otros Costos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.othercostos.edit', 'descripcion' => 'Editar Otro Costo', 'tabla' => 'Otros Costos'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.othercostos.delete', 'descripcion' => 'Eliminar Otro Costo', 'tabla' => 'Otros Costos'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.formpayment', 'descripcion' => 'Ver/Administrar Formas Pago', 'tabla' => 'Forms Pago'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.formpayment.create', 'descripcion' => 'Crear Forma Pago', 'tabla' => 'Forms Pago'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.formpayment.edit', 'descripcion' => 'Editar Forma Pago', 'tabla' => 'Forms Pago'])->syncRoles([$master, $admin]);
        Permission::create(['name' => 'admin.formpayment.delete', 'descripcion' => 'Eliminar Forma Pago', 'tabla' => 'Forms Pago'])->syncRoles([$master, $admin]);

        Permission::create(['name' => 'admin.reportes', 'descripcion' => 'Generar Reportes', 'tabla' => 'Reportes'])->syncRoles([$master, $admin]);


        $user = User::create([
            'name' => 'VEGA ML',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
            'status' => 0,
            'role_id' => $master->id,
        ]);


        $user->roles()->sync($master->id);
    }
}
