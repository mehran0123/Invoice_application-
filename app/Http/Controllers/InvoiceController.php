<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function getInvoices(){
        $invoices = Invoice::with('customer')->orderBy('id','DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ],200);
    }

    public function getFilterInvoice(Request $request){
        $search = $request->get('s');
       if($search !=''){
           $invoices = Invoice::with('customer')
               ->where('id','LIKE',"%$search%")->get();
           return response()->json([
               'invoices' => $invoices
           ],200);
       }else{
        return $this->getInvoices();
       }
    }

    public function createInvoice(Request $request){

        $counter = Counter::where('key','invoice')->first();
        $random = Counter::where('key','invoice')->first();

        $invoice = Invoice::orderBy('id','DESC')->first();

        if($invoice){
            $invoice = $invoice->id + 1;
            $counters = $counter->value + $invoice;
        }else{
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'term_and_conditions' => 'Default terms And conditions',
            'items' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quantity' => 1,
            ]
        ];

        return response()->json($formData);
    }

    public function addInvoice(Request $request){

        $invoiceItems = $request->invoice_item;

        $invoiceData['sub_total'] = $request->subtotal;
        $invoiceData['total'] = $request->total;
        $invoiceData['customer_id'] = $request->customer_id;
        $invoiceData['number'] = $request->number;
        $invoiceData['date'] = $request->date;
        $invoiceData['due_date'] = $request->due_date;
        $invoiceData['discount'] = $request->discount ? $request->discount : 0 ;
        $invoiceData['reference'] = $request->reference;
        $invoiceData['terms_and_conditions'] = $request->terms_and_conditions;

        $invoice = Invoice::create($invoiceData);

        foreach (json_decode($invoiceItems) as $item){
            $itemdata['product_id'] = $item->id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemdata);
        }
    }

    public function showInvoice($id){
      $invoice = Invoice::with('customer')->with(['invoice_items' => function($q){
          $q->with('product');
      }])->where('id',$id)->first();

      return response()->json([
         'invoice' => $invoice
      ],200);
    }

    public function editInvoice($id){
        $invoice = Invoice::with('customer')->with(['invoice_items' => function($q){
            $q->with('product');
        }])->where('id',$id)->first();

        return response()->json([
            'invoice' => $invoice
        ],200);
    }

    public function deleteInvoiceItem($id){
        $item = InvoiceItem::findOrFail($id);
        $item->delete();
    }

    public function deleteInvoice($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_items()->delete();
        $invoice->delete();
    }

    public function updateInvoice(Request $request ,$id){

        $invoice = Invoice::where('id',$id)->first();
        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number ? $request->number  : $invoice->number;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->discount = $request->discount !=null ? $request->discount : $invoice->discount;
        $invoice->reference = $request->reference;
        $invoice->terms_and_conditions = $request->terms_and_conditions;
        $invoice->save();

        $invoiceItem = $request->input('invoice_items');

        $invoice->invoice_items()->delete();

        foreach (json_decode($invoiceItem) as $item){
            $productItem = new InvoiceItem();
            $productItem->product_id = $item->product_id;
            $productItem->invoice_id = $invoice->id;
            $productItem->quantity = $item->quantity;
            $productItem->unit_price = $item->unit_price;
            $productItem->save();
        }
    }



}
