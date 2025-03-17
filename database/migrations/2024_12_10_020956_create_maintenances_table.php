<?php

use App\Models\Bike;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Bike::class)->constrained('bikes');
            $table->foreignIdFor(User::class, 'attendant_id')->constrained('users');
            $table->date('maintenance_date');
            $table->time('maintenance_time')->nullable();
            //            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');

            $table->text('type');
            $table->timestamp('started_at')->nullable();
            //            $table->json('check_list')->nullable();
//            $table->json('repaired')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
