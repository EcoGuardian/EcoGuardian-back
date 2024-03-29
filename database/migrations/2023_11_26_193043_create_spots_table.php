<?php

use App\Models\Spot;
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
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
//            $table->string('name');
            $table->string('latitude');
            $table->string('longitude');
            $table->foreignId('type_id')->constrained('types');
            $table->timestamps();
        });

        $spot = new Spot();

//        $spot->name = "Kanta1";
        $spot->latitude = "33.43296265331129";
        $spot->longitude = "-123.43296265331129";
        $spot->type()->associate(Type::all()->first());

        $spot->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};
