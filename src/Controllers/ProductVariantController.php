<?php

namespace Mlantz\Hadron\Controllers;

use Quarx;
use Response;
use CryptoService;
use ValidationService;
use App\Http\Requests;
use Illuminate\Http\Request;
use Mlantz\Hadron\Models\Products;
use Mlantz\Quarx\Controllers\QuarxController;
use Mlantz\Hadron\Requests\CreateProductsRequest;
use Mlantz\Hadron\Repositories\ProductRepository;
use Mlantz\Hadron\Repositories\ProductVariantRepository;

class ProductVariantController extends QuarxController
{

    /** @var  ProductsRepository */
    private $productVariantRepository;

    function __construct(ProductVariantRepository $productVariantRepository, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function variants($id, Request $request)
    {
        $id = CryptoService::decrypt($id);
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            Quarx::notification('Product not found', 'warning');
            return redirect(route('quarx.products.index'));
        }

        $this->productVariantRepository->addVariant($product, $request->all());
        Quarx::notification('Variant successfully added.', 'success');

        return redirect(route('quarx.products.edit', CryptoService::encrypt($id)).'?variants');
    }

    public function saveVariant(Request $request)
    {
        $this->productVariantRepository->saveVariant($request->all());
        return Response::json(['success']);
    }

    public function deleteVariant(Request $request)
    {
        $this->productVariantRepository->deleteVariant($request->all());
        return Response::json(['success']);
    }

}