<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class PermissionProformaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'enable_proforma',
            'description' => 'Habilitar Mod. Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'list_proforma',
            'description' => 'Listar Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'create_proforma',
            'description' => 'Crear Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'update_proforma',
            'description' => 'Editar Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'destroy_proforma',
            'description' => 'Eliminar Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'print_proforma',
            'description' => 'Imprimir Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'show_proforma',
            'description' => 'Ver detalles Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'confirm_proforma',
            'description' => 'Confirmar Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'changePercentage_proforma',
            'description' => 'Cambiar porcentajes Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'editEquipment_proforma',
            'description' => 'Editar equipos Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'destroyEquipment_proforma',
            'description' => 'Eliminar equipos Pre Cotizaciones'
        ]);
        Permission::create([
            'name' => 'saveEquipment_proforma',
            'description' => 'Agregar/Guardar equipos Pre Cotizaciones'
        ]);

        $role = Role::findByName('admin');

        $role->givePermissionTo([
            'enable_proforma',
            'list_proforma',
            'create_proforma',
            'update_proforma',
            'destroy_proforma',
            'print_proforma',
            'show_proforma',
            'confirm_proforma',
            'changePercentage_proforma',
            'editEquipment_proforma',
            'destroyEquipment_proforma',
            'saveEquipment_proforma',
        ]);
    }
}
