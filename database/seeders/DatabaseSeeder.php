<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────
        // 1. USUARIOS
        // ─────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@concar.pe'],
            ['name' => 'Administrador', 'password' => Hash::make('password')]
        );

        $contador = User::firstOrCreate(
            ['email' => 'contador@concar.pe'],
            ['name' => 'María Quispe', 'password' => Hash::make('password')]
        );

        $asistente = User::firstOrCreate(
            ['email' => 'asistente@concar.pe'],
            ['name' => 'Carlos Rojas', 'password' => Hash::make('password')]
        );

        // ─────────────────────────────────────────
        // 2. EMPRESA
        // ─────────────────────────────────────────
        $empresa = DB::table('empresas')->insertGetId([
            'ruc'                      => '20601234567',
            'razon_social'             => 'Comercial Andina S.A.C.',
            'moneda'                   => 'PEN',
            'fecha_inicio_actividades' => '2020-01-02',
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        // ─────────────────────────────────────────
        // 3. USUARIOS → EMPRESA
        // ─────────────────────────────────────────
        DB::table('empresa_usuario')->insert([
            ['empresa_id' => $empresa, 'user_id' => $admin->id,     'rol' => 'administrador', 'created_at' => now(), 'updated_at' => now()],
            ['empresa_id' => $empresa, 'user_id' => $contador->id,  'rol' => 'contador',      'created_at' => now(), 'updated_at' => now()],
            ['empresa_id' => $empresa, 'user_id' => $asistente->id, 'rol' => 'asistente',     'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─────────────────────────────────────────
        // 4. PLAN DE CUENTAS (PCGE simplificado)
        // ─────────────────────────────────────────
        $cuentas = [
            // Elemento 1 — Activo
            ['codigo' => '10',    'denominacion' => 'Efectivo y Equivalentes de Efectivo',   'nivel' => 'elemento',      'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '1011',  'denominacion' => 'Caja',                                  'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '10'],
            ['codigo' => '1041',  'denominacion' => 'Cuentas Corrientes en Instituciones Financieras', 'nivel' => 'divisionaria', 'acepta_movimiento' => true, 'padre' => '10'],
            ['codigo' => '12',    'denominacion' => 'Cuentas por Cobrar Comerciales - Terceros', 'nivel' => 'elemento',   'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '1212',  'denominacion' => 'Emitidas en Cartera',                   'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '12'],
            ['codigo' => '20',    'denominacion' => 'Mercaderías',                            'nivel' => 'elemento',      'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '2011',  'denominacion' => 'Mercaderías Manufacturadas',             'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '20'],
            // Elemento 4 — Pasivo
            ['codigo' => '40',    'denominacion' => 'Tributos, Contraprestaciones y Aportes al Sistema de Pensiones', 'nivel' => 'elemento', 'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '4011',  'denominacion' => 'Impuesto General a las Ventas',         'nivel' => 'subcuenta',     'acepta_movimiento' => false, 'padre' => '40'],
            ['codigo' => '40111', 'denominacion' => 'IGV - Cuenta Propia',                   'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '4011'],
            ['codigo' => '42',    'denominacion' => 'Cuentas por Pagar Comerciales - Terceros', 'nivel' => 'elemento',   'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '4212',  'denominacion' => 'Emitidas',                               'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '42'],
            // Elemento 6 — Compras
            ['codigo' => '60',    'denominacion' => 'Compras',                                'nivel' => 'elemento',      'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '6011',  'denominacion' => 'Mercaderías',                            'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '60'],
            // Elemento 7 — Ventas
            ['codigo' => '70',    'denominacion' => 'Ventas',                                 'nivel' => 'elemento',      'acepta_movimiento' => false, 'padre' => null],
            ['codigo' => '7011',  'denominacion' => 'Mercaderías',                            'nivel' => 'divisionaria',  'acepta_movimiento' => true,  'padre' => '70'],
        ];

        $cuentaIds = [];
        foreach ($cuentas as $c) {
            $padreId = $c['padre'] ? ($cuentaIds[$c['padre']] ?? null) : null;
            $id = DB::table('plan_cuentas')->insertGetId([
                'empresa_id'       => $empresa,
                'codigo'           => $c['codigo'],
                'denominacion'     => $c['denominacion'],
                'nivel'            => $c['nivel'],
                'cuenta_padre_id'  => $padreId,
                'acepta_movimiento'=> $c['acepta_movimiento'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            $cuentaIds[$c['codigo']] = $id;
        }

        // ─────────────────────────────────────────
        // 5. CONFIGURACIÓN CONTABLE
        // ─────────────────────────────────────────
        DB::table('configuracion_contables')->insert([
            'empresa_id'           => $empresa,
            'cuenta_clientes_id'   => $cuentaIds['1212'],
            'cuenta_proveedores_id'=> $cuentaIds['4212'],
            'cuenta_igv_compras_id'=> $cuentaIds['40111'],
            'cuenta_igv_ventas_id' => $cuentaIds['40111'],
            'cuenta_compras_id'    => $cuentaIds['6011'],
            'cuenta_ventas_id'     => $cuentaIds['7011'],
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        // ─────────────────────────────────────────
        // 6. TERCEROS
        // ─────────────────────────────────────────
        $proveedores = [
            ['tipo_documento' => 'RUC', 'numero_documento' => '20100070970', 'razon_social' => 'Alicorp S.A.A.',          'tipo' => 'proveedor'],
            ['tipo_documento' => 'RUC', 'numero_documento' => '20131312955', 'razon_social' => 'Gloria S.A.',             'tipo' => 'proveedor'],
            ['tipo_documento' => 'RUC', 'numero_documento' => '20602525749', 'razon_social' => 'Distribuciones Norte E.I.R.L.', 'tipo' => 'proveedor'],
        ];
        $clientes = [
            ['tipo_documento' => 'RUC', 'numero_documento' => '20550145275', 'razon_social' => 'Supermercados Peruanos S.A.', 'tipo' => 'cliente'],
            ['tipo_documento' => 'RUC', 'numero_documento' => '20293420035', 'razon_social' => 'Cencosud Retail Perú S.A.',  'tipo' => 'cliente'],
            ['tipo_documento' => 'DNI', 'numero_documento' => '45678901',    'razon_social' => 'Juan Pérez Mamani',           'tipo' => 'cliente'],
        ];

        $terceroIds = [];
        foreach (array_merge($proveedores, $clientes) as $t) {
            $id = DB::table('terceros')->insertGetId([
                'empresa_id'       => $empresa,
                'tipo_documento'   => $t['tipo_documento'],
                'numero_documento' => $t['numero_documento'],
                'razon_social'     => $t['razon_social'],
                'tipo'             => $t['tipo'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            $terceroIds[$t['numero_documento']] = $id;
        }

        // ─────────────────────────────────────────
        // 7. COMPRAS (con asiento automático)
        // ─────────────────────────────────────────
        $comprasData = [
            [
                'tercero'          => '20100070970',
                'tipo_comprobante' => 'Factura',
                'serie'            => 'F001',
                'numero'           => '00001234',
                'fecha_emision'    => '2025-01-05',
                'fecha_registro'   => '2025-01-05',
                'base_imponible'   => 5000.00,
                'igv'              => 900.00,
                'total'            => 5900.00,
            ],
            [
                'tercero'          => '20131312955',
                'tipo_comprobante' => 'Factura',
                'serie'            => 'F002',
                'numero'           => '00000567',
                'fecha_emision'    => '2025-01-10',
                'fecha_registro'   => '2025-01-10',
                'base_imponible'   => 2800.00,
                'igv'              => 504.00,
                'total'            => 3304.00,
            ],
        ];

        foreach ($comprasData as $i => $c) {
            $numAsiento = 'C-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            $asientoId = DB::table('asientos')->insertGetId([
                'empresa_id'  => $empresa,
                'numero'      => $numAsiento,
                'fecha'       => $c['fecha_emision'],
                'tipo'        => 'compras',
                'glosa'       => 'Compra ' . $c['tipo_comprobante'] . ' ' . $c['serie'] . '-' . $c['numero'],
                'total_debe'  => $c['total'],
                'total_haber' => $c['total'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            DB::table('asiento_detalles')->insert([
                ['asiento_id' => $asientoId, 'plan_cuenta_id' => $cuentaIds['6011'],  'glosa' => 'Compra mercadería', 'debe' => $c['base_imponible'], 'haber' => 0,          'created_at' => now(), 'updated_at' => now()],
                ['asiento_id' => $asientoId, 'plan_cuenta_id' => $cuentaIds['40111'], 'glosa' => 'IGV compras',       'debe' => $c['igv'],            'haber' => 0,          'created_at' => now(), 'updated_at' => now()],
                ['asiento_id' => $asientoId, 'plan_cuenta_id' => $cuentaIds['4212'],  'glosa' => 'Por pagar',         'debe' => 0,                    'haber' => $c['total'],'created_at' => now(), 'updated_at' => now()],
            ]);

            DB::table('registro_compras')->insert([
                'empresa_id'       => $empresa,
                'tercero_id'       => $terceroIds[$c['tercero']],
                'asiento_id'       => $asientoId,
                'tipo_comprobante' => $c['tipo_comprobante'],
                'serie'            => $c['serie'],
                'numero'           => $c['numero'],
                'fecha_emision'    => $c['fecha_emision'],
                'fecha_registro'   => $c['fecha_registro'],
                'base_imponible'   => $c['base_imponible'],
                'igv'              => $c['igv'],
                'total'            => $c['total'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // ─────────────────────────────────────────
        // 8. VENTAS (con asiento automático)
        // ─────────────────────────────────────────
        $ventasData = [
            [
                'tercero'          => '20550145275',
                'tipo_comprobante' => 'Factura',
                'serie'            => 'F001',
                'numero'           => '00000001',
                'fecha_emision'    => '2025-01-08',
                'fecha_registro'   => '2025-01-08',
                'base_imponible'   => 8474.58,
                'igv'              => 1525.42,
                'total'            => 10000.00,
            ],
            [
                'tercero'          => '20293420035',
                'tipo_comprobante' => 'Factura',
                'serie'            => 'F001',
                'numero'           => '00000002',
                'fecha_emision'    => '2025-01-15',
                'fecha_registro'   => '2025-01-15',
                'base_imponible'   => 4237.29,
                'igv'              => 762.71,
                'total'            => 5000.00,
            ],
            [
                'tercero'          => '45678901',
                'tipo_comprobante' => 'Boleta',
                'serie'            => 'B001',
                'numero'           => '00000001',
                'fecha_emision'    => '2025-01-20',
                'fecha_registro'   => '2025-01-20',
                'base_imponible'   => 847.46,
                'igv'              => 152.54,
                'total'            => 1000.00,
            ],
        ];

        foreach ($ventasData as $i => $v) {
            $numAsiento = 'V-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            $asientoId = DB::table('asientos')->insertGetId([
                'empresa_id'  => $empresa,
                'numero'      => $numAsiento,
                'fecha'       => $v['fecha_emision'],
                'tipo'        => 'ventas',
                'glosa'       => 'Venta ' . $v['tipo_comprobante'] . ' ' . $v['serie'] . '-' . $v['numero'],
                'total_debe'  => $v['total'],
                'total_haber' => $v['total'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            DB::table('asiento_detalles')->insert([
                ['asiento_id' => $asientoId, 'plan_cuenta_id' => $cuentaIds['1212'],  'glosa' => 'Por cobrar',    'debe' => $v['total'],           'haber' => 0,                   'created_at' => now(), 'updated_at' => now()],
                ['asiento_id' => $asientoId, 'plan_cuenta_id' => $cuentaIds['40111'], 'glosa' => 'IGV ventas',    'debe' => 0,                     'haber' => $v['igv'],           'created_at' => now(), 'updated_at' => now()],
                ['asiento_id' => $asientoId, 'plan_cuenta_id' => $cuentaIds['7011'],  'glosa' => 'Venta mercad.', 'debe' => 0,                     'haber' => $v['base_imponible'],'created_at' => now(), 'updated_at' => now()],
            ]);

            DB::table('registro_ventas')->insert([
                'empresa_id'       => $empresa,
                'tercero_id'       => $terceroIds[$v['tercero']],
                'asiento_id'       => $asientoId,
                'tipo_comprobante' => $v['tipo_comprobante'],
                'serie'            => $v['serie'],
                'numero'           => $v['numero'],
                'fecha_emision'    => $v['fecha_emision'],
                'fecha_registro'   => $v['fecha_registro'],
                'base_imponible'   => $v['base_imponible'],
                'igv'              => $v['igv'],
                'total'            => $v['total'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // ─────────────────────────────────────────
        // 9. ASIENTO DE APERTURA
        // ─────────────────────────────────────────
        $apertura = DB::table('asientos')->insertGetId([
            'empresa_id'  => $empresa,
            'numero'      => 'A-00001',
            'fecha'       => '2025-01-01',
            'tipo'        => 'apertura',
            'glosa'       => 'Asiento de apertura ejercicio 2025',
            'total_debe'  => 15000.00,
            'total_haber' => 15000.00,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        DB::table('asiento_detalles')->insert([
            ['asiento_id' => $apertura, 'plan_cuenta_id' => $cuentaIds['1011'], 'glosa' => 'Saldo inicial caja',   'debe' => 5000.00,  'haber' => 0,         'created_at' => now(), 'updated_at' => now()],
            ['asiento_id' => $apertura, 'plan_cuenta_id' => $cuentaIds['1041'], 'glosa' => 'Saldo inicial banco',  'debe' => 10000.00, 'haber' => 0,         'created_at' => now(), 'updated_at' => now()],
            ['asiento_id' => $apertura, 'plan_cuenta_id' => $cuentaIds['4212'], 'glosa' => 'Deuda inicial proveed','debe' => 0,        'haber' => 15000.00,  'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('✅ Seeder completado:');
        $this->command->info('   Empresa:   Comercial Andina S.A.C. (RUC 20601234567)');
        $this->command->info('   Usuarios:  admin@concar.pe / contador@concar.pe / asistente@concar.pe');
        $this->command->info('   Password:  password');
        $this->command->info('   Cuentas:   ' . count($cuentas) . ' del PCGE');
        $this->command->info('   Terceros:  ' . count($proveedores) + count($clientes) . ' (3 proveedores + 3 clientes)');
        $this->command->info('   Compras:   ' . count($comprasData));
        $this->command->info('   Ventas:    ' . count($ventasData));
        $this->command->info('   Asientos:  ' . (count($comprasData) + count($ventasData) + 1) . ' (apertura + compras + ventas)');
    }
}