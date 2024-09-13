<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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

class CustomerController extends Controller
// {

//     public function customersData(){
//     	$customers = Customer::all();
//     	return view('Admin.all_customers',compact('customers'));
//     }

//     public function update($id,Request $request)
//     {
       
//         $customers =  Customer::find($id);
//         $customers->name = $request->name;
//         // $customers->email = $request->email;
//         // $customers->password = $request->password;
//         // $customers->gender = $request->gender;
//         // if($request->is_active){
//         //     $employee->is_active = 1;

//         // }
      
//         // $employee->date_of_birth = $request->date_of_birth;
//         // $employee->roll = $request->roll;

//         if($employee->save())
//         {
           
//             return redirect()->back()->with(['msg' => 1]);
//         }
//         else
//         {
//             return redirect()->back()->with(['msg' => 2]);
//         }
     
//         return view('update.customer',compact('customers'));

//     }

//     public function edit($id){
//         $customers = Customer::find($id);
//         return view('edit.customer', compact('customers'));
//     }
    
// }
{
     function __construct() {
        $resource = ResourceInfoFactory::emptyResource()->merge(
            ResourceInfo::create(Attributes::create([
                ResourceAttributes::SERVICE_NAMESPACE => 'prod',
                ResourceAttributes::SERVICE_NAME => 'Customers Services',
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
        $this->tracer = $productTracerProvider->getTracer('CustomerService', '1.0.0');
    }

    public function index()
    {
        $customer = new Customer();
        $customer = $customer->get();
        return view('dashbord.dashbord',[
            'customer' =>$customer
            ]);

    }

    public function edit($id)
    {
        $customers = Customer::where('id' ,'=',$id)->get();
     
        return view('customer.edit_customer',compact('customers'));

    }


    public function create()
    {
        return view('customer.create');

    }

    public function store(Request $request)
    {
	$parentSpan = $this->tracer->spanBuilder('add-customer')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();
        $parentScope = $parentSpan->activate();

        try{
                $populateCustomerSpan= $this->tracer->spanBuilder('populate-customer-model')
                                                    ->setSpanKind(SpanKind::KIND_SERVER)
                                                    ->startSpan();

                $customer = new Customer();
                $customer->name = $request->name;
                $customer->email = $request->email;
                $customer->company = $request->company;
                $customer->address = $request->address;
                $customer->phone = $request->phone;

		$populateCustomerSpan->end();
                $populateCustomerSpan->setStatus(StatusCode::STATUS_OK, "populate-customer-model success");
		$populateCustomerScope = $populateCustomerSpan->activate();
		$insertCustomerSpan= $this->tracer->spanBuilder('insert-customer-to-database')
                                                  ->setSpanKind(SpanKind::KIND_SERVER)
                                                  ->startSpan();
		$customer->save();
		$insertCustomerSpan->end();
                $insertCustomerSpan->setStatus(StatusCode::STATUS_OK, "insert customer to database success");
		$parentSpan->setStatus(StatusCode::STATUS_OK, "add customer traces success");

                return Redirect()->route('add.customer');
        }catch (\Exception $e) {
                if (isset($populateCustomerSpan)) {
                    $populateCustomerSpan->recordException($e);
                    $populateCustomerSpan->setStatus(StatusCode::STATUS_ERROR, "Failed to populate customer model");
                    $populateCustomerSpan->end();
                }

		if (isset($insertCustomerSpan)) {
                    $insertCustomerSpan->recordException($e);
                    $insertCustomerSpan->setStatus(StatusCode::STATUS_ERROR, "Failed to insert customer to database");
                    $insertCustomerSpan->end();
                }

                if (isset($parentSpan)){
                    $parentSpan->recordException($e);
                    $parentSpan->setStatus(StatusCode::STATUS_ERROR, "Something bad happened!");
                }

                return Redirect()->route('add.customer');
        }finally{
		$parentSpan->end();
		$populateCustomerScope->detach();
                $parentScope->detach();
	}
    }

    public function update($id,Request $request)
    {
       
        $customer =  Customer::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = $request->password;
        $customer->gender = $request->gender;
        if($request->is_active){
            $customer->is_active = 1;

        }
      
        $customer->date_of_birth = $request->date_of_birth;
        $customer->roll = $request->roll;

        if($customer->save())
        {
           
            return redirect()->back()->with(['msg' => 1]);
        }
        else
        {
            return redirect()->back()->with(['msg' => 2]);
        }
     
        return view('customer.edit',compact('customers'));

    }

        
    public function customersData(){
	$parentSpan = $this->tracer->spanBuilder('customers-list')->setSpanKind(SpanKind::KIND_SERVER)->startSpan();
	$parentScope = $parentSpan->activate();

        try{
                $getCustomersSpan= $this->tracer->spanBuilder('get-customers-from-database')
                                                ->setSpanKind(SpanKind::KIND_SERVER)
                                                ->startSpan();
                $customers = Customer::all();
                $parentSpan->setStatus(StatusCode::STATUS_OK, "customers list traces success");
                $getCustomersSpan->setStatus(StatusCode::STATUS_OK, "customers retrieved successfully");
                $getCustomersSpan->end();

        	return view('Admin.all_customers',compact('customers'));
        }catch (\Exception $e) {
                if (isset($getCustomersSpan)) {
                    $getCustomersSpan->recordException($e);
                    $getCustomersSpan->setStatus(StatusCode::STATUS_ERROR, "Failed to retrieve customers list");
                    $getCustomersSpan->end();
                }

                if (isset($parentSpan)){
                    $parentSpan->recordException($e);
                    $parentSpan->setStatus(StatusCode::STATUS_ERROR, "Something bad happened!");
                }

                return view('Admin.all_customers',compact('customers'));
        }finally{
		$parentSpan->end();
                $parentScope->detach();
        }
    }



    public function delete($id)
    {
        $customer =  Customer::find($id);
        if($customer->delete())
        {
           
            return redirect()->back()->with(['msg' => 1]);
        }
        else
        {
            return redirect()->back()->with(['msg' => 2]);
        }

    }

}
