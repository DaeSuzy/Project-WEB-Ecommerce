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
        Schema::create('dompets', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->string('thumbnail');
            $table->text('about');
            $table->unsignedBigInteger('price');//Angkanya tidak bisa (Negatif -)
            $table->unsignedBigInteger('stock');
            $table->boolean('is_popular');// false or true

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->cascadeOnDelete();
            
            $table->softDeletes();//data tidak akan hilang 100%

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dompets');
    }
};
