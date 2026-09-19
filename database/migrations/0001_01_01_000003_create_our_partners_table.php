<?php

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
        Schema::create("our_partners", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120)->nullable();
            $table->string("logo", 100)->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("is_backend", [0, 1])->index("is_backend")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_frontend", [0, 1])->index("is_frontend")->default(0)->comment("0-No, 1-Yes");
            $table->enum("type", [0, 1, 2, 3, 4, 5])->index("type")->default(0)->comment("0-others, 1-Public Sector Banks, 2-Private Sector Banks, 3-NBFCs, 4-FinTech Lenders, 5-Foreign Banks");
            $table->enum("status", [0, 1, 2])->index("status")->default(1)->comment("0-pending, 1-active, 2-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->integer("created_by")->index("created_by")->default(0);
            $table->integer("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("our_partners");
    }
};
