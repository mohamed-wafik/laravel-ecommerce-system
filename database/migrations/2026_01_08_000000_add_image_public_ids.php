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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image_public_id')->nullable()->after('image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('image_public_id')->nullable()->after('image');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_public_id')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image_public_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_public_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_public_id');
        });
    }
};
