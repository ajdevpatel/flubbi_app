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
        Schema::create("marketing_manual_index", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->timestamp("rec_date")->useCurrent();
            $table->enum("is_phone", [0, 1])->index("is_phone")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_wapp", [0, 1])->index("is_wapp")->default(0)->comment("0-No, 1-Yes");
            $table->enum("is_mail", [0, 1])->index("is_mail")->default(0)->comment("0-No, 1-Yes");
            $table->text("message_text")->nullable();
            $table->integer("total_data")->index("total_data")->default(0);
            $table->longText("note")->nullable();
            $table->enum("status", [0, 1, 2, 3, 4])->index("status")->default(0)->comment("0-pending, 1-success , 2-failed, 3-hold, 4-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("marketing_manual_data", function (Blueprint $table) {
            $table->id();
            $table->integer("marketing_manual_index_id")->index("marketing_manual_index_id")->default(0)->comment("marketing_manual_index.id");
            $table->string("name", 80)->nullable();
            $table->string("mail", 80)->nullable();
            $table->string("phone", 12)->nullable();
            $table->enum("is_dnd", [0, 1])->index("is_dnd")->default(0)->comment("0-No, 1-Yes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("marketing_manual_index");
        Schema::dropIfExists("marketing_manual_data");
    }
};