<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Bersihkan data di kolom `response_content` agar valid JSON
        DB::table('responses')->whereNull('response_content')->update(['response_content' => '[]']);
        DB::table('responses')->whereRaw('JSON_VALID(response_content) = 0')->update(['response_content' => '[]']);

        // Ubah tipe kolom menjadi JSON
        Schema::table('responses', function (Blueprint $table) {
            $table->json('response_content')->nullable()->change();
        });
    }

    public function down()
    {
        // Kembalikan tipe kolom ke string
        Schema::table('responses', function (Blueprint $table) {
            $table->string('response_content', 255)->nullable()->change();
        });
    }
};
