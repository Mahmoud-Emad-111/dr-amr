<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageRresource;
use App\Models\Message;
use App\Traits\RandomIDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    use RandomIDTrait;
    public function create_message(MessageRequest $request)
    {
// 
        Message::create([
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'subject' => $request->subject,
            'random_id'=>$this->RandomID(),
        ]);
        return $this->handelResponse('','The message has added  successfully');
        }

        public function get_message()
        {
           $data = Message::all();
          return MessageRresource::collection($data)->resolve();

        }

        //edit
     public function edit_Message(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:messages,random_id',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Message::class, $request->id);
        $data =  Message::findOrFail($ID);

      return MessageRresource::make($data[0])->resolve();


    }
    /**update_Message */
    public function update_Message(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'first_name' => 'required|string|max:255',
            'phone' => 'required|integer', // Adjust max length according to your needs
            'email' => 'required|email|max:255',
            'subject' => 'required|string',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Message::class, $request->id)->id;
        $data =  Message::find($ID);

        $data->update([
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'subject' => $request->subject,
        ]);
        return $this->handelResponse('','The message has updated successfully');
    }

    /**deleteMessage */
    public function deleteMessage(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'id'=> 'required|integer|exists:messages,random_id',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
        }
        $ID=$this->getRealID(Message::class, $request->id);
        $message =  Message::find($ID)[0];

       $message->delete();
       return $this->handelResponse('','The message has deleted successfully');


    }

}
