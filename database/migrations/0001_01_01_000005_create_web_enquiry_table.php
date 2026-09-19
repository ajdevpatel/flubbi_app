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
        Schema::create("web_enquiry", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->string("name", 120);
            $table->string("phone", 12)->nullable();
            $table->string("mail", 120);
            $table->string("ip_address", 100)->nullable();
            $table->text("description")->nullable();
            $table->longText("note")->nullable();
            $table->enum("status", [0, 1, 2])->index("status")->default(0)->comment("0-Pending, 1-Progress, 2-Done");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("web_enquiry");
    }
};