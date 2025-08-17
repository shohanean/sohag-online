<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->string('package_name');
            $table->decimal('package_price', 8, 2);
            $table->date('generated_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('generated_by');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_fees');
    }
};
