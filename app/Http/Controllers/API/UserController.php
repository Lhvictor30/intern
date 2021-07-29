<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;
use App\Http\Resources\User as UserResource;
   
class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $users = DB::table('users')->paginate(15);

        $user = User::all();
    return view('user.index', ['users' => $users]);
        //return $this->sendResponse(UserResource::collection($user), 'Display successful.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
  
        if (is_null($user)) {
            return $this->sendError('Error not found.');
        }
   
        return $this->sendResponse(new UserResource($user), 'Display successful.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
   
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    $input = $request->all();
        $user->name = $input['name'];
        $user->email = $input['email'];
		$input['password'] = bcrypt($input['password']);
        $user->password = $input['password'];
        $user->save();
   
        return $this->sendResponse(new UserResource($user), 'Updated successful.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
   
        return $this->sendResponse([], 'Deleted successful.');
    }
	public function indexPaging()
{
    $user = User::paginate(5);

    return view('user.index-paging')->with('user', $user);
	}
	
	public function indexFiltering(Request $request)
{
    $filter = $request->query('filter');

    if (!empty($filter)) {
        $user = User::sortable()
            ->where('user.name', 'like', '%'.$filter.'%')
            ->paginate(5);
    } else {
        $user = User::sortable()
            ->paginate(5);
    }

    return view('user.index-filtering')->with('user', $user)->with('filter', $filter);
	
}


  public function fileUpload(Request $req){
        $req->validate([
        'file' => 'required|mimes:csv,xlx,xls|max:2048'
        ]);

        $fileModel = new File;

        if($req->file()) {
            foreach($req->toArray() as $key => $value)
      {
       foreach($value as $row)
       {
        $insert_data[] = array(
         'name'  => $row['name'],
         'email'   => $row['email'],
         'password'   => $row['password'],
		 'c_password'   => $row['c_password']
        );
		  $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
       }
      }

            return back()
            ->with('success','File has been uploaded.')
            ->with('file', $fileName);

}