<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trends', function (Blueprint $table) {
            $table->integer('no'); // Nomor urut
            $table->string('bulan'); // Bulan
            $table->integer('jumlah_kat_a')->nullable(); // Jumlah KAT-A
            $table->integer('qoh_rop')->nullable(); // QOH > ROP
            $table->integer('qoh_rop_kurang')->nullable(); // QOH < ROP
            $table->integer('qoh_terisi')->nullable(); // QOH Terisi
            $table->integer('cukup_pr')->nullable(); // Cukup PR
            $table->integer('cukup_po')->nullable(); // Cukup PO
            $table->integer('cukup_pr_po')->nullable(); // Cukup PR & PO
            $table->integer('create_pr')->nullable(); // Create PR
            $table->decimal('ketersediaan', 5, 2)->nullable(); // Ketersediaan (%) with decimal
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trend');
    }
}
