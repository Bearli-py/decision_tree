
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->longText('data_json'); // Store parsed data sebagai JSON
            $table->integer('total_records');
            $table->integer('yes_count');
            $table->integer('no_count');
            $table->longText('calculation_json')->nullable(); // Store hasil perhitungan
            $table->longText('tree_json')->nullable(); // Store struktur tree
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};