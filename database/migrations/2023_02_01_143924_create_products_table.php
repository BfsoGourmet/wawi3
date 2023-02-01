<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('sku');
            $table->tinyInteger('in_stock');
            $table->string('title', 45);
            $table->tinyText('short_description');
            $table->longText('description');
            $table->tinyInteger('is_vegetarian');
            $table->tinyInteger('is_vegan');
            $table->decimal('calories', 5, 2);
            $table->decimal('sugar_in_calories', 5, 2);
            $table->string('slug', 100);
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
        Schema::dropIfExists('products');
    }
};