<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            DB::statement("ALTER TABLE profiles MODIFY gender ENUM('Laki-laki', 'Perempuan', 'Tidak Ingin Menyebutkan', 'Tidak Diketahui', 'Tulis Sendiri')");
            $table->string('gender_other')->nullable()->after('gender');
            $table->string('place_of_birth')->nullable()->after('gender_other');
            $table->date('date_of_birth')->nullable()->after('place_of_birth');
            $table->string('phone_number')->nullable()->after('date_of_birth');
            $table->bigInteger('regency_id', false, true)->nullable()->after('phone_number');

            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            DB::statement("ALTER TABLE profiles MODIFY gender ENUM('Laki-laki', 'Perempuan')");
            $table->dropForeign(['regency_id']);
            $table->dropColumn(['gender_other', 'place_of_birth', 'date_of_birth', 'phone_number', 'regency_id']);
        });
    }
};
