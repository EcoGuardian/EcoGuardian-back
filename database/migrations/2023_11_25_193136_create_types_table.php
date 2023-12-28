<?php

use App\Models\Type;
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

        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->timestamps();
        });

        Type::create([
            "name" => "Papir",
            "color" => "hueGreen"
        ]);

        Type::create([
            "name" => "Plastika",
            "color" => "hueYellow"
        ]);

        Type::create([
            "name" => "Staklo",
            "color" => "hueBlue"
        ]);

        Type::create([
            "name" => "Otpad",
            "color" => "hueCyan"
        ]);

        Type::create([
            "name" => "Opasne materije",
            "color" => "hueRed"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
