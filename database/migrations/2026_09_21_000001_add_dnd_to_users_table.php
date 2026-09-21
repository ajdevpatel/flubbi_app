<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->tinyInteger("is_dnd")->default(0)->after("status");
            $table->timestamp("dnd_at")->nullable()->after("is_dnd");
            $table->index("is_dnd");
        });
    }

    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropIndex(["is_dnd"]);
            $table->dropColumn(["is_dnd", "dnd_at"]);
        });
    }
};
