<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn("users", "is_dnd")) {
            Schema::table("users", function (Blueprint $table) {
                $table->tinyInteger("is_dnd")->default(0)->after("status");
            });
        }

        if (!Schema::hasColumn("users", "dnd_at")) {
            Schema::table("users", function (Blueprint $table) {
                $table->timestamp("dnd_at")->nullable()->after("is_dnd");
            });
        }

        if (!Schema::hasIndex("users", "users_is_dnd_index")) {
            Schema::table("users", function (Blueprint $table) {
                $table->index("is_dnd", "users_is_dnd_index");
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex("users", "users_is_dnd_index")) {
            Schema::table("users", function (Blueprint $table) {
                $table->dropIndex("users_is_dnd_index");
            });
        }

        $drop = array_values(array_filter(["is_dnd", "dnd_at"], fn (string $column) => Schema::hasColumn("users", $column)));
        if ([] !== $drop) {
            Schema::table("users", function (Blueprint $table) use ($drop) {
                $table->dropColumn($drop);
            });
        }
    }
};
