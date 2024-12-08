<?php

use App\Models\Permission;
use App\Models\Role;
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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->string("description")->nullable();
            $table->string("group")->nullable();
            $table->boolean("default")->default(false);
//            $table->boolean("active")->default(true);

            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Role::class);
            $table->foreignIdFor(Permission::class);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
