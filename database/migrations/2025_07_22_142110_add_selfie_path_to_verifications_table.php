<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('verifications', function (Blueprint $table) {
            $table->string('selfie_path')->nullable()->after('proof_path');
        });
    }

    public function down(): void {
        Schema::table('verifications', function (Blueprint $table) {
            $table->dropColumn('selfie_path');
        });
    }
};
