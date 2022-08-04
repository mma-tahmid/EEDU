<?php

namespace App\Http\Controllers;

use App\Mail\PaymentDone;
use App\Models\Courses;
use App\Models\Feedback;
use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

class UserController extends Controller
{
    //

    //==========================Registration==========================//
    public function SignUp(Request $request)
    {

        $Validator = Validator::make($request->all(), [


            'name' => 'required|max:25',
            'email' => 'required|unique:users|max:30',
            'password' => 'required',
            'std_id' => 'required'


        ]);

        if ($Validator->fails()) {
            return response()->json($Validator->errors(), 422);
        }

        $result = DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'std_id' => $request->std_id

        ]);

        return $result;

        if ($result) {
            return "User Registration Complete. <a href='/login'>Login Here</a>";
        } else {
            return "Failed to register";
        }
    }

    //================================= Login ==================================//
    public function Login(Request $request)
    {



        $result = DB::table('users')
            ->where('email', $request->email)
            ->where('password', $request->password)
            ->first();


        return json_encode($result);
        return response()->json($result);

        if ($result) {
            $id = $result->id;
            $name = $result->name;
            $request->session()->put('id', $id);
            $request->session()->put('name', $name);



            return redirect('/home');
        } else {
            $request->session()->flash('errorMsg', 'Invalid username or password!');
            return redirect('/login');
        }
    }

    //=========================User Profile=============================//

    public function ViewProfile(Request $request)
    {

        try {

            $data = DB::table('users')
            ->join('students', 'users.std_id','=', 'students.id')
           
            ->select('users.*')->get(); 

            return response([



                'status' => 200,
                'Book' => $data,
                'message' => 'success',

            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ]);
        }
    }

    //=========================Payment Slip===================//

    public function Payment_Slip(Request $req)
    {

        $Validator = Validator::make($req->all(), [

            'name' => 'required',

            'email' => 'required',

            's_id' => 'required',

            'g_id' => 'required',
            'bankname' => 'required',

            'acnumber' => 'required',

            'date' => 'required',
            'c_id' => 'required',




        ]);

        if ($Validator->fails()) {

            return response([
                'validator_err' => $Validator->getMessageBag()

            ]);
        } else {
            try {

                $Payment = new Payment();
                $Payment->name = $req->name;
                $Payment->email = $req->email;
                $Payment->s_id = $req->s_id;
                $Payment->g_id = $req->g_id;
                $Payment->bankname = $req->bankname;
                $Payment->acnumber = $req->acnumber;
                $Payment->date = $req->date;
                $Payment->c_id = $req->c_id;
                // $Book->BookSampleImage1=$req->file('BookSampleImage1')->store('books');

                $Payment->save();


                return response([
                    'message' => 'Payment Complete',
                    'status' => 200,
                    'Book' => $Payment,
                ]);
            } catch (Exception $ex) {
                return response([
                    'message' => $ex->getmessage()
                ]);
            }
        }
    }



    public function Payment_List()
    {



        try {

            //$data = Payment::all();
              $data = DB::table('payments')
                ->join('users', 'payments.g_id', 'users.id')
                ->join('students','payments.s_id','students.id')
               
                ->select('payments.*')->get();

            return response([


                'status' => 200,
                'Book' => $data,
                'message' => 'success',

            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ]);
        }
    }



    public function Edit($id)
    {

        $payment = Payment::find($id);


        return response([
            'status' => 200,
            'Book' => $payment,

        ]);
    }


    public function Update(Request $req, $id)
    {

        try {
            $payment = Payment::find($id);
            $payment->name = $req->name;
            $payment->email = $req->email;
            $payment->s_id = $req->s_id;
            $payment->g_id = $req->g_id;
            $payment->bankname = $req->bankname;
            $payment->acnumber = $req->acnumber;
            $payment->date = $req->date;
            $payment->c_id = $req->c_id;

            $payment->update();
            return response([
                'status' => 200,
                'message' => 'Payment updated',
                'Book' => $payment,
            ]);
        } catch (Throwable $th) {
            return response([
                'message' => $th->getmessage()
            ]);
        }
    }


    public function DestroyPayment($id)
    {

        try {
            $Payment = Payment::find($id);
            $Payment->delete();

            return response([
                'status' => 200,
                'message' => 'Payment deleted',

            ]);
        } catch (Throwable $th) {
            return response([
                'message' => $th->getmessage()
            ]);
        }
    }


    //===========================Student Course============================//
    public function StudentCourse()
    {


        try {
            $data = DB::table('courses')
                ->join('users', 'courses.g_id','=', 'users.id')
                ->join('students', 'courses.std_id','=', 'students.id')
                
                ->select('courses.*')->get();

            return response([


                'status' => 200,
                'Book' => $data,
                'message' => 'success',

            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ]);
        }
    }


    public function Add_Report(Request $req)
    {

        $Validator = Validator::make($req->all(), [

            'name' => 'required',

            'studentname' => 'required',

            'student_id' => 'required',

            'email' => 'required',
          



        ]);

        if ($Validator->fails()) {

            return response([
                'validator_err' => $Validator->getMessageBag()

            ]);
        } else {
            try {

                $report = new Report();
                $report->name = $req->name;
                $report->studentname = $req->studentname;
                $report->student_id = $req->student_id;
                $report->email = $req->email;
               
                $report->save();


                return response([
                    'message' => 'Report Added Complete',
                    'status' => 200,
                    'Book' => $report,
                ]);
            } catch (Exception $ex) {
                return response([
                    'message' => $ex->getmessage()
                ]);
            }
        }
    }

    public function ReportList()
    {



        try {

            $data = Report::all();
             

            return response([


                'status' => 200,
                'Book' => $data,
                'message' => 'success',

            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ]);
        }
    }




    public function Edit_Report($id)
    {

        $payment = Report::find($id);


        return response([
            'status' => 200,
            'Book' => $payment,

        ]);
    }


    public function Update_Report(Request $req, $id)
    {

        try {
            $payment = Report::find($id);
            $payment->name = $req->name;
            $payment->studentname = $req->studentname;
            $payment->student_id = $req->student_id;
            $payment->email = $req->email;
          

            $payment->update();
            return response([
                'status' => 200,
                'message' => 'Payment updated',
                'Book' => $payment,
            ]);
        } catch (Throwable $th) {
            return response([
                'message' => $th->getmessage()
            ]);
        }
    }


    public function DestroyReport($id)
    {

        try {
            $Payment = Report::find($id);
            $Payment->delete();

            return response([
                'status' => 200,
                'message' => 'Payment deleted',

            ]);
        } catch (Throwable $th) {
            return response([
                'message' => $th->getmessage()
            ]);
        }
    }


















    //=======================Student Profile====================//
    public function StudentProfile()
    {

        try {
            $data = DB::table('students')
                ->join('users', 'students.g_id', '=', 'users.id')

                ->select('students.*')

                ->get();

            return response([


                'status' => 200,
                'Book' => $data,
                'message' => 'success',

            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ]);
        }
    }


    //=================================Mail======================//

    public function mail(Request $request)
    {
        
        $data = [
                'Name'  => $request->input('myUsername'),
                'Email' => $request->input('myEmail'),
                'Query' => $request->input('textquery')
                ];
      
        Mail::send('email.name', ['data1' => $data], function ($m) {
         
            $m->to('mma.tahmid@gmail.com')->subject('Contact Form Mail!');
    });
      
        return response()->json(["message" => "Email sent successfully."]);
    }




    //==============================FeedBack==========================//

    public function give_feedback(Request $req)
    {

        $Validator = Validator::make($req->all(), [

            'name' => 'required',

            'comment' => 'required',

            




        ]);

        if ($Validator->fails()) {

            return response([
                'validator_err' => $Validator->getMessageBag()

            ]);
        } else {
            try {

                $feedback = new Feedback();
                $feedback->name = $req->name;
                $feedback->comment = $req->comment;
               
            
                $feedback->save();


                return response([
                    'message' => 'Payment Complete',
                    'status' => 200,
                    'Book' => $feedback,
                ]);
            } catch (Exception $ex) {
                return response([
                    'message' => $ex->getmessage()
                ]);
            }
        }
    }



    public function Feedback_List()
    {



        try {

            $data = Feedback::all();
            

            return response([


                'status' => 200,
                'Book' => $data,
                'message' => 'success',

            ]);
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ]);
        }
    }



    public function Edit_Feedback($id)
    {

        $payment = Feedback::find($id);


        return response([
            'status' => 200,
            'Book' => $payment,

        ]);
    }


    public function Update_Feedback(Request $req, $id)
    {

        try {
            $feedback = Feedback::find($id);
            $feedback->name = $req->name;
            $feedback->comment = $req->comment;
           

            $feedback->update();
            return response([
                'status' => 200,
                'message' => 'Payment updated',
                'Book' => $feedback,
            ]);
        } catch (Throwable $th) {
            return response([
                'message' => $th->getmessage()
            ]);
        }
    }


    public function DestroyFeedback($id)
    {

        try {
            $feedback = Feedback::find($id);
            $feedback->delete();

            return response([
                'status' => 200,
                'message' => 'Feedback deleted',

            ]);
        } catch (Throwable $th) {
            return response([
                'message' => $th->getmessage()
            ]);
        }
    }







}


