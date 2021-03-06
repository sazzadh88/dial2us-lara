<?php

namespace App\Http\Controllers;

use App\Bill;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\Complaint;


class UserController extends Controller
{

     public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','register']]);
          
        }


    public function register(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
                
        if($validator->fails()) {
           return response([
               'errors' => $validator->errors()
           ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'New user has been created',
            'data' => $user
        ], 200);
        
    }
     
     
    /**
     * Validation rules
     */
    private function rules()
    {
        return [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
        ];
    }

    /**
     * Validation message
     */
    private function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email is already exist',
            'password' => 'password is required'
        ];
    }


    /**
         * Create a new AuthController instance.
         *
         * @return void
         */
       

        /**
         * Get a JWT token via given credentials.
         *
         * @param  \Illuminate\Http\Request  $request
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function login(Request $request)
        {
           $credentials = $request->only('email', 'password');

           if ($token = $this->guard()->attempt($credentials)) {
               return $this->respondWithToken($token);

           }

           return response()->json(['error' => 'Unauthorized'], 401);
        }

        /**
         * Get the authenticated User
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function me()
        {
            return response()->json($this->guard()->user());
        }

        /**
         * Log the user out (Invalidate the token)
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function logout()
        {
            $this->guard()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }

        /**
         * Refresh a token.
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function refresh()
        {
            return $this->respondWithToken($this->guard()->refresh());
        }

        /**
         * Get the token array structure.
         *
         * @param  string $token
         *
         * @return \Illuminate\Http\JsonResponse
         */
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $this->guard()->user(),
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        }

        /**
         * Get the guard to be used during authentication.
         *
         * @return \Illuminate\Contracts\Auth\Guard
         */
        public function guard()
        {
            return Auth::guard();
        }

        public function checkToken(Request $request)
        {
             try {
            $tokenFetch = JWTAuth::parseToken()->authenticate();
            if ($tokenFetch) {
                $token = str_replace("Bearer ", "", $request->header('Authorization'));
            } else {
                return response()->json(['message' => 'active']);
            }
        } catch(\Tymon\JWTAuth\Exceptions\JWTException $e){//general JWT exception
            return response()->json(['message' => 'expired']);
        }
        }

        public function bills(Request $request){
         
            $bills = User::find($request->user_id)->bill()->orderBy('bill_date','desc')->get()->toArray();

          

            return response()->json( $bills );
        }

         public function billDetails(Request $request){
         
            $bill = Bill::find($request->bill_id)->toArray();

          

            return response()->json($bill);
        }
        public function getComplaints(Request $request){
         
            $complaints = Complaint::where('user_id', $request->user_id)->get()->toArray();

          

            return response()->json($complaints);
        }
}