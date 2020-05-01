<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;

    class CreateEdUserTypeTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('user_type', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_type', 30);
                $table->boolean('is_delete')->default(0);;
                $table->integer('created_by')->default(1);
                $table->integer('updated_by')->default(1);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::drop('user_type');
        }

    }
