<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') .'products', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->primary()->comment('ID');
            // 卖家信息
            $table->morphs('owner', 'idx_owner');
            $table->string('title')->comment('标题');
            $table->string('product_type', 32)->comment(ProductTypeEnum::comments('商品类型'));
            $table->string('shipping_type', 32)->comment(ShippingTypeEnum::comments('发货类型'));
            $table->string('status', 32)->comment(ProductStatusEnum::comments('状态'));
            // 基础信息
            $table->string('image')->nullable()->comment('主图');
            $table->string('barcode', 32)->nullable()->comment('条形码');
            $table->string('outer_id')->nullable()->comment('商品编码');
            $table->unsignedTinyInteger('is_customized')->default(0)->comment('是否定制');
            $table->unsignedTinyInteger('is_multiple_spec')->default(0)->comment('是否为多规格');
            $table->string('slogan')->nullable()->comment('广告语');
            // 类目信息
            $table->unsignedTinyInteger('spu_id')->nullable()->comment('标品ID');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('品牌ID');
            $table->string('product_model')->nullable()->comment('产品型号');
            $table->unsignedBigInteger('category_id')->nullable()->comment('类目ID');
            $table->unsignedBigInteger('seller_category_id')->nullable()->comment('卖家分类ID');
            // 运费
            $table->string('freight_payer', 32)->comment(FreightPayerEnum::comments('运费承担方'));
            $table->unsignedBigInteger('postage_id')->nullable()->comment('运费模板ID');
            // 价格
            $table->decimal('price', 10)->default(0)->comment('销售价');
            $table->decimal('market_price', 10)->default(0)->comment('市场价');
            $table->decimal('cost_price', 10)->default(0)->comment('成本价');

            //单位
            $table->string('unit', 10)->nullable()->comment('单位');
            $table->unsignedBigInteger('unit_quantity')->default(1)->comment('单位数量');
            // 库存
            $table->string('sub_stock', 32)->comment(SubStockTypeEnum::comments('减库存方式'));
            $table->bigInteger('stock')->default(0)->comment('库存');
            $table->bigInteger('channel_stock')->default(0)->comment('渠道库存');
            $table->bigInteger('lock_stock')->default(0)->comment('锁定库存');
            $table->unsignedBigInteger('safety_stock')->default(0)->comment('安全库存');
            // 承诺服务

            $table->unsignedInteger('delivery_time')->default(0)->comment('发货时间:小时');
            // 运营类
            $table->unsignedTinyInteger('vip')->default(0)->comment('VIP');
            $table->unsignedInteger('points')->default(0)->comment('积分');
            $table->unsignedBigInteger('min_limit')->default(0)->comment('起购量');
            $table->unsignedBigInteger('max_limit')->default(0)->comment('限购量');
            $table->unsignedBigInteger('step_limit')->default(1)->comment('数量步长');
            // 展示类
            $table->unsignedTinyInteger('is_hot')->default(0)->comment('热销');
            $table->unsignedTinyInteger('is_new')->default(0)->comment('新品');
            $table->unsignedTinyInteger('is_best')->default(0)->comment('精品');
            $table->unsignedTinyInteger('is_benefit')->default(0)->comment('特惠');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            // 时间
            $table->timestamp('on_sale_time')->nullable()->comment('上架时间');
            $table->timestamp('sold_out_time')->nullable()->comment('售停时间');
            $table->timestamp('off_sale_time')->nullable()->comment('下架时间');
            // 供应商
            $table->string('supplier_type')->nullable()->comment('供应商类型');
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('供应商ID');
            $table->unsignedBigInteger('supplier_product_id')->nullable()->comment('供应商 商品ID');
            // 税率
            // 审核状态
            // 是否违规

            // 统计项
            $table->unsignedBigInteger('sales')->default(0)->comment('销售量');
            $table->unsignedBigInteger('views')->default(0)->comment('浏览量');

            // 操作
            $table->timestamp('modified_time')->nullable()->comment('修改时间');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();

            $table->comment('商品表');


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') .'products');
    }
};
