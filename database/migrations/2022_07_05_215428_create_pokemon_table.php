<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->unique();
            $table->string('description');
            $table->foreignId('pokemon_type_one_id')
                ->constrained('pokemon_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('pokemon_type_two_id')
                ->nullable()
                ->constrained('pokemon_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('hit_points');
            $table->integer('attack');
            $table->integer('defense');
            $table->integer('speed');
            $table->integer('special');
            $table->string('image_url_gif');
            $table->string('image_url_png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemon');
    }
};
