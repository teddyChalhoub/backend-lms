<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Services\PhotoService;
use Illuminate\Http\Request;


class AdminController extends Controller
{

    protected $photoService;

    public function __construct()
    {
        $this->photoService= new PhotoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $admins = Admin::with('photo')->paginate(5);

        if(!$admins->isEmpty()){
            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> 'Retrieved admins successfully',
                'data'=>$admins
            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "No admins had been Added",
                'data'=> $admins
            ]);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddAdminRequest $request)
    {
            $inputs = $request->validated();

            $admin = new Admin();
            $admin->fill($inputs);

            $admin->save();


           if($request->file('picture') != null) {
               $admin->photo()->create([
                   'url' => $this->photoService->addFile($request),
               ]);
           }

            $token = auth()->login($admin);

            return response()->json([
                "status"=>200,
                "success" => true,
                "message" => "Admin created successfully",
                "data"=>$admin
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $admin = Admin::with('photo')->where('id',$id)->first();

        if($admin){
            return response()->json([
                "status"=>200,
                'success'=> true,
                'message'=> 'Retrieved admin '.$admin->name.' successfully',
                'data'=>$admin
            ]);
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Admin doesn't exist",
                'data'=> $admin
            ]);
        }


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAdminRequest $request, $id)
    {
        $inputs = $request->validated();

        $admin = Admin::find($id);

        if($admin){
            if(!empty($inputs)){

                $admin->fill($inputs);
                $admin->save();

                return response()->json([
                    "status"=>200,
                    'success'=> true,
                    'message'=> 'Updated admin '.$admin->name.' successfully',
                    'data'=>$admin
                ]);
            }else{
                return response()->json([
                    "status"=>404,
                    'success'=> false,
                    'message'=> 'No values provided for update',
                    'data'=>$admin
                ]);
            }
        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Admin doesn't exist",
                'data'=> $admin
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $admin = Admin::find($id);



        if($admin){

//            $this->deleteFile($admin->picture);
            if($admin->photo!= null && $this->photoService->deleteFile($admin->photo->url)){
                $admin->delete();
                $admin->photo()->delete();
                return response()->json([
                    "status"=>200,
                    'success'=> true,
                    'message'=> $admin->name.' has been deleted successfully',
                    'data'=>$admin
                ]);
            }else{
                $admin->delete();
                return response()->json([
                    "status"=>200,
                    'success'=> true,
                    'message'=> $admin->name.' has been deleted successfully',
                    'data'=>$admin
                ]);
            }


        }else{
            return response()->json([
                "status"=>404,
                'success'=> false,
                'message'=> "Admin doesn't exist",
                'data'=>$admin
            ]);
        }
    }
}
