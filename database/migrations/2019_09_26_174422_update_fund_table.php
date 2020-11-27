<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fund', function (Blueprint $table) {
            $table->text('index_description')->nullable();
            $table->string('index_learn_more_link')->nullable();
            $table->integer('f_override_asset_allocation')->nullable();
            $table->integer('f_active_asset_allocation')->nullable();
            $table->string('f_title_asset_allocation')->nullable();
            $table->date('asset_allocation_as_of_date')->nullable();
            $table->string('holdings_file')->nullable();
            $table->date('fund_holdings_as_of_date')->nullable();
            $table->integer('f_override_fund_holdings')->nullable();
            $table->integer('f_active_fund_holdings')->nullable();
            $table->date('performance_as_of_date')->nullable();
            $table->string('performance_expense_ratio')->nullable();
            $table->integer('f_override_performance')->nullable();
            $table->integer('f_active_performance')->nullable();
            $table->date('fund_prices_as_of_date')->nullable();
            $table->integer('f_override_fund_prices')->nullable();
            $table->integer('f_active_fund_prices')->nullable();
            $table->date('premium_discount_as_of_date')->nullable();
            $table->integer('f_override_premium_discount')->nullable();
            $table->integer('f_active_premium_discount')->nullable();
            $table->integer('f_active_fund_distribution')->nullable();
            $table->date('fund_data_and_pricing_as_of_date')->nullable();
            $table->string('white_paper_file')->nullable();
            $table->text('performance_table_headers')->nullable();
            $table->string('performance_heading')->nullable();
            $table->string('distribution_schedule_file')->nullable();
            $table->text('monthly_performance_text')->nullable();
            $table->text('quarterly_performance_text')->nullable();
            $table->longText('monthly_performance_table')->nullable();
            $table->longText('quarterly_performance_table')->nullable();
            $table->integer('monthly_performance_display')->nullable();
            $table->integer('quarterly_performance_display')->nullable();
            $table->string('performance_available_after')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fund', function (Blueprint $table) {
            $table->dropColumn(['index_description',
                                'index_learn_more_link',
                                'f_override_asset_allocation',
                                'f_active_asset_allocation',
                                'f_title_asset_allocation',
                                'asset_allocation_as_of_date',
                                'holdings_file',
                                'fund_holdings_as_of_date',
                                'f_override_fund_holdings',
                                'f_active_fund_holdings',
                                'performance_as_of_date',
                                'performance_expense_ratio',
                                'f_override_performance',
                                'f_active_performance',
                                'fund_prices_as_of_date',
                                'f_override_fund_prices',
                                'f_active_fund_prices',
                                'premium_discount_as_of_date',
                                'f_override_premium_discount',
                                'f_active_premium_discount',
                                'f_active_fund_distribution',
                                'fund_data_and_pricing_as_of_date',
                                'white_paper_file',
                                'performance_table_headers',
                                'performance_heading',
                                'distribution_schedule_file',
                                'monthly_performance_text',
                                'quarterly_performance_text',
                                'monthly_performance_table',
                                'quarterly_performance_table',
                                'monthly_performance_display',
                                'quarterly_performance_display']);
        });
    }
}
