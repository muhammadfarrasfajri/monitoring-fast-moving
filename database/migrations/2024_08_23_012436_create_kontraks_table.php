<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontraksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id(); // Kolom No sebagai primary key
            $table->string('material'); // Kolom Material (string karena bisa berupa kode atau teks)
            $table->string('material_description'); // Kolom Material Description (deskripsi material)
            $table->string('uom'); // Kolom UOM (Unit of Measure)
            $table->string('abc'); // Kolom ABC (klasifikasi ABC)
            $table->string('mrp_type'); // Kolom MRP Type
            $table->string('mrp_control'); // Kolom MRP Control
            $table->string('pg'); // Kolom PG (Purchasing Group)
            $table->string('mg'); // Kolom MG (Material Group)
            $table->integer('qoh')->nullable(); // Kolom QOH (Quantity on Hand) per 01/08/24, bisa null
            $table->integer('rop')->nullable(); // Kolom ROP (Reorder Point), bisa null
            $table->integer('max')->nullable(); // Kolom MAX (Maximum Quantity), bisa null
            $table->string('no_kontrak')->nullable(); // Kolom NO KONTRAK, bisa null
            $table->date('validity_end')->nullable(); // Kolom VALIDITY END, bisa null
            $table->string('pr_kontrak')->nullable(); // Kolom PR KONTRAK, bisa null
            $table->date('tgl_pr')->nullable(); // Kolom TGL PR, bisa null
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kontraks');
    }
}
