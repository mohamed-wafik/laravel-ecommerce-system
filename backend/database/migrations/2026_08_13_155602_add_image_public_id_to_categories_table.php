<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The ERD only listed `image_url` for categories, but cleaning up
     * Cloudinary assets on replace/delete (same as brands.logo_public_id
     * and users.avatar_public_id) needs the public_id stored too.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image_public_id')->nullable()->after('image_url')->comment('cloudinary');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image_public_id');
        });
    }
};
