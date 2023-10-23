<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->boolean('blocked')
                ->after('host')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('domain', function (Blueprint $table) {
            $table->dropColumn('blocked');
        });
    }
};
