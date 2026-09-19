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
        $gateway_key = [];
        $gateway_label = "";
        if ([] != config("web.gateway.payment_gateway")) {
            foreach (config("web.gateway.payment_gateway") as $k => $v) {
                $gateway_label .= $k . "-" . $v . ", ";
                array_push($gateway_key, $k);
            }
        }

        Schema::create("transactions", function (Blueprint $table) use ($gateway_key, $gateway_label) {
            $table->id();
            $table->string("uuid")->unique();
            $table->bigInteger("user_id")->index("user_id")->default(0);
            $table->timestamp("rec_date")->useCurrent();
            $table->enum("gateway_type", $gateway_key)->index("gateway_type")->default(0)->comment($gateway_label);
            $table->string("transaction_id")->unique()->nullable()->comment("response_gateway_transaction_id");
            $table->string("payment_id")->unique()->nullable()->comment("response_gateway_payment_id");
            $table->string("order_id")->unique()->nullable()->comment("response_gateway_order_id");
            $table->decimal("amount", 10, 2)->default(0);
            $table->enum("object_type", [0])->index("object_type")->default(0)->comment("0-user_subscriptions");
            $table->integer("object_id")->index("object_id")->default(0)->comment("object_type.id");
            $table->integer("application_id")->index("application_id")->default(0)->comment("loan_applications.id");
            $table->integer("offer_lead_id")->index("offer_lead_id")->default(0)->comment("offer_leads.id");
            $table->enum("is_manual", [0, 1])->index("is_manual")->default(0)->comment("0-No, 1-Yes");
            $table->integer("emp_id")->index("emp_id")->default(0)->comment("this field is use employee referral");
            $table->enum("status", [0, 1, 2, 3, 4, 5])->index("status")->default(0)->comment("0-pending, 1-success, 2-failed, 3-refund, 4-suspect, 5-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->text("description")->nullable();
            $table->longText("note")->nullable();
            $table->bigInteger("created_by")->index("created_by")->default(0);
            $table->bigInteger("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        Schema::create("transaction_history", function (Blueprint $table) {
            $table->id();
            $table->bigInteger("transaction_id")->index("transaction_id")->default(0)->comment("transactions.id");
            $table->longText("json_data")->nullable();
            $table->timestamp("created_at")->useCurrent();
        });

        Schema::create("payment_gateway_log", function (Blueprint $table) use ($gateway_key, $gateway_label) {
            $table->id();
            $table->enum("type", [0, 1])->index("type")->default(0)->comment("0-subscription, 1-Offer Lead");
            $table->enum("gateway_type", $gateway_key)->index("gateway_type")->default(0)->comment($gateway_label);
            $table->longText("json_data")->nullable();
            $table->timestamp("created_at")->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("transactions");
        Schema::dropIfExists("transaction_history");
        Schema::dropIfExists("payment_gateway_log");
    }
};
