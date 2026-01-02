<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bale_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id');
            $table->uuid('bale_id')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('icon');
            $table->string('route_name');
            $table->enum('type', ['system', 'custom']);
            $table->integer('order');
            $table->boolean('is_active');
            $table->string('permission')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bale_menus');
    }
};
