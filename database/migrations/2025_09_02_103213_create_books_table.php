<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {   
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('name',255)->notNull();
            $table->unsignedBigInteger('category_id')->notNull();
            $table->integer('price')->notNull();
            $table->date('publication_date')->notNull();
            $table->integer('edition')->notNull();
            $table->unsignedBigInteger('author_id')->notNull();
            $table->string('isbn', 20)->nullable()->unique()->notNull();
            $table->string('cover',255)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
