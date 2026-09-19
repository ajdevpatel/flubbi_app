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
        Schema::create("user_subscriptions", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->bigInteger("user_id")->index("user_id")->default(0);
            $table->timestamp("rec_date")->useCurrent();
            $table->timestamp("start_date")->useCurrent();
            $table->timestamp("expiry_date")->useCurrent();
            $table->string("card_number", 100)->unique()->nullable();
            $table->decimal("amount", 10, 2)->default(0);
            $table->integer("application_id")->index("application_id")->default(0)->comment("loan_applications.id");
            $table->enum("is_manual", [0, 1])->index("is_manual")->default(0)->comment("0-No, 1-Yes");
            $table->enum("status", [0, 1, 2, 3, 4])->index("status")->default(0)->comment("0-pending, 1-active, 2-expired, 3-refund, 4-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("user_invoices", function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->enum("object_type", [0])->index("object_type")->default(0)->comment("0-user_subscriptions");
            $table->bigInteger("object_id")->index("object_id")->default(0)->comment("object_type.id");
            $table->integer("inv_number");
            $table->decimal("price", 10, 2)->default(0);
            $table->decimal("cgst", 8, 2)->default(0);
            $table->decimal("sgst", 8, 2)->default(0);
            $table->decimal("igst", 8, 2)->default(0);
            $table->decimal("grandtotal", 10, 2)->default(0);
            $table->text("remarks")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_subscriptions");
        Schema::dropIfExists("user_invoices");
    }
};
