<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Product;

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\API\Trace\SpanContext;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\Context\Context;
use OpenTelemetry\Context\ContextInterface;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\Sampler\ParentBased;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SemConv\ResourceAttributes;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

class ProductController extends Controller
{
    // public function __construct(){
    // 	$this->middleware('auth');
    // }

    function __construct() {
        $resource = ResourceInfoFactory::emptyResource()->merge(
            ResourceInfo::create(Attributes::create([
                ResourceAttributes::SERVICE_NAMESPACE => 'prod',
                ResourceAttributes::SERVICE_NAME => 'Products Services',
                ResourceAttributes::SERVICE_VERSION => '0.1',
            ]))
        );

        $productTransport = (new OtlpHttpTransportFactory())->create('http://10.30.30.5:4420/v1/traces', 'application/json');
        $productSpanExporter = new SpanExporter($productTransport);
        $productTracerProvider = TracerProvider::builder()
            ->addSpanProcessor(new SimpleSpanProcessor($productSpanExporter))
	    ->setSampler(new ParentBased(new AlwaysOnSampler()))
            ->setResource($resource)
            ->build();
        $this->tracer = $productTracerProvider->getTracer('ProductService', '1.0.0');
    }

    public function store(Request $request){
	$parentSpan = $this->tracer->spanBuilder('add-product')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();
	$parentScope= $parentSpan->activate();

	try{
	$populateProductSpan = $this->tracer->spanBuilder('populate-product-model')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();

    	$data=new Product;
        $data->product_code=$request->code;
    	$data->name= $request->name;
        $data->category = $request->category;
    	$data->stock = $request->stock;
    	$data->unit_price = $request->unit_price;
    	// $data->total_price = $request->stock * $request->unit_price;
        $data->sales_unit_price = $request->sale_price;
        // $data->sales_stock_price =$request->stock * $request->sale_price;

	$populateProductSpan->end();
	$populateProductSpan->setStatus(StatusCode::STATUS_OK, "populate-product-model success");
	$populateProductScope = $populateProductSpan->activate();

	$insertSpan = $this->tracer->spanBuilder('insert-product-to-database')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();
	$data->save();

	$insertSpan->end();
	$insertSpan->setStatus(StatusCode::STATUS_OK, "insert product to database success");
	$parentSpan->setStatus(StatusCode::STATUS_OK, "add product traces success");

        return Redirect()->route('add.product');
	}catch (\Exception $e) {
		if (isset($populateProductSpan)) {
                    $populateProductSpan->recordException($e);
                    $populateProductSpan->setStatus(StatusCode::STATUS_ERROR, "Failed to populate product model");
                    $populateProductSpan->end();
                }

		if (isset($insertSpan)) {
	            $insertSpan->recordException($e);
	            $insertSpan->setStatus(StatusCode::STATUS_ERROR, "insert product to database failed");
	            $insertSpan->end();
	        }

		if (isset($parentSpan)){
                    $parentSpan->recordException($e);
                    $parentSpan->setStatus(StatusCode::STATUS_ERROR, "Something bad happened!");
                }

		return Redirect()->route('add.product');
	} finally{
		$parentSpan->end();
		$populateProductScope->detach();
		$parentScope->detach();
	}
    }

    public function allProduct(){
	$parentSpan = $this->tracer->spanBuilder('report-products')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();
	$context = Context::getCurrent()->withContextValue($parentSpan);
	$parentScope = $parentSpan->activate();
	$traceID = $parentSpan->getContext()->getTraceId();
	$parentSpan->setAttribute('trace_id', $traceID);

	try{
		$getProductSpan= $this->tracer->spanBuilder('get-product-from-database')
					      ->setSpanKind(SpanKind::KIND_SERVER)
					      ->setParent($context)
					      ->setAttribute('trace_id', $traceID)
					      ->startSpan();
		$products = Product::all();
		$parentSpan->setStatus(StatusCode::STATUS_OK, "report product traces success");
		$getProductSpan->setStatus(StatusCode::STATUS_OK, "report product retrieved successfully");
		$getProductSpan->end();

		return view('Admin.all_product',compact('products'));
	}catch (\Exception $e) {
                if (isset($getProductSpan)) {
	            $getProductSpan->recordException($e);
	            $getProductSpan->setStatus(StatusCode::STATUS_ERROR, "Failed to retrieve report products");
	            $getProductSpan->end();
	        }

		if (isset($parentSpan)){
		    $parentSpan->recordException($e);
	            $parentSpan->setStatus(StatusCode::STATUS_ERROR, "Something bad happened!");
		}

		return view('Admin.all_product',compact('products'));
        }finally{
                $parentSpan->end();
		$parentScope->detach();
	}
    }

    public function availableProducts(){
	$parentSpan = $this->tracer->spanBuilder('available-product')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();
	$parentScope = $parentSpan->activate();

        try{
                $getAvailableProductSpan = $this->tracer->spanBuilder('get-availableProduct-from-database')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();

		$products = Product::where('stock','>','0')->get();
		$getAvailableProductSpan->setStatus(StatusCode::STATUS_OK, "available products retrieved successfully");
		$parentSpan->setStatus(StatusCode::STATUS_OK, "available products traces successful");
        	$getAvailableProductSpan->end();

		return view('Admin.available_products',compact('products'));
        }catch (\Exception $e) {
                if (isset($getAvailableProductSpan)) {
                    $getAvailableProductSpan->recordException($e);
                    $getAvailableProductSpan->setStatus(StatusCode::STATUS_ERROR, "Failed to retrieve available products");
                    $getAvailableProductSpan->end();
                }

                if (isset($parentSpan)){
                    $parentSpan->recordException($e);
                    $parentSpan->setStatus(StatusCode::STATUS_ERROR, "Something bad happened!");
                }
		return view('Admin.available_products',compact('products'));
        }finally {
		$parentSpan->end();
		$parentScope->detach();
	}
    }

    public function formData($id){
        $product = Product::find($id);
        
        return view('Admin.add_order',compact('product'));
        // return view('Admin.add_order',['product'=>$product]);
    }

    public function purchaseData($id){
        $product = Product::find($id);
        
        return view('Admin.purchase_products',compact('product'));
    }

    public function storePurchase(Request $request){

        Product::where('name',$request->name)->update(['stock' => $request->stock + $request->purchase]);
        
        return Redirect()->route('all.product');
    }
    

}
