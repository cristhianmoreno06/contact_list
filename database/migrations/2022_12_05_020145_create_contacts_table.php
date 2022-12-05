<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del contacto');
            $table->integer('phone')->comment('Teléfono del contacto');
            $table->date('Birthdate')->comment('Fecha de nacimiento del contacto');
            $table->string('address')->comment('Dirección del contacto');
            $table->string('email')->comment('Correo electrónico del contacto');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
