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
        Schema::create("offer_lead_type", function (Blueprint $table) {
            $table->integerIncrements("id");
            $table->string("uuid")->unique();
            $table->string("label", 120)->nullable();
            $table->string("slug", 40)->unique()->nullable();
            $table->decimal("price", 10, 2)->default(0);
            $table->decimal("s_price", 10, 2)->default(0);
            $table->text("heading")->nullable();
            $table->longText("description")->nullable();
            $table->integer("priority")->index("priority")->default(0);
            $table->enum("status", [0, 1])->index("status")->default(0)->comment("0-active, 1-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
            $table->integer("created_by")->index("created_by")->default(0);
            $table->integer("updated_by")->index("updated_by")->default(0);
            $table->timestamp("created_at")->useCurrent();
            $table->timestamp("updated_at")->useCurrent();
        });

        $gateway_key = [];
        $gateway_label = "";
        if ([] != config("web.gateway.payment_gateway")) {
            foreach (config("web.gateway.payment_gateway") as $k => $v) {
                $gateway_label .= $k . "-" . $v . ", ";
                array_push($gateway_key, $k);
            }
        }

        Schema::create("offer_leads", function (Blueprint $table) use ($gateway_key, $gateway_label) {
            $table->id();
            $table->string("uuid")->unique();
            $table->integer("type_id")->index("type_id")->default(0)->comment("offer_lead_type.id");
            $table->enum("gateway_type", $gateway_key)->index("gateway_type")->default(0)->comment($gateway_label);
            $table->string("name", 120)->nullable();
            $table->string("phone", 12)->nullable();
            $table->string("email", 120)->nullable();
            $table->decimal("loan_amount", 12, 2)->index("loan_amount")->default(0);
            $table->decimal("amount", 10, 2)->default(0)->comment("offer_lead_type.s_price");
            $table->decimal("grandtotal", 10, 2)->default(0)->comment("offer_lead_type.s_price + tax(GST)");
            $table->string("gateway_transaction_id")->unique()->nullable()->comment("response_gateway_transaction_id");
            $table->string("gateway_payment_id")->unique()->nullable()->comment("response_gateway_payment_id");
            $table->string("gateway_order_id")->unique()->nullable()->comment("response_gateway_order_id");
            $table->bigInteger("is_customer")->index("is_customer")->default(0)->comment("users.id");
            $table->bigInteger("transaction_id")->unique()->nullable()->comment("transactions.id");
            $table->text("note")->nullable();
            $table->longText("json_data")->nullable();
            $table->enum("status", [0, 1, 2, 3, 4, 5])->index("status")->default(0)->comment("0-pending, 1-success, 2-failed, 3-refund, 4-suspect, 5-deactive");
            $table->enum("deleted", [0, 1])->index("deleted")->default(0)->comment("0-No, 1-Yes");
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
        Schema::dropIfExists("offer_lead_type");
        Schema::dropIfExists("offer_leads");
    }
};
