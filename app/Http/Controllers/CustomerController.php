<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->company = $request->company;
        $customer->address = $request->address;
        $customer->phone = $request->phone;

        $customer->save();
        return Redirect()->route('add.customer');
        
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
        $customers = Customer::all();
        return view('Admin.all_customers',compact('customers'));
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