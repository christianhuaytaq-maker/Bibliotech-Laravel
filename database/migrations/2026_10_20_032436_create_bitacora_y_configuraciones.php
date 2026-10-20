<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bitacora', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('usuario_id')->nullable();
            $t->string('accion', 100);
            $t->text('detalle')->nullable();
            $t->timestamps();
        });

        Schema::create('configuraciones', function (Blueprint $t) {
            $t->id();
            $t->string('clave')->unique();
            $t->string('valor')->nullable();
            $t->timestamps();
        });

        DB::table('configuraciones')->insert([
            ['clave' => 'dias_prestamo',    'valor' => '7', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'multa_por_dia',    'valor' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'max_renovaciones', 'valor' => '2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora');
        Schema::dropIfExists('configuraciones');
    }
};
