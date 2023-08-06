<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('team_id')->index();
            $table->foreignUlid('domain_id')->index();
            $table->string('destination_path', 2048)->nullable();
            $table->string('alias', 20)->unique()->collation('utf8mb4_bin');
            $table->string('password')->nullable();
            $table->mediumInteger('visit_limit')->unsigned()->nullable();
            $table->mediumInteger('total_visits')->unsigned()->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
