<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\User;
use Session;

class UserController extends Controller{

    public function postSignUp(Request $request){
       $this -> validate($request, [
            'email' => 'email|required|unique:users',
            'password' => 'required|min:4'
        ]);

        $first_name = $request->input('first_name');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));

//ye bhi chalega
////        $user = new User([
//            'name' => $request->input('name'),
//            'email' => $request->input('email'),
//            'password' => bcrypt($ request->input('password'))
//        ]);
        $user = new User();
        $user->first_name = $first_name;
        $user->email = $email;
        $user->password = $password;
        $user->save();
        Auth::login($user);
        return redirect()->route('dashboard');

        //prev
//        Auth::login($user);
//        if(Session::has('oldUrl')){
//            $oldUrl = Session::get('oldUrl');
//            Session::forget('oldUrl');
//            return redirect()->to($oldUrl);
//        }
        //return redirect()->route('product.index'); this is also ok
        //return redirect()->route('user.profile');
    }

    public function postSignIn(Request $request){
        $this -> validate($request, [
            'email' => 'email|required',
            'password' => 'required|min:4'
        ]);
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            /*if(Session::has('oldUrl')){
                $oldUrl = Session::get('oldUrl');
                //dd($oldUrl);
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }*/
            return redirect()->route('dashboard');
        }

        return redirect()->back();
    }

    public function getAccount(){
        return view('account',['user' => Auth::user()]);
    }

    public function postSaveAccount(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:120'
        ]);
        $user = Auth::user();
        $old_name = $user->first_name;
        $user->first_name = $request['first_name'];
        $user->update();
        $file = $request->file('image');
        $filename = $request['first_name'] . '-' . $user->id . '.jpg';
        $old_filename = $old_name . '-' . $user->id . '.jpg';
        $update = false;
//        if (Storage::disk('local')->has($old_filename)) {
//            $old_file = Storage::disk('local')->get($old_filename);
//            Storage::disk('local')->put($filename, $old_file);
//            $update = true;
//        }
        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
        }
//        if ($update && $old_filename !== $filename) {
//            Storage::delete($old_filename);
//        }
        return redirect()->route('account');
    }

    public function getUserImage($filename){
        $file = Storage::disk('local')->get($filename);
        return Response($file,200);
    }
}