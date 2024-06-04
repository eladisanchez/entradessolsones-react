<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Order;

class MigrateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            DB::beginTransaction();

            $oldPrefix = 'cvo_';
            $newPrefix = '';

            $tables = DB::select("SHOW TABLES LIKE '{$oldPrefix}%'");

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                $newTableName = str_replace($oldPrefix, $newPrefix, $tableName);
                Schema::rename($tableName, $newTableName);
                $this->info("Renamed $tableName to $newTableName");
            }

            $tables = [
                'codis' => 'coupons',
                'comandes' => 'orders',
                'devolucions' => 'refunds',
                'extractes' => 'extracts',
                'opcions' => 'options',
                'producte_tarifa' => 'product_rate',
                'productes' => 'products',
                'productes_categories' => 'categories',
                'productes_entrades' => 'products_tickets',
                'productes_packs' => 'products_packs',
                'productes_usuaris' => 'products_users',
                'reserves' => 'bookings',
                'tarifes' => 'rates',
                'productes_espais' => 'venues',
                'productespais' => 'venues'
            ];

            foreach ($tables as $oldTableName => $newTableName) {
                if (!Schema::hasTable($oldTableName)) {
                    $this->warn("Table $oldTableName does not exist");
                    continue;
                }
                Schema::rename($newPrefix . $oldTableName, $newPrefix . $newTableName);
                $this->info("Renamed $oldTableName to $newTableName");
            }

            $databaseName = config('database.connections.' . env('DB_CONNECTION') . '.database');
            if ($databaseName) {
                DB::statement("DROP TABLE IF EXISTS `productes_sessions`;");
                DB::statement("SET foreign_key_checks = 0;");
                DB::statement("ALTER TABLE products MODIFY COLUMN nom VARCHAR(191);");
                DB::statement("ALTER DATABASE `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                DB::statement("ALTER TABLE products CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                DB::statement("ALTER TABLE products_packs DROP FOREIGN KEY productes_packs_pack_id_foreign");
                DB::statement("ALTER TABLE products_packs DROP FOREIGN KEY productes_packs_producte_id_foreign;");
                DB::statement("ALTER TABLE product_rate DROP FOREIGN KEY producte_tarifa_producte_id_foreign;");
                DB::statement("ALTER TABLE product_rate DROP FOREIGN KEY producte_tarifa_tarifa_id_foreign;");
                DB::statement("ALTER TABLE bookings DROP FOREIGN KEY reserves_comanda_id_foreign;");
                DB::statement("ALTER TABLE bookings DROP FOREIGN KEY reserves_producte_id_foreign;");
                DB::statement("ALTER TABLE bookings DROP FOREIGN KEY reserves_tarifa_id_foreign;");
                DB::statement("ALTER TABLE scans DROP FOREIGN KEY reserva;");
                DB::statement("ALTER TABLE products_tickets DROP FOREIGN KEY productes_entrades_producte_id_foreign;");
                DB::statement("ALTER TABLE products DROP FOREIGN KEY productes_categoria_id_foreign;");
                DB::statement("ALTER TABLE products DROP FOREIGN KEY productes_parent_id_foreign;");
                DB::statement("ALTER TABLE role_user DROP FOREIGN KEY role_user_role_id_foreign;");
                DB::statement("ALTER TABLE role_user DROP FOREIGN KEY role_user_user_id_foreign2;");
                DB::statement("ALTER TABLE products_users DROP FOREIGN KEY cvo_productes_usuaris_ibfk_1;");
                DB::statement("ALTER TABLE products_users DROP FOREIGN KEY cvo_productes_usuaris_ibfk_2;");
                DB::statement("ALTER TABLE extracts DROP FOREIGN KEY usuaris;");
                DB::statement("ALTER TABLE permission_role DROP FOREIGN KEY permission_role_permission_id_foreign;");
                DB::statement("ALTER TABLE permission_role DROP FOREIGN KEY permission_role_role_id_foreign;");
            }
            
            $newColumns = [
                'categories' => [
                    'ordre' => 'order',
                    'titol_ca' => 'title',
                    'resum_ca' => 'summary',
                ],
                'coupons' => [
                    'codi' => 'code',
                    'descompte' => 'discount',
                    'preu' => 'price',
                    'descripcio' => 'description',
                    'producte_id' => 'product_id',
                    'tarifa_id' => 'rate_id',
                    'validesa' => 'validity',
                ],
                'orders' => [
                    'sessio' => 'session',
                    'nom' => 'name',
                    'telefon' => 'phone',
                    'observacions' => 'observations',
                    'pagament' => 'payment',
                    'pagat' => 'paid',
                    'idioma' => 'lang',
                    'codi' => 'coupon',
                ],
                'products' => [
                    'nom' => 'name',
                    'actiu' => 'active',
                    'ordre' => 'order',
                    'espack' => 'is_pack',
                    'titol_ca' => 'title',
                    'resum_ca' => 'summary',
                    'descripcio_ca' => 'description',
                    'horaris_ca' => 'schedule',
                    'minimEntrades' => 'min_tickets',
                    'maximEntrades' => 'max_tickets',
                    'limitHores' => 'hour_limit',
                    'categoria_id' => 'category_id',
                    'espai_id' => 'venue_id',
                    'distancia_social' => 'social_distance',
                    'aforament' => 'capacity',
                    'lloc' => 'place',
                    'totalVendes' => 'DELETE',
                ],
                'rates' => [
                    'ambdescompte' => 'DELETE',
                    'ordre' => 'order',
                    'titol_ca' => 'title',
                    'descripcio_ca' => 'description',
                ],
                'product_rate' => [
                    'producte_id' => 'product_id',
                    'tarifa_id' => 'rate_id',
                    'preu' => 'price',
                    'preuzona' => 'pricezone'
                ],
                'products_tickets' => [
                    'producte_id' => 'product_id',
                    'entrades' => 'tickets',
                    'dia' => 'day',
                    'hora' => 'hour',
                    'idioma' => 'lang',
                    'localitats' => 'seats',
                    'cancelat' => 'cancelled',
                ],
                'products_packs' => [
                    'producte_id' => 'product_id',
                ],
                'products_users' => [
                    'producte_id' => 'product_id',
                    'usuari_id' => 'user_id',
                ],
                'users' => [
                    'condicions' => 'conditions',
                ],
                'venues' => [
                    'nom' => 'name',
                    'adreca' => 'address',
                    'localitats' => 'seats',
                    'escenari' => 'stage',
                ],
                'bookings' => [
                    'producte_id' => 'product_id',
                    'tarifa_id' => 'rate_id',
                    'comanda_id' => 'order_id',
                    'dia' => 'day',
                    'hora' => 'hour',
                    'localitat' => 'seat',
                    'numEntrades' => 'tickets',
                    'preu' => 'price',
                    'espack' => 'is_pack',
                    'devolucio' => 'refund'
                ],
                'scans' => [
                    'reserva_id' => 'booking_id'
                ]
            ];

            foreach ($newColumns as $tableName => $columns) {
                foreach ($columns as $oldColumnName => $newColumnName) {
                    if (!Schema::hasColumn($tableName, $oldColumnName)) {
                        $this->warn("Column $oldColumnName does not exist in $tableName table");
                        continue;
                    }
                    if ($newColumnName === 'DELETE') {
                        Schema::table($tableName, function ($table) use ($oldColumnName) {
                            $table->dropColumn($oldColumnName);
                        });
                        $this->info("Deleted $oldColumnName from $tableName table");
                        continue;
                    }
                    Schema::table($tableName, function ($table) use ($oldColumnName, $newColumnName) {
                        $table->renameColumn($oldColumnName, $newColumnName);
                    });
                    $this->info("Renamed $oldColumnName to $newColumnName in $tableName table");
                }
            }

            if (!Schema::hasTable('password_reset_tokens')) {
                Schema::create('password_reset_tokens', function ($table) {
                    $table->string('email')->primary();
                    $table->string('token');
                    $table->timestamp('created_at')->nullable();
                });
                $this->info("Added password reset tokens table");
            }
            

            Schema::table('products_tickets', function ($table) {
                if (!Schema::hasColumn('products_tickets', 'id')) {
                    Schema::disableForeignKeyConstraints();
                    DB::statement('ALTER TABLE products_tickets DROP PRIMARY KEY');
                    $table->id();
                    $this->info("Fixed products_tickets table primary key");
                }
            });
            Schema::table('users', function ($table) {
                if (!Schema::hasColumn('users', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                    $this->info("Added email_verified_at column to users table");
                }
            });
            Schema::table('products', function ($table) {
                if (!Schema::hasColumn('products', 'image')) {
                    $table->string('image')->nullable();
                    $table->string('image_header')->nullable();
                    $this->info("Added image and image_header columns to products table");
                }
            });
            Schema::table('options', function($table) {
                if (!Schema::hasColumn('options', 'name')) {
                    $table->string('name')->nullable();
                    $table->string('description')->nullable();
                    $this->info("Added name and description columns to options table");
                }
            });

            DB::table('orders')->where('payment', 'targeta')->update(['payment' => 'card']);
            $this->info("Updated payment method in orders table");

            DB::table('roles')->where('name','entitat')->update([
                'name' => 'organizer',
                'display_name' => 'Organitzador'
            ]);
            $this->info("Updated role name in roles table");

            DB::statement("SET foreign_key_checks = 1;");

            DB::commit();

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
            DB::rollBack();
        }
    }
}
